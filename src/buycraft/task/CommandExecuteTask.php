<?php
namespace buycraft\task;


/*
 * Thread used for polling BuyCraft API
 */

use buycraft\api\ApiTask;

class CommandExecuteTask extends ApiTask{
    public function onRun($tick){

    }
    public function call(){
        $this->getScheduler()->scheduleRepeatingTask($this, 5);
    }
}