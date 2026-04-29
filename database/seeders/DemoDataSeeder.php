<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\City;
use App\Models\Address;
use App\Models\Design;
use App\Models\DesignImage;
use App\Models\DesignOption;
use App\Models\Measurement;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Models\Review;
use App\Models\Invoice;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DemoDataSeeder extends Seeder
{
    public function run()
    {
        // Get existing users and create additional ones
        echo "👥 Getting and Creating Users...\n";

        $superAdmin = User::where('email', 'admin@kandura.com')->first();
        $users = [];
        $users[] = User::where('email', 'user@kandura.com')->first();

        // Create additional demo users
        $users[] = User::create([
            'name' => 'Ahmad Khatib',
            'email' => 'ahmad@example.com',
            'phone' => '+963992345678',
            'password' => Hash::make('password'),
            'is_active' => true,
            'preferred_locale' => 'en',
        ]);
        $users[1]->assignRole('user');

        $users[] = User::create([
            'name' => 'Khaled Homsi',
            'email' => 'khaled@example.com',
            'phone' => '+963993456789',
            'password' => Hash::make('password'),
            'is_active' => true,
            'preferred_locale' => 'en',
        ]);
        $users[2]->assignRole('user');

        $users[] = User::create([
            'name' => 'Abdullah Shami',
            'email' => 'abdullah@example.com',
            'phone' => '+963994567890',
            'password' => Hash::make('password'),
            'is_active' => true,
            'preferred_locale' => 'en',
        ]);
        $users[3]->assignRole('user');

        // 3️⃣ Addresses
        echo "📮 Creating Addresses...\n";
        $allCities = City::all();

        Address::create([
            'user_id' => $users[0]->id,
            'city_id' => $allCities[0]->id,
            'district' => 'Mazzeh',
            'street' => 'Al-Thawra Street',
            'building_number' => '1234',
            'latitude' => 33.5138,
            'longitude' => 36.2765,
        ]);

        Address::create([
            'user_id' => $users[1]->id,
            'city_id' => $allCities[1]->id,
            'district' => 'Aziziyah',
            'street' => 'Baron Street',
            'building_number' => '5678',
            'latitude' => 36.2021,
            'longitude' => 37.1343,
        ]);

        Address::create([
            'user_id' => $users[2]->id,
            'city_id' => $allCities[2]->id,
            'district' => 'Al-Waer',
            'street' => 'Hamra Street',
            'building_number' => '9012',
            'latitude' => 34.7298,
            'longitude' => 36.7184,
        ]);

        Address::create([
            'user_id' => $users[0]->id, // Second address for same user
            'city_id' => $allCities[0]->id,
            'district' => 'Abu Rummaneh',
            'street' => 'Baghdad Street',
            'building_number' => '3456',
            'latitude' => 33.5024,
            'longitude' => 36.2989,
        ]);

        Address::create([
            'user_id' => $users[3]->id,
            'city_id' => $allCities[3]->id,
            'district' => 'Zira\'a',
            'street' => 'Corniche Street',
            'building_number' => '7890',
            'latitude' => 35.5182,
            'longitude' => 35.7750,
        ]);

        // 4️⃣ Design Options
        echo "🎨 Creating Design Options...\n";

        // Colors
        $colors = [
            ['name' => ['en' => 'White', 'ar' => 'أبيض'], 'type' => 'color', 'color_code' => '#FFFFFF'],
            ['name' => ['en' => 'Black', 'ar' => 'أسود'], 'type' => 'color', 'color_code' => '#000000'],
            ['name' => ['en' => 'Brown', 'ar' => 'بني'], 'type' => 'color', 'color_code' => '#8B4513'],
            ['name' => ['en' => 'Beige', 'ar' => 'بيج'], 'type' => 'color', 'color_code' => '#F5F5DC'],
            ['name' => ['en' => 'Navy Blue', 'ar' => 'كحلي'], 'type' => 'color', 'color_code' => '#000080'],
        ];

        foreach ($colors as $color) {
            DesignOption::create($color);
        }

        // Fabric Types
        $fabrics = [
            ['name' => ['en' => 'Cotton', 'ar' => 'قطن'], 'type' => 'fabric_type'],
            ['name' => ['en' => 'Silk', 'ar' => 'حرير'], 'type' => 'fabric_type'],
            ['name' => ['en' => 'Wool', 'ar' => 'صوف'], 'type' => 'fabric_type'],
        ];

        foreach ($fabrics as $fabric) {
            DesignOption::create($fabric);
        }

        // Sleeve Types
        $sleeves = [
            ['name' => ['en' => 'Long Sleeve', 'ar' => 'كم طويل'], 'type' => 'sleeve_type'],
            ['name' => ['en' => 'Short Sleeve', 'ar' => 'كم قصير'], 'type' => 'sleeve_type'],
        ];

        foreach ($sleeves as $sleeve) {
            DesignOption::create($sleeve);
        }

        // 5️⃣ Designs
        echo "👔 Creating Designs...\n";

        $designs = [];
        $designs[] = Design::create([
            'user_id' => $users[0]->id,
            'name' => ['en' => 'Classic White', 'ar' => 'الكلاسيكية البيضاء'],
            'description' => ['en' => 'Traditional white kandura perfect for all occasions', 'ar' => 'كندورة بيضاء تقليدية مناسبة لجميع المناسبات'],
            'price' => 299.00,
        ]);

        $designs[] = Design::create([
            'user_id' => $users[1]->id,
            'name' => ['en' => 'Elegant Black', 'ar' => 'الأنيقة السوداء'],
            'description' => ['en' => 'Modern black kandura for formal events', 'ar' => 'كندورة سوداء عصرية للمناسبات الرسمية'],
            'price' => 349.00,
        ]);

        $designs[] = Design::create([
            'user_id' => $users[2]->id,
            'name' => ['en' => 'Royal Brown', 'ar' => 'البنية الملكية'],
            'description' => ['en' => 'Premium brown kandura with silk fabric', 'ar' => 'كندورة بنية فاخرة بقماش حريري'],
            'price' => 450.00,
        ]);

        $designs[] = Design::create([
            'user_id' => $users[0]->id,
            'name' => ['en' => 'Summer Beige', 'ar' => 'البيج الصيفية'],
            'description' => ['en' => 'Light beige kandura perfect for summer', 'ar' => 'كندورة بيج خفيفة مثالية للصيف'],
            'price' => 275.00,
        ]);

        $designs[] = Design::create([
            'user_id' => $users[3]->id,
            'name' => ['en' => 'Navy Premium', 'ar' => 'الكحلية الفاخرة'],
            'description' => ['en' => 'Premium navy kandura with wool fabric', 'ar' => 'كندورة كحلية فاخرة بقماش صوفي'],
            'price' => 399.00,
        ]);

        // Link designs to measurements
        $allMeasurements = Measurement::all();
        foreach ($designs as $design) {
            foreach ($allMeasurements as $measurement) {
                $design->measurements()->attach($measurement->id, ['quantity' => rand(5, 20)]);
            }
        }

        // Link designs to options
        $allOptions = DesignOption::all();
        foreach ($designs as $index => $design) {
            // Each design has color + fabric + sleeve type
            $design->designOptions()->attach([
                $allOptions[$index]->id, // Color
                $allOptions[5 + ($index % 3)]->id, // Fabric
                $allOptions[8 + ($index % 2)]->id, // Sleeve
            ]);
        }

        // 6️⃣ Design Images
        echo "📷 Creating Design Images...\n";
        foreach ($designs as $design) {
            DesignImage::create([
                'design_id' => $design->id,
                'image_path' => 'designs/design_' . $design->id . '_main.jpg',
                'is_primary' => true,
            ]);

            DesignImage::create([
                'design_id' => $design->id,
                'image_path' => 'designs/design_' . $design->id . '_side.jpg',
                'is_primary' => false,
            ]);
        }

        // 7️⃣ Coupons
        echo "🎟️ Creating Coupons...\n";

        $coupons = [];
        $coupons[] = Coupon::create([
            'code' => 'WELCOME10',
            'description' => 'Welcome discount for new customers',
            'type' => 'percentage',
            'value' => 10.00,
            'max_uses' => 100,
            'used_count' => 15,
            'max_uses_per_user' => 1,
            'starts_at' => now()->subDays(30),
            'expires_at' => now()->addDays(30),
            'min_order_amount' => 200.00,
            'is_active' => true,
        ]);

        $coupons[] = Coupon::create([
            'code' => 'SUMMER20',
            'description' => 'Big summer discount',
            'type' => 'percentage',
            'value' => 20.00,
            'max_uses' => 50,
            'used_count' => 8,
            'max_uses_per_user' => 2,
            'starts_at' => now()->subDays(15),
            'expires_at' => now()->addDays(45),
            'min_order_amount' => 500.00,
            'is_active' => true,
        ]);

        $coupons[] = Coupon::create([
            'code' => 'FLASH50',
            'description' => 'Flash discount 50 SAR',
            'type' => 'fixed',
            'value' => 50.00,
            'max_uses' => 200,
            'used_count' => 45,
            'max_uses_per_user' => 1,
            'starts_at' => now()->subDays(5),
            'expires_at' => now()->addDays(10),
            'min_order_amount' => 300.00,
            'is_active' => true,
        ]);

        $coupons[] = Coupon::create([
            'code' => 'VIP100',
            'description' => 'Special VIP coupon',
            'type' => 'fixed',
            'value' => 100.00,
            'max_uses' => 10,
            'used_count' => 2,
            'max_uses_per_user' => 1,
            'starts_at' => now()->subDays(60),
            'expires_at' => now()->addDays(90),
            'min_order_amount' => 800.00,
            'is_active' => true,
        ]);

        $coupons[] = Coupon::create([
            'code' => 'EXPIRED',
            'description' => 'Expired coupon',
            'type' => 'percentage',
            'value' => 15.00,
            'max_uses' => 100,
            'used_count' => 25,
            'max_uses_per_user' => 1,
            'starts_at' => now()->subDays(90),
            'expires_at' => now()->subDays(10),
            'is_active' => false,
        ]);

        // Personal coupon for first user
        $coupons[3]->allowedUsers()->attach($users[0]->id);

        // 8️⃣ Wallets (created automatically with users)
        echo "💰 Updating Wallet Balances...\n";
        $wallets = [];
        $allUsers = User::all(); // Get all users in the system
        foreach ($allUsers as $user) {
            $wallet = $user->wallet;
            if (!$wallet) {
                // Create wallet if it doesn't exist
                $wallet = Wallet::create([
                    'user_id' => $user->id,
                    'balance' => rand(0, 1000),
                ]);
            } else {
                $wallet->update(['balance' => rand(0, 1000)]);
            }
            $wallets[] = $wallet;
        }

        // 9️⃣ Orders
        echo "🛒 Creating Orders...\n";

        $addresses = Address::all();
        $orders = [];

        // Completed order
        $orders[] = Order::create([
            'order_number' => 'ORD-000001',
            'user_id' => $users[0]->id,
            'address_id' => $addresses[0]->id,
            'subtotal' => 598.00,
            'tax' => 89.70,
            'shipping_cost' => 20.00,
            'discount' => 59.80,
            'total' => 647.90,
            'payment_method' => 'card',
            'payment_status' => 'paid',
            'paid_at' => now()->subDays(10),
            'status' => 'completed',
            'coupon_id' => $coupons[0]->id,
            'confirmed_at' => now()->subDays(10),
            'processing_at' => now()->subDays(9),
            'completed_at' => now()->subDays(5),
        ]);

        // Processing order
        $orders[] = Order::create([
            'order_number' => 'ORD-000002',
            'user_id' => $users[1]->id,
            'address_id' => $addresses[1]->id,
            'subtotal' => 349.00,
            'tax' => 52.35,
            'shipping_cost' => 20.00,
            'discount' => 0,
            'total' => 421.35,
            'payment_method' => 'wallet',
            'payment_status' => 'paid',
            'paid_at' => now()->subDays(3),
            'status' => 'processing',
            'confirmed_at' => now()->subDays(3),
            'processing_at' => now()->subDays(2),
        ]);

        // Confirmed order
        $orders[] = Order::create([
            'order_number' => 'ORD-000003',
            'user_id' => $users[2]->id,
            'address_id' => $addresses[2]->id,
            'subtotal' => 450.00,
            'tax' => 67.50,
            'shipping_cost' => 20.00,
            'discount' => 50.00,
            'total' => 487.50,
            'payment_method' => 'card',
            'payment_status' => 'paid',
            'paid_at' => now()->subDays(1),
            'status' => 'confirmed',
            'coupon_id' => $coupons[2]->id,
            'confirmed_at' => now()->subDays(1),
        ]);

        // Pending order
        $orders[] = Order::create([
            'order_number' => 'ORD-000004',
            'user_id' => $users[0]->id,
            'address_id' => $addresses[3]->id,
            'subtotal' => 674.00,
            'tax' => 101.10,
            'shipping_cost' => 20.00,
            'discount' => 100.00,
            'total' => 695.10,
            'payment_method' => 'cash',
            'payment_status' => 'pending',
            'status' => 'pending',
            'coupon_id' => $coupons[3]->id,
        ]);

        // Cancelled order
        $orders[] = Order::create([
            'order_number' => 'ORD-000005',
            'user_id' => $users[3]->id,
            'address_id' => $addresses[4]->id,
            'subtotal' => 399.00,
            'tax' => 59.85,
            'shipping_cost' => 20.00,
            'discount' => 0,
            'total' => 478.85,
            'payment_method' => 'wallet',
            'payment_status' => 'refunded',
            'paid_at' => now()->subDays(7),
            'status' => 'cancelled',
            'confirmed_at' => now()->subDays(7),
            'cancelled_at' => now()->subDays(6),
        ]);

        // 🔟 Order Items
        echo "📦 Creating Order Items...\n";

        // Order 1
        OrderItem::create([
            'order_id' => $orders[0]->id,
            'design_id' => $designs[0]->id,
            'quantity' => 2,
            'unit_price' => 299.00,
            'total_price' => 598.00,
            'design_snapshot' => $designs[0]->toArray(),
        ]);

        // Order 2
        OrderItem::create([
            'order_id' => $orders[1]->id,
            'design_id' => $designs[1]->id,
            'quantity' => 1,
            'unit_price' => 349.00,
            'total_price' => 349.00,
            'design_snapshot' => $designs[1]->toArray(),
        ]);

        // Order 3
        OrderItem::create([
            'order_id' => $orders[2]->id,
            'design_id' => $designs[2]->id,
            'quantity' => 1,
            'unit_price' => 450.00,
            'total_price' => 450.00,
            'design_snapshot' => $designs[2]->toArray(),
        ]);

        // Order 4
        OrderItem::create([
            'order_id' => $orders[3]->id,
            'design_id' => $designs[0]->id,
            'quantity' => 1,
            'unit_price' => 299.00,
            'total_price' => 299.00,
            'design_snapshot' => $designs[0]->toArray(),
        ]);

        OrderItem::create([
            'order_id' => $orders[3]->id,
            'design_id' => $designs[3]->id,
            'quantity' => 1,
            'unit_price' => 275.00,
            'total_price' => 275.00,
            'design_snapshot' => $designs[3]->toArray(),
        ]);

        OrderItem::create([
            'order_id' => $orders[3]->id,
            'design_id' => $designs[4]->id,
            'quantity' => 1,
            'unit_price' => 100.00,
            'total_price' => 100.00,
            'design_snapshot' => $designs[4]->toArray(),
        ]);

        // Order 5
        OrderItem::create([
            'order_id' => $orders[4]->id,
            'design_id' => $designs[4]->id,
            'quantity' => 1,
            'unit_price' => 399.00,
            'total_price' => 399.00,
            'design_snapshot' => $designs[4]->toArray(),
        ]);

        // 1️⃣1️⃣ Reviews
        echo "⭐ Creating Reviews...\n";

        Review::create([
            'user_id' => $users[0]->id,
            'order_id' => $orders[0]->id,
            'rating' => 5,
            'comment' => 'Excellent quality and fast delivery! Highly recommended',
        ]);

        Review::create([
            'user_id' => $users[1]->id,
            'order_id' => $orders[1]->id,
            'rating' => 4,
            'comment' => 'Great design but delivery was slightly delayed',
        ]);

        Review::create([
            'user_id' => $users[2]->id,
            'order_id' => $orders[2]->id,
            'rating' => 5,
            'comment' => 'Premium fabric and perfect tailoring',
        ]);

        // 1️⃣2️⃣ Invoices
        echo "📄 Creating Invoices...\n";

        Invoice::create([
            'invoice_number' => 'INV-000001',
            'order_id' => $orders[0]->id,
            'total' => 647.90,
            'pdf_url' => 'invoices/INV-000001.pdf',
        ]);

        Invoice::create([
            'invoice_number' => 'INV-000002',
            'order_id' => $orders[1]->id,
            'total' => 421.35,
            'pdf_url' => 'invoices/INV-000002.pdf',
        ]);

        Invoice::create([
            'invoice_number' => 'INV-000003',
            'order_id' => $orders[2]->id,
            'total' => 487.50,
            'pdf_url' => 'invoices/INV-000003.pdf',
        ]);

        Invoice::create([
            'invoice_number' => 'INV-000005',
            'order_id' => $orders[4]->id,
            'total' => 478.85,
            'pdf_url' => 'invoices/INV-000005.pdf',
        ]);

        // 1️⃣3️⃣ Wallet Transactions
        echo "💳 Creating Wallet Transactions...\n";

        // Deposit for first user
        if (isset($wallets[0])) {
            WalletTransaction::create([
                'wallet_id' => $wallets[0]->id,
                'type' => 'deposit',
                'amount' => 500.00,
                'balance_before' => 0,
                'balance_after' => 500.00,
                'description' => 'Deposit by admin',
                'performed_by' => $superAdmin->id,
            ]);
        }

        // Payment from wallet
        if (isset($wallets[1])) {
            WalletTransaction::create([
                'wallet_id' => $wallets[1]->id,
                'type' => 'payment',
                'amount' => 421.35,
                'balance_before' => 600.00,
                'balance_after' => 178.65,
                'description' => 'Payment for order #ORD-000002',
                'order_id' => $orders[1]->id,
            ]);
        }

        // Refund for cancelled order
        if (isset($wallets[3])) {
            WalletTransaction::create([
                'wallet_id' => $wallets[3]->id,
                'type' => 'refund',
                'amount' => 478.85,
                'balance_before' => 100.00,
                'balance_after' => 578.85,
                'description' => 'Refund for cancelled order #ORD-000005',
                'order_id' => $orders[4]->id,
                'performed_by' => $superAdmin->id,
            ]);
        }
        // Register coupon usages
        $coupons[0]->users()->attach($users[0]->id, [
            'order_id' => $orders[0]->id,
            'used_at' => now()->subDays(10),
        ]);

        $coupons[2]->users()->attach($users[2]->id, [
            'order_id' => $orders[2]->id,
            'used_at' => now()->subDays(1),
        ]);

        echo "\n";
        echo "✅ Demo data created successfully!\n";
        echo "══════════════════════════════════════════\n";
        echo "📊 Data Summary:\n";
        echo "══════════════════════════════════════════\n";
        echo "👥 Users: " . User::count() . "\n";
        echo "📍 Cities: " . City::count() . "\n";
        echo "📮 Addresses: " . Address::count() . "\n";
        echo "👔 Designs: " . Design::count() . "\n";
        echo "📏 Measurements: " . Measurement::count() . "\n";
        echo "🎨 Design Options: " . DesignOption::count() . "\n";
        echo "📷 Design Images: " . DesignImage::count() . "\n";
        echo "🎟️ Coupons: " . Coupon::count() . "\n";
        echo "🛒 Orders: " . Order::count() . "\n";
        echo "📦 Order Items: " . OrderItem::count() . "\n";
        echo "⭐ Reviews: " . Review::count() . "\n";
        echo "📄 Invoices: " . Invoice::count() . "\n";
        echo "💰 Wallets: " . Wallet::count() . "\n";
        echo "💳 Wallet Transactions: " . WalletTransaction::count() . "\n";
        echo "══════════════════════════════════════════\n";
        echo "\n📧 Login Credentials:\n";
        echo "Admin: admin@kandura.com / password\n";
        echo "User: ahmed@example.com / password\n";
        echo "══════════════════════════════════════════\n";
    }
}
