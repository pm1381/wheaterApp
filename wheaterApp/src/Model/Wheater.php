<?php

namespace App\Model;

class Wheater
{
    private $data;

    public function __construct()
    {
        $this->data = [];
    }

    public function wheaterCurrent($current)
    {
        $this->data['current'] = [
            'feelsLike' => $current['feelslike_c'],
            'humidity' => $current['humidity'],
            'isDay' => $current['is_day'],
            'currentTemp' => $current['temp_c'],
            'uv' => $current['uv'],
            'windSpeed' => $current['wind_kph'],
            'windDegree' => $current['wind_degree'],
            'windDir' => $current['wind_dir'],
            'cloud' => $current['cloud'],
            'status' => $current['condition']['text'],
            'iconStatus' => $current['condition']['icon']
        ];
    }

    public function wheaterLocation($location)
    {
        $this->data['location'] = [
            'region' => $location['region'],
            'city' => $location['name'],
            'country' => $location['country'],
            'time' => $location['localtime']
        ];
    }

    public function wheaterForecast($forecast)
    {
        foreach ($forecast['forecastday'] as $value) {
            $this->data['forecast'][] = [
                'date' => $value['date'],
                'moonrise' => $value['astro']['moonrise'],
                'moonset' => $value['astro']['moonset'],
                'sunrise' => $value['astro']['sunrise'],
                'sunset' => $value['astro']['sunset'],
                'humidity' => $value['day']['avghumidity'],
                'avgTemp' => $value['day']['avgtemp_c'],
                'rainChance' => $value['day']['daily_chance_of_rain'],
                'snowChance' => $value['day']['daily_chance_of_snow'],
                'maxTemp' => $value['day']['maxtemp_c'],
                'minTemp' => $value['day']['mintemp_c'],
                'windSpeed' => $value['day']['maxwind_kph'],
                'uv' => $value['day']['uv'],
                'status' => $value['day']['condition']['text'],
                'iconStatus' => $value['day']['condition']['icon']
            ];
            // the first element will be today wheater data
        }
    }

    public function joinResults()
    {
        return $this->data;
    }

}