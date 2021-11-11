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
        // 'employee_id' 
    ];
    protected $casts = [
        'job_id' => 'string',
        'candat_applay_status' => 'string',
        'meeting_time_status' => 'string',
        'candat_status' => 'string',
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
