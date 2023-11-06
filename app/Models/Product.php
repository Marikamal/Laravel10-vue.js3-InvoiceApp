<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\InvoiceItem;

class Product extends Model
{
    use HasFactory;
    protected $fillable = ['item_code','description','unit_price'];

    public function invoiceItem()
    {
        return $this->hasMany(InvoiceItem::class);
    }
}
