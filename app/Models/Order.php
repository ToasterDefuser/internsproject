<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'BuyerOrderNumber',
        'BuyerOrderDate'
    ];
    public $timestamps = false;
    public function invoice(){
        return $this.hasMany(Invoice::class);
    }
}
