<?php

namespace App\Http\Resources;

use App\Models\CategoryTranslation;
use Illuminate\Http\Resources\Json\JsonResource;
use App\CustomClass\response;
use App\Models\Avmeeting;
use App\Models\City;
use App\Models\Country;
use App\Models\EmployeeJob;
use Carbon\Carbon;


class acceptedEmployeeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request) {
        $job_id = $request->job_id;

        return [
            'id' => $this->id,
            'make_interview' => (EmployeeJob::where('employee_id', '=', $this->id)->where('job_id', '=', $job_id)->first()->candat_status == null) ? false :true ,
            'interview_date' =>[ 
                            'meeting_date'  =>  EmployeeJob::where('employee_id', '=', $this->id)->where('job_id', '=', $job_id)->first()->job->meeting_date,
                            'meeting_from'     => Avmeeting::find(EmployeeJob::where('employee_id', '=', $this->id)->where('job_id', '=', $job_id)->first()->avmeeting_id)->time_from,
                            ],
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
            
            'industry'   => CategoryTranslation::where('category_id', '=', $this->category_id)->where('locale', '=', 'en')->select('category_id','name', 'description')->first(),
            'cv'   => response::filePath(url('/') . '/uploads/employee/cv', $this->cv),
            'audio'=> response::filePath(url('/') . '/uploads/employee/audio', $this->audio),
            'video'=> response::filePath(url('/') . '/uploads/employee/video', $this->video),
            'image'=> ($this->image != null) ? (url('/') . '/uploads/employee/image/' . $this->image) : (url('/') . '/uploads/employee/image/default.jpg'),
        ];
    }
}
