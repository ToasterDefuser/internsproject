@extends('layouts.default')
@section('content')
@if(Session::has('alert'))
    <script>
        window.alert('{{ Session::get('alert')}}');
    </script>
@endif
<style>
   .leftMenu{
      display: none;
   }
   #svgBars{
      display: none;
   }
</style>
   <div class="register-container">
   <form action="/RegisterController" method="post" name="registerForm" onSubmit="return validateForm()">
        @csrf
        <label for="name"><h5>Nazwa użytkownika</h5></label>
        <input type="text" name="name" id="name" placeholder="Podaj nazwe" required>
        <label for="pwd"><h5>Hasło</h5></label>
        <input type="password" name="pwd" id="pwd" placeholder="Podaj hasło" required>
        <label for="rpwd"><h5>Powtórz hasło</h5></label>
        <input type="password" name="rpwd" id="rpwd" placeholder="Powtórz hasło" required>
        <p>Masz już konto? <a href="/login">Zaloguj się</a></p>
        <input type="submit" value="Zarejestruj się" name="submit" id="submit_btn" class="btn-submit">
   </form>
   </div>
@stop

<script>
    function validateForm() {
        let pwd = document.forms["registerForm"]["pwd"].value;
        let rpwd = document.forms["registerForm"]["rpwd"].value;
        
        if(pwd.length < 8){
            alert("Minimalna długość hasła to 8 znaków")
            return false;
        }

        if(pwd !== rpwd){
            alert("Hasła się nie zgadzają")
            return false;
        }
    }
</script>