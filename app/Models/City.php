<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class City extends Model
{
    use Notifiable;

    protected $table = 'cities';

    protected $guarded = [];

    protected $casts = [
        'country_id' => 'integer',
    ];

    protected $hidden = [
        'created_at', 'updated_at' 
    ];

    public function countries(){
        return $this->belongsTo(Country::class,'country_id');
    }
}
