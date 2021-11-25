<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Employer extends Authenticatable implements JWTSubject
{
    use Notifiable;
    protected $guard = 'employer';

    protected $table = 'employers';

    protected $guarded = [];

    protected $hidden = [

    ];

    protected $append=[
        'image_path'
    ];

    protected $casts = [
        'established_at' => 'integer',
    ];

    public function getImagePathAttribute()
    {
        return $this->image ==null ? asset('uploads/employer/image/default.jpg') : asset('uploads/employer/image/' . $this->image); 
    }

    public function jobs()
    {
        return $this->hasMany('App\Models\job', 'employer_id');
    }

    public function report()
    {
        return $this->hasMany('App\Models\Report', 'employer_id');
    }

    public function employeeChat(){
        return $this->hasMany(EmployeeChat::class, 'employer_id');
    }

    public function employerChat(){
        return $this->hasMany(EmployerChat::class, 'employer_id');
    }

    public function countries(){
        return $this->belongsTo(Country::class,'country_id');
    }

    public function cities()
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [
            'type'       => 'Employer'
        ];
    }
}
