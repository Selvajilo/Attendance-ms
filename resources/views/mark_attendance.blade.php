<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mark Attendance — Selva AMS</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- jQuery -->
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
            background: var(--light-bg);
            font-family: 'Segoe UI', sans-serif;
            min-height: 100vh;
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
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 30px rgba(0,0,0,0.12);
        }

        .btn-outline-light {
            background-color: transparent;
            border-color: #ffffff80;
            color: white;
        }
        .btn-outline-light:hover {
            background-color: white;
            color: var(--primary);
            border-color: white;
        }

        .table thead {
            background: #f8f9fa;
            font-weight: 600;
        }

        .btn-group .btn-check:checked + .btn {
            box-shadow: none;
        }

        .alert {
            border-radius: 10px;
        }
    </style>
</head>
<body>

    <!-- Navbar (exactly like admin) -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid px-4">
            <a class="navbar-brand" href="#">Selva AMS</a>
            <div class="ms-auto d-flex align-items-center gap-4 text-white">
                <span class="fw-medium">
                    <i class="far fa-user-circle me-2"></i>
                    {{ auth()->user()->name ?? 'Staff' }}
                </span>
                <span>
                    <i class="far fa-calendar-alt me-2"></i>
                    {{ now()->format('d M Y') }}
                </span>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container-fluid px-4 px-xl-5 py-4 py-xl-5">
        <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-calendar-check me-2"></i>
                    Mark Attendance — {{ $department }}
                </h5>
                <a href="{{ route('staffdashboard') }}" class="btn btn-outline-light btn-sm">
                    <i class="fas fa-arrow-left me-1"></i> Back
                </a>
            </div>

            <div class="card-body p-4">
                <!-- Success/Error messages (if any) -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- Date selection form -->
                <form method="GET" action="{{ route('attendance.mark', $department) }}" class="mb-4">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <label class="form-label fw-medium">Select Date</label>
                            <input type="date" name="date" value="{{ $date }}" class="form-control">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-sync-alt me-1"></i> Load
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Attendance form -->
                <form method="POST" action="{{ route('attendance.store') }}">
                    @csrf
                    <input type="hidden" name="date" value="{{ $date }}">
                    <input type="hidden" name="department" value="{{ $department }}">

                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead class="text-center">
                                <tr>
                                    <th>#</th>
                                    <th style="width:100px">Student ID</th>
                                    <th>Name</th>
                                    <th style="width:280px">Attendance</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($students as $student)
                                    @php
                                        $status = $existing[$student->id] ?? null;
                                    @endphp
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td class="text-center">{{ $student->student_code }}</td>
                                        <td>{{ $student->first_name }} {{ $student->last_name }}</td>
                                        <td class="text-center">
                                            <div class="btn-group" role="group">
                                                <!-- Present -->
                                                <input type="radio"
                                                       class="btn-check"
                                                       name="attendance[{{ $student->id }}]"
                                                       id="p{{ $student->id }}"
                                                       value="Present"
                                                       {{ $status == 'Present' ? 'checked disabled' : '' }}
                                                       {{ $status ? 'disabled' : '' }}>
                                                <label class="btn {{ $status == 'Present' ? 'btn-success' : 'btn-outline-success' }}"
                                                       for="p{{ $student->id }}">
                                                    <i class="fas fa-check-circle me-1"></i> Present
                                                </label>

                                                <!-- Absent -->
                                                <input type="radio"
                                                       class="btn-check"
                                                       name="attendance[{{ $student->id }}]"
                                                       id="a{{ $student->id }}"
                                                       value="Absent"
                                                       {{ $status == 'Absent' ? 'checked disabled' : '' }}
                                                       {{ $status ? 'disabled' : '' }}>
                                                <label class="btn {{ $status == 'Absent' ? 'btn-danger' : 'btn-outline-danger' }}"
                                                       for="a{{ $student->id }}">
                                                    <i class="fas fa-times-circle me-1"></i> Absent
                                                </label>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-5">
                                            No students found in this department.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($students->count())
                        <div class="mt-4 text-end">
                            <button type="submit" class="btn btn-success px-5 py-2">
                                <i class="fas fa-save me-2"></i> Save Attendance
                            </button>
                        </div>
                    @endif
                </form>
            </div>
        </div>
    </div>

    <script>
        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            $('.alert').fadeOut('slow');
        }, 5000);
    </script>

</body>
</html>