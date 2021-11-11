<?php

namespace App\Http\Resources;

use App\Models\Avmeeting;
use App\Models\Country;
use App\Models\EmployeeJob;
use App\Models\job;
use Illuminate\Http\Resources\Json\JsonResource;

class notificationResource extends JsonResource
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
            'id'                => $this->id,
            'type'              => $this->type,
            'employee_id'       => $this->employee_id,
            'title'             => $this->title,
            'body'              => $this->body,
            'read_at'           =>$this->read_at != null ? true :false,
            'candate_id'        => $this->candate_id,
            'employer_id'       => $this->employer_id,
            // 'job_id'            => ($this->candate_id != null) ? EmployeeJob::find($this->candate_id)->job_id : null,
            'job_id'            => $this->job_id,
            'job'               => [
                                    'id'    => ($this->candate_id != null) ? job::find(EmployeeJob::find($this->candate_id)->job_id)->id : null,
                                    'title' => ($this->candate_id != null) ? job::find(EmployeeJob::find($this->candate_id)->job_id)->title : null,
                                    'contry'=> ($this->candate_id != null) ? job::find(EmployeeJob::find($this->candate_id)->job_id)->employer->countries->name : null,
                                    'city'  => ($this->candate_id != null) ? job::find(EmployeeJob::find($this->candate_id)->job_id)->employer->cities->name : null,
                                    'image' => ($this->candate_id != null) ? url('/') . '/uploads/employer/image/' . job::find(EmployeeJob::find($this->candate_id)->job_id)->employer->image : null,
                                    ],
            'meeting_date'      => ($this->candate_id != null) ? new avmeetingResource(Avmeeting::find(EmployeeJob::find($this->candate_id)->avmeeting_id)) : $this->meeting_date,
            'viedo_channel_name'=> $this->viedo_channel_name,
            'viedo_token'       => $this->viedo_token,
            'created_at'        => date("Y-m-d H:i", strtotime($this->created_at)),
        ];
    }
}
