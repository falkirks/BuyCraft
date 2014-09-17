<?php
namespace buycraft\util;

use pocketmine\block\Block;
use pocketmine\Player;

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
    public function getRequiredInventorySlots($p){
        if($p instanceof Player){
            return $this->data["requireInventorySlot"] - ($p->getInventory()->getSize() - count($p->getInventory()->getContents()));
        }
        else{
            return -1;
        }
    }
    public function getReplacedCommand(){
        return str_replace("{name}", $this->getUser(), $this->getCommand());
    }
    public function requiresInventorySlots(){
        return $this->data["requireInventorySlot"] > 0;
    }
    public function delayTick(){
        $this->data["delay"]--;
    }
    public function setDelay($delay){
        $this->data["delay"] = $delay;
    }
}