@extends('layouts.default')
@section('content')
   <div class="table_div">
    <div class="filters">
        <h4>Filtry:</h4>
        <form method="GET" id="formularz"action="" class="filer_form">
            <div>
                <p>Akronim dostawcy</p>
                <select name="wartosci" id="" onchange="this.form.submit()">
                <option value="all"<?php if(isset($_GET['wartosci']) && $_GET['wartosci'] == "all") echo 'selected';?>>Wszystkie</option>

                <script>
                    function submitEnter(event){
                        if(event.key === "Enter"){
                            document.getElementById("formularz").submit();
                            return false;
                        }
                        return true;
                    }
                </script>

                <script>
                function showHint(str) {
                    if (str.length == 0) {
                        document.getElementById("txtHint").innerHTML = "";
                        return;
                    } else {
                        var xmlhttp = new XMLHttpRequest();
                        xmlhttp.onreadystatechange = function() {
                        if (this.readyState == 4 && this.status == 200) {
                            document.getElementById("txtHint").innerHTML = this.responseText;
                        }
                        };
                        xmlhttp.open("GET", "/getHint?q=" + str, true);
                        xmlhttp.send();
                    }
                }
                </script>

                <?php
                    use App\Models\Invoice;
                    use App\Models\Order;
                    use App\Models\Delivery;
                    use App\Models\Seller;
                    use App\Models\Buyer;
                    use App\Models\Item;
                    $selectedBuyerName= "all";
                    $selectedInvoiceNumber = "all";
                    $selectedEAN = "all";

                    //dodawanie opcji select dla buyer
                    //pętla sprawdza czy jakaś wartość została już przedtem wybrana i nadaje jej 'selected', inaczej
                    //po każdej zmianie filtry sie restują i można wybrać tylko 1 filtr
                    $buyers = Buyer::all();
                    $selectedValue = false;
                    foreach($buyers as $buyer){
                        if(isset($_GET['wartosci']) && urldecode($_GET['wartosci']) == $buyer->Name){
                            $selectedValue = true;
                        }
                        if($selectedValue){
                            echo '<option value='.urlencode($buyer->Name)." ".'selected'.'>'.$buyer->Name.'</option>';
                        }else{
                            echo '<option value='.urlencode($buyer->Name).'>'.$buyer->Name.'</option>';
                        }
                        $selectedValue = false;
                        
                    }
                    if(isset($_GET["wartosci"])){
                        $selectedBuyerName = urldecode($_GET["wartosci"]);
                        if($selectedBuyerName == ""){
                            $selectedBuyerName = "all";
                        }
                    }
                ?>
                </select>
            </div>
            <div>
                <p>Nr faktury</p>
                <select name="faktura" id="" onchange="this.form.submit()">
                <option value="all"<?php if(isset($_GET['faktura']) && $_GET['faktura'] == "all") echo 'selected';?>>Wszystkie</option>
                <?php
                    //dodawanie opcji select dla nr. faktury
                    $invoiceForm = Invoice::select('InvoiceNumber')->distinct()->get();
                    $selectedValue = false;
                            foreach($invoiceForm as $invoice){
                                if(isset($_GET['faktura']) && urldecode($_GET['faktura']) == $invoice->InvoiceNumber){
                                    $selectedValue = true;
                                }
                                if($selectedValue){
                                    echo '<option value='.urlencode($invoice->InvoiceNumber)." ".'selected'.'>'.$invoice->InvoiceNumber.'</option>';
                                }else{
                                    echo '<option value='.urlencode($invoice->InvoiceNumber).'>'.$invoice->InvoiceNumber.'</option>';
                                }
                                $selectedValue = false;
                                
                            }
                    if(isset($_GET["faktura"])){
                            $selectedInvoiceNumber = urldecode($_GET["faktura"]);
                            if($selectedInvoiceNumber == ""){
                                $selectedInvoiceNumber = "all";
                            }
                        }
                ?>
                </select>
            </div>
            <div>
                <p>kod towaru</p>
                <?php
                    //dodawanie opcji select dla ean
                    $kodTowaru = Item::select('EAN')->distinct()->get();
                    $zapisanaWartosc = false;
                    //$selectedValue = false;
                    foreach($kodTowaru as $kod){

                        if(isset($_GET['towar']) && !empty($_GET['towar'])){
                            $zapisanaWartosc = True;
                        }

                        /*if($selectedValue){
                            echo '<option value='.urlencode($kod->EAN)." ".'selected'.'>'.$kod->EAN.'</option>';
                        }else{
                            echo '<option value='.urlencode($kod->EAN).'>'.$kod->EAN.'</option>';
                        }
                        $selectedValue = false;*/
   
                    }
                    if($zapisanaWartosc){
                        echo '<input type="text" name="towar" id="" value="'.urldecode($_GET['towar']).'"onchange="submitEnter(event)" onkeyup="showHint(this.value)" autocomplete="off">';
                    }else{
                        echo '<input type="text" name="towar" id="" onchange="submitEnter(event)" onkeyup="showHint(this.value)" autocomplete="off">';
                    }
                    if(isset($_GET["towar"])){
                            $selectedEAN = urldecode($_GET["towar"]);
                            if($selectedEAN == ""){
                                $selectedEAN = "all";
                            }
                        }
                ?>
                <p>podpowiedzi: <span id="txtHint"></span></p>
            </div>
        </form>
    </div>
    <table>
        <tr>
            <th>Kontrahent</th>
            <th>Faktura</th>
            <th>Data wystawienia</th>
            <th>Data wpływu</th>
            <th>Kwota netto</th>
            <th>Kwota brutto</th>
            <th>Zamówienie</th>
            <th>Do Pobrania</th>
        </tr>
            <?php      
            $invoices = Invoice::with('summary', 'buyer', 'order','items')->get();
            
            //osobny if który sprawdza czy wszystko jest ustawione na all i od razu wypisuje wszystkie wartości bez
            //potrzeby sprawdzania kilku warunków w pętli
            if($selectedBuyerName == "all" && $selectedInvoiceNumber == "all" && $selectedEAN == "all"){
                foreach($invoices as $invoice){
                    echo "<tr>";
                    // kontrahent
                    echo "<td>".$invoice->buyer->Name."</td>";
                    // Faktura
                    echo "<td>".$invoice->InvoiceNumber."</td>";
                    // Data wystawienia
                    echo "<td>".$invoice->InvoiceDate."</td>";
                    // Data wpływu
                    echo "<td>".$invoice->created_at."</td>";
                    // Kwota netto
                    echo "<td>".$invoice->summary->TotalNetAmount."</td>";
                    // Kwota brutto
                    echo "<td>".$invoice->summary->TotalGrossAmount."</td>";
                    // Zamówienie
                    echo "<td>".$invoice->order->BuyerOrderNumber."</td>";
                    // do pobrania
                    echo "<td>";
                        echo "<form action='/pdf' method='post' id='form$invoice->id' onClick='openPdf($invoice->id)'>";
                            ?>
                            @csrf
                            <?php
                            echo "<input type='hidden' name='invoiceId' value='$invoice->id'>";
                            echo"<svg id='pdficon' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 448 512'><path d='M64 464H96v48H64c-35.3 0-64-28.7-64-64V64C0 28.7 28.7 0 64 0H229.5c17 0 33.3 6.7 45.3 18.7l90.5 90.5c12 12 18.7 28.3 18.7 45.3V288H336V160H256c-17.7 0-32-14.3-32-32V48H64c-8.8 0-16 7.2-16 16V448c0 8.8 7.2 16 16 16zm96-112h24c30.9 0 56 25.1 56 56s-25.1 56-56 56h-8v32c0 8.8-7.2 16-16 16s-16-7.2-16-16V448 368c0-8.8 7.2-16 16-16zm24 80c13.3 0 24-10.7 24-24s-10.7-24-24-24h-8v48h8zm72-64c0-8.8 7.2-16 16-16h24c26.5 0 48 21.5 48 48v64c0 26.5-21.5 48-48 48H272c-8.8 0-16-7.2-16-16V368zm32 112h8c8.8 0 16-7.2 16-16V400c0-8.8-7.2-16-16-16h-8v96zm96-128h48c8.8 0 16 7.2 16 16s-7.2 16-16 16H400v32h32c8.8 0 16 7.2 16 16s-7.2 16-16 16H400v48c0 8.8-7.2 16-16 16s-16-7.2-16-16V432 368c0-8.8 7.2-16 16-16z'/></svg>";
                        echo '</form>';
                    echo "</td>";
              echo "</tr>";
                }
            }else{
                //filtr sprawdzajacy czy podana wartosc jest rowna tej znajdujacej sie w bazie lub czy jest rowna all
                //na każdy element w $invoices jest if który sprawdza wszystkie wartosci poza EAN
                foreach($invoices as $invoice){
                //str_replace() jest użyty ponieważ wartości w bazie danych mają 2 spacje, więc usuwam wszystkie odstępy i dopiero wtedy porównuje 
                if((str_replace(' ','',$selectedBuyerName) == str_replace(' ','',$invoice->buyer->Name) || $selectedBuyerName == "all") && (str_replace(' ','',$selectedInvoiceNumber == str_replace(' ','',$invoice->InvoiceNumber) || $selectedInvoiceNumber == "all"))){
                    //sprawdzenie czy $selectedEAN jest równy all, w przeciwnym razie użycie pętli foreach
                    //taki system zapobiega powtarzaniu się wyników kiedy selectedEAN jest równy all,
                    //a inne wartosci są zmienione
                    if($selectedEAN == "all"){
                        echo "<tr>";
                            // kontrahent
                            echo "<td>".$invoice->buyer->Name."</td>";
                            // Faktura
                            echo "<td>".$invoice->InvoiceNumber."</td>";
                            // Data wystawienia
                            echo "<td>".$invoice->InvoiceDate."</td>";
                            // Data wpływu
                            echo "<td>".$invoice->created_at."</td>";
                            // Kwota netto
                            echo "<td>".$invoice->summary->TotalNetAmount."</td>";
                            // Kwota brutto
                            echo "<td>".$invoice->summary->TotalGrossAmount."</td>";
                            // Zamówienie
                            echo "<td>".$invoice->order->BuyerOrderNumber."</td>";
                            // do pobrania
                            echo "<td>";
                            echo "<form action='/pdf' method='post' id='form$invoice->id' onClick='openPdf($invoice->id)'>";
                                ?>
                                @csrf
                                <?php
                                echo "<input type='hidden' name='invoiceId' value='$invoice->id'>";
                                echo"<svg id='pdficon' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 448 512'><path d='M64 464H96v48H64c-35.3 0-64-28.7-64-64V64C0 28.7 28.7 0 64 0H229.5c17 0 33.3 6.7 45.3 18.7l90.5 90.5c12 12 18.7 28.3 18.7 45.3V288H336V160H256c-17.7 0-32-14.3-32-32V48H64c-8.8 0-16 7.2-16 16V448c0 8.8 7.2 16 16 16zm96-112h24c30.9 0 56 25.1 56 56s-25.1 56-56 56h-8v32c0 8.8-7.2 16-16 16s-16-7.2-16-16V448 368c0-8.8 7.2-16 16-16zm24 80c13.3 0 24-10.7 24-24s-10.7-24-24-24h-8v48h8zm72-64c0-8.8 7.2-16 16-16h24c26.5 0 48 21.5 48 48v64c0 26.5-21.5 48-48 48H272c-8.8 0-16-7.2-16-16V368zm32 112h8c8.8 0 16-7.2 16-16V400c0-8.8-7.2-16-16-16h-8v96zm96-128h48c8.8 0 16 7.2 16 16s-7.2 16-16 16H400v32h32c8.8 0 16 7.2 16 16s-7.2 16-16 16H400v48c0 8.8-7.2 16-16 16s-16-7.2-16-16V432 368c0-8.8 7.2-16 16-16z'/></svg>";
                            echo '</form>';
                        echo "</td>";
                        echo "</tr>";
                    }else{
                        //osobna pętla sprawdzająca czy w zamówieniu pojawia się dany numer EAN, np. firma Test S.A. składa zamówienie
                        //w którym znajduje sie zamrażarka z numerem EAN 2323433343 i zamrażarka z numerem EAN 4323433343
                        foreach($invoice->items as $EAN){
                            if($EAN->EAN == $selectedEAN){
                                echo "<tr>";
                                // kontrahent
                                echo "<td>".$invoice->buyer->Name."</td>";
                                // Faktura
                                echo "<td>".$invoice->InvoiceNumber."</td>";
                                // Data wystawienia
                                echo "<td>".$invoice->InvoiceDate."</td>";
                                // Data wpływu
                                echo "<td>".$invoice->created_at."</td>";
                                // Kwota netto
                                echo "<td>".$invoice->summary->TotalNetAmount."</td>";
                                // Kwota brutto
                                echo "<td>".$invoice->summary->TotalGrossAmount."</td>";
                                // Zamówienie
                                echo "<td>".$invoice->order->BuyerOrderNumber."</td>";
                                // do pobrania
                                echo "<td>";
                                echo "<form action='/pdf' method='post' id='form$invoice->id' onClick='openPdf($invoice->id)'>";
                                    ?>
                                    @csrf
                                    <?php
                                    echo "<input type='hidden' name='invoiceId' value='$invoice->id'>";
                                    echo"<svg id='pdficon' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 448 512'><path d='M64 464H96v48H64c-35.3 0-64-28.7-64-64V64C0 28.7 28.7 0 64 0H229.5c17 0 33.3 6.7 45.3 18.7l90.5 90.5c12 12 18.7 28.3 18.7 45.3V288H336V160H256c-17.7 0-32-14.3-32-32V48H64c-8.8 0-16 7.2-16 16V448c0 8.8 7.2 16 16 16zm96-112h24c30.9 0 56 25.1 56 56s-25.1 56-56 56h-8v32c0 8.8-7.2 16-16 16s-16-7.2-16-16V448 368c0-8.8 7.2-16 16-16zm24 80c13.3 0 24-10.7 24-24s-10.7-24-24-24h-8v48h8zm72-64c0-8.8 7.2-16 16-16h24c26.5 0 48 21.5 48 48v64c0 26.5-21.5 48-48 48H272c-8.8 0-16-7.2-16-16V368zm32 112h8c8.8 0 16-7.2 16-16V400c0-8.8-7.2-16-16-16h-8v96zm96-128h48c8.8 0 16 7.2 16 16s-7.2 16-16 16H400v32h32c8.8 0 16 7.2 16 16s-7.2 16-16 16H400v48c0 8.8-7.2 16-16 16s-16-7.2-16-16V432 368c0-8.8 7.2-16 16-16z'/></svg>";
                                echo '</form>';
                            echo "</td>";
                            echo "</tr>";
    
                            }
                        }
                    }
                }
            }
            }
            
          ?>
      </table>
      <script>
        function openPdf(pdfId){
            document.getElementById("form"+pdfId).submit();
        }
      </script>
 </div>
@stop