<?php
namespace buycraft\task;


use buycraft\api\ApiAsyncTask;
use buycraft\BuyCraft;
use pocketmine\command\CommandSender;
use pocketmine\Player;

class ReportTask extends ApiAsyncTask{
    public function onConfig(BuyCraft $plugin){

    }
    public function onProcess(){

    }

    /**
     * @param BuyCraft $main
     * @param Player $p
     * @return mixed
     */
    public function onOutput(BuyCraft $main, CommandSender $p){
        // TODO: Implement onOutput() method.
    }

}