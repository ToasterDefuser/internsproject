<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Repositories\BuyerRepository;
use App\Repositories\InvoiceRepository;
use App\Repositories\ItemRepository;
use PDF;

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
        
        
        $buyerRepo = new BuyerRepository;
        $allBuyers = $buyerRepo->getAllUniqueNames();

        $invoiceRepo = new InvoiceRepository;
        $allInvoiceNumers = $invoiceRepo->getAllUniqueInvoiceNumbers();

        $itemRepo = new ItemRepository;
        $allItems = $itemRepo->getAllUniqueEAN();

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
            $viewData['invoices'] = $invoiceRepo->getAllInvoices();
            return view('page/viewData', $viewData);
        }

        $invoices = $invoiceRepo->getInvoicesWithAllRel();

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