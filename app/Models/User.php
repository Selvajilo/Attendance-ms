<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    // ... other properties

    /**
     * Get the student record associated with the user.
     */
    public function student()
    {
        return $this->hasOne(Studsent::class, 'email', 'email');
        // 'email' in students table matches 'email' in users table
    }
}