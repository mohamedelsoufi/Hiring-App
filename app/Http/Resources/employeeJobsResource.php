<?php

namespace App\Http\Resources;

use App\Models\Avmeeting;
use App\Models\Employees;
use App\Models\job;
use Illuminate\Http\Resources\Json\JsonResource;

class employeeJobsResource extends JsonResource
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
            'job_id' => $this->job_id,
            'candat_applay_status' => $this->candat_applay_status,
            'meeting_time_status' => $this->meeting_time_status,
            'note' => $this-> note,
            'candat_status' => $this->candat_status,
            'candute_status' => $this->candat_status,
            'created_at' => date("Y-m-d", strtotime($this->created_at)),
            'available_meeting' =>new avmeetingResource(Avmeeting::find($this->avmeeting_id)),
            'employee' => new employeeResource(Employees::find($this->employee_id)),
            'job' => new jobResource(job::find($this->job_id)),
        ];
    }
}
