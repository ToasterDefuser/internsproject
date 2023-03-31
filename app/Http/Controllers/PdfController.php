<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Repositories\InvoiceRepository;
use PDF;

class PdfController extends Controller
{
    public function __invoke(Request $request){
        $invoice_id = $request->invoiceId;

        $invoiceRepo = new InvoiceRepository;
        $invoice = $invoiceRepo->getInvoiceById($invoice_id);
        if($invoice === null){
            return redirect()->route('view'); 
        }
        $pdf = PDF::loadView('pdf', compact('invoice'));
        return $pdf->stream('pdf_file.pdf');
        
    }
}