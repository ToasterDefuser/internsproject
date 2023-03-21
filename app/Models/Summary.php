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
        'TotalGrossAmount',
        // tax
        'TaxRate',
        'TaxCategoryCode',
        'TaxAmount',
        'TaxableBasis',
        'TaxableAmount',
        'GrossAmount'
    ];    
    
    public $timestamps = false;
    
    public function invoice(){
        return $this.belongsTo(Invoice::class);
    }
}
