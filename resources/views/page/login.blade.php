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
</style>
   <div class="login-container">
   <form action="/LoginController" method="post" name="loginForm">
        @csrf
        <label for="name"><h5>Nazwa użytkownika</h5></label>
        <input type="text" name="name" id="name" placeholder="Podaj nazwe" required>
        <label for="pwd"><h5>Hasło</h5></label>
        <input type="password" name="pwd" id="pwd" placeholder="Podaj hasło" required>
        <p>Nie masz konta? <a href="/register">Zarejestruj się</a></p>
        <input type="submit" value="Zaloguj się" name="submit" id="submit_btn" class="btn-submit">
   </form>
   </div>
@stop
