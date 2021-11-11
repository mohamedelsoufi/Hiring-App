<?php

namespace App\Http\Resources;

use App\CustomClass\response;
use App\Models\Category;
use App\Models\CategoryTranslation;
use App\Models\City;
use App\Models\Country;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class employeeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'fullName'=> $this->fullName,
            'email'=> $this->email,
            'country'=> Country::find($this->country_id)->name,
            'city'=> City::find($this->city_id)->name,
            'title'=> $this->title,
            'qualification'=> $this->qualification,
            'university'=> $this->university,
            'graduation_year'=> $this->graduation_year,
            'experience' => $this->experience,
            'birth'=> $this->birth,
            'age'=> Carbon::now()->format('Y') - $this->birth,
            'gender'=>$this->gender,

            'study_field'=> $this->study_field,
            'deriving_licence'=> $this->deriving_licence,
            'skills'=> $this->skills,
            'languages'=> $this->languages,
            'phone'=> $this->phone,
            'industry'   => CategoryTranslation::where('category_id', '=', $this->category_id)->where('locale', '=', 'en')->select('category_id','name')->first(),
            'cv'   => response::filePath(url('/') . '/uploads/employee/cv', $this->cv),
            'audio'=> response::filePath(url('/') . '/uploads/employee/audio', $this->audio),
            'video'=> response::filePath(url('/') . '/uploads/employee/video', $this->video),
            'image'=> ($this->image != null) ? (url('/') . '/uploads/employee/image/' . $this->image) : (url('/') . '/uploads/employee/image/default.jpg'),
        ];
    }
}
