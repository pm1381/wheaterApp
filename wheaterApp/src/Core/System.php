<?php

namespace App\Core;

use Exception;

class System
{
    public static function pageError($code, $message = "error 404 ! page not found")
    {
        if (DEBUG) {
            throw new Exception($message);
        }

        http_response_code($code);
        $data['head']['title']       = $code;
        $data['head']['description'] = $code;
        $data['head']['keywords'] = $code;
        $data['content']['text'] = $code;

        $controller = new Controller();
        $controller->render('error/' . $code, $data);
        exit();
    }

    public static function siteAddress()
    {
        return SITE_ADDRESS;
    }

    public static function instaAddress()
    {
        return INSTAGRAM_ADDRESS;
    }

    public static function googleMapAddress()
    {
        return GOOGLE_MAP_LINK;
    }

    public static function baladAddress()
    {
        return BALAD_LINK;
    }

    public static function headerConfig()
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");
        header('Content-Type: application/json');
    }

    public static function redirect($url, $code = 301)
    {
        header("location: " . $url, true, $code);
        exit();
    }

}