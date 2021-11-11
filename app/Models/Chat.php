<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Chat extends Model
{
    use Notifiable;

    protected $table = 'chats';

    protected $guarded = [];

    public function employee()
    {
        return $this->belongsTo(Employees::class , 'employee_id');
    }

    public function employer()
    {
        return $this->belongsTo(Employer::class , 'employer_id');
    }
    protected $casts = [
        'seen'=> 'integer',
    ];
}
