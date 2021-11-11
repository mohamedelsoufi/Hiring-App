<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class Category extends Model implements TranslatableContract
{
    use Translatable;

    public $translatedAttributes = ['name'];

    protected $guarded = [];

    protected $append = ['image_path'];

    public function getImagePathAttribute(){
        return $this->image != null ? asset('uploads/category_images/'.$this->image) :  asset('uploads/category_images/default.png') ;
    }

    public function employees()
    {
        return $this->hasMany('App\Models\Employees', 'category_id');
    }

    public function job(){
        return $this->hasMany(job::class, 'category_id');
    }
    public function jobspecial()
    {
        return $this->hasMany(Category::class, 'parent_id');
   
    }
}
