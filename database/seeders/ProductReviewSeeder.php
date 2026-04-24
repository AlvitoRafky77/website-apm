<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Product;
use App\Models\ProductReview;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ProductReviewSeeder extends Seeder
{
    public function run(): void
    {
        $products = Product::query()->take(6)->get();
        if ($products->isEmpty()) {
            return;
        }

        $users = User::query()->where('user_type', '!=', 'b2b')->orWhereNull('user_type')->take(3)->get();

        if ($users->isEmpty()) {
            $users = collect([
                User::firstOrCreate(
                    ['email' => 'b2c.reviewer1@apm.local'],
                    [
                        'name' => 'Rina Pelanggan',
                        'password' => Hash::make('password'),
                        'user_type' => 'b2c',
                        'is_active' => true,
                    ]
                ),
                User::firstOrCreate(
                    ['email' => 'b2c.reviewer2@apm.local'],
                    [
                        'name' => 'Andi Customer',
                        'password' => Hash::make('password'),
                        'user_type' => 'b2c',
                        'is_active' => true,
                    ]
                ),
            ]);
        }

        $dummy = [
            ['rating' => 5, 'title' => 'Kualitas mantap', 'comment' => 'Produknya segar, pengiriman cepat, dan packing rapi. Akan repeat order lagi.'],
            ['rating' => 4, 'title' => 'Sesuai deskripsi', 'comment' => 'Berat dan kualitas sesuai, cocok untuk kebutuhan dapur harian.'],
            ['rating' => 5, 'title' => 'Recommended', 'comment' => 'Rasa oke, ukuran sesuai, customer service juga responsif.'],
            ['rating' => 4, 'title' => 'Bagus', 'comment' => 'Sudah beberapa kali beli dan konsisten kualitasnya.'],
            ['rating' => 5, 'title' => 'Fresh dan bersih', 'comment' => 'Produk datang dalam kondisi dingin dan fresh. Sangat puas.'],
        ];

        foreach ($products as $index => $product) {
            $reviewsPerProduct = random_int(2, 4);

            for ($i = 0; $i < $reviewsPerProduct; $i++) {
                $user = $users[($index + $i) % $users->count()];
                $payload = $dummy[($index + $i) % count($dummy)];

                $order = Order::firstOrCreate(
                    [
                        'order_id' => 'DUMMY-REVIEW-' . $product->id . '-' . $user->id,
                    ],
                    [
                        'user_id' => $user->id,
                        'items' => [[
                            'product_id' => $product->id,
                            'name' => $product->name,
                            'price' => (float) $product->price,
                            'quantity' => 1,
                            'weight' => (float) ($product->weight ?? 1),
                            'unit' => $product->unit ?? 'Kg',
                        ]],
                        'subtotal' => (float) $product->price,
                        'shipping_cost' => 0,
                        'total' => (float) $product->price,
                        'recipient_name' => $user->name,
                        'recipient_phone' => $user->phone ?? '081200000000',
                        'recipient_city' => 'Pekanbaru',
                        'recipient_address' => $user->address ?? 'Alamat Dummy',
                        'payment_method' => 'midtrans',
                        'payment_status' => 'paid',
                        'status' => 'delivered',
                        'paid_at' => now()->subDays(random_int(3, 40)),
                        'user_confirmed' => true,
                        'user_confirmed_at' => now()->subDays(random_int(2, 20)),
                    ]
                );

                ProductReview::firstOrCreate(
                    [
                        'product_id' => $product->id,
                        'user_id' => $user->id,
                        'order_id' => $order->id,
                    ],
                    [
                        'rating' => $payload['rating'],
                        'title' => $payload['title'],
                        'comment' => $payload['comment'],
                        'is_approved' => true,
                    ]
                );
            }
        }
    }
}
