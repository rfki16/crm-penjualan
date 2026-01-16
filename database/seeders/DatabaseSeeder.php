<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Buat Customer
        $customers = [];

        $customers[] = Customer::create([
            'name' => 'John Doe',
            'status' => 'Member',
            'phone' => '081234567890',
            'address' => 'Jl. Merdeka No. 123, Jakarta'
        ]);

        $customers[] = Customer::create([
            'name' => 'Jane Smith',
            'status' => 'Non-Member',
            'phone' => '081234567891',
            'address' => 'Jl. Sudirman No. 456, Jakarta'
        ]);

        $customers[] = Customer::create([
            'name' => 'Ahmad Wijaya',
            'status' => 'Member',
            'phone' => '081234567892',
            'address' => 'Jl. Gatot Subroto No. 789, Jakarta'
        ]);

        $customers[] = Customer::create([
            'name' => 'Siti Nurhaliza',
            'status' => 'Non-Member',
            'phone' => '081234567893',
            'address' => 'Jl. Thamrin No. 321, Jakarta'
        ]);

        $customers[] = Customer::create([
            'name' => 'Budi Santoso',
            'status' => 'Member',
            'phone' => '081234567894',
            'address' => 'Jl. Kuningan No. 654, Jakarta'
        ]);

        // Buat Produk
        $products = [];

        $products[] = Product::create(['name' => 'Laptop ASUS ROG', 'price' => 15000000, 'stock' => 10]);
        $products[] = Product::create(['name' => 'Mouse Logitech', 'price' => 250000, 'stock' => 50]);
        $products[] = Product::create(['name' => 'Keyboard Mechanical', 'price' => 750000, 'stock' => 30]);
        $products[] = Product::create(['name' => 'Monitor LG 24 inch', 'price' => 2500000, 'stock' => 20]);
        $products[] = Product::create(['name' => 'Headset Gaming', 'price' => 500000, 'stock' => 40]);
        $products[] = Product::create(['name' => 'Webcam HD', 'price' => 800000, 'stock' => 15]);
        $products[] = Product::create(['name' => 'SSD 512GB', 'price' => 1200000, 'stock' => 25]);
        $products[] = Product::create(['name' => 'RAM 16GB DDR4', 'price' => 1000000, 'stock' => 35]);
        $products[] = Product::create(['name' => 'Power Supply 650W', 'price' => 1500000, 'stock' => 12]);
        $products[] = Product::create(['name' => 'Case Gaming RGB', 'price' => 800000, 'stock' => 18]);

        // Buat Transaksi
        for ($i = 0; $i < 5; $i++) {
            $customer = $customers[array_rand($customers)];

            $sale = Sale::create([
                'customer_id' => $customer->id,
                'sale_date' => now()->subDays(rand(0, 30)),
                'subtotal' => 0,
                'discount' => 0,
                'total' => 0
            ]);

            // Tambahkan 2-4 item per transaksi
            $itemCount = rand(2, 4);
            for ($j = 0; $j < $itemCount; $j++) {
                $product = $products[array_rand($products)];
                $quantity = rand(1, 3);

                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'price' => $product->price,
                    'subtotal' => $product->price * $quantity
                ]);

                // Kurangi stok (jaga jangan minus)
                $product->stock = max(0, $product->stock - $quantity);
                $product->save();
            }

            // Hitung total (pastikan method ini ada di model Sale)
            if (method_exists($sale, 'calculateTotals')) {
                $sale->calculateTotals();
                $sale->save();
            }
        }
    }
}
