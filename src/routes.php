<?php

return  [

    "get" => [
        "/admin/index" => "admin@index",
        "/queue?(:any)" => "Queue@GetAll",
        "/queue" => "Queue@GetAll",

    ],
    "post" => [
        "/admin/login" => "Home@Login",
        "/queue" => "Queue@Create",
    ]


];
