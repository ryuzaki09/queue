<?php
namespace src\Controllers;

class Session 
{

    const PREFIX = "mvcv2_";
    const FLASH_PREFIX = "flashmvc_";


    public static function set($key, $value)
    {
        $_SESSION[self::PREFIX.$key] = $value;
    }

    public static function get($key)
    {
        if (array_key_exists($_SESSION[self::PREFIX.$key], $_SESSION)) {
            return $_SESSION[self::PREFIX.$key];
        }
    }

    public static function flashValue($key, $value)
    {
        $_SESSION[self::FLASH_PREFIX.$key] = $value;
    }

    public static function getFlashValue($key)
    {
        if (array_key_exists($_SESSION[self::FLASH_PREFIX.$key], $_SESSION)) {
            $flash = $_SESSION[self::FLASH_PREFIX.$key];
            unset($_SESSION[self::FLASH_PREFIX.$key]);
            return $flash;
        }

    }


}
