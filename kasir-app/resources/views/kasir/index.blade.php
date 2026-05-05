<!DOCTYPE html>
<html>
<head>
    <title>Mouw Dimsum</title>
    <link rel="icon" href="{{ asset('logo.png') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://unpkg.com/heroicons@2.0.18/dist/heroicons.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/geist-font@latest/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Geist:wght@100..900&display=swap" rel="stylesheet">
</head>

<body class="bg-gray-100 font-[Geist] m-0 p-0 flex flex-col h-screen overflow-hidden">
<!-- Header -->
<x-header :events="$events" />
<form method="POST" action="/logout">
    @csrf
    <button class="text-red-500 hover:underline">
        Logout
    </button>
</form>
{{-- <div class="flex flex-1 overflow-hidden">
    @if(session('success'))
        <div class="bg-green-200 text-green-800 p-3 rounded-xl mb-4 w-full">
            {{ session('success') }}
        </div>
    @endif
</div> --}}

@if(session('error'))
    <div class="bg-red-100 border border-red-300 text-red-700 px-4 py-3 rounded-xl mb-4">
        {{ session('error') }}
    </div>
@endif

@if(session('success'))
    <script>
        window.onload = function() {
            showReceiptFromServer();
        }
    </script>
@endif

@if(session('receipt'))
<script>
    window.onload = function () {
        showReceiptFromServer(@json(session('receipt')));
    }
</script>
@endif

<div class="flex flex-1 overflow-hidden">

    <!-- 🧾 LEFT: PRODUCT LIST -->
    <div class="flex-1 p-6 overflow-y-auto">
        <div class="flex items-center justify-between mb-4">

            <!-- KIRI -->
            <h1 class="text-2xl font-bold">Daftar Produk</h1>
        
            <!-- KANAN -->
            <form method="POST" action="/set-event" id="event-form">
                @csrf
        
                <select id="event-select" name="event_id"
                    class="bg-gray-200 px-4 py-2 rounded-xl">
                    <option value="">Pilih Event</option>
        
                    @foreach($events as $event)
                    <option value="{{ $event->event_id }}"
                        {{ session('event_id') == $event->event_id ? 'selected' : '' }}>
                        {{ $event->name }}
                    </option>
                    @endforeach
                </select>
            </form>
        
        </div>
        <!-- 🔍 SEARCH -->
        <div class="relative mb-4">
    
            <!-- ICON -->
            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">
                🔍
            </span>
        
            <!-- INPUT -->
            <input 
                type="text" 
                id="search"
                placeholder="Search products..."
                onkeyup="searchProduct()"
                class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-xl bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:bg-white transition"
            >
        
        </div>

        <!-- PRODUCT LIST -->
        <div class="grid grid-cols-3 gap-4" id="product-list">
            @foreach ($products as $product)
            
            <div class="product-item bg-white p-4 rounded-2xl shadow-md hover:shadow-lg transition">
                <img src="{{ $product->image_url }}"
     
                class="w-full h-40 object-contain bg-gray-50 rounded-xl mb-2">
                <h2 class="font-bold product-name">{{ $product->name }}</h2>
                <p class="text-gray-600 text-sm">
                    Rp {{ number_format($product->price, 0, ',', '.') }}
                </p>

                <button onclick='addToCart({{ json_encode($product) }})'
                    class="mt-2 bg-blue-500 text-white px-3 py-1 rounded-xl hover:bg-blue-600 transition w-full">
                    + Tambah
                </button>
            </div>
            @endforeach
        </div>

    </div>

    <!-- 🛒 RIGHT: CART -->
    <div class="w-[350px] bg-white p-6 border-l flex flex-col">
        <h1 class="text-xl font-semibold mb-4">Shopping Cart</h1>

        <div id="cart-items" class="flex-1 overflow-y-auto pr-2"></div>

        <hr class="border-t border-dashed border-gray-300 my-2">

        <p class="text-lg font-semibold">
            Total: <span id="total">Rp 0</span>
        </p>

        <form id="checkout-form" method="POST" action="/checkout">
            @csrf
            <input type="hidden" name="amount_paid" id="amount-paid-input">
            
            <input type="hidden" name="cart" id="cart-input">
            <input type="hidden" name="payment_method" id="payment-method-input">
            <input type="hidden" name="event_id" id="event-id-input">
        </form>
        
        <button type="button" onclick=" openPayment() " 
            class="mt-4 w-full bg-blue-500 text-white py-2 rounded-xl hover:bg-blue-600 transition">
            Checkout
        </button>
    </div>

