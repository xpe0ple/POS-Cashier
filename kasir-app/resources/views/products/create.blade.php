@extends('layouts.dashboard')

@section('content')

<div class="max-w-2xl mx-auto">

    <!-- HEADER -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold">Tambah Produk</h1>
        <p class="text-gray-400 text-sm">Tambahkan produk baru ke dalam sistem</p>
    </div>

    <!-- CARD -->
    <div class="bg-[#1e293b] p-6 rounded-2xl shadow-xl border border-gray-700">

        <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data"  class="space-y-5">
            @csrf

            <!-- NAMA -->
            <div>
                <label class="block text-sm text-gray-400 mb-1">Nama Produk</label>
                <input type="text" name="name"
                    class="w-full px-4 py-2 rounded-xl bg-gray-800 border border-gray-600 
                           focus:ring-2 focus:ring-blue-500 focus:outline-none text-white"
                    placeholder="Contoh: Dimsum Ayam">
            </div>

            <!-- HARGA -->
            <div>
                <label class="block text-sm text-gray-400 mb-1">Harga</label>
                <input type="number" name="price"
                    class="w-full px-4 py-2 rounded-xl bg-gray-800 border border-gray-600 
                           focus:ring-2 focus:ring-green-500 focus:outline-none text-white"
                    placeholder="Contoh: 15000">
            </div>

            <!-- STOCK -->
            <div>
                <label class="block text-sm text-gray-400 mb-1">Stok</label>
                <input type="number" name="stock"
                    class="w-full px-4 py-2 rounded-xl bg-gray-800 border border-gray-600 
                           focus:ring-2 focus:ring-purple-500 focus:outline-none text-white"
                    placeholder="Contoh: 20">
            </div>
            <div>
                <label class="block text-sm text-gray-400 mb-1">Foto Produk</label>
            
                <input type="file" name="image"
                    class="w-full text-sm text-gray-300 bg-gray-800 border border-gray-600 rounded-xl p-2">
            
            </div>

            <!-- BUTTON -->
            <div class="flex justify-end gap-2 pt-4">

                <a href="{{ route('products.index') }}"
                    class="px-4 py-2 rounded-xl border border-gray-600 text-gray-300 hover:bg-gray-700">
                    Batal
                </a>

                <button type="submit"
                    class="bg-blue-500 hover:bg-blue-600 px-5 py-2 rounded-xl text-white shadow">
                    Simpan Produk
                </button>

            </div>
            

        </form>

    </div>

</div>

@endsection