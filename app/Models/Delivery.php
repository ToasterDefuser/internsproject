<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    protected $fillable = [
        'DeliveryLocationNumber',
        'DeliveryDate',
        'DespatchNumber'
    ];
    
    public $timestamps = false;

    public function invoice(){
        return $this.hasMany(Invoice::class);
    }
}
