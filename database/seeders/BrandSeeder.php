<?php

namespace Database\Seeders;

use App\Models\Brand;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        for ($i = 0; $i < 10000; $i++) {
            Brand::create([
                'user_id' => 340,
                'title' => $faker->sentence,
                'description' => $faker->paragraph,
                'image' => '1721370299.png',
                'link' => 'www.google.com',
                'keyword' => $faker->sentence,
                'status' => 'active'
            ]);
        }
    }
}
