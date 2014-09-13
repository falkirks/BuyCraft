<?php
namespace buycraft\task;

use buycraft\api\ApiTask;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;

/*
 * Looks for players with commands pending. (I think?)
 */
class PendingPlayerCheckerTask extends ApiTask implements Listener{
    private $pendingPlayers = [];
    public function onRun($tick){

    }
    public function call(){
        $this->getScheduler()->scheduleRepeatingTask($this, 20);
    }
    public function onPlayerJoin(PlayerJoinEvent $event){

    }
    public function resetPendingPlayers(){
        $this->pendingPlayers = [];
    }
}