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
    
    protected $casts = [
        'available' => 'integer',
    ];
}
