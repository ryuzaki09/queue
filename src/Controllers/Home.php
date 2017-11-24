<?php

namespace src\Controllers;

use src\Models\User;

class Home extends BaseController 
{
    public function __construct()
    {
        parent::__construct();
    }

    public function Index()
    {
        echo "Welcome";
    }



}
