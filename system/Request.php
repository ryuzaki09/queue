<?php
namespace System;

class Request 
{
    public $request;

    public function __construct()
    {
        $this->request = new \stdClass();
        $this->request->method  = $_SERVER['REQUEST_METHOD']; 
        $this->request->uri     = $_SERVER['REQUEST_URI']; 
        $post = file_get_contents("php://input", false);
        parse_str($post, $this->request->body);
    }

    public function Post($var)
    {
        if (isset($this->request->body[$var])) {
            return filter_var($this->request->body[$var], FILTER_SANITIZE_STRING);
        }
    }

    public function Get($var)
    {
        if (isset($_GET[$var])) {
            return filter_input(INPUT_GET, $var, FILTER_SANITIZE_STRING);
        }
    }

}
