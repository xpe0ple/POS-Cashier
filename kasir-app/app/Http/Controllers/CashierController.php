<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\ProductController;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Http\Request;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;


class CashierController extends Controller
{
    public function index()
    {
        $products = Product::all();
        $events = Event::all();
        return view('kasir.index', compact('products', 'events'));
    }

    public function checkout(Request $request)
    {
        $cart = json_decode($request->cart, true);
        $paymentMethod = $request->payment_method;
        $eventId = session('event_id');
        try {

            // 🔥 ambil hasil dari transaction
            $transaction = DB::transaction(function () use ($cart, $paymentMethod, $eventId, $request) {

                if (empty($cart)) {
                    throw new \Exception("Cart kosong!");
                }

                if (!Auth::check()) {
                    throw new \Exception("User belum login!");
                }

                $total = 0;

                foreach ($cart as $item) {
                    $product = Product::where('product_id', $item['product_id'])->first();

                    if (!$product || $product->stock < $item['qty']) {
                        throw new \Exception("Stok {$product->name} tidak cukup!");
                    }

                    $total += $item['price'] * $item['qty'];
                }

                $amountPaid = (int) $request->input('amount_paid');

                if ($amountPaid < $total) {
                    throw new \Exception("Uang tidak cukup!");
                }

                $change = $amountPaid - $total;

                // ✅ SIMPAN TRANSAKSI
                $transaction = Transaction::create([
                    'user_id' => Auth::user()->user_id,
                    'event_id' => $eventId,
                    'total' => $total,
                    'payment_method' => $paymentMethod,
                    'amount_paid' => $amountPaid,
                    'change' => $change,
                ]);

                foreach ($cart as $item) {

                    TransactionItem::create([
                        'transaction_id' => $transaction->transaction_id,
                        'product_id' => $item['product_id'], // 🔥 FIX
                        'qty' => $item['qty'],
                        'price' => $item['price']
                    ]);

                    Product::where('product_id', $item['product_id']) // 🔥 FIX
                        ->decrement('stock', $item['qty']);
                }

                return $transaction; // 🔥 WAJIB
            });

            return redirect('/kasir')->with([
                'success' => 'Transaksi berhasil!',
                'receipt' => $transaction->load('items.product', 'eventRel')
            ]);
        } catch (\Exception $e) {
            return redirect('/kasir')->with('error', $e->getMessage());
        }
    }
}
