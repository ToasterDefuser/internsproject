<?php

namespace App\Repositories;

use App\Models\Invoice;
use App\Models\Order;
use App\Models\Delivery;
use App\Models\Seller;
use App\Models\Buyer;
use App\Models\Item;
use App\Models\Summary;

class ImportData
{
    public function ImportData($path)
    {
            $plik = simplexml_load_file($path); 

            $xml_header = $plik->{"Invoice-Header"};
            $xml_order = $xml_header->Order;
            $xml_delivery = $xml_header->Delivery;
            $xml_buyer = $plik->{"Invoice-Parties"}->Buyer;
            $xml_seller = $plik->{"Invoice-Parties"}->Seller;
            $xml_summary = $plik->{"Invoice-Summary"};
            $xml_item_quantity = $xml_summary->TotalLines;

            $invoice = new Invoice([
                'InvoiceNumber' => $xml_header->InvoiceNumber,
                'InvoiceDate' => $xml_header->InvoiceDate,
                'SalesDate' =>  $xml_header->SalesDate,
                'InvoiceCurrency'=> $xml_header->InvoiceCurrency,
                'InvoicePaymentDueDate' => $xml_header->InvoicePaymentDueDate,
                'InvoicePaymentTerms' => $xml_header->InvoicePaymentTerms,
                'DocumentFunctionCode' => $xml_header->DocumentFunctionCode,
            ]);
            

            $order = Order::firstOrCreate([
                'BuyerOrderNumber' => $xml_order->BuyerOrderNumber,
                'BuyerOrderDate' => $xml_order->BuyerOrderDate,
            ]);
            $invoice->order()->associate($order);

            $delivery = Delivery::firstOrCreate([
                'DeliveryLocationNumber' => $xml_delivery->DeliveryLocationNumber,
                'DeliveryDate' => $xml_delivery->DeliveryDate,
                'DespatchNumber' => $xml_delivery->DespatchNumber
            ]);
            $invoice->delivery()->associate($delivery);

            $buyer = Buyer::firstOrCreate([
                'ILN' => $xml_buyer->ILN,
                'TaxID' => $xml_buyer->TaxID,
                'Name' => $xml_buyer->Name,
                'StreetAndNumber' => $xml_buyer->StreetAndNumber,
                'CityName' => $xml_buyer->CityName,
                'PostalCode' => $xml_buyer->PostalCode,
                'Country' => $xml_buyer->Country
            ]);
            $invoice->buyer()->associate($buyer);

            $seller = Seller::firstOrCreate([
                'ILN' => $xml_seller->ILN,
                'TaxID' => $xml_seller->TaxID,
                'AccountNumber' => $xml_seller->AccountNumber,
                'CodeByBuyer' => $xml_seller->CodeByBuyer,
                'Name' => $xml_seller->Name,
                'StreetAndNumber' => $xml_seller->StreetAndNumber,
                'CityName' => $xml_seller->CityName,
                'PostalCode' => $xml_seller->PostalCode,
                'Country' => $xml_seller->Country
            ]);
            $invoice->seller()->associate($seller);

            $summary = Summary::firstOrCreate([
                'TotalLines' => $xml_summary->TotalLines,
                'TotalNetAmount' => $xml_summary->TotalNetAmount,
                'TotalTaxableBasis' => $xml_summary->TotalTaxableBasis,
                'TotalTaxAmount' => $xml_summary->TotalTaxAmount,
                'TotalGrossAmount' => $xml_summary->TotalGrossAmount,
                'TaxRate' => $xml_summary->{"Tax-Summary"}->{"Tax-Summary-Line"}->TaxRate,
                'TaxCategoryCode' => $xml_summary->{"Tax-Summary"}->{"Tax-Summary-Line"}->TaxCategoryCode,
                'TaxAmount' => $xml_summary->{"Tax-Summary"}->{"Tax-Summary-Line"}->TaxAmount,
                'TaxableBasis' => $xml_summary->{"Tax-Summary"}->{"Tax-Summary-Line"}->TaxableBasis,
                'TaxableAmount' => $xml_summary->{"Tax-Summary"}->{"Tax-Summary-Line"}->TaxableAmount,
                'GrossAmount' => $xml_summary->{"Tax-Summary"}->{"Tax-Summary-Line"}->GrossAmount


            ]);
            $invoice->summary()->associate($summary);
            
            $invoice->save();

            foreach($plik->{"Invoice-Lines"}->Line as $line){
                $xml_item = $line->{"Line-Item"};
                $item = Item::firstOrCreate([
                    'LineNumber' => $xml_item->LineNumber,
                    'EAN' => $xml_item->EAN,
                    'SupplierItemCode' => $xml_item->SupplierItemCode,
                    'ItemDescription' => $xml_item->ItemDescription,
                    'ItemType' => $xml_item->ItemType,
                    'InvoiceQuantity' => $xml_item->InvoiceQuantity,
                    'UnitOfMeasure' => $xml_item->UnitOfMeasure,
                    'InvoiceUnitPacksize' => $xml_item->InvoiceUnitPacksize,
                    'PackItemUnitOfMeasure' => $xml_item->PackItemUnitOfMeasure,
                    'InvoiceUnitNetPrice' => $xml_item->InvoiceUnitNetPrice,
                    'TaxRate' => $xml_item->TaxRate,
                    'TaxCategoryCode' => $xml_item->TaxCategoryCode,
                    'TaxAmount' => $xml_item->TaxAmount,
                    'NetAmount' => $xml_item->NetAmount
                ]);
                $invoice->items()->attach($item, ['quantity' => $xml_item_quantity, 'invoice_id'=>$invoice->id]);
            }
    }
}