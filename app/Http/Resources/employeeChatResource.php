<?php

namespace App\Http\Resources;

use App\Models\EmployeeChat;
use App\Models\EmployerChat;
use Illuminate\Http\Resources\Json\JsonResource;

class employeeChatResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $employeeChay = ((EmployeeChat::where('employer_id', '=', $request->employer_id)->where('employee_id', '=', $this->id)->first() != null) ? date("Y-m-d H:m", strtotime(EmployeeChat::where('employer_id', '=', $request->employer_id)->where('employee_id', '=', $this->id)->first()->created_at)): null);
        $employerChay = ((EmployerChat::where('employer_id', '=', $request->employer_id)->where('employee_id', '=', $this->id)->first() != null) ? date("Y-m-d H:m", strtotime(EmployerChat::where('employer_id', '=', $request->employer_id)->where('employee_id', '=', $this->id)->first()->created_at)): null);
        return [
            'id'            => $this->id,
            'fullName'      => $this->fullName,
            'image'         => ($this->image != null) ? (url('/') . '/uploads/employee/image/' . $this->image) : (url('/') . '/uploads/employee/image/default.jpg'),
            'date'          => ($employeeChay > $employerChay) ? $employeeChay : $employerChay,
        ];
    }
}
