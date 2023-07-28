<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Section;
use App\Models\ProductKeyword;
use App\Models\Keyword;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // User::factory(10)->create();
        // DB::table('users')->insert([
        //     'name'     => 'Shashank',
        //     'email'    => 'test@codershood.com',
        //     'password' => Hash::make('test'),
        //     'phone_number'=>'9876543212',
        //     'status'=>'active'
        // ]);
        Section::create([
            'title' => 'advertise',
            'description' => 'this is the advertise section',
            'keywords' => 'Advertise',
            'keyword_option' => '',
            'end_point' => 'advertise',
            'order' => '1',
            'dlink' => 'http://fsdfsfasfsdfsdfsfd.com',
            'status' => 'active'
        ]);
        Section::create([
            'title' => 'banner',
            'description' => 'this is the banner section',
            'keywords' => 'SliderBanner',
            'keyword_option' => '',
            'end_point' => 'banner',
            'order' => '2',
            'dlink' => 'http://fsdfsfasfsdfsdfsfd.com',
            'status' => 'active'
        ]);
        Section::create([
            'title' => 'categories',
            'description' => 'this is the categories section',
            'keywords' => 'Categories',
            'keyword_option' => '',
            'end_point' => 'categories',
            'order' => '3',
            'dlink' => 'http://fsdfsfasfsdfsdfsfd.com',
            'status' => 'active'
        ]);
        Section::create([
            'title' => 'trending product',
            'description' => 'this is the product section',
            'keywords' => 'Product',
            'keyword_option' => 'trending_product',
            'end_point' => 'product',
            'order' => '4',
            'dlink' => 'http://fsdfsfasfsdfsdfsfd.com',
            'status' => 'active'
        ]);
        Section::create([
            'title' => 'brand',
            'description' => 'this is the brand section',
            'keywords' => 'Brand',
            'keyword_option' => '',
            'end_point' => 'brand',
            'order' => '5',
            'dlink' => 'http://fsdfsfasfsdfsdfsfd.com',
            'status' => 'active'
        ]);
        Section::create([
            'title' => 'offer',
            'description' => 'this is the offer section',
            'keywords' => 'Offer',
            'keyword_option' => '',
            'end_point' => 'offer',
            'order' => '6',
            'dlink' => 'http://fsdfsfasfsdfsdfsfd.com',
            'status' => 'active'
        ]);

        Keyword::create([
            'title' => 'Advertise',
            'description' => 'this is the banner section',
            'status' => 'active'
        ]);
        Keyword::create([
            'title' => 'SliderBanner',
            'description' => 'this is the banner section',
            'status' => 'active'
        ]);
        Keyword::create([
            'title' => 'Categories',
            'description' => 'this is the categories section',
            'status' => 'active'
        ]);
        Keyword::create([
            'title' => 'Product',
            'description' => 'this is the product section',
            'status' => 'active'
        ]);
        Keyword::create([
            'title' => 'Brand',
            'description' => 'this is the brand section',
            'status' => 'active'
        ]);
        Keyword::create([
            'title' => 'Offer',
            'description' => 'this is the offer section',
            'status' => 'active'
        ]);

        ProductKeyword::create([
            'title' => 'NEW-ARRIVALS',
            'description' => 'this is the new arrival',
            'status' => 'active'
        ]);
        ProductKeyword::create([
            'title' => 'RECENTLY-VIEWED',
            'description' => 'this is the recently viewed',
            'status' => 'active'
        ]);
        ProductKeyword::create([
            'title' => 'BEST-SELLERS',
            'description' => 'this is the best sellers',
            'status' => 'active'
        ]);
        ProductKeyword::create([
            'title' => 'TOP-RATED',
            'description' => 'this is the top rated',
            'status' => 'active'
        ]);
        ProductKeyword::create([
            'title' => 'MOST-VIEWED',
            'description' => 'this is the most viewed',
            'status' => 'active'
        ]);
        ProductKeyword::create([
            'title' => 'OTHER',
            'description' => 'this is the other type of limited editions, trending items, deals of the day, seasonal products, featured products',
            'status' => 'active'
        ]);
    }
}
