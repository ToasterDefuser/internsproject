@extends('layouts.default')
@section('content')
   <div class="table_div">
    <div class="filters">
        <h4>Filtry:</h4>
        <form method="POST" id="formularz" action="/view" class="filer_form">
            @csrf
            <div>
                <p>Akronim dostawcy</p>
                <select name="wartosci" id="" onchange="this.form.submit()">
                    @if($selectedbuyer === "all")
                        <option value="all" selected>Wszystkie</option>
                    @else
                        <option value="all" >Wszystkie</option>
                    @endif
                    
                @foreach($allbuyers as $buyer)
                    @if($buyer->Name === $selectedbuyer)
                        <option value="{{ $buyer->Name }}" selected>{{ $buyer->Name }}</option>
                    @else
                        <option value="{{ $buyer->Name }}">{{ $buyer->Name }}</option>
                    
                    @endif
                    
                
                @endforeach
                </select>
                <p></p>
            </div>
            <div>
                <p>Nr faktury</p>
                <select name="nrFaktury" id="" onchange="this.form.submit()">
                    @if($selectedinvoicenumer === "all")
                        <option value="all" selected>Wszystkie</option>
                    @else
                        <option value="all" >Wszystkie</option>
                    @endif

                    
                    @foreach($allInvoiceNumers as $invoiceNumber)
                        @if($invoiceNumber->InvoiceNumber === $selectedinvoicenumer)
                            <option value="{{ $invoiceNumber->InvoiceNumber }}" selected>{{ $invoiceNumber->InvoiceNumber }}</option>
                        @else
                            <option value="{{ $invoiceNumber->InvoiceNumber }}">{{ $invoiceNumber->InvoiceNumber }}</option>
                        @endif
                    @endforeach
                </select>
                <p></p>
            </div>
            <div style="width: 300px;">
                <p>Kod towaru</p>
                    @if($selectedItem === null)
                        <input type="number" name="kodTowaru" id="kod_towaru"  onchange="submitEnter(event)" onkeyup="showHint(this.value)" autocomplete="off">
                    @else
                        <input type="number" name="kodTowaru" id="kod_towaru" value="{{ $selectedItem }}"  onchange="submitEnter(event)" onkeyup="showHint(this.value)" autocomplete="off"> 
                        
                    @endif
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
       
        function submitEnter(event){
            if(event.key === "Enter"){
                document.getElementById("formularz").submit();
                return false;
            }
            return true;
        }
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
 </div>
@stop