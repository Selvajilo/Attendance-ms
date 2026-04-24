<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use App\Models\Student;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\View\View;




class SelvaController  extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

public function login($role)
{
    return view('welcome', compact('role'));
}

public function adminlogin(Request $request, $role)
{
    $credentials = [
        'email' => $request->email,
        'password' => $request->password,
        'role' => $role
    ];

    if(Auth::attempt($credentials))
    {
        $user = Auth::user();

        if($user->role == 'admin'){
            return redirect()->route('dashboard');
        }

        if($user->role == 'staff'){
            return redirect()->route('staffdashboard');
        }

        if($user->role == 'student'){
            // Pass both user data and email specifically
            return redirect()->route('studdashboard')
                ->with('user', $user)
                ->with('student_email', $user->email);
        }
    }

    return back()->withErrors([
        'email' => 'Invalid login credentials'
    ]);
}



public function storeStudent(Request $request)
{
    $validated = $request->validate([
        'student_code'     => 'required|string|max:20|unique:students,student_code',
        'admission_number' => 'required|string|max:20|unique:students,admission_number',
        'first_name'       => 'required|string|max:100',
        'last_name'        => 'nullable|string|max:100',
        'gender'           => 'required|in:Male,Female,Other',
        'date_of_birth'    => 'nullable|date',
        'email'            => 'nullable|email|max:150|unique:students,email',
        'phone'            => 'nullable|string|max:15',
        'parent_phone'     => 'nullable|string|max:15',
        'department'       => 'required|string|max:100',
        'course'           => 'required|string|max:100',
        'year'             => 'required|integer|min:1|max:5',
        'semester'         => 'required|integer|min:1|max:8',
        'section'          => 'required|string|max:10',
        'age'              => 'nullable|integer|min:15|max:40',
        'address'          => 'nullable|string|max:500',
        'city'             => 'nullable|string|max:100',
        'state'            => 'nullable|string|max:100',
        'country'          => 'required|string|max:100',
        'pincode'          => 'nullable|string|max:10',
        'father_name'      => 'nullable|string|max:150',
        'mother_name'      => 'nullable|string|max:150',
        'admission_date'   => 'nullable|date',
        'blood_group'      => 'nullable|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
        'nationality'      => 'required|string|max:100',
    ]);

    // Optional: auto-calculate age if date_of_birth is given
    if ($request->filled('date_of_birth')) {
        $validated['age'] = now()->diffInYears($request->date_of_birth);
    }

    DB::table('students')->insert([
        ...$validated,
        'status'     => 'Active',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    return back()->with('success', 'Student added successfully!');
}



public function showAttendance(Request $request, $department)
{
    $date = $request->date 
        ? Carbon::parse($request->date)->format('Y-m-d')
        : date('Y-m-d');

    $students = DB::table('students')
        ->where('department', $department)
        ->get();

    $existing = DB::table('attendance')
        ->whereDate('attendance_date', $date)
        // ->where('department', $department) // ✅ FIXED
        ->pluck('status', 'student_id')
        ->toArray();
        

    return view('mark_attendance', compact('students','department','date','existing'));
}


public function storeAttendance(Request $request)
{
    $date = Carbon::parse($request->date)->format('Y-m-d');

    foreach ($request->attendance as $student_id => $status) {

        DB::table('attendance')->updateOrInsert(
            [
                'student_id' => $student_id,
                'attendance_date' => $date
            ],
            [
                'status' => $status,
                'department' => $request->department,
                'created_at' => now(),
                'updated_at' => now()
            ]
        );
    }

    return back()->with('success', 'Attendance saved successfully');
}




public function fetchStudents(Request $request)
    {
        // Optional: filter by department, search, etc.
        $department = $request->query('department');
        $search = $request->query('search');

        $query = DB::table('students')
            ->select(
                'student_code',
                'first_name',
                'last_name',
                'gender',
                'email',
                'phone',
                'department',
                'section',
                'year',
                'semester',
                'status'
            )
            ->orderBy('student_code');

        if ($department) {
            $query->where('department', $department);
        }

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('student_code', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $students = $query->get();

        return response()->json([
            'success' => true,
            'students' => $students,
            'count' => $students->count()
        ]);
    }





public function storeUser(Request $request)
{
    $request->validate([
        'name'     => 'required|string|max:255',
        'email'    => [
            'required',
            'email',
            Rule::unique('users')->where(function ($query) use ($request) {
                return $query->where('role', $request->role);
            }),
        ],
        'password' => 'required|min:6',
        'role'     => 'required|in:admin,staff,student',
    ], [
        'email.unique' => 'A user with this email and role already exists.',
    ]);
    

    User::create([
        'name'     => $request->name,
        'email'    => $request->email,
        'password' => Hash::make($request->password),
        'role'     => $request->role,
    ]);

    return response()->json([
        'success' => true,
        'message' => 'User created successfully!'
    ]);
}


public function getData(Request $request)
{
    $dept    = $request->input('dept');
    $year    = $request->input('year');
    $section = $request->input('section');

    $query = DB::table('students');

    if ($dept)    $query->where('department', $dept);
    if ($year)    $query->where('year', $year);
    if ($section) $query->where('section', $section);

    $students = $query->get();

    // KPI
    $kpi = [
        'total'       => $students->count(),
        'active'      => $students->where('status', 'Active')->count(),
        'departments' => $students->pluck('department')->unique()->count(),
        'sections'    => $students->pluck('section')->unique()->count(),
    ];

    // Department chart data
    $deptGroups = $students->groupBy('department');
    $deptData = [
        'labels' => $deptGroups->keys()->values()->all(),
        'values' => $deptGroups->map->count()->values()->all(),
    ];

    // Charts
    $charts = [
        'dept' => $deptData,
        'sections' => [
            'labels' => ['A', 'B', 'C'],
            'values' => [
                $students->where('section', 'A')->count(),
                $students->where('section', 'B')->count(),
                $students->where('section', 'C')->count(),
            ]
        ],
        'gender' => [
            'labels' => ['Male', 'Female', 'Other'],
            'values' => [
                $students->where('gender', 'Male')->count(),
                $students->where('gender', 'Female')->count(),
                $students->where('gender', 'Other')->count() ?: 0,
            ]
        ]
    ];

    // Departments list for dropdown (full list)
    $departments = DB::table('students')
        ->distinct()
        ->orderBy('department')
        ->pluck('department')
        ->filter()
        ->values()
        ->all();

    // Student data
    $studentData = $students->map(function ($row) {
        return [
            'student_code' => $row->student_code,
            'first_name'   => $row->first_name,
            'last_name'    => $row->last_name ?? '',
            'gender'       => $row->gender,
            'department'   => $row->department,
            'year'         => $row->year,
            'semester'     => $row->semester,
            'section'      => $row->section,
            'email'        => $row->email,
            'phone'        => $row->phone,
            'nationality'  => $row->nationality ?? 'Indian',
            'status'       => $row->status,
        ];
    })->values();

    return response()->json([
        'kpi'         => $kpi,
        'charts'      => $charts,
        'students'    => $studentData,
        'departments' => $departments,
    ]);
}





public function showStudentDashboard()
{
    $email = Auth::user()->email;

    $student = DB::table('students')
        ->leftJoin('fees', 'students.id', '=', 'fees.student_id')
        ->where('students.email', $email)
        ->select(
            'students.*',
            'fees.sem1', 'fees.sem2', 'fees.sem3', 'fees.sem4',
            'fees.sem5', 'fees.sem6', 'fees.sem7', 'fees.sem8',
            'fees.total'
        )
        ->first();

    if (!$student) {
        return redirect()->route('home')
            ->with('error', 'No student profile found for this account.');
    }

    return view('student_dashboard', compact('student'));
}






public function uploadPhoto(Request $request)
{
    $request->validate([
        'photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
    ]);

    $email = auth()->user()->email;
    $student = DB::table('students')->where('email', $email)->first();

    if (!$student) {
        return response()->json(['message' => 'Student profile not found.'], 404);
    }

    $binaryData = file_get_contents($request->file('photo')->getRealPath());

    DB::table('students')
        ->where('id', $student->id)
        ->update(['photo' => $binaryData]);

    return response()->json(['message' => 'Photo uploaded successfully!']);
}



public function staffDashboard(): View
{
    $departments = DB::table('students')
        ->select('department', DB::raw('COUNT(*) as student_count'))
        ->groupBy('department')
        ->orderBy('department')
        ->get();   // ← returns Collection of stdClass objects

    return view('staff_dashboard', compact('departments'));
}


}