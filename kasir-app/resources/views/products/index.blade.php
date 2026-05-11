@extends('layouts.dashboard')

@section('content')

<div class="space-y-6">

    <!-- HEADER -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold">Daftar Produk</h1>
            <p class="text-gray-400 text-sm">Kelola semua produk di sistem</p>
        </div>

        <a href="{{ route('products.create') }}" 
           class="bg-blue-500 hover:bg-blue-600 px-4 py-2 rounded-xl text-white shadow">
           + Tambah Produk
        </a>
    </div>

    <!-- SEARCH + FILTER -->
    <div class="bg-gray-800 p-4 rounded-xl border border-gray-700 flex flex-col md:flex-row gap-3">

        <!-- SEARCH -->
        <input type="text" id="searchInput"
            placeholder="Cari produk..."
            class="flex-1 px-4 py-2 rounded-xl bg-gray-900 border border-gray-600 text-white focus:ring-2 focus:ring-blue-500">

    </div>

    <!-- TABLE -->
    <div class="bg-gray-800 rounded-xl overflow-hidden border border-gray-700 shadow">

        <table class="w-full text-sm text-white">

            <!-- HEADER -->
            <thead class="bg-gray-700 text-gray-300 text-xs uppercase">
                <tr>
                    <th class="p-3 text-left">Produk</th>
                    <th class="p-3 text-center">Harga</th>
                    <th class="p-3 text-center">Stok</th>
                    <th class="p-3 text-center">Foto</th>
                    <th class="p-3 text-center">Aksi</th>
                </tr>
            </thead>

            <!-- BODY -->
            <tbody id="productTable">
                @foreach($products as $p)
                <tr class="border-t border-gray-700 hover:bg-gray-700/40 transition">

                    <!-- NAMA -->
                    <td class="p-3 font-medium">
                        {{ $p->name }}
                    </td>

                    <!-- HARGA -->
                    <td class="p-3 text-center text-green-400 font-semibold">
                        Rp {{ number_format($p->price,0,',','.') }}
                    </td>

                    <!-- STOCK -->
                    <td class="p-3 text-center">
                        <span class="px-2 py-1 rounded bg-blue-500/20 text-blue-300 text-xs">
                            {{ $p->stock }}
                        </span>
                    </td>

                    <!-- FOTO -->
                    <td class="p-3 text-center">
                        <img src="{{ $p->image_url }}"
                             class="w-14 h-14 object-cover rounded-xl border border-gray-600 shadow hover:scale-110 transition">
                    </td>

                    <!-- AKSI -->
                    <td class="p-3 text-center">
                        <div class="flex justify-center gap-2">

                            <a href="{{ route('products.edit', $p->product_id) }}"
                               class="bg-yellow-500 hover:bg-yellow-600 px-3 py-1 rounded-lg text-xs font-semibold">
                                Edit
                            </a>

                            <form action="{{ route('products.destroy', $p->product_id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button class="bg-red-500 hover:bg-red-600 px-3 py-1 rounded-lg text-xs font-semibold">
                                    Hapus
                                </button>
                            </form>

                        </div>
                    </td>

                </tr>
                @endforeach
            </tbody>

        </table>

    </div>

</div>

<!-- 🔥 SEARCH SCRIPT -->
<script>
document.getElementById('searchInput').addEventListener('keyup', function() {
    let value = this.value.toLowerCase();
    let rows = document.querySelectorAll('#productTable tr');

    rows.forEach(row => {
        let text = row.innerText.toLowerCase();
        row.style.display = text.includes(value) ? '' : 'none';
    });
});
</script>

@endsection