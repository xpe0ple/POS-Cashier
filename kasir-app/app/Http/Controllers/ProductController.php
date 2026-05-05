<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    // 🔥 LIST
    public function index()
    {
        $products = Product::all();
        return view('products.index', compact('products'));
    }

    // 🔥 FORM CREATE
    public function create()
    {
        return view('products.create');
    }

    // 🔥 SIMPAN
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        Product::create([
            'name' => $request->name,
            'price' => $request->price,
            'stock' => $request->stock,
            'image' => $imagePath // 🔥 INI YANG PENTING
        ]);


        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil ditambahkan');
    }

    // 🔥 EDIT
    public function edit($id)
    {
        $product = Product::where('product_id', $id)->firstOrFail();
        return view('products.edit', compact('product'));
    }

    // 🔥 UPDATE
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        // 🔥 kalau upload gambar baru
        if ($request->hasFile('image')) {

            // hapus gambar lama
            if ($product->image && str_starts_with($product->image, 'products/')) {
                Storage::disk('public')->delete($product->image);
            }

            $imagePath = $request->file('image')->store('products', 'public');
        } else {
            $imagePath = $product->image;
        }

        $product->update([
            'name' => $request->name,
            'price' => $request->price,
            'stock' => $request->stock,
            'image' => $imagePath
        ]);
        // dd($product->fresh());
        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil diupdate');
    }

    // 🔥 DELETE
    public function destroy($id)
    {
        $product = Product::where('product_id', $id)->firstOrFail();

        // hapus gambar
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil dihapus');
    }
}
