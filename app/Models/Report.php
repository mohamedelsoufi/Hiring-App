<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Report extends Model
{
    use Notifiable;

    protected $table = 'reports';

    protected $guarded = [];

    public function employee()
    {
        return $this->belongsTo('App\Models\Employees', 'employee_id');
    }

    public function employer()
    {
        return $this->belongsTo('App\Models\Employer', 'employer_id');
    }
}
