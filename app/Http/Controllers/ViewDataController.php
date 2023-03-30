<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use PDF;

// models
use App\Models\Invoice;
use App\Models\Order;
use App\Models\Delivery;
use App\Models\Seller;
use App\Models\Buyer;
use App\Models\Item;

class ViewDataController extends Controller
{
    public function __invoke(Request $request){
        
        /*
            TODO:

            - nazwy zmiennych

        */


        $buyerName = $request->input('akronim') ?? "ALL";
        $invoiceNumber = $request->input('nrFaktury') ?? "ALL";
        $kodTowaru = $request->input('kodTowaru') ?? "ALL";
        
        
        $allBuyers = Buyer::all();
        $allInvoiceNumers = Invoice::select('InvoiceNumber')->distinct()->get();

        if($buyerName === "ALL" && $invoiceNumber === "ALL" && $kodTowaru === "ALL"){
            //return Invoice::all();
            return view('page/viewData', ['invoices' => Invoice::all()]);
        }

        $invoices = Invoice::with('summary', 'buyer', 'order','items');

        // 1
        if($buyerName !== "ALL"){
            $invoices->whereHas('buyer', function($query) use($buyerName) {
                $query->where('Name', '=', $buyerName);
            });
        }


        // 2
        if($invoiceNumber !== "ALL"){
            $invoices->where("InvoiceNumber", "=", $invoiceNumber);
        }



        // 3
        if($kodTowaru !== "ALL"){
            $newInvoices = [];
            foreach($invoices->get() as $invoice){
                foreach($invoice->items as $item){
                    if($item->EAN === $kodTowaru){
                        array_push($newInvoices, $invoice);
                    }
                }
            }
            //return $newInvoices;
            return view('page/viewData', ['invoices' => $newInvoices]);
        }

        //return $invoices->get();
        return view('page/viewData', ['invoices' => $invoices->get()]);
    }
}