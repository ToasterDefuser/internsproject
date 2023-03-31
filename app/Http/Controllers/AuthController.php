<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function RegisterUser(Request $request)
    {
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

    public function LoginUser(Request $request)
    {
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

    public function LogoutUser(Request $request)
    {
        Auth::logout();
        return redirect()->route("home");
    }


    public function ViewLoginForm()
    {
        return view('page/login');
    }
    public function ViewRegisterForm()
    {
        return view('page/register');
    }
    public function ViewHomePage()
    {
        return view('page/home');
    }
}