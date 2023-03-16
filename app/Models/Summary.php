<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Summary extends Model
{
    protected $fillable = [
        'TotalLines',
        'TotalNetAmount',
        'TotalTaxableBasis',
        'TotalTaxAmount',
        'TotalGrossAmount'
    ];    
    
    public $timestamps = false;
    
    public function invoice(){
        return $this.belongsTo(Invoice::class);
    }
}
