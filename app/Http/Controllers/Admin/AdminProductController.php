<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class AdminProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->latest()->paginate(10);
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'category_id'   => 'required|exists:categories,id',
            'price'         => 'required|numeric|min:0',
            'price_b2b'     => 'nullable|numeric|min:0',
            'unit'          => 'required|string|max:50',
            'weight'        => 'required|numeric|min:0.01',
            'description'   => 'required|string',
            'image'         => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'is_featured'   => 'nullable|boolean',
            'is_active'     => 'nullable|boolean',
            'stock_b2c'     => 'required|integer|min:0',
            'stock_b2b'     => 'required|integer|min:0',
            'min_order_b2c' => 'nullable|integer|min:1',
            'max_order_b2c' => 'nullable|integer|min:1',
            'min_order_b2b' => 'nullable|integer|min:1',
            'max_order_b2b' => 'nullable|integer|min:1',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_active']   = $request->boolean('is_active');
        $validated['min_order_b2c'] = $validated['min_order_b2c'] ?? 10;
        $validated['max_order_b2c'] = $validated['max_order_b2c'] ?? null;
        $validated['min_order_b2b'] = $validated['min_order_b2b'] ?? 1;
        $validated['max_order_b2b'] = $validated['max_order_b2b'] ?? null;

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $validated['image'] = Storage::url($path);
        }

        Product::create($validated);

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil ditambahkan!');
    }

    public function edit(Product $product)
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'category_id'   => 'required|exists:categories,id',
            'price'         => 'required|numeric|min:0',
            'price_b2b'     => 'nullable|numeric|min:0',
            'unit'          => 'required|string|max:50',
            'weight'        => 'required|numeric|min:0.01',
            'description'   => 'required|string',
            'image'         => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'is_featured'   => 'nullable|boolean',
            'is_active'     => 'nullable|boolean',
            'stock_b2c'     => 'required|integer|min:0',
            'stock_b2b'     => 'required|integer|min:0',
            'min_order_b2c' => 'nullable|integer|min:1',
            'max_order_b2c' => 'nullable|integer|min:1',
            'min_order_b2b' => 'nullable|integer|min:1',
            'max_order_b2b' => 'nullable|integer|min:1',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_active']   = $request->boolean('is_active');
        $validated['min_order_b2c'] = $validated['min_order_b2c'] ?? 10;
        $validated['max_order_b2c'] = $validated['max_order_b2c'] ?? null;
        $validated['min_order_b2b'] = $validated['min_order_b2b'] ?? 1;
        $validated['max_order_b2b'] = $validated['max_order_b2b'] ?? null;

        if ($request->hasFile('image')) {
            // Hapus gambar lama jika bukan URL eksternal
            if ($product->image && !str_starts_with($product->image, 'http')) {
                $oldPath = str_replace('/storage/', '', $product->image);
                Storage::disk('public')->delete($oldPath);
            }
            $path = $request->file('image')->store('products', 'public');
            $validated['image'] = Storage::url($path);
        }

        $product->update($validated);

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil diperbarui!');
    }

    public function destroy(Product $product)
    {
        if ($product->image && !str_starts_with($product->image, 'http')) {
            $oldPath = str_replace('/storage/', '', $product->image);
            Storage::disk('public')->delete($oldPath);
        }
        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil dihapus.');
    }
}
