@props([
    'title' => 'trust me bro'
])





<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width", initial-scale="1">
        <title>{{ $title }}</title>


        <style>
            nav > a {
                color: red;
            }

            .card {
            background: #eafea3; 
            padding: 1rem; 
            text-align: center; 
            }
        </style>
    </head>








<body>

<nav>

    <nav>
        <a href="/">home</a>
        <a href="/about-us">about us</a>
        <a href="/contact">Contact</a>
        <a href="auth/login">Login</a>
    </nav>

    <main>
         {{ $slot }}    
    </main>




</body>


</html>