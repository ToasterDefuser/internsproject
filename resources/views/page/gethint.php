<?php
use App\Models\Invoice;
use App\Models\Order;
use App\Models\Delivery;
use App\Models\Seller;
use App\Models\Buyer;
use App\Models\Item;

$kodTowaru = Item::select('EAN')->distinct()->get();
foreach($kodTowaru as $kod){
    $wartosciEAN[] = $kod->EAN; 
}

$hint = "";

$q = $_REQUEST["q"];

if ($q !== "") {
    $q = strtolower($q);
    $len=strlen($q);
    foreach($wartosciEAN as $name) {
      if (stristr($q, substr($name, 0, $len))) {
        if ($hint === "") {
          $hint = $name;
        } else {
          $hint .= ", $name";
        }
      }
    }
  }

  echo $hint === "" ? "brak" : $hint;



?>