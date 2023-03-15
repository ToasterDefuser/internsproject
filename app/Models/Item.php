<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
        'LineNumber',
        'EAN',
        'SupplierItemCode',
        'ItemDescription',
        'ItemType',
        'InvoiceQuantity',
        'UnitOfMeasure',
        'InvoiceUnitPacksize',
        'PackItemUnitOfMeasure',
        'InvoiceUnitNetPrice',
        'TaxRate',
        'TaxCategoryCode',
        'TaxAmount',
        'NetAmount'
    ];
    public $timestamps = false;
    public function invoice(){
        return $this.belongsToMany(Invoice::class);
    }
}
