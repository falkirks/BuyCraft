<?php
namespace buycraft\task;

use buycraft\api\ApiTask;

class PendingPlayerCheckerTask extends ApiTask{
    public function onRun($tick){

    }
    public function call(){
        if(isset($this->getData()["manual"])){
           $this->onRun(0);
        }
        else{
            $this->getScheduler()->scheduleRepeatingTask($this, $this->getData()["interval"]);
        }
    }
}