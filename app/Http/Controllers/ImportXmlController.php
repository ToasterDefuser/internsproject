<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Validators\XmlValidator;
use App\Repositories\ImportData;

class ImportXmlController extends Controller
{   
    public function view(){
        return view('page/import');
    }
    public function import(Request $request){

        // sprawdzenie czy przesłano plik w formularzu
        if(null === $xml_file = $request->file("xml_file")){
            return redirect()->route('import')->with(['alert' => "Brak pliku"]);
        }

        // sprawdzenie czy plik jest w formacie XML
        if($request->file("xml_file")->getClientOriginalExtension() !== "xml"){
            return redirect()->route('import')->with(['alert' => "Plik nie jest w formacie XMl"]);
        }

        // sciezka do pliku XMl
        $path = $request->file("xml_file")->path();

        
        // walidacja
        $validator = new XmlValidator;
        $validFile = $validator->validate($path);
        
        if(gettype($validFile) !== "boolean"){
            return redirect()->route('import')->with(['alert' => $validFile]); 
        }

        if($validFile){
            $import = new ImportData;
            $import->ImportData($path);
            return redirect()->route('view');
        }else{
            return redirect()->route('import')->with(['alert' => 'Plik nie przeszedł walidacji.']);
        }
    }
}
