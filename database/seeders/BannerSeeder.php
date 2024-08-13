<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Banner;
use Faker\Factory as Faker;

class BannerSeeder extends Seeder
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
            Banner::create([
                'user_id' => 340,
                'title' => $faker->sentence,
                'description' => $faker->paragraph,
                'image' => $faker->imageUrl,
                'showtype' => 'desktop',
                'status' => 'active'
            ]);
        }
    }
}
