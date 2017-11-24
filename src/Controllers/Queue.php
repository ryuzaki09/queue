<?php
namespace src\Controllers;

use src\Models\QueueModel;
use src\Entities\QueueEntity;

class Queue extends BaseController 
{
    public function __construct()
    {
        parent::__construct();
    }

    public function GetAll()
    {
        try {
            $type = $this->request->Get("type");
            $queueEntity = new QueueEntity;

            if ($type) {
                $queueEntity->setQueueType($type);
            }

            $queueModel = new QueueModel($queueEntity);
            $this->response->setData($queueModel->getAll());
        } catch(\Exception $e) {
            $this->response->setData($e->getMessage());
        }

        $this->response->toJson();
	}

    public function Create()
    {
        try {
            $queueEntity = new QueueEntity;
            $queueEntity->setQueueType($this->request->Post("type"));
            $queueEntity->setFirstName($this->request->Post("firstname"));
            $queueEntity->setLastName($this->request->Post("lastname"));
            $queueEntity->setOrganization($this->request->Post("organization"));
            $queueEntity->setService($this->request->Post("service"));

            if ($queueEntity->getQueueType() == "citizen" && (!$queueEntity->getFirstName() || !$queueEntity->getLastName())) {
                throw new \Exception("There are missing data");
            }

            $queueModel = new QueueModel($queueEntity);
            $queueModel->createQueue();
            $this->response->setData("success");
        } catch(\Exception $e) {
            $this->response->setData($e->getMessage());
        }

        $this->response->toJson();
    }



}
