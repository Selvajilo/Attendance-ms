<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Dashboard — Selva AMS</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- jQuery (for alert fading) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        :root {
            --primary: #0f2027;
            --primary-dark: #0a171c;
            --light-bg: #f4f6f9;
            --card-shadow: 0 6px 18px rgba(0,0,0,0.07);
        }

        body {
            font-family: 'Segoe UI', sans-serif;
            min-height: 100vh;
            background: var(--light-bg);
        }

        .navbar {
            background: var(--primary) !important;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        }

        .navbar-brand {
            font-weight: 700;
            letter-spacing: 0.8px;
            font-size: 1.35rem;
        }

        .card {
            border: none;
            border-radius: 14px;
            overflow: hidden;
            box-shadow: var(--card-shadow);
            transition: transform 0.25s ease, box-shadow 0.25s ease;
            background: white;
            color: #212529;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 30px rgba(0,0,0,0.12);
        }

        .btn-primary {
            background-color: var(--primary);
            border: none;
        }
        .btn-primary:hover {
            background-color: #1a3a44;
        }

        .btn-outline-primary {
            border-color: var(--primary);
            color: var(--primary);
        }
        .btn-outline-primary:hover {
            background-color: var(--primary);
            color: white;
        }

        .modal-header {
            background: var(--primary);
            color: white;
        }
        .modal-header .btn-close {
            filter: brightness(0) invert(1);
        }

        .alert {
            border-radius: 10px;
        }

        .btn-back {
            margin: 20px 0 0 20px;
            background: white;
            color: var(--primary);
            border: 1px solid #dee2e6;
            transition: all 0.2s;
        }
        .btn-back:hover {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid px-4">
            <a class="navbar-brand" href="#">Selva AMS</a>
            <div class="ms-auto d-flex align-items-center gap-4 text-white">
                <span class="fw-medium">
                    <i class="far fa-user-circle me-2"></i>
                    @php
                        $hour = date('H');
                        if ($hour < 12) $greet = "Good Morning";
                        elseif ($hour < 17) $greet = "Good Afternoon";
                        else $greet = "Good Evening";
                    @endphp
                    {{ $greet }}, {{ auth()->user()->name ?? 'Staff' }}
                </span>
                <span>
                    <i class="far fa-calendar-alt me-2"></i>
                    {{ now()->format('d M Y') }}
                </span>
            </div>
        </div>
    </nav>

    <!-- Back button -->
    <div class="container-fluid px-4 mt-3">
        <a href="{{ route('home') }}" class="btn btn-back">
            <i class="fas fa-arrow-left me-2"></i>Back
        </a>
    </div>

    <!-- Main Content -->
    <div class="container-fluid px-4 px-xl-5 py-4 py-xl-5">
        <!-- Flash messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Whoops!</strong> There were some problems with your input.<br>
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Department Cards -->
        <div class="row g-4">
            @forelse($departments as $dept)
                <div class="col-md-4 col-sm-6 col-12">
                    <div class="card h-100">
                        <div class="card-body p-4">
                            <h5 class="card-title fw-bold">{{ $dept->department }}</h5>
                            <p class="card-text text-muted">
                                {{ $dept->student_count }} student{{ $dept->student_count == 1 ? '' : 's' }}
                            </p>

                            <div class="d-grid gap-2">
                                <a href="{{ route('attendance.mark', $dept->department) }}" class="btn btn-primary">
                                    Mark Attendance
                                </a>
                                <button class="btn btn-outline-primary"
                                        data-bs-toggle="modal"
                                        data-bs-target="#studentModal"
                                        onclick="setDepartment('{{ addslashes($dept->department) }}')">
                                    Add Student
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info text-center">
                        No departments found with students yet.
                    </div>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Add Student Modal -->
    <div class="modal fade" id="studentModal" tabindex="-1" aria-labelledby="studentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="studentModalLabel">Add New Student</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('students.store') }}">
                        @csrf

                        <div class="row g-3">

                            <!-- Core Identification -->
                            <div class="col-md-6">
                                <label class="form-label">Student Code <span class="text-danger">*</span></label>
                                <input type="text" name="student_code" class="form-control" required autofocus>
                                @error('student_code') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Admission Number <span class="text-danger">*</span></label>
                                <input type="text" name="admission_number" class="form-control" required>
                                @error('admission_number') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <!-- Personal Info -->
                            <div class="col-md-6">
                                <label class="form-label">First Name <span class="text-danger">*</span></label>
                                <input type="text" name="first_name" class="form-control" required>
                                @error('first_name') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Last Name</label>
                                <input type="text" name="last_name" class="form-control">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Gender <span class="text-danger">*</span></label>
                                <select name="gender" class="form-select" required>
                                    <option value="">Select</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Other">Other</option>
                                </select>
                                @error('gender') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Date of Birth</label>
                                <input type="date" name="date_of_birth" class="form-control">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Age</label>
                                <input type="number" name="age" class="form-control" min="15" max="35">
                            </div>

                            <!-- Contact -->
                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Phone</label>
                                <input type="text" name="phone" class="form-control">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Parent / Guardian Phone</label>
                                <input type="text" name="parent_phone" class="form-control">
                            </div>

                            <!-- Academic -->
                            <div class="col-md-6">
                                <label class="form-label">Department <span class="text-danger">*</span></label>
                                <input type="text" id="departmentInput" name="department" class="form-control" readonly required>
                                @error('department') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Course <span class="text-danger">*</span></label>
                                <input type="text" name="course" class="form-control" value="Java" required>
                            </div>

                            <div class="col-md-2">
                                <label class="form-label">Year <span class="text-danger">*</span></label>
                                <input type="number" name="year" class="form-control" min="1" max="5" value="1" required>
                            </div>

                            <div class="col-md-2">
                                <label class="form-label">Semester <span class="text-danger">*</span></label>
                                <input type="number" name="semester" class="form-control" min="1" max="8" value="1" required>
                            </div>

                            <div class="col-md-2">
                                <label class="form-label">Section <span class="text-danger">*</span></label>
                                <input type="text" name="section" class="form-control" maxlength="2" value="A" required>
                            </div>

                            <!-- Additional Info -->
                            <div class="col-md-6">
                                <label class="form-label">Father's Name</label>
                                <input type="text" name="father_name" class="form-control">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Mother's Name</label>
                                <input type="text" name="mother_name" class="form-control">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Admission Date</label>
                                <input type="date" name="admission_date" class="form-control" value="{{ date('Y-m-d') }}">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Blood Group</label>
                                <select name="blood_group" class="form-select">
                                    <option value="">Select</option>
                                    <option>A+</option>
                                    <option>A-</option>
                                    <option>B+</option>
                                    <option>B-</option>
                                    <option>AB+</option>
                                    <option>AB-</option>
                                    <option>O+</option>
                                    <option>O-</option>
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Nationality <span class="text-danger">*</span></label>
                                <input type="text" name="nationality" class="form-control" value="Indian" required>
                            </div>

                            <!-- Address Group -->
                            <div class="col-12 mt-4">
                                <h6 class="mb-3">Address Details</h6>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">Full Address</label>
                                <textarea name="address" class="form-control" rows="2"></textarea>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">City</label>
                                <input type="text" name="city" class="form-control">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">State</label>
                                <input type="text" name="state" class="form-control" value="Tamil Nadu">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Country <span class="text-danger">*</span></label>
                                <input type="text" name="country" class="form-control" value="India" required>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Pincode</label>
                                <input type="text" name="pincode" class="form-control">
                            </div>

                        </div>

                        <button type="submit" class="btn btn-dark w-100 mt-4 py-3 fw-bold">
                            Save Student
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            $('.alert').fadeOut('slow');
        }, 5000);

        function setDepartment(dept) {
            document.getElementById("departmentInput").value = dept;
        }
    </script>

</body>
</html>