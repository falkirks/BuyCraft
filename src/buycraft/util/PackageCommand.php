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
        if($this->data["requireInventorySlot"] )
        if($p instanceof Player){
            $slots = 0;
            foreach($p->getInventory()->getContents() as $item){
                if($item->getID() === Block::AIR){
                    $slots++;
                    if($slots == $this->data["requireInventorySlot"]) return 0;
                }
            }
            return $this->data["requireInventorySlot"] - $slots;
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
}