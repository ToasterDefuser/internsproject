@extends('layouts.default')
@section('content')
   <div class="table_div">
    <div>
        Filtry:
        <br>
        <form method="GET" action="">
        Akronim dostawcy
        
        <select name="wartosci" id="" onchange="this.form.submit()">
            <option value="all"<?php if(isset($_GET['wartosci']) && $_GET['wartosci'] == "all") echo 'selected';?>>wszystkie</option>
        
        </form>

        

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

        <form method="GET" action = "">
        Nr faktury
        <br>
        <select name="faktura" id="" onchange="this.form.submit()">
        <option value="all"<?php if(isset($_GET['faktura']) && $_GET['faktura'] == "all") echo 'selected';?>>wszystkie</option>
        </form>

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

        
        <form method="GET" action="">
        kod towaru        
        <select name="towar" id="" onchange="this.form.submit()">
            <option value="all"<?php if(isset($_GET['towar']) && $_GET['towar'] == "all") echo 'selected';?>>wszystkie</option>
        </form>

        <?php
        //dodawanie opcji select dla ean
        $kodTowaru = Item::select('EAN')->distinct()->get();
        $selectedValue = false;
        foreach($kodTowaru as $kod){
            if(isset($_GET['towar']) && urldecode($_GET['towar']) == $kod->EAN){
                $selectedValue = true;
            }
            if($selectedValue){
                echo '<option value='.urlencode($kod->EAN)." ".'selected'.'>'.$kod->EAN.'</option>';
            }else{
                echo '<option value='.urlencode($kod->EAN).'>'.$kod->EAN.'</option>';
            }
            $selectedValue = false;
            
        }
if(isset($_GET["towar"])){
        $selectedEAN = urldecode($_GET["towar"]);
        if($selectedEAN == ""){
            $selectedEAN = "all";
        }
    }
        ?>
        </select>

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
              echo "</tr>";
                }
            }else{
                //filtr sprawdzajacy czy podana wartosc jest rowna tej znajdujacej sie w bazie lub czy jest rowna all
                //na każdy element w $invoices jest if który sprawdza wszystkie wartosci poza EAN
                foreach($invoices as $invoice){
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
                            echo "</tr>";
    
                            }
                        }
                    }
                }
            }
            }
            
          ?>
      </table>
 </div>
@stop