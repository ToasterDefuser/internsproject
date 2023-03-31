<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\ItemRepository;
use App\Models\Item;

class HintController extends Controller
{
    public function __invoke(Request $request){

        $itemRepo = new ItemRepository;
        $kodTowaru = $itemRepo->getAllUniqueEAN();
        foreach($kodTowaru as $kod){
            $wartosciEAN[] = $kod->EAN; 
        }

        $hint = "";

        $q = $request->input("q");

        if($q !== ""){
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