<?php 

namespace App\Core;

class Tools
{
    public static function print($data, $checkDie = false)
    {
        var_dump($data);
           
        if ($checkDie) {
            die();
        }
    }

    public static function createSalt()
    {
        return password_hash(rand(1000000, 9000000), PASSWORD_DEFAULT);
    }

    public static function randomCreator()
    {
        return rand(10000, 99999);
    }

    public static function checkArray($array, $search)
    {
        if (is_array($array) && array_key_exists($search, $array)) {
            return $array[$search];
        } else {
            return '';
        }
    }

    public static function manageCurl($params, $header, $url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        if (count($header)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        }
        if (count($params)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);
        $result = json_decode($result, true);
        return $result;
    }

    public static function findDayFromDate($date) 
    {
        $timestamp = strtotime($date);
        return date('D', $timestamp);
    }
}
