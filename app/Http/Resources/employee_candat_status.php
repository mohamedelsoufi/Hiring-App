<?php

namespace App\Http\Resources;

use App\CustomClass\response;
use App\Models\CategoryTranslation;
use App\Models\City;
use App\Models\Country;
use App\Models\EmployeeJob;
use Illuminate\Http\Resources\Json\JsonResource;

class employee_candat_status extends JsonResource
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
            'candat_status' => EmployeeJob::where('job_id', '=', $request->job_id)->where('employee_id', '=', $this->id)->first()->candat_status,
            'fullName'=> $this->fullName,
            'email'=> $this->email,
            'country'=> Country::find($this->country_id)->name,
            'city'=> City::find($this->city_id)->name,
            'title'=> $this->title,
            'qualification'=> $this->qualification,
            'university'=> $this->university,
            'experience' => $this->experience,
            'graduation_year'=> $this->graduation_year,
            'study_field'=> $this->study_field,
            'deriving_licence'=> $this->deriving_licence,
            'birth'=> $this->birth,
            'gender'=>$this->gender,
            'stage'    =>  response::candatStage(EmployeeJob::where('employee_id', '=', $this->id)->first()->candat_status),
            'skills'=> $this->skills,
            'languages'=> $this->languages,
            'industry' => CategoryTranslation::where('category_id', '=', $this->category_id)->where('locale', '=', 'en')->select('category_id','name', 'description')->first(),
            'cv'   => response::filePath(url('/') . '/uploads/employee/cv', $this->cv),
            'audio'=> response::filePath(url('/') . '/uploads/employee/audio', $this->audio),
            'video'=> response::filePath(url('/') . '/uploads/employee/video', $this->video),
            'image'=> ($this->image != null) ? (url('/') . '/uploads/employee/image/' . $this->image) : (url('/') . '/uploads/employee/image/default.jpg'),
        ];
    }
}
