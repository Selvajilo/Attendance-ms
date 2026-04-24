<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $table = 'students';
    
    protected $fillable = [
        'student_code',
        'first_name',
        'last_name',
        'gender',
        'date_of_birth',
        'age',
        'email',
        'phone',
        'address',
        'city',
        'state',
        'country',
        'pincode',
        'father_name',
        'mother_name',
        'parent_phone',
        'admission_number',
        'admission_date',
        'course',
        'department',
        'year',
        'semester',
        'section',
        'blood_group',
        'nationality',
        'photo',
        'status'
    ];
    
    /**
     * Get the user that owns the student record.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'email', 'email');
    }
    
    // Accessor for full name
    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }
}