@extends('layouts.dashboard')

@section('content')

<div class="max-w-2xl mx-auto">

    <!-- HEADER -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold">Edit Produk</h1>
        <p class="text-gray-400 text-sm">Perbarui data produk</p>
    </div>

    <!-- SUCCESS ALERT -->
    @if(session('success'))
    <div class="bg-green-500/20 text-green-400 p-3 rounded mb-4">
        {{ session('success') }}
    </div>
    @endif

    <!-- CARD -->
    <div class="bg-[#1e293b] p-6 rounded-2xl shadow-xl border border-gray-700">

        <form method="POST" action="{{ route('products.update', $product->product_id) }}" 
            enctype="multipart/form-data"
            class="space-y-5">
            @csrf
            @method('PUT')

            <!-- NAMA -->
            <div>
                <label class="block text-sm text-gray-400 mb-1">Nama Produk</label>
                <input type="text" name="name" value="{{ old('name', $product->name) }}"
                    class="w-full px-4 py-2 rounded-xl bg-gray-800 border border-gray-600 
                           focus:ring-2 focus:ring-blue-500 focus:outline-none text-white">
                
                @error('name')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- HARGA -->
            <div>
                <label class="block text-sm text-gray-400 mb-1">Harga</label>
                <input type="number" name="price" value="{{ old('price', $product->price) }}"
                    class="w-full px-4 py-2 rounded-xl bg-gray-800 border border-gray-600 
                           focus:ring-2 focus:ring-green-500 focus:outline-none text-white">
                
                @error('price')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- STOCK -->
            <div>
                <label class="block text-sm text-gray-400 mb-1">Stok</label>
                <input type="number" name="stock" value="{{ old('stock', $product->stock) }}"
                    class="w-full px-4 py-2 rounded-xl bg-gray-800 border border-gray-600 
                           focus:ring-2 focus:ring-purple-500 focus:outline-none text-white">
                
                @error('stock')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <!-- FOTO -->
<div>
    <label class="block text-sm text-gray-400 mb-2">Foto Produk</label>

    <!-- PREVIEW -->
    <div class="mb-3">
        @php use Illuminate\Support\Str; @endphp

        <img id="preview-image"
     src="{{ $product->image_url }}"
     class="w-28 h-28 object-cover rounded-xl border border-gray-600">

    </div>

    <!-- INPUT -->
    <input type="file" name="image" id="image-input"
        class="w-full text-sm text-gray-300 bg-gray-800 border border-gray-600 rounded-xl p-2">

    <p class="text-xs text-gray-500 mt-1">
        Kosongkan jika tidak ingin mengganti gambar
    </p>

    @error('image')
        <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
    @enderror
</div>

            <!-- BUTTON -->
            <div class="flex justify-end gap-2 pt-4">

                <a href="{{ route('products.index') }}"
                    class="px-4 py-2 rounded-xl border border-gray-600 text-gray-300 hover:bg-gray-700">
                    Batal
                </a>

                <button type="submit"
                    class="bg-green-500 hover:bg-green-600 px-5 py-2 rounded-xl text-white shadow">
                    Update Produk
                </button>

            </div>

        </form>

    </div>

</div>
<script>
    document.getElementById('image-input').addEventListener('change', function(e) {
        const file = e.target.files[0];
    
        if (file) {
            const reader = new FileReader();
    
            reader.onload = function(e) {
                document.getElementById('preview-image').src = e.target.result;
            }
    
            reader.readAsDataURL(file);
        }
    });
    </script>
@endsection