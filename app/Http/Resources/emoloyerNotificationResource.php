<?php

namespace App\Http\Resources;

use App\Models\EmployeeJob;
use App\Models\job;
use Illuminate\Http\Resources\Json\JsonResource;

class emoloyerNotificationResource extends JsonResource
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
            'employer_id'       => $this->employer_id,
            'title'             => $this->title,
            'body'              => $this->body,
            'read_at'           =>$this->read_at != null ? true :false,
            'candat'           => [
                                        'id'            => ($this->candate_id != null) ? EmployeeJob::find($this->candate_id)->id : null,
                                        'candat_status' => ($this->candate_id != null) ? EmployeeJob::find($this->candate_id)->candat_applay_status : null,
                                    ],
            'employee_id'       => EmployeeJob::find($this->candate_id)->employee_id,
            'created_at'        => date("Y-m-d H:i", strtotime($this->created_at)),
            'job'               => new jobResource(job::find(EmployeeJob::find($this->candate_id)->job_id)),
        ];
    }
}
