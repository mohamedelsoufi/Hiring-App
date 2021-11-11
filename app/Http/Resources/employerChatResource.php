<?php

namespace App\Http\Resources;

use App\Models\EmployeeChat;
use App\Models\EmployerChat;
use Illuminate\Http\Resources\Json\JsonResource;

class employerChatResource extends JsonResource
{
    private $x;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $employeeChay = ((EmployeeChat::where('employer_id', '=', $this->id)->where('employee_id', '=', $request->employee_id)->first() != null) ? date("Y-m-d H:m", strtotime(EmployeeChat::where('employer_id', '=', $this->id)->where('employee_id', '=', $request->employee_id)->first()->created_at)): null);
        $employerChay = ((EmployerChat::where('employer_id', '=', $this->id)->where('employee_id', '=', $request->employee_id)->first() != null) ? date("Y-m-d H:m", strtotime(EmployerChat::where('employer_id', '=', $this->id)->where('employee_id', '=', $request->employee_id)->first()->created_at)): null);
        return [
            'id'            => $this->id,
            'fullName'      => $this->fullName,
            'image'         => ($this->image != null) ? (url('/') . '/uploads/employer/image/' . $this->image) : (url('/') . '/uploads/employer/image/default.jpg'),
            'date'          => ($employeeChay > $employerChay)? $employeeChay : $employerChay,
        ];
    }
}
