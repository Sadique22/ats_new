<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>500: Server Error!</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <link href="{{ asset('assets/css/errorpages.css') }}" rel="stylesheet"/>
</head>

<body>
    <div class="cont_principal">
        <div class="cont_error">
            <h1>500</h1>  
            <p>Internal Server Error!</p>
            <a href="/" class="btn btn-info btn-lg" style="background-color: #8A65DF; border-color:#8A65DF; ">Go to HomePage</a>
        </div>
            <div class="cont_aura_1"></div>
            <div class="cont_aura_2"></div>
    </div>
</body>
<script type="text/javascript">
    window.onload = function()
    {
        document.querySelector('.cont_principal').className= "cont_principal cont_error_active";  
    }
</script>
</html>