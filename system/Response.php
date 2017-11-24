<?php
namespace System;

class Response
{
    const SUCCESS_CODE = 200;
    const FAILURE_CODE = 404;

    private $resultdata = array();

    public function setData($data)
    {
        $this->resultdata = $data;
    }

    public function toJson()
    {
        $result = array("Status" => self::SUCCESS_CODE, "Data" => $this->resultdata);
        echo json_encode($result);
    }

}
