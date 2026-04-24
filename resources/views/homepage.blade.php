<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Admin Dashboard — Selva AMS</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>

    <!-- SheetJS for Excel export -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- Bootstrap JavaScript (with Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>   

    <style>
        :root {
            --primary: #0f2027;
            --primary-dark: #0a171c;
            --light-bg: #f4f6f9;
            --card-shadow: 0 6px 18px rgba(0,0,0,0.07);
        }

        body {
            /* background: var(--light-bg); */
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

        .chart-container {
            position: relative;
            height: 340px;
            width: 100%;
        }

        .table thead th {
            background: var(--primary);
            color: white;
            border: none;
            font-weight: 600;
            text-align: center;
        }

        .table tbody tr:hover {
            background-color: rgba(15,32,39,0.04);
        }

        .badge-active   { background-color: #198754; font-weight: 600; }
        .badge-inactive { background-color: #dc3545; font-weight: 600; }

        #tableLoading {
            position: absolute;
            inset: 0;
            background: rgba(244,246,249,0.8);
            z-index: 5;
            display: none;
            align-items: center;
            justify-content: center;
            flex-direction: column;
        }

        .pagination-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
            padding: 15px 20px;
            background: white;
            border-top: 1px solid #dee2e6;
        }

        .page-length-select {
            width: auto;
            min-width: 120px;
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
                {{ $greet ?? 'Welcome' }}, {{ auth()->user()->name ?? 'Admin' }}
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

    <!-- KPI Cards -->
    <div class="row g-4 mb-5">
        <div class="col-6 col-lg-3">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="stat-icon bg-primary me-3"><i class="fas fa-user-graduate"></i></div>
                    <div>
                        <small class="text-muted d-block">Total Students</small>
                        <h3 class="mb-0 fw-bold" id="kpiTotal">—</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="stat-icon bg-success me-3"><i class="fas fa-check"></i></div>
                    <div>
                        <small class="text-muted d-block">Active Students</small>
                        <h3 class="mb-0 fw-bold" id="kpiActive">—</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="stat-icon bg-info me-3"><i class="fas fa-layer-group"></i></div>
                    <div>
                        <small class="text-muted d-block">Departments</small>
                        <h3 class="mb-0 fw-bold" id="kpiDepts">—</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="card h-100 position-relative">
                <div class="card-body d-flex align-items-center">
                    <div class="stat-icon bg-warning me-3"><i class="fas fa-th-large"></i></div>
                    <div>
                        <small class="text-muted d-block">Sections</small>
                        <h3 class="mb-0 fw-bold" id="kpiSections">—</h3>
                    </div>
                </div>
                <button class="btn btn-light position-absolute top-0 end-0 m-3" data-bs-toggle="modal" data-bs-target="#addUserModal">
                    <i class="fas fa-user-plus me-1"></i> Add User
                </button>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label fw-medium">Department</label>
                    <select id="filterDept" class="form-select">
                        <option value="">All Departments</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-medium">Year</label>
                    <select id="filterYear" class="form-select">
                        <option value="">All Years</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-medium">Section</label>
                    <select id="filterSection" class="form-select">
                        <option value="">All Sections</option>
                        <option value="A">A</option>
                        <option value="B">B</option>
                        <option value="C">C</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button id="refreshBtn" class="btn btn-dark flex-fill">
                        <i class="fas fa-sync-alt me-2"></i>Refresh
                    </button>
                    <button id="exportExcelBtn" class="btn btn-success flex-fill">
                        <i class="fas fa-file-excel me-2"></i>Export
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Grid -->
    <div class="row g-4 mb-5">
        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Department Distribution</h5>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="deptChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Section Distribution</h5>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="sectionChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Gender Distribution</h5>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="genderChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Student Table -->
    <div class="card">
        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Student Records</h5>
            <span id="recordCount" class="badge bg-light text-dark">0 records</span>
        </div>
        <div class="card-body p-0 position-relative">
            <div id="tableLoading">
                <div class="spinner-border text-light" style="width:3rem;height:3rem;"></div>
                <p class="mt-3 text-light">Loading student data...</p>
            </div>

            <div class="table-responsive">
                <table class="table table-hover mb-0 text-center align-middle" id="studentsTable">
                    <thead>
                        <tr>
                            <th>S No</th>
                            <th>Code</th>
                            <th>Name</th>
                            <th>Gender</th>
                            <th>Department</th>
                            <th>Year / Sem</th>
                            <th>Section</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Nationality</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody id="studentTableBody"></tbody>
                </table>
            </div>

            <!-- Pagination & Length Controls -->
            <div class="pagination-container">
                <div class="d-flex align-items-center gap-3">
                    <label class="mb-0 fw-medium">Show entries:</label>
                    <select id="pageLength" class="form-select page-length-select">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                        <option value="all">All</option>
                    </select>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <span id="showingInfo">Showing 0 to 0 of 0 entries</span>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title" id="addUserModalLabel">Add New User</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Alert container inside modal (for AJAX messages) -->
                <div id="userFormAlert" class="mb-3" style="display:none;"></div>

                <!-- Show success message (from session) -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- Show error message (from session) -->
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- Show validation errors (from session) -->
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('users.store') }}" id="addUserForm">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required minlength="6">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Role</label>
                        <select name="role" class="form-select" required>
                            <option value="admin">Admin</option>
                            <option value="staff">Staff</option>
                            <option value="student">Student</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-dark w-100" id="createUserBtn">Create User</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Global variables
let allStudents = [];
let currentPage = 1;
let pageSize = 10;

// Chart instances
let deptChartInst, sectionChartInst, genderChartInst;

$(document).ready(function() {
    loadDashboard();

    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);

    $('#refreshBtn').click(function(e) {
        e.preventDefault();
        loadDashboard();
    });

    // Filter change events
    $('#filterDept, #filterYear, #filterSection').change(function() {
        loadDashboard();
    });

    // Page length change
    $('#pageLength').change(function() {
        pageSize = $(this).val() === 'all' ? allStudents.length : parseInt($(this).val());
        currentPage = 1;
        renderTable(allStudents);
    });

    // Export Excel
    $('#exportExcelBtn').click(function() {
        if (allStudents.length === 0) return alert("No data to export!");

        const data = allStudents.map(s => ({
            "Student Code": s.student_code,
            "Name": `${s.first_name} ${s.last_name || ''}`,
            "Gender": s.gender || '-',
            "Department": s.department,
            "Year / Sem": `${s.year} / ${s.semester}`,
            "Section": s.section,
            "Email": s.email || '-',
            "Phone": s.phone || '-',
            "Nationality": s.nationality || 'Indian',
            "Status": s.status
        }));

        const ws = XLSX.utils.json_to_sheet(data);
        const wb = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(wb, ws, "Students");
        XLSX.writeFile(wb, `Selva_AMS_Students_${new Date().toISOString().slice(0,10)}.xlsx`);
    });

    // AJAX form submission for Add User modal
    $('#addUserForm').submit(function(e) {
        e.preventDefault(); // Prevent normal form submission
        
        const form = $(this);
        const submitBtn = $('#createUserBtn');
        const alertDiv = $('#userFormAlert');
        
        // Show loading state
        submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Creating...');
        alertDiv.hide().removeClass('alert-success alert-danger').empty();
        
        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: form.serialize(),
            dataType: 'json',
            success: function(response) {
                // Show success message
                alertDiv
                    .addClass('alert alert-success')
                    .html(response.message || 'User created successfully!')
                    .show();
                
                // Reset form
                form[0].reset();
                
                // Optional: refresh dashboard data to show new user if student
                loadDashboard();
                
                // Auto-hide alert after 3 seconds
                setTimeout(() => alertDiv.fadeOut('slow'), 3000);
            },
            error: function(xhr) {
                let errorMessage = 'An error occurred. Please try again.';
                
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    // Validation errors
                    const errors = xhr.responseJSON.errors;
                    errorMessage = '<ul class="mb-0">';
                    for (let field in errors) {
                        errorMessage += `<li>${errors[field][0]}</li>`;
                    }
                    errorMessage += '</ul>';
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                
                // Show error message
                alertDiv
                    .addClass('alert alert-danger')
                    .html(errorMessage)
                    .show();
            },
            complete: function() {
                // Reset button state
                submitBtn.prop('disabled', false).html('Create User');
            }
        });
    });
});

