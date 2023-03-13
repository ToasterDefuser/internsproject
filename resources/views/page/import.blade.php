@extends('layouts.default')
@section('content')
<div class="form">
    <form action="" method="post" enctype="multipart/form-data">
        <label for="file"><h3>Wybierz plik do importu</h3></label>
        <input type="file" accept=".xml">
        <input type="submit" value="Wyslij" name="submit">
   </form>
</div>
@stop