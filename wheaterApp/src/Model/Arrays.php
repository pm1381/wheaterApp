<?php
namespace App\Model;

class Arrays 
{

    protected static $nights = [];

    public static function nights()
    {
        if (empty($nights)) {
            $theater = new Theater();
            $result = $theater
                ->row(['theaterId', 'theaterAddress', 'theaterCapacity', 'theaterName', 'theaterShow'])
                ->where([])
                ->select();
            if (count($result)) {
                foreach ($result as $key => $value) {
                    $nights[$value['theaterId']] = [
                        'id' => $value['theaterId'],
                        'address' => $value['theaterAddress'],
                        'capacity' => $value['theaterCapacity'],
                        'title' => $value['theaterName'],
                        'show' => $value['theaterShow']
                    ];
                }
            }    
        }
        return $nights;
    }
}
?>