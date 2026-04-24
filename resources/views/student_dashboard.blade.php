<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Student Dashboard — Selva AMS</title>

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

    <meta name="csrf-token" content="{{ csrf_token() }}">

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

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            color: white;
        }

        .badge-active   { background-color: #198754; font-weight: 600; }
        .badge-inactive { background-color: #dc3545; font-weight: 600; }

        .nav-tabs .nav-link {
            color: #6c757d;
            border: none;
            border-bottom: 2px solid transparent;
            transition: all 0.2s ease-in-out;
        }
        .nav-tabs .nav-link:hover {
            border-color: #dee2e6 #dee2e6 #dee2e6;
            isolation: isolate;
            color: #495057;
        }
        .nav-tabs .nav-link.active {
            color: #0d6efd;
            border-bottom: 2px solid #0d6efd;
            background: transparent;
        }

        .profile-pic-container {
            position: relative;
            display: inline-block;
        }

        .upload-overlay {
            position: absolute;
            bottom: 0;
            right: 0;
            background: rgba(0,0,0,0.6);
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: 0.3s;
            opacity: 0;
        }

        .profile-pic-container:hover .upload-overlay {
            opacity: 1;
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

        #feesChart {
            max-height: 320px;
        }

        .fees-section {
            margin-top: 2rem;
        }
        
        .fees-card {
            background: white;
            border-radius: 14px;
            padding: 1.5rem;
        }
        
        .fees-header {
            border-bottom: 2px solid #e9ecef;
            margin-bottom: 1.5rem;
            padding-bottom: 0.75rem;
        }
        
        .fees-header h4 {
            color: var(--primary);
            font-weight: 700;
            margin: 0;
        }
        
        .total-fees-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 12px;
            padding: 1.5rem;
            text-align: center;
        }
        
        .total-fees-card h3 {
            font-size: 2.5rem;
            font-weight: 700;
            margin: 0.5rem 0;
        }
        
        .total-fees-card p {
            margin: 0;
            opacity: 0.9;
        }
        
        .fees-table th {
            background-color: #f8f9fa;
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
                Welcome, {{ auth()->user()->name ?? 'Student' }}
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
    @if($student)
        <!-- Student Profile Card -->
        <div class="card shadow-sm border-0 rounded-4 overflow-hidden mb-4">
            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Student Dashboard</h5>
                <span class="badge bg-light text-dark">{{ $student->status ?? 'Active' }}</span>
            </div>

            <div class="card-body p-4 p-lg-5">
                <!-- Alerts -->
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

                <div class="row g-5">
                    <!-- Profile Picture & Basic Info -->
                    <div class="col-md-4 text-center">
                        <div class="profile-pic-container">
                            @if($student?->photo)
                                @php
                                    $finfo = finfo_open(FILEINFO_MIME_TYPE);
                                    $mime = finfo_buffer($finfo, $student->photo);
                                    finfo_close($finfo);
                                    $base64 = base64_encode($student->photo);
                                @endphp
                                <img src="data:{{ $mime }};base64,{{ $base64 }}"
                                     class="rounded-circle border border-3 border-white shadow-sm"
                                     style="width: 140px; height: 140px; object-fit: cover;"
                                     alt="Profile Photo">
                            @else
                                <div class="bg-secondary bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center mx-auto"
                                     style="width: 140px; height: 140px; font-size: 3.5rem; font-weight: 500;">
                                    {{ strtoupper(substr($student?->first_name ?? 'S', 0, 1)) }}
                                </div>
                            @endif

                            @if($student)
                                <div class="upload-overlay" data-bs-toggle="modal" data-bs-target="#uploadPhotoModal">
                                    <i class="fas fa-camera text-white"></i>
                                </div>
                            @endif
                        </div>

                        <h4 class="mt-3 mb-1 fw-bold">
                            {{ $student?->first_name ?? 'Student' }} {{ $student?->last_name ?? '' }}
                        </h4>
                        <p class="text-muted mb-2">
                            Student ID: {{ $student?->student_code ?? 'Not Registered' }}
                        </p>
                        <span class="badge bg-{{ ($student?->status ?? 'Inactive') == 'Active' ? 'success' : 'danger' }} px-3 py-2 rounded-pill">
                            {{ $student?->status ?? 'Inactive' }}
                        </span>
                    </div>

                    <!-- Tabs (Fees tab removed) -->
                    <div class="col-md-8">
                        <ul class="nav nav-tabs gap-3" id="studentTab" role="tablist">
                            <li class="nav-item">
                                <button class="nav-link active fw-semibold" id="personal-tab" data-bs-toggle="tab" data-bs-target="#personal" type="button">
                                    <i class="fas fa-user me-2"></i>Personal Info
                                </button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link fw-semibold" id="academic-tab" data-bs-toggle="tab" data-bs-target="#academic" type="button">
                                    <i class="fas fa-graduation-cap me-2"></i>Academic Info
                                </button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link fw-semibold" id="parent-tab" data-bs-toggle="tab" data-bs-target="#parent" type="button">
                                    <i class="fas fa-users me-2"></i>Parent/Guardian
                                </button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link fw-semibold" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact" type="button">
                                    <i class="fas fa-address-card me-2"></i>Contact Details
                                </button>
                            </li>
                        </ul>

                        <div class="tab-content mt-4">
                            <!-- Personal Info -->
                            <div class="tab-pane fade show active" id="personal">
                                <div class="row g-3">
                                    <div class="col-sm-6">
                                        <div class="border-bottom pb-2 mb-3">
                                            <small class="text-muted text-uppercase">Full Name</small>
                                            <div class="fw-semibold">{{ $student->first_name }} {{ $student->last_name }}</div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="border-bottom pb-2 mb-3">
                                            <small class="text-muted text-uppercase">Gender</small>
                                            <div class="fw-semibold">{{ $student->gender ?? 'Not specified' }}</div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="border-bottom pb-2 mb-3">
                                            <small class="text-muted text-uppercase">Date of Birth</small>
                                            <div class="fw-semibold">{{ $student->date_of_birth ? \Carbon\Carbon::parse($student->date_of_birth)->format('d M, Y') : 'N/A' }}</div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="border-bottom pb-2 mb-3">
                                            <small class="text-muted text-uppercase">Age</small>
                                            <div class="fw-semibold">{{ $student->age ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="border-bottom pb-2 mb-3">
                                            <small class="text-muted text-uppercase">Blood Group</small>
                                            <div class="fw-semibold">{{ $student->blood_group ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="border-bottom pb-2 mb-3">
                                            <small class="text-muted text-uppercase">Nationality</small>
                                            <div class="fw-semibold">{{ $student->nationality ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Academic Info -->
                            <div class="tab-pane fade" id="academic">
                                <div class="row g-3">
                                    <div class="col-sm-6">
                                        <div class="border-bottom pb-2 mb-3">
                                            <small class="text-muted text-uppercase">Admission Number</small>
                                            <div class="fw-semibold">{{ $student->admission_number ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="border-bottom pb-2 mb-3">
                                            <small class="text-muted text-uppercase">Admission Date</small>
                                            <div class="fw-semibold">{{ $student->admission_date ? \Carbon\Carbon::parse($student->admission_date)->format('d M, Y') : 'N/A' }}</div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="border-bottom pb-2 mb-3">
                                            <small class="text-muted text-uppercase">Course</small>
                                            <div class="fw-semibold">{{ $student->course ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="border-bottom pb-2 mb-3">
                                            <small class="text-muted text-uppercase">Department</small>
                                            <div class="fw-semibold">{{ $student->department ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="border-bottom pb-2 mb-3">
                                            <small class="text-muted text-uppercase">Year</small>
                                            <div class="fw-semibold">{{ $student->year ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="border-bottom pb-2 mb-3">
                                            <small class="text-muted text-uppercase">Semester</small>
                                            <div class="fw-semibold">{{ $student->semester ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="border-bottom pb-2 mb-3">
                                            <small class="text-muted text-uppercase">Section</small>
                                            <div class="fw-semibold">{{ $student->section ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Parent/Guardian -->
                            <div class="tab-pane fade" id="parent">
                                <div class="row g-3">
                                    <div class="col-sm-6">
                                        <div class="border-bottom pb-2 mb-3">
                                            <small class="text-muted text-uppercase">Father's Name</small>
                                            <div class="fw-semibold">{{ $student->father_name ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="border-bottom pb-2 mb-3">
                                            <small class="text-muted text-uppercase">Mother's Name</small>
                                            <div class="fw-semibold">{{ $student->mother_name ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="border-bottom pb-2 mb-3">
                                            <small class="text-muted text-uppercase">Parent Phone</small>
                                            <div class="fw-semibold">{{ $student->parent_phone ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Contact Details -->
                            <div class="tab-pane fade" id="contact">
                                <div class="row g-3">
                                    <div class="col-sm-6">
                                        <div class="border-bottom pb-2 mb-3">
                                            <small class="text-muted text-uppercase">Email</small>
                                            <div class="fw-semibold">{{ $student->email }}</div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="border-bottom pb-2 mb-3">
                                            <small class="text-muted text-uppercase">Phone</small>
                                            <div class="fw-semibold">{{ $student->phone ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="border-bottom pb-2 mb-3">
                                            <small class="text-muted text-uppercase">Address</small>
                                            <div class="fw-semibold">{{ $student->address ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="border-bottom pb-2 mb-3">
                                            <small class="text-muted text-uppercase">City</small>
                                            <div class="fw-semibold">{{ $student->city ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="border-bottom pb-2 mb-3">
                                            <small class="text-muted text-uppercase">State</small>
                                            <div class="fw-semibold">{{ $student->state ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="border-bottom pb-2 mb-3">
                                            <small class="text-muted text-uppercase">Country</small>
                                            <div class="fw-semibold">{{ $student->country ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="border-bottom pb-2 mb-3">
                                            <small class="text-muted text-uppercase">Pincode</small>
                                            <div class="fw-semibold">{{ $student->pincode ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Fees Overview - Separate Section Below -->
        <div class="fees-section">
            <div class="fees-card">
                <div class="fees-header">
                    <h4><i class="fas fa-rupee-sign me-2"></i>Fees Overview</h4>
                </div>

                <div class="row g-4 mb-4">
                    <div class="col-md-3">
                        <div class="total-fees-card">
                            <p><i class="fas fa-wallet me-2"></i>Total Fees Paid</p>
                            <h3>₹ {{ number_format($student->total ?? 0, 2) }}</h3>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div style="height: 300px;">
                            <canvas id="feesChart"></canvas>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover fees-table">
                        <thead class="table-dark">
                            <tr>
                                <th><i class="fas fa-book-open me-2"></i>Semester</th>
                                <th><i class="fas fa-rupee-sign me-2"></i>Amount Paid (₹)</th>
                                <th><i class="fas fa-chart-line me-2"></i>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $totalPaid = 0;
                            @endphp
                            @for($i = 1; $i <= 8; $i++)
                                @php
                                    $amount = $student->{"sem$i"} ?? 0;
                                    $totalPaid += $amount;
                                    $status = $amount > 0 ? 'Paid' : 'Pending';
                                    $badgeClass = $amount > 0 ? 'bg-success' : 'bg-warning text-dark';
                                @endphp
                                <tr>
                                    <td class="fw-semibold">Semester {{ $i }}</td>
                                    <td>₹ {{ number_format($amount, 2) }}</td>
                                    <td>
                                        <span class="badge {{ $badgeClass }} px-3 py-2">
                                            {{ $status }}
                                        </span>
                                    </td>
                                </tr>
                            @endfor
                        </tbody>
                        <tfoot class="table-secondary">
                            <tr class="fw-bold">
                                <td>Total</td>
                                <td>₹ {{ number_format($totalPaid, 2) }}</td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- Photo Upload Modal -->
        <div class="modal fade" id="uploadPhotoModal" tabindex="-1" aria-labelledby="uploadPhotoModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-dark text-white">
                        <h5 class="modal-title" id="uploadPhotoModalLabel">Upload Profile Photo</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div id="uploadAlert" class="alert alert-danger" style="display:none;"></div>
                        <form id="photoUploadForm" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="studentPhoto" class="form-label">Select Image (JPEG, PNG, JPG, max 2MB)</label>
                                <input type="file" class="form-control" id="studentPhoto" name="photo" accept="image/jpeg,image/png,image/jpg" required>
                            </div>
                            <div class="text-center">
                                <img id="photoPreview" src="#" alt="Preview" style="max-width: 100%; max-height: 200px; display:none;" class="img-thumbnail mb-2">
                            </div>
                            <button type="submit" class="btn btn-primary w-100" id="uploadBtn">Upload Photo</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="alert alert-warning text-center py-5">
            <h4><i class="fas fa-exclamation-triangle me-2"></i>No Student Profile Found</h4>
            <p>Your account is not linked to any student record yet.</p>
            <p>Please contact the administration to register your profile.</p>
        </div>
    @endif
</div>

<script>
    // Auto-hide alerts
    setTimeout(() => $('.alert').fadeOut('slow'), 5000);

    // Photo preview
    $('#studentPhoto').on('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = e => $('#photoPreview').attr('src', e.target.result).show();
            reader.readAsDataURL(file);
        } else {
            $('#photoPreview').hide();
        }
    });

    // AJAX photo upload
    $('#photoUploadForm').on('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const btn = $('#uploadBtn');
        const alert = $('#uploadAlert');

        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Uploading...');
        alert.hide().removeClass('alert-danger alert-success').empty();

        $.ajax({
            url: "{{ route('student.upload.photo') }}",
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: response => {
                alert.addClass('alert-success').html(response.message || 'Photo uploaded successfully!').show();
                setTimeout(() => location.reload(), 1500);
            },
            error: xhr => {
                let msg = 'An error occurred.';
                if (xhr.responseJSON?.message) msg = xhr.responseJSON.message;
                if (xhr.responseJSON?.errors) msg = Object.values(xhr.responseJSON.errors).flat().join('<br>');
                alert.addClass('alert-danger').html(msg).show();
                btn.prop('disabled', false).html('Upload Photo');
            }
        });
    });

    // Fees Bar Chart (only if student exists)
    @if($student)
    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('feesChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Sem 1', 'Sem 2', 'Sem 3', 'Sem 4', 'Sem 5', 'Sem 6', 'Sem 7', 'Sem 8'],
                datasets: [{
                    label: 'Fees Paid (₹)',
                    data: [
                        {{ $student->sem1 ?? 0 }},
                        {{ $student->sem2 ?? 0 }},
                        {{ $student->sem3 ?? 0 }},
                        {{ $student->sem4 ?? 0 }},
                        {{ $student->sem5 ?? 0 }},
                        {{ $student->sem6 ?? 0 }},
                        {{ $student->sem7 ?? 0 }},
                        {{ $student->sem8 ?? 0 }}
                    ],
                    backgroundColor: [
                        '#0d6efd', '#198754', '#ffc107', '#dc3545',
                        '#6f42c1', '#fd7e14', '#20c997', '#6610f2'
                    ],
                    borderColor: '#0f2027',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: { 
                        beginAtZero: true, 
                        title: { 
                            display: true, 
                            text: 'Amount (₹)',
                            font: {
                                weight: 'bold'
                            }
                        } 
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Semesters',
                            font: {
                                weight: 'bold'
                            }
                        }
                    }
                },
                plugins: {
                    legend: { 
                        display: true,
                        position: 'top',
                        labels: {
                            font: {
                                weight: 'bold'
                            }
                        }
                    },
                    tooltip: { 
                        callbacks: { 
                            label: function(context) {
                                return '₹ ' + context.parsed.y.toLocaleString();
                            } 
                        } 
                    }
                }
            }
        });
    });
    @endif
</script>

</body>
</html>