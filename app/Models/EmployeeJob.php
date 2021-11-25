<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class EmployeeJob extends Model
{
    use Notifiable;

    protected $table = 'employee_job';

    protected $guarded = [];

    protected $hidden = [
        
    ];
    protected $casts = [
        
    ];

    public function employee()
    {
        return $this->belongsTo('App\Models\Employees', 'employee_id');
    }

    public function avmeetings()
    {
        return $this->belongsTo('App\Models\Avmeeting', 'employee_id');
    }

    public function EmployeeJob()
    {
        return $this->belongsTo('App\Models\job', 'avmeeting_id');
    }

    public function job()
    {
        return $this->belongsTo('App\Models\job', 'job_id');
    }

}
