<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
                //połączenie z ssms
                $serverName = "ITDEV02";
                $baza = "InternsProject";
                $connection = array("Database"=>$baza,"TrustServerCertificate"=>true);
                $conn = sqlsrv_connect( $serverName, $connection);
                
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
                }else{
                    echo"<br><br> Plik <b>nie</b> jest odpowiedni";

                }
                echo"<br><br>";

                if( $conn ) {
                    echo "Connection established.<br />";


                    // uzupełnienie bazy danych
                    


            }else{
                    echo "Connection could not be established.<br />";
                    die( print_r( sqlsrv_errors(), true));
            }
        }
        validate($request->file("xml_file")->path());
        
        
    }
}
