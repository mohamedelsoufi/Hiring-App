<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\job;
class Avmeeting extends Model
{
    protected $guarded=[];
    public function job()
    {
        return $this->belongsTo(job::class);
    }
    // public function employee_job()
    // {
    //     return $this->hasOne('App\Models\EmployeeJob', 'avmeeting_id');
    // }
    protected $casts = [
        'available' => 'integer',
    ];
}
