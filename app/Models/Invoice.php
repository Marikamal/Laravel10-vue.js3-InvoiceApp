<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Customer;
use App\Models\InvoiceItem;

class Invoice extends Model
{
    use HasFactory;
    protected $fillable = ['number','customer_id','date','due_date','reference','terms_and_conditions','subtotal','discount','total'];

    public function customer(){
        return $this->belongsTo(Customer::class);
    }

    public function invoice_items()
    {
        return $this->hasMany(InvoiceItem::class);
    }
}
