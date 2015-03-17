<?php
namespace buycraft\task;


/*
 * Clears processed commands from buycraft.net.
 *
 * MUST be run on shutdown, to prevent repeated execution.
 */

use buycraft\api\Actions;
use buycraft\api\ApiTask;
use buycraft\util\DebugUtils;

class CommandDeleteTask extends ApiTask{
    private $deleteQueue = [];
    public function onRun($tick){
        DebugUtils::taskCalled($this);
        if(count($this->deleteQueue) > 0){
            $this->data["commands"] = json_encode($this->deleteQueue);
            $this->send();
            $this->deleteQueue = [];
        }
    }
    public function call(){
        DebugUtils::taskRegistered($this);
        $this->data["action"] = Actions::COMMANDS;
        $this->data["do"] = Actions::DO_REMOVE;
        $this->getScheduler()->scheduleRepeatingTask($this, 20);
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