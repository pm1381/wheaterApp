<?php
namespace App\Controller\Site;

use App\Controller\Refrence\SiteRefrenceController;
use App\Core\Tools;
use App\Model\Cookie;
use App\Model\Input;
use App\Model\User;
use App\Model\Wheater;

class HomeController extends SiteRefrenceController
{

    public function home()
    {
        $searchedCity = Input::get('searchInput');
        
        $this->data['userResult'] = [];
        $this->data['userCitiesArray'] = "";
        $this->data['wheaterData'] = [];
        $this->data['addSvg'] = false;
        $this->data['error'] = false;
        $this->data['warning'] = false;
        
        $cookie = new Cookie();
        $token = $cookie->name('wheaterAppUserToken')->select();
        $user = new User();
        $result = $user->row($this->userRow)
            ->where(['userToken' => $token])->limit(1)->select();
        if (count($result)) {
            $this->checkUser($result, $user, $searchedCity);
        } else {
            $this->addNewUser();
        }
        $this->render('site/home', $this->data);
    }
    
    private function addNewUser()
    {
        $token = Tools::createSalt();
        $cookie = new Cookie();
        $cookie->name('wheaterAppUserToken')->content($token)->expire("1 year")->add();
        $user = new User();
        $user->insert(
            [
                'userCity'  => "",
                'userToken' => $token,
                'userActiveCity' => ""
            ]
        );
        $this->data['warning'] = true;
        $this->data['errorMessage'] = 'search a city first';
    }

    private function wheaterApi($location)
    {
        $params = array(
            'key' => API_KEY,
            'q' => $location,
            'days' => '7',
            'aqi'=> 'yes',
            'alerts' => 'no'
        );
        $response = Tools::manageCurl($params, [], "https://api.weatherapi.com/v1/forecast.json");
        if (array_key_exists('error', $response)) {
            $this->data['error'] = true;
            $this->data['errorMessage'] = $response['error']['message'];
        } else {
            $wheather = new Wheater();
            $wheather->wheaterCurrent($response['current']);
            $wheather->wheaterLocation($response['location']);
            $wheather->wheaterForecast($response['forecast']);
            $this->data['wheaterData'] = $wheather->joinResults();
        }
    }

    private function checkUser($result, $user, $searchedCity)
    {
        $this->data['userResult'] = $result;
        $this->data['userCitiesArray'] = $user->manageUserCities($result['userId']);
        $activeCity = $result['userActiveCity'];
        $this->trashOrAdd($this->data['userCitiesArray'], $searchedCity);
        if ($activeCity != "" || $searchedCity != "") {
            $location = $activeCity;
            if ($searchedCity != "") {
                $location = $searchedCity;
                $this->data['userResult']['userActiveCity'] = $location;
            }
            $this->wheaterApi($location);
        } else {
            $this->data['warning'] = true;
            $this->data['errorMessage'] = 'search a city first';
        }
    }

    private function trashOrAdd($cityArray, $searchedCity)
    {   
        if (! array_key_exists($searchedCity, $cityArray) && $searchedCity != "") {
            $this->data['addSvg'] = true;
        }
    }
}