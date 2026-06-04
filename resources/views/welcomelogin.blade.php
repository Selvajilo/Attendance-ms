<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Selva's Attendance MS</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

<style>

body{
background: linear-gradient(135deg,#0f2027,#203a43,#2c5364);
min-height:100vh;
font-family: 'Segoe UI', sans-serif;
color:white;
}

.navbar{
background: rgba(0,0,0,0.3);
backdrop-filter: blur(10px);
}

.hero-title{
font-size:48px;
font-weight:bold;
}

.hero-sub{
color:#d1d1d1;
margin-top:10px;
}

.card{
background: rgba(255,255,255,0.1);
border:none;
border-radius:18px;
backdrop-filter: blur(10px);
color:white;
transition:0.3s;
}

.card:hover{
transform: translateY(-8px);
box-shadow:0 10px 30px rgba(0,0,0,0.3);
}

.role-icon{
font-size:50px;
margin-bottom:10px;
}

.login-btn{
font-size:17px;
padding:10px;
margin-top:10px;
}

.footer{
margin-top:80px;
opacity:0.7;
font-size:14px;
}
.navbar-expand{
background: rgba(0,0,0,0.3);
backdrop-filter: blur(10px);
height: 60px;
}
</style>

</head>

<body>

<!-- Navbar -->
<nav class="navbar navbar-expand">
<div class="container">
</div>
</nav>

<!-- Hero Section -->
<div class="container text-center mt-5">

<h1 class="hero-title">Selva's Attendance Management System</h1>

<p class="hero-sub">
Manage attendance efficiently for Admin, Staff and Students
</p>

</div>

<!-- Login Cards -->
<div class="container mt-5">

<div class="row justify-content-center g-4">

<!-- Admin -->
<div class="col-md-3">
<div class="card shadow p-4 text-center">

<i class="bi bi-shield-lock role-icon text-danger"></i>

<h4>Admin</h4>
<p>Manage system settings and users</p>

<a href="{{ route('login','admin') }}" class="btn btn-danger login-btn w-100">
Admin Login
</a>

</div>
</div>

<!-- Staff -->
<div class="col-md-3">
<div class="card shadow p-4 text-center">

<i class="bi bi-person-workspace role-icon text-primary"></i>

<h4>Staff</h4>
<p>Mark and manage attendance</p>

<a href="{{ route('login','staff') }}" class="btn btn-primary login-btn w-100">
Staff Login
</a>

</div>
</div>

<!-- Student -->
<div class="col-md-3">
<div class="card shadow p-4 text-center">

<i class="bi bi-mortarboard role-icon text-success"></i>

<h4>Student</h4>
<p>View attendance and reports</p>

<a href="{{ route('login','student') }}" class="btn btn-success login-btn w-100">
Student Login
</a>

</div>
</div>

</div>

</div>

<!-- Footer -->
<div class="container text-center footer">

<p>© 2026 Selva's Attendance Management System</p>

</div>

</body>
</html>