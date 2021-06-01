<?php

namespace Classes\Tools;

Class Routes
{

    public static function getMethod(){
        return $_SERVER['REQUEST_METHOD'];
    }   

    public static function getUrls(){
        $ursl = explode( "/" ,$_SERVER['REQUEST_URI']);
        array_shift($ursl);
        return $ursl;
    }

    public static function getAll(){
        return $_SERVER;
    }

}
