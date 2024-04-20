<?php

namespace App\Http\Controllers\v1\Mobile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Keyword;
use App\Models\ProductKeyword;
use Illuminate\Support\Facades\Validator;
use Response;

class KeywordController extends Controller
{
    public function get_keywords(){
        $keywords = Keyword::all();
        if($keywords){
            return Response::json([
                'status' => '200',
                'message' => 'Keywords list get successfully',
                'data' => $keywords
            ], 200);
        }else{
            return Response::json([
                'status' => '404',
                'message' => 'keywords not found'
            ], 404);
        }
    }
    public function get_product_keywords_options(){
        $keywords_option = ProductKeyword::all();
        if($keywords_option){
            return Response::json([
                'status' => '200',
                'message' => 'Keywords Option list get successfully',
                'data' => $keywords_option
            ], 200);
        }else{
            return Response::json([
                'status' => '404',
                'message' => 'keywords Option not found'
            ], 404);
        }
    }
}
