<?php
    require_once "vendor/autoload.php";

    //ORM używany do code first
    use Doctrie\ORM\Tools\Setup;
    use Doctrine\ORM\EntityManager;

    $isDevMode = false;
    //phpinfo();
    error_reporting(E_ALL & ~E_NOTICE);

    if(isset($_FILES['file'])){
        $file_extension = strtolower(end(explode(".",$_FILES['file']['name'])));

        
        if ($file_extension == "xml"){

            echo "<p>Pomyślnie zaimportowano plik</p>";
            echo "<p>Zawartość pliku: </p>";
            //xml => json
            $plik = simplexml_load_file($_FILES['file']['tmp_name']);
            $convert = json_encode($plik,JSON_PRETTY_PRINT);
            echo $convert;
            echo"<br><br>";
            //połączenie z ssms
            $serverName = "ITDEV02";
            $baza = "InternsProject";
            $connection = array("Database"=>$baza,"TrustServerCertificate"=>true);
            $conn = sqlsrv_connect( $serverName, $connection);
            
                //walidacja
            $validFile = true;
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
           }else{
                echo "Connection could not be established.<br />";
                die( print_r( sqlsrv_errors(), true));
           }

        }else{
            echo "Plik jest nieodpowiedni";
        }

    }
    

?>