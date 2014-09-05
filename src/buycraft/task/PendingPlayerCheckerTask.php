<?php
namespace buycraft\task;

use buycraft\api\ApiTask;

class PendingPlayerCheckerTask extends ApiTask{
    public function onRun($tick){

    }
    public function call(){
        $this->getScheduler()->scheduleRepeatingTask($this, 20);
    }
}