function loadDashboard() {
    const filters = {
        dept:    $('#filterDept').val()    || '',
        year:    $('#filterYear').val()    || '',
        section: $('#filterSection').val() || ''
    };

    console.log("Loading with filters:", filters);
    $('#tableLoading').show();

    $.ajax({
        url: "{{ route('admin.dashboard.data') }}",
        type: "GET",
        data: filters,
        dataType: "json",
        success: function(res) {
            console.log("Dashboard data loaded:", res);
            
            // KPI
            $('#kpiTotal').text(res.kpi?.total ?? 0);
            $('#kpiActive').text(res.kpi?.active ?? 0);
            $('#kpiDepts').text(res.kpi?.departments ?? 0);
            $('#kpiSections').text(res.kpi?.sections ?? 0);

            // Populate departments once
            if ($('#filterDept option').length <= 1 && res.departments && res.departments.length > 0) {
                res.departments.forEach(d => {
                    $('#filterDept').append(`<option value="${d}">${d}</option>`);
                });
            }

            allStudents = res.students || [];
            console.log("Students loaded:", allStudents.length);

            // Charts
            if (res.charts) {
                renderCharts(res.charts);
            }

            // Table
            renderTable(allStudents);

            $('#tableLoading').hide();
        },
        error: function(xhr, status, error) {
            console.error("AJAX Error:", status, error);
            console.error("Response:", xhr.responseText);
            
            let errorMsg = "Failed to load data";
            try {
                const response = JSON.parse(xhr.responseText);
                errorMsg = response.message || errorMsg;
            } catch(e) {
                // Ignore parsing error
            }
            
            alert(errorMsg + ": " + error);
            $('#tableLoading').hide();
        }
    });
}

