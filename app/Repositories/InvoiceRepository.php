<?php

namespace App\Repositories;

use App\Models\Invoice;

class InvoiceRepository
{   
    public function getAllInvoices(){
        return Invoice::all();
    }

    public function getAllUniqueInvoiceNumbers()
    {
        return Invoice::select('InvoiceNumber')->distinct()->get();
    }
    public function getInvoiceById($id)
    {
        return Invoice::find($id);
    }
}

