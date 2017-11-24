<?php
namespace src\Models;

use System\mvc\Model;
use src\Entities\QueueEntity;

class QueueModel extends Model
{
    private $entity;
    protected $table = "queue";

    public function __construct(QueueEntity $entity)
    {
        parent::__construct();
        $this->entity = $entity;
    }

    public function getAll()
    {
        $type = "";

        if ($this->entity->getQueueType()) {
            $type = " AND type='".$this->entity->getQueueType()."' "; 
        }

        $this->query("SELECT * FROM ".$this->table." WHERE queuedDate >= '".date("Y-m-d")." 00:00:00'".$type);
        $this->execute();
        return $this->fetchAll();
    }

    public function createQueue()
    {
        $data = array(
            "firstName" => $this->entity->getFirstName(),
            "lastName" => $this->entity->getLastName(),
            "organization" => $this->entity->getOrganization(),
            "type" => $this->entity->getQueueType(),
            "service" => $this->entity->getService()
        );
        // error_log("data: ".var_export($data, true));
        $this->set($data);
        $this->insertData();
    }

}
