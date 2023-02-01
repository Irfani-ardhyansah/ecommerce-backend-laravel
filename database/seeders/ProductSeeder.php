<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i = 1; $i <= 20; $i++){
                // insert data ke table pegawai menggunakan Faker
            DB::table('pegawai')->insert([
                'category_id'   => 1,
                'name'          => 'Product Seeder ' . $i,
                'description'   => 'Product DescriptionSeeder ' . $i,
                'price'         => $i . 000,
                'stock'         => 10,
            ]);

        }
    }
}
