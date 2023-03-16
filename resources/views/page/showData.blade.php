@extends('layouts.default')
@section('content')
   <div class="table_div">
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
                use App\Models\Invoice;
                use App\Models\Order;
                use App\Models\Delivery;
                use App\Models\Seller;
                use App\Models\Buyer;
                use App\Models\Item;



               $invoices = Invoice::with('summary', 'buyer', 'order')->get();
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

            ?>
        </table>
   </div>
@stop