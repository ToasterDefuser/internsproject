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

            $invoiceLines = $plik->Invoice-Lines;
            $invoiceSummary = $plik->Invoice-Summary;

            $totalLines = (int)$invoiceSummary->TotalLines;
            $numberOfLines = count($invoiceLines->Line);

            if($totalLines !== $numberOfLines){
                echo "Plik ma niepoprawne wartości!";
            }



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