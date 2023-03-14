<?php
    require_once "vendor/autoload.php";

    //ORM używany do code first
    use Doctrine\ORM\Tools\Setup;
    use Doctrine\Common\Annotations\AnnotationReader;
    use Doctrine\Common\Cache\ArrayCache;
    use Doctrine\Common\Persistence\Mapping\Driver\MappingDriverChain;
    use Doctrine\ORM\EntityManager;
    use Doctrine\ORM\Mapping as ORM;
    use Doctrine\Common\Collections\ArrayCollection;
    use Doctrine\Common\Collections\Collection;

    use Symfony\Component\Cache\Adapter\FilesystemAdapter;
    

    /**
     * @ORM\Entity
     * @ORM\Table(name="invoices")
     **/


    class invoice{
        /**
         * @ORM\Id
         * @ORM\Column(type="integer")
         * @ORM\GeneratedValue
         **/
        private $id;

        /**
         * @ORM\Column(type="string")
         **/
        private $invoiceNumber;

        /**
         * @ORM\Column(type="date")
         **/
        private $invoiceDate;

        /**
         * @ORM\Column(type="date")
         **/
        private $salesDate;

        /**
         * @ORM\Column(type="string")
         **/
        private $invoiceCurrency;
        
        /**
         * @ORM\Column(type="date")
         **/
        private $invoicePaymentDueDate;

        /**
         * @ORM\Column(type="integer")
         **/
        private $invoicePaymentTerms;

        /**
         * @ORM\Column(type="integer")
         **/
        private $documentFunctionCode;

        /**
         * @ORM\Embedded(class="Order") 
         **/
        private $order;

        /**
         * @ORM\Embedded(class="Delivery")
         **/
        private $delivery;

        /**
         * @ORM\Embedded(class="Buyer")
         **/
        private $buyer;

        /**
         * @ORM\Embedded(class="Seller")
         **/
        private $seller;

        /**
         * @ORM\OneToMany(targetEntity="LineItem",mappedBy="invoice",cascade={"persist"})
         **/
        private $lineItems;

        /**
         * @ORM\Embedded(class="InvoiceSummary")
         **/
        private $invoiceSummary;
    }



    $isDevMode = true;
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

            //ilosc
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
            if(abs($sum-$totalGrossAmount) > 0.0001){
                $validFile = false;
            }
            
            if($validFile){
                echo"<br><br> Plik jest odpowiedni";
                //orm
                $xmlMappingPaths = array($plik);
                $config = Setup::createXMLMetadataConfiguration($xmlMappingPaths,$isDevMode);
                //ssms - $connection z argumentem driver do EM
                $params = array('driver'=>'sqlsrv','server'=>'ITDEV02','database'=>'InternsProject','trusted_connection'=>true);
                $entityManager = EntityManager::create($params,$config);
                //

                $tool= new \Doctrine\ORM\Tools\SchemaTool($entityManager);
                $classes = array($entityManager->getClassMetadata('invoice'));
                
                //$tool->createSchema($classes);
            }else{
                echo"<br><br> Plik <b>nie</b> jest odpowiedni";
            }
            echo"<br><br>";

            if($conn) {
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