<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class categoryWithJobCountResource extends JsonResource
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
            'category_id'           => $this->translations[1]->category_id,
            'locale'                => $this->translations[1]->locale,
            'name'                  => $this->translations[1]->name,
            'description'           => $this->translations[1]->description,
            'job_count'             => $this->job_count,
        ];
    }
}
