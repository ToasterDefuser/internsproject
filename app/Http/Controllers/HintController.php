<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Invoice;

use App\Models\Order;
use App\Models\Delivery;
use App\Models\Seller;
use App\Models\Buyer;
use App\Models\Item;

class HintController extends Controller
{
    public function __invoke(Request $request){
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

        return $hint === "" ? "brak" : $hint;
            }
}