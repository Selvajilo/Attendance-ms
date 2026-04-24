<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign Up · Admin / Staff / Student</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400;14..32,500;14..32,600;14..32,700&display=swap" rel="stylesheet">

  <style>
:root {
  --primary: #6366f1;       /* indigo-500 */
  --primary-dark: #4f46e5;   /* indigo-600 */
  --primary-light: #818cf8;  /* indigo-400 */
  --bg: #f8fafc;
  --card-bg: #ffffff;
  --text: #0f172a;
  --text-muted: #64748b;
  --border: #e2e8f0;
  --danger: #ef4444;
  --success: #10b981;
}

* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

body {
  font-family: 'Inter', system-ui, -apple-system, sans-serif;
  background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
  color: var(--text);
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 20px;
}

.signup-container {
  width: 100%;
  max-width: 460px;
}

.signup-card {
  background: var(--card-bg);
  border-radius: 16px;
  box-shadow: 
    0 20px 40px -12px rgba(0,0,0,0.12),
    0 8px 16px -8px rgba(0,0,0,0.08);
  padding: 2.75rem 2.25rem;
  border: 1px solid var(--border);
  position: relative;
  overflow: hidden;
}

.signup-card::before {
  content: "";
  position: absolute;
  top: 0; left: 0; right: 0;
  height: 6px;
  background: linear-gradient(90deg, var(--primary), var(--primary-light));
}

.brand-icon {
  font-size: 3.5rem;
  color: var(--primary);
  margin-bottom: 0.75rem;
}

.signup-title {
  font-size: 2.1rem;
  font-weight: 700;
  margin-bottom: 0.5rem;
}

.welcome-tag {
  color: var(--text-muted);
  font-size: 0.98rem;
  margin-bottom: 2rem;
}

.role-selector {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 14px;
  margin-bottom: 2rem;
}

.role-btn {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  gap: 10px;
  padding: 18px 14px;
  border: 2px solid var(--border);
  border-radius: 14px;
  background: #f9fafb;
  color: var(--text-muted);
  font-weight: 600;
  transition: all 0.25s ease;
  cursor: pointer;
}

.role-btn:hover {
  border-color: var(--primary-light);
  color: var(--primary);
  transform: translateY(-3px);
  box-shadow: 0 8px 18px -8px rgba(99,102,241,0.22);
}

.role-btn.active {
  background: var(--primary);
  color: white;
  border-color: var(--primary);
  box-shadow: 0 10px 24px -10px rgba(99,102,241,0.45);
  transform: translateY(-2px);
}

.role-btn i {
  font-size: 1.8rem;
}

.role-btn span {
  font-size: 0.98rem;
}

.form-floating > label {
  color: var(--text-muted);
  padding-left: 0.75rem;
}

.form-control {
  border-radius: 10px;
  border: 1px solid #cbd5e1;
  padding: 1.625rem 1rem 0.625rem;
}

.form-control:focus {
  border-color: var(--primary);
  box-shadow: 0 0 0 4px rgba(99,102,241,0.18);
}

.is-invalid {
  border-color: var(--danger) !important;
}

.invalid-feedback {
  color: var(--danger);
  font-size: 0.875rem;
  margin-top: 0.25rem;
}

.password-wrapper {
  position: relative;
}

.password-toggle {
  position: absolute;
  right: 16px;
  top: 50%;
  transform: translateY(-40%);
  background: none;
  border: none;
  color: var(--text-muted);
  font-size: 1.3rem;
  cursor: pointer;
  padding: 6px;
  z-index: 5;
}

.password-toggle:hover {
  color: var(--primary);
}

.role-hint {
  background: #f1f5f9;
  padding: 10px 18px;
  border-radius: 50px;
  font-size: 0.92rem;
  color: var(--text-muted);
  display: inline-flex;
  align-items: center;
  gap: 8px;
  margin-bottom: 1.6rem;
}

.role-badge {
  background: var(--primary);
  color: white;
  padding: 4px 12px;
  border-radius: 999px;
  font-weight: 600;
  font-size: 0.88rem;
}

.signup-btn {
  width: 100%;
  padding: 14px;
  font-size: 1.08rem;
  font-weight: 600;
  background: var(--primary);
  border: none;
  color: white;
  border-radius: 10px;
  transition: all 0.28s ease;
}

.signup-btn:hover {
  background: var(--primary-dark);
  transform: translateY(-3px);
  box-shadow: 0 12px 28px -10px rgba(79,70,229,0.5);
}

.form-extra {
  font-size: 0.97rem;
  color: var(--text-muted);
  text-align: center;
  margin-top: 1.8rem;
}

.login-link {
  color: var(--primary);
  font-weight: 600;
  text-decoration: none;
}

.login-link:hover {
  text-decoration: underline;
}

.security-note {
  color: var(--text-muted);
  font-size: 0.85rem;
  text-align: center;
  margin-top: 1.6rem;
}

@media (max-width: 460px) {
  .signup-card {
    padding: 2rem 1.5rem;
  }
  .role-selector {
    grid-template-columns: 1fr;
    gap: 12px;
  }
}
  </style>
