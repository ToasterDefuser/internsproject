<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'InvoiceNumber',
        'InvoiceDate',
        'SalesDate',
        'InvoiceCurrency',
        'InvoicePaymentDueDate',
        'InvoicePaymentTerms',
        'DocumentFunctionCode',
    ];
    public function order(){
        return $this->belongsTo(Order::class);
    }
    public function delivery(){
        return $this->belongsTo(Delivery::class);
    }
    public function buyer(){
        return $this->belongsTo(Buyer::class);
    }
    public function seller(){
        return $this->belongsTo(Seller::class);
    }
    public function items(){
        return $this->belongsToMany(Item::class);
    }
    public function summary(){
        return $this->belongsTo(Summary::class);
    }
}
