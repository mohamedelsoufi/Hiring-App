<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class EmployeeNotifications extends Model
{
    use Notifiable;

    protected $table = 'employeenotifications';

    protected $guarded = [];

    protected $hidden = [

    ];

    protected $casts = [
        'type'          => 'integer',
        'employee_id'   => 'integer',
        'candate_id'    => 'integer',
    ];


}
