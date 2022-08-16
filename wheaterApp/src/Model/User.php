<?php

namespace App\Model;

use App\Core\Generator;
use App\Core\Tools;

class User extends Generator
{
    public static $current;

    public function __construct()
    {
        $tableName = 'user';
        parent::__construct($tableName);
    }

    public static function current()
    {
        if (empty(self::$current)) {
            self::$current['id'] =  0;
            self::$current['login'] = false;

            $cookie = new Cookie();
            $token = $cookie->name('wheaterAppUserToken')->select();

            $user = new User();
            $result = $user->row(['userId', 'userCity'])
                ->where(['userToken' => $token])
                ->limit(1)->select();
            if (count($result)) {
                self::$current['id']     = $result['userId'];
                self::$current['login']  = true;
            }
        }
        return self::$current;
    }

    public function manageUserCities($userId)
    {
        $user = new User();
        $result = $user->row(['userCity'])
            ->where(['userId' => $userId])->limit(1)->select();
        if (count($result)) {
            $citiesArray = explode(",", $result['userCity']);
            return $citiesArray;
        }
        return [];
    }
}