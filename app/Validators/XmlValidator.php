<?php

namespace App\Validators;

class XmlValidator
{
    public function validate($path)
    {
        $plik = simplexml_load_file($path);
        
        //walidacja
        $validFile = true;
        
        // sprawdzenie czy plik zawiera linie "Invoice-Lines"
        if(!isset($plik->{"Invoice-Lines"})){
            return 'Brak lini Invoice-Lines w pliku';
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
        porównać czy suma totalNet i totalTax jest równa totalGross, jeżeli występuje błąd w totalTax lub TotalNet
        to wynik i tak będzie taki sam - $validFile równe false
        */

        $totalGrossAmount = (float)$plik->{"Invoice-Summary"}->TotalGrossAmount;
        $sum = $totalNetAmount + $totalTaxAmount;
        $SumarryGrossAmount = (float)$plik->{"Invoice-Summary"}->{"Tax-Summary"}->{"Tax-Summary-Line"}->GrossAmount;
        $TaxableBasis = (float)$plik->{"Invoice-Summary"}->{"Tax-Summary"}->{"Tax-Summary-Line"}->TaxableBasis;
        $TotalTaxableBasis = (float)$plik->{"Invoice-Summary"}->TotalTaxableBasis;
        

        //floating point precision
        if(round($sum, 2) != round($totalGrossAmount, 2) || round($totalGrossAmount,2) != round($SumarryGrossAmount,2) || round($TaxableBasis,2) != round($TotalTaxableBasis,2)){
            $validFile = false;
        }

        return $validFile;
    }
}