</div>
</div> <!-- end main layout -->

<!-- 🔥 PAYMENT MODAL -->
<div id="payment-modal" class="hidden fixed inset-0 bg-black/40 flex items-center justify-center">
<!-- 🔥 STOCK ALERT -->

    <div class="bg-white w-full max-w-[500px] rounded-2xl p-6 shadow-lg">
        <div id="stock-alert" class="hidden bg-yellow-100 border border-yellow-300 text-yellow-800 p-3 rounded-xl mb-4 text-sm">
            <p class="font-semibold mb-1">⚠️ Out of Stock Warning</p>
            <ul id="stock-list" class="list-disc ml-5"></ul>
            <p class="mt-2 text-xs">Silakan kurangi jumlah sebelum checkout.</p>
        </div>
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold">Payment Details</h2>
            <button onclick="closeModal()">✖</button>
        </div>
    
        <!-- 🔥 PAYMENT SUMMARY -->
        <div class="bg-gray-100 p-4 rounded-xl mb-4">
    
            <!-- Subtotal -->
            <div class="flex justify-between text-sm text-gray-600">
                <span>Subtotal</span>
                <span id="modal-subtotal"></span>
            </div>
    
            <!-- 🔥 LIST PRODUK -->
            <div id="modal-items" class="mt-2 space-y-1 text-sm text-gray-600 max-h-32 overflow-y-auto"></div>
    
            <hr class="border-t border-dashed border-gray-300 my-2">
    
            <!-- Total -->
            <div class="flex justify-between font-bold text-lg">
                <span>Total</span>
                <span id="modal-total"></span>
            </div>
    
        </div>
    
        <!-- PAYMENT METHOD -->
        <div class="space-y-2 mb-4">
            <button onclick="selectMethod('cash', event)" 
                class="method-btn w-full border p-3 rounded-xl text-sm font-medium hover:bg-blue-50 hover:border-blue-400 transition transition-all duration-200">
                    Cash
            </button>

            <button onclick="selectMethod('qris', event)" 
                class="method-btn w-full border p-3 rounded-xl text-sm font-medium hover:bg-blue-50 hover:border-blue-400 transition transition-all duration-200">
                    QRIS
            </button>

        </div>
        
        <!-- 🔥 CASH SECTION -->
<div id="cash-section" class="hidden mt-4">

    <label class="text-sm text-gray-600">Uang Dibayar</label>
    <input 
        type="number" 
        id="cash-input"
        oninput="hitungKembalian()"
        class="w-full mt-1 p-2 border rounded-xl"
        placeholder="Masukkan uang..."
    >

    <div class="mt-2 text-sm text-gray-600">
        Kembalian:
        <span id="change" class="font-semibold">Rp 0</span>
    </div>

</div>
    
        <div class="flex gap-2">
            <button onclick="closeModal()" class="flex-1 border rounded-xl py-2">
                Cancel
            </button>
    
            <button id="btn-checkout-final" 
            onclick="submitCheckout()" class="flex-1 bg-blue-500 text-white rounded-xl py-2">
                Complete Payment
            </button>
        </div>
    
    </div>

</div>

<div id="receipt-modal" class="hidden fixed inset-0 bg-black/40 flex items-center justify-center z-50">

    <div class="bg-white w-full max-w-[500px] rounded-2xl p-6 shadow-lg">

        <div class="flex justify-between mb-4">
            <h2 class="font-semibold">Receipt</h2>
            <button onclick="closeReceipt()">✖</button>
        </div>

        <div class="text-center mb-4">
            <h1 class="text-xl font-bold">RECEIPT</h1>
            <p class="text-sm text-gray-500" id="receipt-date"></p>
        </div>

        <hr class="border-t border-dashed border-gray-300 my-2">

        <!-- ITEMS -->
        <div id="receipt-items" class="space-y-2 text-sm"></div>

        <hr class="border-t border-dashed border-gray-300 my-2">

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

        <hr class="border-t border-dashed border-gray-300 my-2">

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
        <div class="flex gap-2 mt-6">

            <!-- PRINT -->
            <button onclick="printReceipt()" 
                class="flex-1 border rounded-xl py-2 flex items-center justify-center gap-2 text-sm font-medium hover:bg-gray-100 transition">
        
                <!-- ICON PRINT -->
                <svg xmlns="http://www.w3.org/2000/svg" 
                    class="w-4 h-4 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                        d="M6 9V2h12v7M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2M6 14h12v8H6v-8z"/>
                </svg>
        
                Print
            </button>
        
            <!-- DOWNLOAD PDF -->
            <button onclick="downloadPDF()" 
                class="flex-1 border rounded-xl py-2 flex items-center justify-center gap-2 text-sm font-medium hover:bg-gray-100 transition">
        
                <!-- ICON DOWNLOAD -->
                <svg xmlns="http://www.w3.org/2000/svg" 
                    class="w-4 h-4 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                        d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5m0 0l5-5m-5 5V4"/>
                </svg>
        
                Download PDF
            </button>
        
            <!-- DONE -->
            <button onclick="closeReceipt()" 
                class="flex-1 bg-blue-600 text-white rounded-xl py-2 text-sm font-medium hover:bg-blue-700 transition">
                Done
            </button>
        
        </div>
    </div>
