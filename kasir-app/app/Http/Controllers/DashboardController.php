<?php

namespace App\Http\Controllers;


use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use App\Models\TransactionItem;
use Carbon\Carbon;


class DashboardController extends Controller
{
    public function index()
    {
        // ===== DATA UTAMA =====
        $totalPenjualan = Transaction::sum('total');
        $totalTransaksi = Transaction::count();
        $totalStock = Product::sum('stock');

        $productDonut = TransactionItem::select('product_id')
            ->selectRaw('SUM(qty) as total, SUM(qty * price) as revenue')
            ->groupBy('product_id')
            ->orderByDesc('total')
            ->with('product')
            ->get();

        $topProduct = TransactionItem::select('product_id')
            ->selectRaw('SUM(qty) as total')
            ->groupBy('product_id')
            ->orderByDesc('total')
            ->with('product')
            ->first();

        // ===== CHART 1: PENJUALAN =====
        $salesChart = Transaction::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('SUM(total) as total')
        )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // ===== CHART 2: TRANSAKSI =====
        $transactionChart = Transaction::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as total')
        )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // ===== CHART 3: TOP PRODUK =====
        $productChart = TransactionItem::select('product_id')
            ->selectRaw('SUM(qty) as total')
            ->groupBy('product_id')
            ->orderByDesc('total')
            ->with('product')
            ->limit(5)
            ->get();


        $todayTransactions = Transaction::whereDate('created_at', Carbon::today())->get();

        $todayCount = $todayTransactions->count();
        $todayRevenue = $todayTransactions->sum('total');

        $todayActivities = Transaction::latest()
            ->take(5)
            ->get()
            ->map(function ($trx) {
                return [
                    'title' => 'Transaksi berhasil',
                    'subtitle' => 'Pesanan #' . $trx->transaction_id,
                    'time' => Carbon::parse($trx->created_at)
                        ->locale('id')
                        ->diffForHumans()
                ];
            });

        return view('dashboard', compact(
            'totalPenjualan',
            'totalTransaksi',
            'totalStock',
            'topProduct',
            'salesChart',
            'transactionChart',
            'productChart',
            'productDonut',
            'todayCount',
            'todayRevenue',
            'todayActivities'
        ));
    }

    public function reports(Request $request)
    {
        $events = Event::all();
        $eventId = $request->event_id;


        // ===== FILTER TRANSAKSI =====
        $transactions = Transaction::when($eventId, function ($q) use ($eventId) {
            $q->where('event_id', $eventId);
        });

        $todayTransactions = Transaction::when($eventId, function ($q) use ($eventId) {
            if ($eventId) {
                $q->where('event_id', $eventId);
            }
        })
            ->whereBetween('created_at', [
                Carbon::now()->startOfDay(),
                Carbon::now()->endOfDay()
            ])
            ->get();

        $todayCount = $todayTransactions->count();
        $todayRevenue = $todayTransactions->sum('total');
        // ===== SUMMARY =====

        $totalPenjualan = $transactions->sum('total');
        $totalTransaksi = $transactions->count();

        // ===== TOP PRODUCT =====
        $topProduct = TransactionItem::select('product_id')
            ->selectRaw('SUM(qty) as total')
            ->whereHas('transaction', function ($q) use ($eventId) {
                if ($eventId) {
                    $q->where('event_id', $eventId);
                }
            })
            ->groupBy('product_id')
            ->orderByDesc('total')
            ->with('product')
            ->first();

        // ===== CHART =====
        $salesChart = Transaction::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('SUM(total) as total')
        )
            ->when($eventId, fn($q) => $q->where('event_id', $eventId))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // ===== DONUT =====
        $productDonut = TransactionItem::select('product_id')
            ->selectRaw('SUM(qty * price) as revenue')
            ->whereHas('transaction', function ($q) use ($eventId) {
                if ($eventId) {
                    $q->where('event_id', $eventId);
                }
            })
            ->groupBy('product_id')
            ->with('product')
            ->get();

        // ===== RECENT =====
        $recent = Transaction::when($eventId, fn($q) => $q->where('event_id', $eventId))
            ->latest()
            ->take(5)
            ->get();

        $productSales = TransactionItem::select('product_id')
            ->selectRaw('SUM(qty) as total_sold, SUM(qty * price) as revenue')
            ->whereHas('transaction', function ($q) use ($eventId) {
                if ($eventId) {
                    $q->where('event_id', $eventId);
                }
            })
            ->groupBy('product_id')
            ->orderByDesc('total_sold')
            ->with('product')
            ->get();

        $top5Products = $productSales->take(5);


        // 🔥 RETURN HARUS PALING BAWAH
        return view('reports.index', compact(
            'events',
            'eventId',
            'totalPenjualan',
            'totalTransaksi',
            'topProduct',
            'salesChart',
            'productDonut',
            'productSales',
            'recent',
            'todayCount',
            'todayRevenue',
            'top5Products',
        ));
    }
}
