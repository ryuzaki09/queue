<?php
namespace src\Controllers;

class View {

    const PARENT_FOLDER = "src/Views/";

    public static function render($view, $data=array())
    {
        
        if (file_exists(self::PARENT_FOLDER.$view.".php")) {
            if (!empty($data)) {
                extract($data);
            }
            require_once self::PARENT_FOLDER.$view.".php";

        }

    }


}
