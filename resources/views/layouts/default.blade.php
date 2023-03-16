<!doctype html>
<html>
<head>
   @include('includes.head')
</head>
<body>
    @include('includes.nav')
    <main>
        @include('includes.leftMenu')
        @yield('content')
    </main>
    <footer>
       @include('includes.footer')
   </footer>
</div>
</body>
</html>