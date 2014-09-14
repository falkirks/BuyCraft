<?php
namespace buycraft\task;


/*
 * Executes commands from a command queue and marks them for deletion.
 */

use buycraft\api\ApiTask;
use buycraft\util\PackageCommand;

class CommandExecuteTask extends ApiTask{
    private $commandQueue = [];
    public function onRun($tick){

    }
    public function call(){
        $this->getScheduler()->scheduleRepeatingTask($this, 20);
    }
    public function queueCommand(PackageCommand $command){
        if(!$this->getOwner()->getCommandDeleteTask()->isQueued($command->getCommandID()) && !in_array($command, $this->commandQueue)){
            $this->commandQueue[] = $command;
            return true;
        }
        else{
            return false;
        }
    }
}