<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class EmployeeChat extends Model
{
    use Notifiable;

    protected $table = 'employeechats';

    protected $guarded = [];

    public function employee()
    {
        return $this->belongsTo(Employees::class , 'employee_id');
    }

    public function employer()
    {
        return $this->belongsTo(Employer::class , 'employer_id');
    }
}
