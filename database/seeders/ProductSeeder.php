<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
        \App\Models\Product::truncate();
        \App\Models\Category::truncate();
        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();

        $poultry = \App\Models\Category::create(['name' => 'Poultry', 'slug' => 'poultry']);
        $seafood = \App\Models\Category::create(['name' => 'Seafood', 'slug' => 'seafood']);
        $meat = \App\Models\Category::create(['name' => 'Meat', 'slug' => 'meat']);

        // POULTRY
        \App\Models\Product::create([
            'category_id' => $poultry->id,
            'name' => 'Dada Ayam Fillet Premium',
            'slug' => 'dada-ayam-fillet',
            'description' => 'Potongan dada ayam segar tanpa tulang dan kulit, sangat empuk dan cocok untuk berbagai olahan sehat. Diproses secara higienis dari peternakan pilihan dengan standar kualitas tinggi.',
            'price' => 45000.00,
            'unit' => '1 Kg',
            'image' => 'https://images.unsplash.com/photo-1604503468506-a8da13d82791?ixlib=rb-1.2.1&auto=format&fit=crop&w=400&q=80',
            'is_featured' => true,
            'is_active' => true,
        ]);
        \App\Models\Product::create([
            'category_id' => $poultry->id,
            'name' => 'Paha Ayam Atas Bawah',
            'slug' => 'paha-ayam',
            'description' => 'Potongan paha ayam lezat dengan daging yang lebih juicy.',
            'price' => 42000.00,
            'unit' => '1 Kg',
            'image' => 'https://images.unsplash.com/photo-1587593810167-a84920ea0781?ixlib=rb-1.2.1&auto=format&fit=crop&w=400&q=80',
            'is_featured' => false,
            'is_active' => true,
        ]);
        \App\Models\Product::create([
            'category_id' => $poultry->id,
            'name' => 'Sayap Ayam Utuh',
            'slug' => 'sayap-ayam',
            'description' => 'Sayap ayam segar pilihan ideal untuk diolah aneka bumbu masakan.',
            'price' => 38000.00,
            'unit' => '1 Kg',
            'image' => 'https://images.unsplash.com/photo-1574672280600-4accfa5b6f98?ixlib=rb-1.2.1&auto=format&fit=crop&w=400&q=80',
            'is_featured' => false,
            'is_active' => true,
        ]);

        // SEAFOOD
        \App\Models\Product::create([
            'category_id' => $seafood->id,
            'name' => 'Udang Windu Segar',
            'slug' => 'udang-windu',
            'description' => 'Udang windu kualitas premium hasil tangkapan laut terbaik. Segar, lezat, dan kaya nutrisi. Sangat ideal untuk sajian restoran maupun konsumsi rumah tangga.',
            'price' => 125000.00,
            'unit' => '1 Kg',
            'image' => 'https://images.unsplash.com/photo-1565680018434-b513d5e5fd47?ixlib=rb-1.2.1&auto=format&fit=crop&w=400&q=80',
            'is_featured' => true,
            'is_active' => true,
        ]);
        \App\Models\Product::create([
            'category_id' => $seafood->id,
            'name' => 'Cumi-cumi Biasa',
            'slug' => 'cumi-cumi',
            'description' => 'Cumi-cumi segar ukuran sedang dan besar.',
            'price' => 85000.00,
            'unit' => '1 Kg',
            'image' => 'https://images.unsplash.com/photo-1599084993091-1cb5c0721cc6?ixlib=rb-1.2.1&auto=format&fit=crop&w=400&q=80',
            'is_featured' => false,
            'is_active' => true,
        ]);
        \App\Models\Product::create([
            'category_id' => $seafood->id,
            'name' => 'Kepiting Soka',
            'slug' => 'kepiting-soka',
            'description' => 'Kepiting cangkang lunak pilihan terbaik yang bisa dimakan utuh. Renyah saat digoreng, lembut di dalam. Favorit para pecinta seafood di seluruh Indonesia.',
            'price' => 140000.00,
            'unit' => '500 Gram',
            'image' => 'https://plus.unsplash.com/premium_photo-1669687759882-f5ce8be381eb?ixlib=rb-1.2.1&auto=format&fit=crop&w=400&q=80',
            'is_featured' => true,
            'is_active' => true,
        ]);

        // MEAT
        \App\Models\Product::create([
            'category_id' => $meat->id,
            'name' => 'Daging Sapi Has Dalam',
            'slug' => 'daging-sapi-tenderloin',
            'description' => 'Daging sapi lokal pilihan dengan kualitas premium lapis.',
            'price' => 150000.00,
            'unit' => '1 Kg',
            'image' => 'https://images.unsplash.com/photo-1603048297172-c92544798d5e?ixlib=rb-1.2.1&auto=format&fit=crop&w=400&q=80',
            'is_featured' => false,
            'is_active' => true,
        ]);
        \App\Models\Product::create([
            'category_id' => $meat->id,
            'name' => 'Daging Sapi Cincang',
            'slug' => 'daging-sapi-cincang',
            'description' => 'Daging sapi kualitas baik digiling untuk aneka keperluan dapur harian.',
            'price' => 120000.00,
            'unit' => '1 Kg',
            'image' => 'https://images.unsplash.com/photo-1588168333986-5078d3ae3976?ixlib=rb-1.2.1&auto=format&fit=crop&w=400&q=80',
            'is_featured' => false,
            'is_active' => true,
        ]);
        \App\Models\Product::create([
            'category_id' => $meat->id,
            'name' => 'Daging Kambing Paha',
            'slug' => 'kambing-paha',
            'description' => 'Potongan paha kambing muda super empuk, cocok untuk hidangan sate.',
            'price' => 135000.00,
            'unit' => '1 Kg',
            'image' => 'https://images.unsplash.com/photo-1602434193562-b9cf6e0ad391?ixlib=rb-1.2.1&auto=format&fit=crop&w=400&q=80',
            'is_featured' => true,
            'is_active' => true,
        ]);
    }
}
