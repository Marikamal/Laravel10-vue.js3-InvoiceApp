<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class InvoiceItem extends Model
{
    use HasFactory;
    protected $fillable = ['invoice_id','product_id','unit_price','quantity'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
