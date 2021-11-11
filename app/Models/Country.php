<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Country extends Model
{
    use Notifiable;

    protected $table = 'countries';

    protected $guarded = [];
    protected $appended=['image_path'];
    public function getImagePathAttribute()
    {
        return $this->image !=null ? asset('uploads/country/' . $this->image) :asset('uploads/country/default.png');
    }

    protected $casts = [
    ];

    protected $hidden = [
        'created_at', 'updated_at' 
    ];

    public function cities()
    {
        return $this->hasMany(City::class, 'country_id');
    }
}
