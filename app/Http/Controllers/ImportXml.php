<?php

namespace App\Http\Controllers;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\Delivery;
use App\Models\Seller;
use App\Models\Buyer;
use App\Models\Item;
use App\Models\Summary;

class ImportXml extends Controller
{
    public function __invoke(Request $request){

        // sprawdzenie czy przesłano plik w formularzu
        if(null === $xml_file = $request->file("xml_file")){
            echo "Brak pliku";
            return;
        }

        // sprawdzenie czy plik jest formatu XML
        if($request->file("xml_file")->getClientOriginalExtension() !== "xml"){
            echo "Plik nie jest formatu XMl";
            return;  
        }

        function validate($path){  
                $plik = simplexml_load_file($path);
                
                //walidacja
                $validFile = true;
                
                // sprawdzenie czy plik zawiera linie "Invoice-Lines"
                if(!isset($plik->{"Invoice-Lines"})){
                    echo 'Brak lini "Invoice-Lines" w pliku';
                    return;
                }

                //lines
                $invoiceLines = count($plik->{"Invoice-Lines"}->Line);
                $totalLines = (int)$plik->{"Invoice-Summary"}->TotalLines;

                //Total Values + net amount
                $totalNetAmount = (float)$plik->{"Invoice-Summary"}->TotalNetAmount;
                $SumNetAmount = 0;
                $SumTaxAmount = 0;

                foreach($plik->{"Invoice-Lines"}->Line as $line){
                    $SumNetAmount += (float)$line->{"Line-Item"}->NetAmount;
                    $SumTaxAmount += (float)$line->{"Line-Item"}->TaxAmount;
                }

                //taxAmount
                $totalTaxAmount = (float)$plik->{"Invoice-Summary"}->TotalTaxAmount;

                if($totalLines !== $invoiceLines || $SumNetAmount !== $totalNetAmount || $totalTaxAmount !== $SumTaxAmount){
                    $validFile = false;
                }
                /*Total Gross - if statement powyzej sprawdza czy suma net amount i suma tax amount jest poprawna,
                zamiast robić kolejną pętlę która sumuje wartości i porównuje z totalGrossAmount, szybciej jest zwykle
                porównać czy totalNet i totalTax jest równa totalGross, jeżeli występuje błąd w totalTax lub TotalNet
                to wynik i tak będzie taki sam - $validFile równe false
                */

                $totalGrossAmount = (float)$plik->{"Invoice-Summary"}->TotalGrossAmount;
                $sum = $totalNetAmount + $totalTaxAmount;
                                                //floating point precision
                if(abs($sum-$totalGrossAmount) > 0.00001){
                    $validFile = false;
                }
                
                if($validFile){
                    echo"<br><br> Plik jest odpowiedni";

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
                    

                    $order = new Order([
                        'BuyerOrderNumber' => $xml_order->BuyerOrderNumber,
                        'BuyerOrderDate' => $xml_order->BuyerOrderDate,
                    ]);
                    $order->save();
                    $invoice->order()->associate($order);

                    $delivery = new Delivery([
                        'DeliveryLocationNumber' => $xml_delivery->DeliveryLocationNumber,
                        'DeliveryDate' => $xml_delivery->DeliveryDate,
                        'DespatchNumber' => $xml_delivery->DespatchNumber
                    ]);
                    $delivery->save();
                    $invoice->delivery()->associate($delivery);

                    $buyer = new Buyer([
                        'ILN' => $xml_buyer->ILN,
                        'TaxID' => $xml_buyer->TaxID,
                        'Name' => $xml_buyer->Name,
                        'StreetAndNumber' => $xml_buyer->StreetAndNumber,
                        'CityName' => $xml_buyer->CityName,
                        'PostalCode' => $xml_buyer->PostalCode,
                        'Country' => $xml_buyer->Country
                    ]);
                    $buyer->save();
                    $invoice->buyer()->associate($buyer);

                    $seller = new Seller([
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
                    $seller->save();
                    $invoice->seller()->associate($seller);

                    $summary = new Summary([
                        'TotalLines' => $xml_summary->TotalLines,
                        'TotalNetAmount' => $xml_summary->TotalNetAmount,
                        'TotalTaxableBasis' => $xml_summary->TotalTaxableBasis,
                        'TotalTaxAmount' => $xml_summary->TotalTaxAmount,
                        'TotalGrossAmount' => $xml_summary->TotalGrossAmount
                    ]);
                    $summary->save();
                    $invoice->summary()->associate($summary);
                    
                    $invoice->save();

                    foreach($plik->{"Invoice-Lines"}->Line as $line){
                        $xml_item = $line->{"Line-Item"};
                        $item = new Item([
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
                        $item->save();
                        $invoice->items()->attach($item, ['quantity' => $xml_item_quantity, 'invoice_id'=>$invoice->id]);
                    }

                    echo"<br><br> Zapisano dane";
                    //return redirect('/import');

                }else{
                    echo"<br><br> Plik <b>nie</b> jest odpowiedni";
                }
        }
        validate($request->file("xml_file")->path());
        return redirect()->route('dane');
        
    }
}
