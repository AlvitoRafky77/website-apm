<?php

namespace App\Http\Controllers;

use App\Models\ProductReview;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $categories = \App\Models\Category::with('products')->get();
        $featuredProducts = \App\Models\Product::where('is_featured', true)->take(3)->get();

        return view('products.index', compact('categories', 'featuredProducts'));
    }

    public function show(\App\Models\Product $product)
    {
        $product->load('category');

        $reviews = ProductReview::query()
            ->with('user')
            ->approved()
            ->where('product_id', $product->id)
            ->latest()
            ->get();

        $reviewCount = $reviews->count();
        $averageRating = $reviewCount > 0 ? round($reviews->avg('rating'), 1) : null;
        $ratingDistribution = collect(range(1, 5))
            ->mapWithKeys(fn ($rating) => [$rating => $reviews->where('rating', $rating)->count()])
            ->all();

        return view('products.show', compact('product', 'reviews', 'reviewCount', 'averageRating', 'ratingDistribution'));
    }

}
