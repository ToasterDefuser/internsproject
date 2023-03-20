@extends('layouts.default')
@section('content')
<style>
   .leftMenu{
      display: none;
   }
</style>
   <div class="auth-container">
      <button id="login" onClick="redirect('/login')">Logowanie</button>
      <button id="register" onClick="redirect('/register')">Rejestracja</button>
   </div>
@stop

<script>
   function redirect(page){
         window.location.href = page;
      }
</script>