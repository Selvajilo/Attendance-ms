<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Login | Selva's Attendance MS</title>

    <!-- Bootstrap 5.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>
    <!-- Chart.js (for fees graph) -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<style>

body{
background: linear-gradient(135deg,#0f2027,#203a43,#2c5364);
min-height:100vh;
font-family: 'Segoe UI', sans-serif;
color:white;
display:flex;
flex-direction:column;
}

/* Navbar */

.navbar{
background: rgba(0,0,0,0.3);
backdrop-filter: blur(10px);
text-align: center;
}
.container{
justify-content: center;
text-align: center;
}

/* Center container */

.login-wrapper{
flex:1;
display:flex;
align-items:center;
justify-content:center;
}

/* glass card */

.login-card{
width:380px;
padding:35px;
border-radius:18px;
background: rgba(255,255,255,0.1);
backdrop-filter: blur(12px);
box-shadow:0 10px 30px rgba(0,0,0,0.4);
color:white;
}

/* title */

.system-title{
font-weight:700;
}

/* inputs */

.form-control{
background: rgba(255,255,255,0.15);
border:none;
color:white;
}

.form-control::placeholder{
color:#d3d3d3;
}

.form-control:focus{
box-shadow:none;
background: rgba(255,255,255,0.2);
}

/* icon box */

.input-group-text{
background: rgba(255,255,255,0.2);
border:none;
color:white;
}

/* login button */

.login-btn{
background:#000;
border:none;
padding:10px;
transition:0.3s;
}

.login-btn:hover{
background:#111;
transform:scale(1.03);
}

/* back link */

.back-link{
color:#ddd;
}

.back-link:hover{
color:white;
}

/* error message */

.error-msg{
font-size:14px;
color:#ffb3b3;
}

</style>

</head>

<body>

<!-- Navbar -->

<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container-fluid px-4">
        <a class="navbar-brand" href="#">Selva's Attendance Management System</a>
        <div class="ms-auto d-flex align-items-center gap-4 text-white">
            <span>
                <i class="far fa-calendar-alt me-2"></i>
                {{ now()->format('d M Y') }}
            </span>
        </div>
    </div>
</nav>


<div class="login-wrapper">

<div class="login-card">

<div class="text-center mb-4">

<h4 class="system-title">
Selva's Attendance MS
</h4>

<p>
Login as <strong>{{ ucfirst($role) }}</strong>
</p>

</div>

<form method="POST" action="{{ route('login.submit',$role) }}">
@csrf

<div class="mb-3">

<label class="form-label">Email</label>

<div class="input-group">

<span class="input-group-text">
<i class="bi bi-envelope"></i>
</span>

<input type="email" name="email" class="form-control" placeholder="Enter your email" required>

</div>

@error('email')
<div class="error-msg mt-1">{{ $message }}</div>
@enderror

</div>

<div class="mb-3">

<label class="form-label">Password</label>

<div class="input-group">

<span class="input-group-text">
<i class="bi bi-lock"></i>
</span>

<input type="password" name="password" class="form-control" placeholder="Enter password" required>

</div>

</div>

<input type="hidden" name="role" value="{{ $role }}">

<button type="submit" class="btn login-btn w-100 text-white mt-2">
Login as {{ ucfirst($role) }}
</button>

</form>

<div class="text-center mt-3">

<a href="/" class="text-decoration-none back-link">
<i class="bi bi-arrow-left"></i> Back to Home
</a>

</div>

</div>

</div>

</body>
</html>