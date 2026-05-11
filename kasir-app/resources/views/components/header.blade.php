<div class="bg-white border-b px-6 py-4 flex justify-between items-center">

    <!-- LEFT -->
    <div>
        <h1 class="text-xl font-semibold">
            Mouw Dimsum
        </h1>

        <p class="text-sm text-gray-500">
            Point of Sale 
        </p>
    </div>

    <!-- RIGHT -->
    <div class="flex items-center gap-3">

        <!-- HISTORY -->
        <a href="{{ route('transactions') }}" 
            class="flex items-center gap-2 border px-4 py-2 
                   rounded-xl text-sm hover:bg-gray-100 transition">

            🕒 Riwayat Transaksi
        </a>

        <!-- PROFILE -->
        <div class="relative group">

            <!-- BUTTON -->
            <button 
                class="w-10 h-10 rounded-full bg-gray-200 
                       hover:bg-gray-300 transition
                       flex items-center justify-center">

                👤
            </button>
<!-- DROPDOWN -->
<div class="absolute right-0 top-full pt-2
hidden group-hover:block z-50">

<div class="w-44 bg-white rounded-2xl shadow-xl 
    border border-gray-100 overflow-hidden">

<!-- USER INFO -->
<div class="px-4 py-3 border-b bg-gray-50">
<p class="text-sm font-medium text-gray-700">
    Kasir
</p>

<p class="text-xs text-gray-400">
    Sesi Aktif
</p>
</div>

<!-- LOGOUT -->
<form method="POST" action="/logout">
@csrf

<button 
    class="w-full text-left px-4 py-3 
           hover:bg-red-50 transition
           text-red-500 text-sm">

    Logout
</button>
</form>

</div>

</div>

        </div>

    </div>

</div>