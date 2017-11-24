<?php
namespace src\Entities;

use \System\Request;

class QueueEntity
{
    private $firstName;
    private $lastName;
    private $queueType;
    private $organization;
    private $service;
    private $acceptedQTypes = array("citizen", "anonymous");
    private $acceptedServices = array("council tax", "benetifs", "rent");

    public function setFirstName($firstname)
    {
        $this->firstName = $firstname;
    }

    public function setLastName($lastname)
    {
        $this->lastName = $lastname;
    }

    public function setQueueType($type)
    {
        if (in_array(strtolower($type), $this->acceptedQTypes)) {
            $this->queueType = $type;
            return;
        }

        throw new \Exception("This queue type is not accepted");
    }

    public function setService($service)
    {
        if (in_array(strtolower($service), $this->acceptedServices)) {
            $this->service = $service;
            return;
        }

        throw new \Exception("This service is not accepted");
    }

    public function setOrganization($org)
    {
        $this->organization = $org;
    }

    public function getFirstName()
    {
        return $this->firstName; 
    }

    public function getLastName()
    {
        return $this->lastName;
    }

    public function getQueueType()
    {
        return $this->queueType;
    }

    public function getOrganization()
    {
        return $this->organization;
    }

    public function getService()
    {
        return $this->service;
    }


}