</div>



<script>
    let cart = [];
    let selectedMethod = null;
    
    // 🔥 FORMAT RUPIAH
    function formatRupiah(number) {
        return 'Rp ' + Number(number).toLocaleString('id-ID');
    }
    
    // 🔍 SEARCH
    function searchProduct() {
        let input = document.getElementById('search').value.toLowerCase();
        let items = document.querySelectorAll('.product-item');
    
        items.forEach(item => {
            let name = item.querySelector('.product-name').innerText.toLowerCase();
            item.style.display = name.includes(input) ? 'block' : 'none';
        });
    }
    
    // 🛒 ADD TO CART
    function addToCart(product) {
        let item = cart.find(i => i.product_id === product.product_id);
    
        if (item) {
            item.qty++;
        } else {
            cart.push({
                ...product,
                product_id: product.product_id,
                qty: 1,
                stock: product.stock // 🔥 penting
            });
        }
    
        renderCart();
    }
    
    // 🧾 RENDER CART
    function renderCart() {
        let cartDiv = document.getElementById('cart-items');
        cartDiv.innerHTML = '';
    
        let total = 0;
    
        cart.forEach(item => {
            total += item.price * item.qty;
    
            cartDiv.innerHTML += `
                <div class="bg-white rounded-2xl shadow-sm p-4 mb-3">
                    <div class="flex justify-between">
                        <div>
                            <p class="font-semibold">${item.name}</p>
                            <p class="text-sm text-gray-500">${formatRupiah(item.price)}</p>
                        </div>
                        <button onclick="removeItem(${item.product_id})">🗑️</button>
                    </div>
    
                    <div class="flex justify-between mt-3">
                        <div class="flex items-center bg-gray-100 rounded-xl px-2 py-1 gap-2">
    <button onclick="decrease(${item.product_id})" 
        class="px-2 text-lg hover:text-blue-500">-</button>

        <input 
    type="number" 
    value="${item.qty}" 
    min="1"
    onchange="updateQty(${item.product_id}, this.value)"
    onfocus="this.select()"
    class="w-14 text-center border rounded-lg"
/>

    <button onclick="increase(${item.product_id})" 
        class="px-2 text-lg hover:text-blue-500">+</button>
</div>
                        <span>${formatRupiah(item.price * item.qty)}</span>
                    </div>
                </div>
            `;
        });
    
        document.getElementById('total').innerText = formatRupiah(total);
        document.getElementById('cart-input').value = JSON.stringify(cart);
    
        checkStock(); // 🔥 update stock warning
    }
    

    function updateQty(id, value) {
        let item = cart.find(i => i.product_id === id);

    let qty = parseInt(value);

    if (isNaN(qty) || qty < 1) {
        qty = 1;
    }

    item.qty = qty;
    renderCart();
}
    // ➕➖
    function increase(id) {
        let item = cart.find(i => i.product_id === id);
        item.qty++;
        renderCart();
    }
    
    function decrease(id) {
        let item = cart.find(i => i.product_id === id);
        item.qty > 1 ? item.qty-- : cart = cart.filter(i => i.id !== id);
        renderCart();
    }
    
    function removeItem(id) {
        cart = cart.filter(i => i.product_id !== id);
        renderCart();
    }
    
    // 💳 OPEN PAYMENT
    function openPayment() {
        document.getElementById('payment-modal').classList.remove('hidden');
    
        let modalItems = document.getElementById('modal-items');
        modalItems.innerHTML = '';
    
        let subtotal = 0;
    
        cart.forEach(item => {
            let total = item.price * item.qty;
            subtotal += total;
    
            modalItems.innerHTML += `
                <div class="flex justify-between">
                    <span>${item.name} x${item.qty}</span>
                    <span>${formatRupiah(total)}</span>
                </div>
            `;
        });
    
        document.getElementById('modal-subtotal').innerText = formatRupiah(subtotal);
        document.getElementById('modal-total').innerText = formatRupiah(subtotal);
    
        checkStock();
    }
    
    function closeModal() {
        document.getElementById('payment-modal').classList.add('hidden');
    }
    
    // 💰 PAYMENT METHOD
    function selectMethod(method, event) {
        selectedMethod = method;
    
        document.querySelectorAll('.method-btn').forEach(btn => {
            btn.classList.remove('bg-blue-500', 'text-white');
        });
    
        event.target.classList.add('bg-blue-500', 'text-white');
    
        document.getElementById('cash-section')
            .classList.toggle('hidden', method !== 'cash');
    }
    
    // 💸 HITUNG KEMBALIAN
    function hitungKembalian() {
        let total = parseInt(document.getElementById('modal-total').innerText.replace(/\D/g,''));
        let bayar = parseInt(document.getElementById('cash-input').value) || 0;
    
        let change = bayar - total;
    
        document.getElementById('change').innerText =
            change < 0 ? "Uang kurang!" : formatRupiah(change);
    }
    
    // 🚫 STOCK VALIDATION
    function checkStock() {
        let out = [];
    
        cart.forEach(item => {
            if (item.qty > item.stock) {
                out.push(item);
            }
        });
    
        let alertBox = document.getElementById('stock-alert');
        let list = document.getElementById('stock-list');
        let btn = document.getElementById('btn-checkout-final');
    
        list.innerHTML = '';
    
        if (out.length > 0) {
            alertBox.classList.remove('hidden');
    
            out.forEach(i => {
                list.innerHTML += `<li>${i.name} (stok ${i.stock})</li>`;
            });
    
            btn.disabled = true;
            btn.classList.add('opacity-50');
        } else {
            alertBox.classList.add('hidden');
    
            btn.disabled = false;
            btn.classList.remove('opacity-50');
        }
    }
    
    // 🚀 SUBMIT CHECKOUT
    function submitCheckout() {
    let select = document.getElementById('event-select');
    let eventId = select.value;

    console.log("Event ID:", eventId); // 🔍 debug

    if (!eventId || eventId === "") {
        alert("Pilih event dulu!");
        return;
    }

    if (!selectedMethod) {
        alert("Pilih metode pembayaran!");
        return;
    }

    let total = parseInt(document.getElementById('modal-total').innerText.replace(/\D/g,''));
    let bayar = parseInt(document.getElementById('cash-input').value) || 0;

    if (selectedMethod === 'cash' && bayar < total) {
        alert("Uang tidak cukup!");
        return;
    }

    // 🔥 SET KE HIDDEN INPUT
    document.getElementById('event-id-input').value = eventId;
    document.getElementById('payment-method-input').value = selectedMethod;
    document.getElementById('amount-paid-input').value = selectedMethod === 'cash' ? bayar : total;

    document.getElementById('checkout-form').submit();
}
    
    // 🧾 RECEIPT DARI BACKEND
    function showReceiptFromServer(data) {
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
                        <p class="text-xs">${item.qty} x ${formatRupiah(item.price)}</p>
                    </div>
                    <span>${formatRupiah(total)}</span>
                </div>
            `;
        });
    
        document.getElementById('receipt-subtotal').innerText = formatRupiah(subtotal);
        document.getElementById('receipt-total').innerText = formatRupiah(data.total);
        document.getElementById('receipt-method').innerText = data.payment_method;
        let paid = parseInt(data.amount_paid);
let total = parseInt(data.total);

// 🔥 HANDLE NULL / UNDEFINED
if (isNaN(paid)) {
    paid = total;
}

document.getElementById('receipt-paid').innerText =
    formatRupiah(paid);

document.getElementById('receipt-change').innerText =
    formatRupiah(paid - total);
    }
    
    // ❌ CLOSE RECEIPT
    function closeReceipt() {
        document.getElementById('receipt-modal').classList.add('hidden');
    }
    
    // 🖨️ PRINT
    function printReceipt() {
        window.print();
    }
    
    // 📄 PDF
    function downloadPDF() {
        const { jsPDF } = window.jspdf;
        let doc = new jsPDF();
        doc.text(document.getElementById('receipt-modal').innerText, 10, 10);
        doc.save("receipt.pdf");
    }

    document.getElementById('event-select').addEventListener('change', function() {
    document.getElementById('event-form').submit();
});

    </script>
    

</body>
</html>