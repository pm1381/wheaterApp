<?php
namespace App\Model;

use App\Core\Tools;

class Form 
{

    public static function option($array, $selected)
    {
        $list = '';
        foreach ($array as $key => $value) {
            $get = '';
            if ($key == $selected) {
                $get = 'selected';
            }
            $list .= '<option class="optionData" data-name="' . $value['title'] . '" data-id="' . $value['id'] .'" value="' . $value['id'] . '" ' . $get . '>' . $value['title'] . '</option>';
        }
        return $list;
    }
}
?>