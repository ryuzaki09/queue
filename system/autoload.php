<?php


spl_autoload_register(function($class){
    if (file_exists(lcfirst(str_replace("\\", "/", $class)).".php")) {
        require_once lcfirst(str_replace("\\", "/", $class)).".php";
        return;
    }

});