function renderCharts(data) {
    try {
        // Department Pie
        if (deptChartInst) deptChartInst.destroy();
        const deptCtx = document.getElementById('deptChart');
        if (deptCtx) {
            deptChartInst = new Chart(deptCtx, {
                type: 'pie',
                data: {
                    labels: data.dept?.labels || ['No Data'],
                    datasets: [{
                        data: data.dept?.values || [1],
                        backgroundColor: [
                            '#0f2027', '#1a3745', '#2c5364', '#3f6d80',
                            '#5a8aa3', '#7aa8c4', '#9fc6e6', '#c4e4ff'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { 
                        legend: { position: 'bottom' },
                        title: { display: true, text: 'Department Distribution' }
                    }
                }
            });
        }

        // Section Pie
        if (sectionChartInst) sectionChartInst.destroy();
        const sectionCtx = document.getElementById('sectionChart');
        if (sectionCtx) {
            sectionChartInst = new Chart(sectionCtx, {
                type: 'pie',
                data: {
                    labels: data.sections?.labels || ['A','B','C'],
                    datasets: [{
                        data: data.sections?.values || [0,0,0],
                        backgroundColor: ['#0f2027','#203a43','#2c5364'],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { 
                        legend: { position: 'bottom' },
                        title: { display: true, text: 'Section Distribution' }
                    }
                }
            });
        }

        // Gender Pie
        if (genderChartInst) genderChartInst.destroy();
        const genderCtx = document.getElementById('genderChart');
        if (genderCtx) {
            genderChartInst = new Chart(genderCtx, {
                type: 'pie',
                data: {
                    labels: data.gender?.labels || ['Male','Female','Other'],
                    datasets: [{
                        data: data.gender?.values || [0,0,0],
                        backgroundColor: ['#0f2027','#5a8aa3','#94a3b8'],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { 
                        legend: { position: 'bottom' },
                        title: { display: true, text: 'Gender Distribution' }
                    }
                }
            });
        }
    } catch (e) {
        console.error("Chart rendering error:", e);
    }
}

function renderTable(students) {
    const tbody = $('#studentTableBody');
    tbody.empty();

    if (!students || students.length === 0) {
        tbody.html('<tr><td colspan="10" class="text-center py-5 fw-medium text-muted">No students found</td></tr>');
        $('#recordCount').text('0 records');
        $('#showingInfo').text('Showing 0 to 0 of 0 entries');
        return;
    }

    const total = students.length;
    const start = (currentPage - 1) * pageSize;
    const end = Math.min(start + pageSize, total);
    const pageData = students.slice(start, end);

    pageData.forEach((s, i) => {
        const statusClass = s.status === 'Active' ? 'badge-active' : 'badge-inactive';
        const row = `
            <tr>
                <td>${start + i + 1}</td>
                <td><strong>${s.student_code || ''}</strong></td>
                <td class="text-start">${s.first_name || ''} ${s.last_name || ''}</td>
                <td>${s.gender || '-'}</td>
                <td>${s.department || '-'}</td>
                <td>${s.year || ''} / ${s.semester || ''}</td>
                <td>${s.section || '-'}</td>
                <td>${s.email || '-'}</td>
                <td>${s.phone || '-'}</td>
                <td>${s.nationality || 'Indian'}</td>
                <td><span class="badge ${statusClass} px-3 py-2">${s.status || 'Inactive'}</span></td>
            </tr>`;
        tbody.append(row);
    });

    $('#recordCount').text(total + ' records');
    $('#showingInfo').text(`Showing ${start + 1} to ${end} of ${total} entries`);
}
</script>

</body>
</html>