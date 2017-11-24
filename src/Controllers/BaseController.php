<?php

namespace src\Controllers;

use System\Core;
use System\Response;
use System\Request;

class BaseController extends Core 
{
    protected $response;

    public function __construct()
    {
        $this->response = new Response;    
        $this->request = new Request;
    }


}
