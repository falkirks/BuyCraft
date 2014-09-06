<?php
namespace buycraft\task;

use buycraft\api\ApiAsyncTask;
use buycraft\BuyCraft;
use pocketmine\Player;
/*
 * Like PendingPlayerChecker but run manually.
 */
class ManualPlayerCheckTask extends ApiAsyncTask{
    public function onConfig(BuyCraft $main){

    }
    public function onRun(){

    }
    public function onOutput(BuyCraft $main, Player $player = null){

    }
}