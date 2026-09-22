<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\IncomeSource;
use App\Models\TourismPlace;
use App\Models\Transaction;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Atraksi Wisata', 'type' => 'income'],
            ['name' => 'Pendapatan Desa Wisata', 'type' => 'income'],
            ['name' => 'Tour Guide', 'type' => 'income'],
            ['name' => 'Workshop Batik', 'type' => 'income'],
            ['name' => 'Wihara', 'type' => 'income'],
            ['name' => 'Paket Wisata', 'type' => 'income'],
            ['name' => 'Operations', 'type' => 'expense'],
            ['name' => 'Maintenance', 'type' => 'expense'],
            ['name' => 'Marketing', 'type' => 'expense'],
            ['name' => 'Transportasi', 'type' => 'expense'],
            ['name' => 'Lainnya', 'type' => 'both'],
        ];
        foreach ($categories as $item) Category::create($item + ['is_active' => true]);

        foreach (['Wihara', 'Kampung Naga', 'Batik Proklim Lestari', 'Basisir Muda Demang (BMD)'] as $name) {
            TourismPlace::create(['name' => $name, 'is_active' => true]);
        }
        foreach (['Atraksi Wisata', 'Desa Wisata', 'Tour Guide', 'Workshop Batik', 'Paket Wisata', 'Donasi Wihara'] as $name) {
            IncomeSource::create(['name' => $name, 'is_active' => true]);
        }

        $category = fn ($name) => Category::where('name', $name)->first();
        $place = fn ($name) => TourismPlace::where('name', $name)->first();
        $source = fn ($name) => IncomeSource::where('name', $name)->first();

        $rows = [
            ['type'=>'income','date'=>'2026-09-05','cat'=>'Paket Wisata','place'=>'Kampung Naga','source'=>'Paket Wisata','package'=>'Paket Jelajah Kampung Naga','description'=>'Paket wisata 10 peserta','qty'=>10,'price'=>150000,'payment'=>'Transfer','status'=>'completed'],
            ['type'=>'income','date'=>'2026-09-08','cat'=>'Wihara','place'=>'Wihara','source'=>'Donasi Wihara','package'=>null,'description'=>'Donasi pengunjung','qty'=>1,'price'=>4500000,'payment'=>'QRIS','status'=>'completed'],
            ['type'=>'income','date'=>'2026-09-12','cat'=>'Tour Guide','place'=>'Kampung Naga','source'=>'Tour Guide','package'=>'Paket Heritage Walk','description'=>'Jasa pemandu 2 sesi','qty'=>2,'price'=>750000,'payment'=>'Cash','status'=>'pending'],
            ['type'=>'expense','date'=>'2026-09-10','cat'=>'Maintenance','place'=>'Wihara','source'=>null,'package'=>null,'description'=>'Perawatan area wisata','qty'=>1,'price'=>1200000,'payment'=>'Transfer','status'=>'completed'],
            ['type'=>'expense','date'=>'2026-09-14','cat'=>'Marketing','place'=>'Batik Proklim Lestari','source'=>null,'package'=>null,'description'=>'Promosi digital paket wisata','qty'=>1,'price'=>850000,'payment'=>'Transfer','status'=>'completed'],
            ['type'=>'expense','date'=>'2026-09-18','cat'=>'Operations','place'=>'Kampung Naga','source'=>null,'package'=>null,'description'=>'Perlengkapan operasional','qty'=>4,'price'=>375000,'payment'=>'Cash','status'=>'completed'],
        ];
        foreach ($rows as $row) {
            Transaction::create([
                'type'=>$row['type'], 'transaction_date'=>Carbon::parse($row['date']), 'category_id'=>$category($row['cat'])->id,
                'tourism_place_id'=>$place($row['place'])->id, 'income_source_id'=>$row['source'] ? $source($row['source'])->id : null,
                'package_name'=>$row['package'], 'description'=>$row['description'], 'quantity'=>$row['qty'], 'unit_price'=>$row['price'],
                'amount'=>$row['qty'] * $row['price'], 'payment_method'=>$row['payment'], 'status'=>$row['status'],
            ]);
        }
    }
}
