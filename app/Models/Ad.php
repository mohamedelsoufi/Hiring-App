<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ad extends Model
{
    
    protected $guarded=[];
    protected $appended=['image_path'];
    public function getImagePathAttribute()
    {
        return $this->image !=null ? asset('uploads/ads/'.$this->image) : asset('uploads/ads/default.png');
    }}
