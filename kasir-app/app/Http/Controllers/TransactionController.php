<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = \App\Models\Transaction::with('items', 'eventRel')
            ->latest()
            ->get();


        return view('transactions.index', compact('transactions'));
    }
    public function show($id)
    {
        $transaction = Transaction::with('items.product', 'eventRel')
            ->where('transaction_id', $id)
            ->firstOrFail();

        return response()->json($transaction);
    }
}
