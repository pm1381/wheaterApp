<?php

namespace App\Core;

class Controller {
    public $data = [];

    public function render($path, $data = [])
    {
        $this->data = json_decode(json_encode($data, JSON_UNESCAPED_UNICODE), true);
        $filePath = TEMPLATE . $path . '.php';
        if (file_exists($filePath)) {
            include_once $filePath;
        } else {
            System::pageError(404, "class " . $filePath . ".php not found");
        }
    }

    public function readJson($data = [])
    {
        System::headerConfig();
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit();
    }

    public function getData()
    {
        return $this->data;
    }
}