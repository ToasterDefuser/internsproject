<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use PDF;
use App\Models\Invoice;

class PdfController extends Controller
{
    public function __invoke(Request $request){
        $invoice_id = 1;
        $invoice = Invoice::find($invoice_id);

        $pdf = PDF::loadView('pdf', compact('invoice'));
        return $pdf->stream('pdf_file.pdf');
        
    }
}