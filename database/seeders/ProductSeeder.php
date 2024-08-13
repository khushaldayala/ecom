<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductImage;
use App\Models\ProductVariantImage;
use App\Models\ProductVariantAttribute;
use App\Models\SectionProduct;
use App\Models\Offer;
use App\Models\OfferProduct;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $names = ["16918638811.jpg", "16918639991.jpg", "16918639990.jpg", "16918641251.jpg", "16919358530.jpg"];

        //     $randomKey = array_rand($names);
        //     $randomName = $names[$randomKey];
            
        // $faker = Faker::create();
        // DB::table('products')
        // ->update(['brand_id' => $faker->randomElement([1, 2, 7, 6, 8, 9, 10])]);

        // for ($i = 0; $i < 1000; $i++) {

            // $userId = 340;
            // $product = new Product;
            // $product->user_id = $userId;
            // $product->category_id = $faker->randomElement([25, 26, 27, 28, 29, 30, 31]);
            // $product->subcategory_id = $faker->randomElement([23, 24, 25, 27, 28, 29]);
            // $product->brand_id = $faker->randomElement([22, 23, 24, 25, 26, 27, 28, 29, 30]);
            // $product->wishlist = '0';
            // $product->product_name = $faker->word;
            // $product->description = $faker->paragraph;
            // $product->status = 'active';
            // $product->save();

            // $productId = $product->id;

            // $names = ["1722489819.png", "1722490098.png", "1721986515.jpg", "1722490864.jpg", "1723102225.png"];

            // $randomKey = array_rand($names);
            // $randomName = $names[$randomKey];

            // // Handle product images
            // for ($i = 0; $i < 2; $i++) {
            //     $productImage = new ProductImage;
            //     $productImage->product_id = $productId;
            //     $productImage->image = $randomName;
            //     $productImage->status = 'active';
            //     $productImage->save();
            // }

            // $products = Product::get();
            // foreach ($products as $product) {

            // for ($i = 0; $i < 10; $i++) {

            //     $offerId = $faker->randomElement([48, 49, 50, 52, 53]);

            //     if ($offerId) {
            //         $this->productAssignToOffer($product, $offerId);
            //     }

            //     $offer = Offer::find($offerId);
            //     $discountPrice = null;
            //     $discountType = null;
            //     $offPrice = null;
            //     $offPercentage = null;

            //     if ($offer) {
            //         if ($offer->type != '') {
            //             if ($offer->type == 0) {
            //                 $discountPrice = 20000 - $offer->discount;
            //                 $offPrice = $offer->discount;
            //                 $offPercentage = Null;
            //                 $discountType = 'price';
            //             } elseif ($offer->type == 1) {
            //                 $discountPrice = 20000 - (20000 * ($offer->discount / 100));
            //                 $offPercentage = $offer->discount;
            //                 $offPrice = Null;
            //                 $discountType = 'percentage';
            //             }
            //         }
            //     }

            //     $namesssky = ["AK-20025", "AD-56565", "DD-877877", "CG-545454", "HG-98989"];

            //     $randomSKuKey = array_rand($namesssky);
            //     $randomSkUName = $namesssky[$randomSKuKey];

            //     $productVariant = new ProductVariant;
            //     $productVariant->product_id = $product->id;
            //     $productVariant->offer_id = $offerId;
            //     $productVariant->name = $faker->word;
            //     $productVariant->qty = $faker->randomElement([55, 58, 59, 60, 61]);
            //     $productVariant->sku = $randomSkUName;
            //     $productVariant->discount_type = $discountType;
            //     $productVariant->off_price = $offPrice;
            //     $productVariant->off_percentage = $offPercentage;
            //     $productVariant->original_price = 20000;
            //     $productVariant->discount_price = $discountPrice < 0 ? 0 : $discountPrice;
            //     $productVariant->status = 'active';
            //     $productVariant->save();

            //     for ($i = 0; $i < 2; $i++) {
            //         ProductVariantAttribute::create([
            //             'user_id' => 340,
            //             'variant_id' => $productVariant->id,
            //             'attribute_id' => 50,
            //             'attribute_option_id' => 288
            //         ]);
            //     }

            //     // Handle variant images
            //     for ($i = 0; $i < 2; $i++) {
            //         $productVariantImage = new ProductVariantImage;
            //         $productVariantImage->product_variant_id = $productVariant->id;
            //         $productVariantImage->image = $randomName;
            //         $productVariantImage->save();
            //     }
            // }
            // }
        // }
    }

    // public function productAssignToOffer($product, $offerIds)
    // {
    //     if ($offerIds) {
    //         OfferProduct::updateOrCreate(
    //             [
    //                 'offer_id' => $offerIds,
    //                 'product_id' => $product->id
    //             ],
    //             [
    //                 'user_id' => 340
    //             ]
    //         );
    //     }
    // }
}
