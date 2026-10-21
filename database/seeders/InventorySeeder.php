<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Item;
use App\Models\Loan;
use App\Models\ReturnLog;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class InventorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Buat Akun Users (Admin & User)
        $admin = User::create([
            'name' => 'Zahra Araya',
            'email' => 'admin@sekolah.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        $user1 = User::create([
            'name' => 'Aya',
            'email' => 'aya@siswa.com',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);

        $user2 = User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@siswa.com',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);

        // 2. Buat Kategori Barang
        $catLab = Category::create(['name' => 'Alat Laboratorium']);
        $catOlahRaga = Category::create(['name' => 'Alat Olahraga']);
        $catElektronik = Category::create(['name' => 'Elektronik & Multimedia']);

        // 3. Buat Data Barang (Items) - Minimal 10 Data
        $items = [
            ['category_id' => $catLab->id, 'name' => 'Mikroskop Binokuler', 'code' => 'LAB-001', 'stock' => 5, 'condition' => 'baik'],
            ['category_id' => $catLab->id, 'name' => 'Gelas Ukur 500ml', 'code' => 'LAB-002', 'stock' => 20, 'condition' => 'baik'],
            ['category_id' => $catLab->id, 'name' => 'Tabung Reaksi', 'code' => 'LAB-003', 'stock' => 50, 'condition' => 'baik'],
            ['category_id' => $catOlahRaga->id, 'name' => 'Bola Basket', 'code' => 'OLH-001', 'stock' => 10, 'condition' => 'baik'],
            ['category_id' => $catOlahRaga->id, 'name' => 'Raket Badminton', 'code' => 'OLH-002', 'stock' => 12, 'condition' => 'rusak ringan'],
            ['category_id' => $catOlahRaga->id, 'name' => 'Bola Futsal', 'code' => 'OLH-003', 'stock' => 8, 'condition' => 'baik'],
            ['category_id' => $catElektronik->id, 'name' => 'Proyektor Epson', 'code' => 'ELK-001', 'stock' => 4, 'condition' => 'baik'],
            ['category_id' => $catElektronik->id, 'name' => 'Laptop Acer', 'code' => 'ELK-002', 'stock' => 6, 'condition' => 'baik'],
            ['category_id' => $catElektronik->id, 'name' => 'Kabel HDMI 15m', 'code' => 'ELK-003', 'stock' => 15, 'condition' => 'baik'],
            ['category_id' => $catElektronik->id, 'name' => 'Speaker Portable', 'code' => 'ELK-004', 'stock' => 3, 'condition' => 'rusak ringan'],
        ];

        foreach ($items as $item) {
            Item::create($item);
        }

        // 4. Buat Data Peminjaman (Loans) - Minimal 5 Data
        $loan1 = Loan::create([
            'user_id' => $user1->id,
            'item_id' => 1, // Mikroskop
            'quantity' => 1,
            'loan_date' => Carbon::now()->subDays(5),
            'due_date' => Carbon::now()->subDays(1),
            'status' => 'dikembalikan',
        ]);

        $loan2 = Loan::create([
            'user_id' => $user1->id,
            'item_id' => 4, // Bola Basket
            'quantity' => 2,
            'loan_date' => Carbon::now()->subDays(3),
            'due_date' => Carbon::now()->addDays(2),
            'status' => 'dipinjam',
        ]);

        $loan3 = Loan::create([
            'user_id' => $user2->id,
            'item_id' => 7, // Proyektor
            'quantity' => 1,
            'loan_date' => Carbon::now()->subDays(4),
            'due_date' => Carbon::now()->addDays(1),
            'status' => 'dipinjam',
        ]);

        $loan4 = Loan::create([
            'user_id' => $user2->id,
            'item_id' => 5, // Raket
            'quantity' => 2,
            'loan_date' => Carbon::now()->subDays(6),
            'due_date' => Carbon::now()->subDays(2),
            'status' => 'terlambat',
        ]);

        $loan5 = Loan::create([
            'user_id' => $user1->id,
            'item_id' => 2, // Gelas Ukur
            'quantity' => 5,
            'loan_date' => Carbon::now()->subDays(2),
            'due_date' => Carbon::now()->addDays(5),
            'status' => 'pending',
        ]);

        // 5. Buat Data Pengembalian (Return Logs) - Minimal 3 Data
        ReturnLog::create([
            'loan_id' => $loan1->id,
            'returned_at' => Carbon::now()->subDays(1),
            'condition_on_return' => 'baik',
            'notes' => 'Barang dikembalikan lengkap dan bersih.',
        ]);

        ReturnLog::create([
            'loan_id' => $loan4->id,
            'returned_at' => Carbon::now()->subDays(2),
            'condition_on_return' => 'rusak ringan',
            'notes' => 'Senar raket agak longgar saat dikembalikan.',
        ]);

        // 6. Buat Activity Logs
        ActivityLog::create([
            'user_id' => $admin->id,
            'action' => 'create_item',
            'description' => 'Zahra Araya menambahkan data barang baru: Mikroskop Binokuler.',
        ]);

        ActivityLog::create([
            'user_id' => $user1->id,
            'action' => 'create_loan',
            'description' => 'Aya mengajukan peminjaman barang: Bola Basket.',
        ]);
    }
}