<?php

namespace App\Http\Resources;

use App\Models\job;
use Illuminate\Http\Resources\Json\JsonResource;

class avmeetingResource extends JsonResource
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
            'job_id'        => $this->job_id,
            'time_from'     => date("H:i", strtotime($this->time_from)),
            'time_to'       => date("H:i", strtotime($this->time_to)),
            'time'          => job::find($this->job_id)->meeting_time,
            'date'          => job::find($this->job_id)->meeting_date,
            'available'     => $this->available,
        ];
    }
}
