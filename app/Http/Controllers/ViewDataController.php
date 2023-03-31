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


        $buyerName = $request->input('wartosci') ?? "all";
        $buyerName = preg_replace('/\+/', " ", $buyerName);
        $invoiceNumber = $request->input('nrFaktury') ?? "all";
        $kodTowaru = $request->input('kodTowaru') ?? null;
        
        
        $allBuyers = Buyer::select('Name')->distinct()->get();
        $allInvoiceNumers = Invoice::select('InvoiceNumber')->distinct()->get();
        $allItems = Item::select('EAN')->distinct()->get();

        $viewData = [
            'selectedbuyer' => $buyerName,
            'allbuyers' =>  $allBuyers,

            'selectedinvoicenumer' => $invoiceNumber,
            'allInvoiceNumers' => $allInvoiceNumers,

            'selectedItem' => $kodTowaru,
            'allItems' => $allItems,
        ];

        if($buyerName === "all" && $invoiceNumber === "all" && $kodTowaru === "all"){
            //return Invoice::all();
            $viewData['invoices'] = Invoice::all();
            return view('page/viewData', $viewData);
        }

        $invoices = Invoice::with('summary', 'buyer', 'order','items');

        // 1
        if($buyerName !== "all"){
            $invoices->whereHas('buyer', function($query) use($buyerName) {
                $query->where('Name', '=', $buyerName);
            });
        }


        // 2
        if($invoiceNumber !== "all"){
            $invoices->where("InvoiceNumber", "=", $invoiceNumber);
        }



        // 3
        if($kodTowaru !== null){
            $newInvoices = [];
            foreach($invoices->get() as $invoice){
                foreach($invoice->items as $item){
                    if($item->EAN === $kodTowaru){
                        array_push($newInvoices, $invoice);
                    }
                }
            }
            //return $newInvoices;
            $viewData['invoices'] = $newInvoices;
            return view('page/viewData', $viewData);
        }

        //return $invoices->get();


        $viewData['invoices'] = $invoices->get();
        return view('page/viewData', $viewData);
       
        //return $buyerName;
    }
}