<!DOCTYPE html>
<html>
<head>
    <title>Detail Transaksi</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">

<h1 class="text-2xl font-bold mb-4">Detail Transaksi #{{ $transaction->transaction_id }}</h1>

<div class="bg-white p-4 rounded-xl shadow">
    @foreach ($transaction->items as $item)
        <div class="flex justify-between border-b py-2">
            <span>{{ $item->product->name }} x{{ $item->qty }}</span>
            <span>Rp {{ number_format($item->price * $item->qty,0,',','.') }}</span>
        </div>
        
    @endforeach

    <div class="flex justify-between mt-4 font-bold">
        <span>Total</span>
        <span>Rp {{ number_format($transaction->total,0,',','.') }}</span>
    </div>
</div>

</body>
</html>