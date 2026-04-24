<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>@yield('title')</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

body{
    background: linear-gradient(135deg,#0f2027,#203a43,#2c5364);
    min-height:100vh;
    font-family: 'Segoe UI', sans-serif;
    color:white;
}

/* Navbar */

.navbar{
background: rgba(0,0,0,0.3);
backdrop-filter: blur(10px);
}

.navbar-brand{
    color:white;
    font-weight:bold;
}

.navbar-brand:hover{
    color:white;
}

.welcome-text{
    color:white;
}

/* Cards */

.card{
    background: rgba(255,255,255,0.08);
    border:none;
    border-radius:15px;
    backdrop-filter: blur(10px);
}

/* Table */

.table{
    color:white;
}

.table thead{
    background:#1c2b33;
}

</style>

</head>

<body>

<!-- NAVBAR -->

<nav class="navbar navbar-expand-lg px-4">

<span class="navbar-brand">
Selva AMS
</span>

<div class="ms-auto welcome-text">

@auth

@php
$hour = date('H');

if($hour < 12){
$greet = "Good Morning";
}
elseif($hour < 17){
$greet = "Good Afternoon";
}
else{
$greet = "Good Evening";
}
@endphp

{{$greet}}, {{ auth()->user()->name }}

@endauth

</div>

</nav>


<div class="container mt-5">

@yield('content')

</div>

</body>
</html>