<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  @include('includes.head')
  <body>
  @include('includes.navbar')
  
        @yield('content')
           
  @include('includes.footer')
  @include('includes.scripts')
  </body>
</html>
