<?php
namespace buycraft\util;

class PackageCommand{
    private $data;
    public function __construct(array $data){
        $this->data = $data;
    }
    public function getCommandID(){
        return $this->data["id"];
    }
    public function getUser(){
        return $this->data["ign"];
    }
    public function getCommand(){
        return $this->data["command"];
    }
    public function getDelay(){
        return $this->data["delay"];
    }
    public function getRequiredInventorySlots(){
        return $this->data["requireInventorySlot"];
    }
    public function getReplacedCommand(){
        return str_replace("{name}", $this->getUser(), $this->getCommand());
    }
    public function requiresInventorySlots(){
        return $this->data["requireInventorySlot"] > 0;
    }
}