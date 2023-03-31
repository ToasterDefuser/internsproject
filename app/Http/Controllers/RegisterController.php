<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function __invoke(Request $request){
        $name = $request->name;
        $pwd = $request->pwd;

        $user = User::where('Username', $name)->first();
        if($user){
            //  znaleziono usera o takiej nazwie
            return redirect()->route("register")->with("alert", "Nazwa jest zajęta");
        }

        $newUser = User::create([
            "Username" => $name,
            "Password" => Hash::make($pwd)
        ]);

        Auth::login($newUser);
        return redirect()->route("import");
        
    }
}
