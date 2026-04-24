<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $products = Product::with('category')->latest()->paginate(15);

        return view('admin.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $categories = Category::orderBy('name')->get();

        return view('admin.products.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'unit' => 'required|string|max:100',
            'price' => 'required|numeric|min:0',
            'price_b2b' => 'nullable|numeric|min:0',
            'min_order_b2c' => 'nullable|integer|min:1',
            'max_order_b2c' => 'nullable|integer|min:1',
            'min_order_b2b' => 'nullable|integer|min:1',
            'max_order_b2b' => 'nullable|integer|min:1',
            'stock_b2c' => 'required|integer|min:0',
            'stock_b2b' => 'required|integer|min:0',
            'weight' => 'required|numeric|min:0.01',
            'description' => 'required|string',
            'image' => 'nullable|image|max:2048',
            'is_featured' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $validated['slug'] = Product::generateSlug($validated['name'] ?? $request->name);
        $validated['price_b2b'] = $validated['price_b2b'] ?? null;
        $validated['min_order_b2c'] = $validated['min_order_b2c'] ?? 10;
        $validated['max_order_b2c'] = $validated['max_order_b2c'] ?? null;
        $validated['min_order_b2b'] = $validated['min_order_b2b'] ?? 1;
        $validated['max_order_b2b'] = $validated['max_order_b2b'] ?? null;
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_active'] = $request->boolean('is_active');

        Product::create($validated);

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): View
    {
        $product = Product::findOrFail($id);
        $categories = Category::orderBy('name')->get();

        return view('admin.products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): RedirectResponse
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'unit' => 'required|string|max:100',
            'price' => 'required|numeric|min:0',
            'price_b2b' => 'nullable|numeric|min:0',
            'min_order_b2c' => 'nullable|integer|min:1',
            'max_order_b2c' => 'nullable|integer|min:1',
            'min_order_b2b' => 'nullable|integer|min:1',
            'max_order_b2b' => 'nullable|integer|min:1',
            'stock_b2c' => 'required|integer|min:0',
            'stock_b2b' => 'required|integer|min:0',
            'weight' => 'required|numeric|min:0.01',
            'description' => 'required|string',
            'image' => 'nullable|image|max:2048',
            'is_featured' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
        ]);

        if ($request->hasFile('image')) {
            if ($product->image && str_starts_with($product->image, 'products/')) {
                Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = $request->file('image')->store('products', 'public');
        } else {
            unset($validated['image']);
        }

        $validated['slug'] = $product->slug === $validated['name'] ? $product->slug : Product::generateSlug($validated['name']);
        $validated['price_b2b'] = $validated['price_b2b'] ?? null;
        $validated['min_order_b2c'] = $validated['min_order_b2c'] ?? 10;
        $validated['max_order_b2c'] = $validated['max_order_b2c'] ?? null;
        $validated['min_order_b2b'] = $validated['min_order_b2b'] ?? 1;
        $validated['max_order_b2b'] = $validated['max_order_b2b'] ?? null;
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_active'] = $request->boolean('is_active');

        $product->update($validated);

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): RedirectResponse
    {
        $product = Product::findOrFail($id);

        if ($product->image && str_starts_with($product->image, 'products/')) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil dihapus.');
    }
}
