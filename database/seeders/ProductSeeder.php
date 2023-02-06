<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = Category::get();
        foreach($categories as $category) {
            $seed = [];
            for($i = 1; $i <= 40; $i++){
                $seed[]= [
                    // insert data ke table pegawai menggunakan Faker
                    'category_id'   => $category->id,
                    'name'          => 'Product Seeder ' . $i . ' ' . $category->name,
                    'description'   => 'Product DescriptionSeeder ' . $i,
                    'price'         => $i . '000',
                    'stock'         => 10,
                ];
            }
            
            Product::insert($seed);
        }
    }
}
