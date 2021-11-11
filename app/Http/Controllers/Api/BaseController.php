<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class BaseController extends Controller
{

    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
        $this->middleware(['permission:read-'   . $this->getClassNameFromModel()])->only('index');
        $this->middleware(['permission:create-' . $this->getClassNameFromModel()])->only('store');
        $this->middleware(['permission:update-' . $this->getClassNameFromModel()])->only('update');
        $this->middleware(['permission:delete-' . $this->getClassNameFromModel()])->only('destroy');
    }

    public function sendResponse($result, $message)
    {
        $response = [
            'success' => true,
            'data' => $result,
            'message' => $message,
        ];
        return response()->json($response, 200);
    } //end of function sentResponse

    public function sendError($error, $errorMessages = [], $code = 200)
    {
        $response = [
            'success' => false,
            'message' => $error,
        ];

        if (!empty($errorMessages)) {
            $response['data'] = $errorMessages;
        }
        return response()->json($response, $code);
    }

    public function getClassNameFromModel()
    {
        return Str::plural($this->getSingularModelName());
    } //end of get class name

    public function getSingularModelName()
    {

        return strtolower(class_basename($this->model));
    } //end of get singular model name
}
