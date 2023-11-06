<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\Counter;
use App\Models\Customer;

class CustomerController extends Controller
{
    
    public function customers(){
        $customers = Customer::orderBy('id','DESC')->get();

        return response()->json([
            'customers'=>$customers
        ],200);
    }
}
