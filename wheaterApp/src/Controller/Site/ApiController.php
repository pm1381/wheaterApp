<?php
namespace App\Controller\Site;

use App\Controller\Refrence\SiteRefrenceController;
use App\Core\Tools;
use App\Model\Input;
use App\Model\User;
use App\Model\Wheater;

class ApiController extends SiteRefrenceController
{
    /**
     * @Route("api/search/")
    */
    public function search()
    {
        $data = [];
        $location = Input::post('location');
        $params = array(
            'key' => API_KEY,
            'q' => $location,
            'days' => '7',
            'aqi'=> 'yes',
            'alerts' => 'no'
        );
        $response = Tools::manageCurl($params, [], "https://api.weatherapi.com/v1/forecast.json");
        $wheather = new Wheater();
        $wheather->wheaterCurrent($response['current']);
        $wheather->wheaterLocation($response['location']);
        $wheather->wheaterForecast($response['forecast']);
        $data['wheaterData'] = $wheather->joinResults();
        return $this->readJson($data);
    }

    /**
     * @Route("api/addToCities/")
    */
    public function addCity()
    {
        $data = [];
        $data['error'] = false;
        $city = Input::post('city');
        $userId = User::current()['id'];
        $result = $this->manageAddErrors($city, $data, $userId);
        if ($data['error']) {
            $this->readJson($data);
        }
        $userCityString = $city;
        if (count($result) && $result['userCity'] != "") {
            $userCityString .= "," . $result['userCity'];
        }
        $user = new User();
        $user->where(['userId' => $userId])->update(
            [
                'userActiveCity' => $city,
                'userCity' => $userCityString
            ]
        );
        return $this->readJson($data);
    }

    /**
     * @Route("api/changeCity/")
    */
    public function changeCity()
    {
        $city = Input::post('city');
        $user = new User();
        $user->where(['userId' => User::current()['id']])->update(
            [
                'userActiveCity' => $city
            ]
        );
        $this->readJson([]);
    }

    /**
     * @Route("api/removeCity/")
    */
    public function remove()
    {
        $data = [];
        $data['error'] = false;
        $userId = User::current()['id'];
        $city = Input::post('city');

        $user = new User();
        $result = $user->row(['userCity'])
            ->where(['userId' => $userId])->limit(1)->select();
        if (count($result)) {
            if (strpos($result['userCity'], $city) === false) {
                $data['error'] = true;
                $data['message'] = 'city has not added yet';    
            } else {
                $this->removeSelectedCity($result, $city, $userId);
            }
        } else {
            $data['error'] = true;
            $data['message'] = 'no user found';
        }
        $this->readJson($data);
    }

    private function manageAddErrors($city, &$data, $userId)
    {
        if ($city == "") {
            $data['error']  = true;
            $data['message']= 'please choose a city first';
            return [];
        }

        if ($userId == 0) {
            $data['error']  = true;
            $data['message']= 'no user found';
            return [];
        }

        $user = new User();
        $result = $user->row(['userCity'])
            ->where(['userId' => $userId])->limit(1)->select();
        if (strpos($result['userCity'], $city) !== false) {
            $data['error']  = true;
            $data['message']= 'city was added before';
        }
        return $result;
    }

    private function removeSelectedCity($result, $city, $userId)
    {
        $cityArray = explode(",", $result['userCity']);
        $i = 0;
        foreach ($cityArray as $val) {
            if ($val == $city) {
                unset($cityArray[$i]);
            }
            $i++;
        }
        $cityArray = array_values($cityArray);
        $cities = implode(",", $cityArray);
        $user = new User();
        if (count($cityArray) == 0) {
            $selectedCity = "";
        } else {
            $selectedCity = $cityArray[0];
        }
        $user->where(['userId' => $userId])->update(
            [
                'userCity' => $cities,
                'userActiveCity' => $selectedCity
            ]
        );
    }
}