<!DOCTYPE html>
<html>
<head>
    <title>Transaction History</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 p-6">

    <!-- BACK -->
    <a href="/kasir" 
   class="group inline-flex items-center gap-2 px-3 py-2 rounded-xl 
   text-sm font-medium text-gray-700 
   hover:bg-blue-500 hover:text-white 
   transition-all duration-200 hover:scale-105 active:scale-95">

    <svg xmlns="http://www.w3.org/2000/svg" 
        class="w-4 h-4 transition-colors duration-200 group-hover:text-white" 
        fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
            d="M15 19l-7-7 7-7" />
    </svg>

    Back to Cashier
</a>

    <!-- TITLE -->
    <h1 class="text-2xl font-bold">Transaction History</h1>
    <p class="text-gray-500 mb-6">View all past transactions and receipts</p>

    <!-- LIST -->
    <div class="space-y-4">

        @foreach($transactions as $t)
        <div class="bg-white p-5 rounded-2xl shadow-sm flex justify-between items-center">

<!-- LEFT -->
<div class="grid grid-cols-4 gap-10">

    <!-- ID -->
    <div>
        <p class="text-sm text-gray-500">Transaction ID</p>
        <p class="font-medium">txn-{{ str_pad($t->transaction_id, 3, '0', STR_PAD_LEFT) }}</p>
    </div>

    <!-- DATE -->
    <div>
        <p class="text-sm text-gray-500">Date & Time</p>
        <p class="font-medium">
            {{ $t->created_at->format('M d, Y, h:i A') }}
        </p>
    </div>

    <!-- ITEMS -->
    <div>
        <p class="text-sm text-gray-500">Items</p>
        <p class="font-medium">{{ $t->items->count() }} items</p>
    </div>

    <!-- EVENT 🔥 -->
    <div>
        <p class="text-sm text-gray-500">Event</p>
        <p class="font-medium">
            <span class="px-2 py-1 bg-blue-100 text-blue-600 rounded-lg text-xs">
                {{-- {{ $t->event->name ?? 'No Event' }} --}}
                {{ $t->eventRel->name ?? 'No Event' }}
                {{-- {{ dd($t->event_id, $t->event) }} --}}
                {{-- {{ \App\Models\Event::find($t->event_id)->name ?? 'NULL' }} --}}
            </span>
        </p>
    </div>

</div>

            <!-- RIGHT -->
            <div class="text-right">

                <p class="text-sm text-gray-500">Total</p>
                <p class="text-blue-600 font-bold text-lg">
                    Rp {{ number_format($t->total, 0, ',', '.') }}
                </p>

                <button onclick="openReceipt({{ $t->transaction_id }})""
                    class="mt-2 px-4 py-1 border rounded-lg text-sm hover:bg-gray-100">
                    View Receipt
                </button>

            </div>

        </div>
        @endforeach

    </div>
    <!-- 🔥 RECEIPT MODAL -->
<div id="receipt-modal" class="hidden fixed inset-0 bg-black/40 flex items-center justify-center z-50">

    <div class="bg-white w-full max-w-[500px] rounded-2xl p-6 shadow-lg max-h-[90vh] overflow-y-auto">

        <div class="flex justify-between mb-4">
            <h2 class="font-semibold">Receipt</h2>
            <button onclick="closeReceipt()">✖</button>
        </div>

        <div class="text-center mb-4">
            <h1 class="text-xl font-bold">RECEIPT</h1>
            <p id="receipt-date" class="text-sm text-gray-500"></p>
        </div>

        <hr class="border-dashed my-2">

        <!-- ITEMS -->
        <div id="receipt-items" class="space-y-2 text-sm"></div>

        <hr class="border-dashed my-2">

        <!-- TOTAL -->
        <div class="text-sm space-y-1">
            <div class="flex justify-between">
                <span>Subtotal</span>
                <span id="receipt-subtotal"></span>
            </div>

            <div class="flex justify-between font-bold text-lg">
                <span>Total</span>
                <span id="receipt-total"></span>
            </div>
        </div>

        <hr class="border-dashed my-2">

        <!-- PAYMENT -->
        <div class="text-sm space-y-1">
            <div class="flex justify-between">
                <span>Payment Method</span>
                <span id="receipt-method"></span>
            </div>

            <div class="flex justify-between">
                <span>Amount Paid</span>
                <span id="receipt-paid"></span>
            </div>

            <div class="flex justify-between text-green-600 font-semibold">
                <span>Change</span>
                <span id="receipt-change"></span>
            </div>
        </div>

        <div class="text-center text-xs text-gray-400 mt-4">
            Thank you for your purchase!
        </div>

        <!-- BUTTON -->
        <div class="flex gap-2 mt-6">
            <button onclick="closeReceipt()" 
                class="flex-1 bg-blue-600 text-white rounded-xl py-2 hover:bg-blue-700">
                Done
            </button>
        </div>

    </div>

</div>

    <script>
        // 🔥 FORMAT RUPIAH (WAJIB ADA)
        function formatRupiah(number) {
            return 'Rp ' + Number(number).toLocaleString('id-ID');
        }
    
        // 🔥 OPEN RECEIPT DARI HISTORY
        async function openReceipt(id) {
    
            let res = await fetch(`/transactions/${id}`);
            let data = await res.json();
    
            document.getElementById('receipt-modal').classList.remove('hidden');
    
            let itemsDiv = document.getElementById('receipt-items');
            itemsDiv.innerHTML = '';
    
            let subtotal = 0;
    
            data.items.forEach(item => {
                let total = item.price * item.qty;
                subtotal += total;
    
                itemsDiv.innerHTML += `
                    <div class="flex justify-between">
                        <div>
                            <p>${item.product.name}</p>
                            <p class="text-gray-500 text-xs">
                                ${item.qty} x ${formatRupiah(item.price)}
                            </p>
                        </div>
                        <span>${formatRupiah(total)}</span>
                    </div>
                `;
            });
    
            document.getElementById('receipt-subtotal').innerText = formatRupiah(subtotal);
            document.getElementById('receipt-total').innerText = formatRupiah(data.total);
    
            document.getElementById('receipt-method').innerText = data.payment_method.toUpperCase();
            document.getElementById('receipt-paid').innerText = formatRupiah(data.total);
            document.getElementById('receipt-change').innerText = formatRupiah(0);
    
            document.getElementById('receipt-date').innerText = 
                new Date(data.created_at).toLocaleString();
        }
    
        function closeReceipt() {
            document.getElementById('receipt-modal').classList.add('hidden');
        }
    </script>
</body>
</html>