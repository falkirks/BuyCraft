<?php
namespace buycraft\task;


/*
 * Checks buycraft.net for new commands.
 */

use buycraft\api\ApiTask;

class CommandExecuteTask extends ApiTask{
    public function onRun($tick){

    }
    public function call(){
        $this->getScheduler()->scheduleRepeatingTask($this, 10);
    }
}