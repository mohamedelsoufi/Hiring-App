<?php
namespace App\CustomClass;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;
use App\t_log;
use App\User;

class response
{
    public static function suceess($message, $statusCode, $dataName = '', $data = ''){
        if($dataName == ''){
            return response()->json([
                'status'  => true,
                'message' => $message,
            ], $statusCode);
        } else {
            return response()->json([
                'status'  => true,
                'message' => $message,
                $dataName => $data,
            ], $statusCode);
        }
    }

    public static function falid($message, $statusCode){
        return response()->json([
            'status'  => false,
            'message' => $message,
        ], $statusCode);
    }

    public static function filePath($path, $value){
        if($value == null){
            return null;
        } else {
            return $path . '/' . $value;
        }
    }

    public static function candatStage($number){
        if($number == 0){
            return 'reject';
        } else if($number == 1){
            return 'accept';
        } else if($number == 2){
            return 'underreview';
        } else {
            return 'need to confirmed';
        }
    }
}