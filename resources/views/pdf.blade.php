<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PDF</title>
    <style>
body{
    margin: 0;
    padding: 0;
    font-family: Arial, Helvetica, sans-serif;
}

.header{
    background-color: lightgray;
    width: 100%;
    height: 100px;
    text-align: center;
}
.header h2{
    background-color: lightgray;
    padding: 50px;
}

.row1{
    text-align: center;
}
.row2{
    min-width: 100%;
}
.row2 > *{
    text-align: center;
}
.seller{
    float: left;
}
.buyer{
    float: right;
}
.x{
    float: left;
    margin-left: 240px
}

h5{
    margin: 0px;
    padding: 0px;
}
p{
    font-size: 10px;
}
.row3{

}
.items{
    margin-top: 200px;
}
table{
    border-collapse: collapse;

}
th, td{
    font-size: 10px;
}
table, td {
  border: 1px solid;
  text-align: center;
}

.row4 th, td{
    margin: 10px;
}
.summary th, .summary td, .summary2 th, .summary2 td{
    padding: 10px;
}

.summary, .summary2{
    margin-top: 100px;
}


    </style>
</head>
<body>
    <div class="header">
        <h2>Faktura - orginal nr: {{ $invoice->InvoiceNumber }}</h2>
    </div>
    <div class="row1">
        <h4>Data wystawienia: {{ $invoice->InvoiceDate }}</h4>
        <h4>Data sprzedarzy: {{ $invoice->SalesDate }}</h4>
        <h4>Awizo: {Awizo}</h4>
    </div>
    <div class="row2">

        <div class="seller">
            <h5>Sprzedawca:</h5>
            <p>ILN: {{ $invoice->seller->ILN }}</p>
            <p>NIP: {{ $invoice->seller->TaxID }}</p>
            <p>{{ $invoice->seller->Name }}</p>
            <p>{{ $invoice->seller->PostalCode }} {{ $invoice->seller->CityName }}</p>
            <p>{{ $invoice->seller->StreetAndNumber }}</p>
            <p>{{ $invoice->seller->Country }}</p>
        </div>

        <div class="x">
        <h5>xxx:</h5>
            <p>x</p>
            <p>x</p>
            <p>x</p>
            <p>x</p>
            <p>x</p>
            <p>x</p>
        </div>

        <div class="buyer">
        <h5>Nabywca:</h5>
            <p>ILN: {{ $invoice->buyer->ILN }}</p>
            <p>NIP: {{ $invoice->buyer->TaxID }}</p>
            <p>{{ $invoice->buyer->Name }}</p>
            <p>{{ $invoice->buyer->PostalCode }} {{ $invoice->buyer->CityName }}</p>
            <p>{{ $invoice->buyer->StreetAndNumber }}</p>
            <p>{{ $invoice->buyer->Country }}</p>
        </div>

    </div>
    <div class="row3">
        <table class="items">
            <tr>
                <th>LP</th>
                <th>OPIS</th>
                <th>EAN</th>
                <th>TYP</th>
                <th>ILOSC</th>
                <th>CENA[j]</th>
                <th>VAT</th>
                <th>WALUTA</th>
                <th>KWOTA PODATKU</th>
                <th>KWOTA NETTO</th>
                <th>ZAMÓWIENIE</th>
                <th>DATA</th>
            </tr>
            @foreach($invoice->items as $item)
            <tr>
                <td>{{ $item->id }}</td>
                <td>{{ $item->ItemDescription }}</td>
                <td>{{ $item->EAN }}</td>
                <td>{{ $item->ItemType }}</td>
                <td>{{ $item->InvoiceQuantity }}</td>
                <td>{{ $item->InvoiceUnitNetPrice }}</td>
                <td>{{ $item->TaxRate }}</td>
                <td>{{ $invoice->InvoiceCurrency }}</td>
                <td>{{ $item->TaxAmount }}</td>
                <td>{{ $item->NetAmount }}</td>
                <td>{{ $invoice->order->BuyerOrderNumber }}</td>
                <td>{{ $invoice->InvoiceDate }}</td>
            </tr>
            @endforeach
        </table>
    </div>
    <div class="row4">
        <table class="summary">
            <tr>
                <th>Numer rachunku</th>
                <th>Data platnosci</th>
                <th>Termin platnosci</th>
                <th>Forma platnosci</th>
                <th>Kwota do zaplaty</th>
                <th>Waluta</th>
            </tr>
            <tr>
                <td>{{ $invoice->seller->AccountNumber }}</td>
                <td>{{ $invoice->InvoicePaymentDueDate }}</td>
                <td>{{ $invoice->InvoicePaymentTerms }}</td>
                <td>Brak informacji</td>
                <td>{{ $invoice->summary->TotalGrossAmount }}</td>
                <td>{{ $invoice->InvoiceCurrency }}</td>
            </tr>
        </table>
    </div>
    <div class="row5">
        <table class="summary2">
            <tr>
                <th>Kwota netto</th>
                <th>Kwota podatku</th>
                <th>Vat</th>
                <th>Kwota brutto</th>
                <th>Waluta</th>
                <th>Kategoria podatku</th>
            </tr>
            <tr>
                <td>{{ $invoice->summary->TotalNetAmount }}</td>
                <td>{{ $invoice->summary->TaxAmount }}</td>
                <td>{{ $invoice->summary->TaxRate }}</td>
                <td>{{ $invoice->summary->GrossAmount }}</td>
                <td>{{ $invoice->InvoiceCurrency }}</td>
                <td>{{ $invoice->summary->TaxCategoryCode }}</td>
            </tr>
        </table>
    </div>
    <div class="row6">
        <h4>Uwagi:</h4>
    </div>
</body>
</html>