<?php

namespace App\Http\Resources;

use App\Models\Avmeeting;
use App\Models\CategoryTranslation;
use App\Models\Employer;
use App\Models\EmployeeJob;
use Illuminate\Http\Resources\Json\JsonResource;

class jobAvMeetings extends JsonResource
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
            'id'            => $this->id,
            'title'         => $this->title,
            'details'       => $this->details,
            'start_from'    => date("H:i", strtotime($this->meeting_from)),
            'meeting_end'    => date("H:i", strtotime($this->meeting_to)),
            'meeting_date'  => date("Y-m-d", strtotime($this->meeting_date)),
            'meeting_time'  => $this->meeting_time,
            'note'          => $this->note,
            'salary'        => $this->salary,
            'gender'        => $this->gender,
            'experience'    => $this->experience,
            'qualification' => $this->qualification,
            'interviewer_name'   => $this->interviewer_name,
            'interviewer_role'   => $this->interviewer_role,
            'status'             => $this->status,
            'applies'            => $this->applies,
            'avmeetings'         => avmeetingResource::collection(Avmeeting::where('job_id', '=', $this->id)->where('available', '=', 0)->get()),
            'employeejob_count'  => EmployeeJob::where('job_id', '=', $this->id)->get()->count(),
            'job_field'          => CategoryTranslation::where('category_id', '=', $this->category_id)->where('locale', '=', 'en')->select('category_id','name')->first(),
            'employer'           => new employerResource(Employer::find($this->employer_id)),
        ];
    }
}
