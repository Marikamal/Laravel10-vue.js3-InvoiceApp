<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\Counter;
use App\Models\Customer;
use App\Models\Product;

class ProductController extends Controller
{

    public function products(){

        $products =Product::orderBy('id','DESC')->get();
        return response()->json([
            'products' => $products
        ], 200);

    }
    //
}
