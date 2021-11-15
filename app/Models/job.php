<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use App\Models\Avmeeting;
class job extends Model
{
    use Notifiable;

    protected $table = 'jobs';

    protected $guarded = [];

    protected $hidden = [
        'created_at','updated_at'
    ];

    protected $casts = [
        'experience'    => 'integer',
        'meeting_time'  => 'integer',
        'salary'        => 'integer',
        'gender'        => 'integer',
        'applies'       => 'integer',
    ];

    //relations
    public function employer()
    {
        return $this->belongsTo('App\Models\Employer', 'employer_id');
    }
    
    public function category()
    {
        return $this->belongsTo('App\Models\Category', 'category_id');
    }
    public function special()
    {
        return $this->belongsTo('App\Models\Category', 'job_specialize');
    }
    public function EmployeeJob()
    {
        return $this->hasMany('App\Models\EmployeeJob', 'job_id');
    }
    public function avmeetings()
    {
    return $this->hasMany(Avmeeting::class,'job_id');
    }
    public function country(){
        return $this->belongsTo(Country::class,'country_id');
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }


    //scope 
    public function scopeDateNotCome($query){
        return $query->Where('meeting_date', '>', date('Y-m-d'));
    }

    public function scopeTimeNotCome($query){
        return $query->where('meeting_date', '=', date('Y-m-d'))->where('meeting_from', '>', date('H:i:s'));
    }
}