</head>
<body>

  <div class="signup-container">
    <div class="signup-card">
      <div class="text-center">
        <div class="brand-icon">
          <i class="bi bi-shield-plus"></i>
        </div>
        <h1 class="signup-title">create account</h1>
        <p class="welcome-tag"><i class="bi bi-dot"></i> choose your role & get started</p>
      </div>

      @if (session('success'))
        <div class="alert alert-success text-center">
          {{ session('success') }}
        </div>
      @endif

      @if ($errors->any())
        <div class="alert alert-danger">
          <ul class="mb-0">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form method="POST" action="{{ route('registeruser') }}" id="signupForm">
        @csrf

        <!-- Role selector -->
        <div class="role-selector" id="roleSelector">
          <button type="button" class="role-btn {{ old('role', 'student') === 'admin' ? 'active' : '' }}" data-role="admin" id="roleAdmin">
            <i class="bi bi-person-gear"></i> <span>Admin</span>
          </button>
          <button type="button" class="role-btn {{ old('role') === 'staff' ? 'active' : '' }}" data-role="staff" id="roleStaff">
            <i class="bi bi-person-badge"></i> <span>Staff</span>
          </button>
          <button type="button" class="role-btn {{ old('role', 'student') === 'student' ? 'active' : '' }}" data-role="student" id="roleStudent">
            <i class="bi bi-mortarboard"></i> <span>Student</span>
          </button>
        </div>

        <input type="hidden" name="role" id="selectedRole" value="{{ old('role', 'student') }}">

        <!-- Full Name -->
        <div class="form-floating mb-3">
          <input type="text" class="form-control @error('name') is-invalid @enderror" id="floatingName" name="name"
                 placeholder="John Doe" value="{{ old('name') }}" required autofocus>
          <label for="floatingName"><i class="bi bi-person me-2"></i>Full Name</label>
          @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <!-- Email -->
        <div class="form-floating mb-3">
          <input type="email" class="form-control @error('email') is-invalid @enderror" id="floatingEmail" name="email"
                 placeholder="name@example.com" value="{{ old('email') }}" required>
          <label for="floatingEmail"><i class="bi bi-envelope me-2"></i>Email</label>
          @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <!-- Password -->
        <div class="password-wrapper mb-3">
          <div class="form-floating">
            <input type="password" class="form-control @error('password') is-invalid @enderror" id="floatingPassword" name="password"
                   placeholder="Password" required>
            <label for="floatingPassword"><i class="bi bi-lock me-2"></i>Password</label>
            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>
          <button type="button" class="password-toggle" id="togglePassword" aria-label="Toggle password visibility">
            <i class="bi bi-eye-slash" id="toggleIcon"></i>
          </button>
        </div>

        <!-- Confirm Password -->
        <div class="form-floating mb-4">
          <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" id="floatingPasswordConfirm" name="password_confirmation"
                 placeholder="Confirm Password" required>
          <label for="floatingPasswordConfirm"><i class="bi bi-lock-fill me-2"></i>Confirm Password</label>
          @error('password_confirmation') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <!-- Role hint -->
        <div class="text-center">
          <span class="role-hint" id="roleHint">
            <i class="bi bi-info-circle"></i> Creating account as
            <span class="role-badge" id="activeRoleLabel">{{ ucfirst(old('role', 'student')) }}</span>
          </span>
        </div>

        <button type="submit" class="signup-btn" id="signupBtn">
          <i class="bi bi-person-plus"></i> Create Account
        </button>

        <div class="form-extra text-center mt-4">
          Already have an account? <a href="{{ route('login') }}" class="login-link">Log in</a>
        </div>

        <p class="text-center mt-4 small" style="color:#6b8aa3;">
          <i class="bi bi-shield-check"></i> secured by Laravel & advanced CSS
        </p>
      </form>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    (function() {
      const roleButtons = {
        admin: document.getElementById('roleAdmin'),
        staff: document.getElementById('roleStaff'),
        student: document.getElementById('roleStudent')
      };
      const activeRoleLabel = document.getElementById('activeRoleLabel');
      const selectedRoleInput = document.getElementById('selectedRole');
      const signupBtn = document.getElementById('signupBtn');

      function setActiveRole(role) {
        Object.values(roleButtons).forEach(btn => btn.classList.remove('active'));
        roleButtons[role].classList.add('active');
        activeRoleLabel.innerText = role.charAt(0).toUpperCase() + role.slice(1);
        selectedRoleInput.value = role;
        signupBtn.innerHTML = `<i class="bi bi-person-plus"></i> Create ${activeRoleLabel.innerText} Account`;
      }

      Object.keys(roleButtons).forEach(role => {
        roleButtons[role].addEventListener('click', (e) => {
          e.preventDefault();
          setActiveRole(role);
        });
      });

      const togglePassword = document.getElementById('togglePassword');
      const passwordField = document.getElementById('floatingPassword');
      const toggleIcon = document.getElementById('toggleIcon');

      togglePassword.addEventListener('click', () => {
        const type = passwordField.type === 'password' ? 'text' : 'password';
        passwordField.type = type;
        toggleIcon.classList.toggle('bi-eye-slash');
        toggleIcon.classList.toggle('bi-eye');
      });

      const initialRole = selectedRoleInput.value || 'student';
      setActiveRole(initialRole);
    })();
  </script>
</body>
</html>