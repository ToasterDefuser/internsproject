<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function __invoke(Request $request){
        $name = $request->name;
        $pwd = $request->pwd;

        $user = User::where('Username', $name)->first();

        if(!$user){
            // nie znaleziono usera o takiej nazwie
            return redirect()->route("login")->with("alert", "Niepoprawna nazwa");
        }
        if(!Hash::check($pwd, $user->Password)){
            // niepoprawne hasło
            return redirect()->route("login")->with("alert", "Niepoprawne hasło");
        }else{
            Auth::login($user);
            return redirect()->route("import");
        }
        
    }
}
