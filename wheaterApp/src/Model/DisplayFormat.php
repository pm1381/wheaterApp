<?php

namespace App\Model;

class DisplayFormat {

    public static function jsonFormat($result, $message='', $data = [], $error = [])
    {
        //$dataObject = json_encode($data);
        $result = ['status' => $result, 'message' => $message, 'data' => $data, 'error' => $error];
        
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }
}