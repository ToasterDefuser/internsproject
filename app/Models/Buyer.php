<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Buyer extends Model
{
    protected $fillable = [
        'ILN',
        'TaxID',
        'Name',
        'StreetAndNumber',
        'CityName',
        'PostalCode',
        'Country'
    ];
    public $timestamps = false;
    public function invoice(){
        return $this.hasMany(Invoice::class);
    }
}
