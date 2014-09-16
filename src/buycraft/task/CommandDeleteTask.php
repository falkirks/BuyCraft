<?php
namespace buycraft\task;


/*
 * Clears processed commands from buycraft.net.
 *
 * MUST be run on shutdown, to prevent repeated execution.
 */

use buycraft\api\Actions;
use buycraft\api\ApiTask;

class CommandDeleteTask extends ApiTask{
    private $deleteQueue = [];
    public function onRun($tick){
        if(count($this->deleteQueue) > 0){
            $this->data["commands"] = json_encode($this->deleteQueue);
            $this->send();
            $this->deleteQueue = [];
        }
    }
    public function call(){
        $this->data["action"] = Actions::COMMANDS;
        $this->data["do"] = Actions::DO_REMOVE;
        $this->getScheduler()->scheduleRepeatingTask($this, 20*20);
    }
    public function isQueued($cid){
        return in_array($cid, $this->deleteQueue);
    }
    public function deleteCommand($cid){
        if(!$this->isQueued($cid)){
            $this->deleteQueue[] = $cid;
        }
    }
}