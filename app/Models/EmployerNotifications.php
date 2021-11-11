<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class EmployerNotifications extends Model
{
    use Notifiable;

    protected $table = 'employernotifications';

    protected $guarded = [];

    protected $hidden = [
        // 'employee_id' 
    ];

    protected $casts = [
        'type' => 'integer',
        'employer_id'=> 'integer',
        'candate_id' => 'integer',
    ];
}
