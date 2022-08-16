<?php
namespace App\Model;

use App\Core\Tools;

class Cookie 
{
    protected $name;
    protected $content;
    protected $expire;

    public function name($value)
    {
        $this->name = $value;
        return $this;
    }

    public function content($value)
    {
        $this->content = $value;
        return $this;
    }

    public function expire($value)
    {
        $this->expire = $value;
        return $this;
    }

    public function add()
    {
        $_COOKIE[$this->name] = $this->content;
        setcookie($this->name, $this->content, strtotime($this->expire), 'wheaterApp/');
        return $this;
    }

    public function remove()
    {
        setcookie($this->name, "", time()-3600, '/');
    }

    public function select()
    {
        if (isset($_COOKIE[$this->name])) {
            return $_COOKIE[$this->name];
        } else {
            return "";
        }
    }
}
?>