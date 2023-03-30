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
                <p></p>
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
                <p></p>
            </div>
            <div style="width: 300px;">
                <p>Kod towaru</p>
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
                        echo '<input type="text" name="towar" id="kod_towaru" value="'.urldecode($_GET['towar']).'"onchange="submitEnter(event)" onkeyup="showHint(this.value)" autocomplete="off">';
                    }else{
                        echo '<input type="text" name="towar" id="kod_towaru" onchange="submitEnter(event)" onkeyup="showHint(this.value)" autocomplete="off">';
                    }
                    if(isset($_GET["towar"])){
                            $selectedEAN = urldecode($_GET["towar"]);
                            if($selectedEAN == ""){
                                $selectedEAN = "all";
                            }
                        }
                ?>
                <p>Podpowiedzi: <span id="txtHint">brak</span></p>
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
        @foreach($invoices as $invoice)
        <tr>
            <td>{{ $invoice->buyer->Name }}</td>
            <td>{{ $invoice->InvoiceNumber }}</td>
            <td>{{ $invoice->InvoiceDate }}</td>
            <td>{{ $invoice->created_at }}</td>
            <td>{{ $invoice->summary->TotalNetAmount }}</td>
            <td>{{ $invoice->summary->TotalGrossAmount }}</td>
            <td>{{ $invoice->order->BuyerOrderNumber }}</td>
            <td>
                Pobierz
            </td>
        </tr>
        @endforeach

      </table>
      <script>
        function openPdf(pdfId){
            document.getElementById("form"+pdfId).submit();
        }
      </script>
 </div>
@stop