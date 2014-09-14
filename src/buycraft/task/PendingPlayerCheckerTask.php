<?php
namespace buycraft\task;

use buycraft\api\ApiTask;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;

/*
 * Looks for players with commands pending
 */
class PendingPlayerCheckerTask extends ApiTask implements Listener{
    private $pendingPlayers = [];
    public function onRun($tick, $manual = false){
        if($this->getOwner()->getConfig()->get('commandChecker') || $manual){

        }
    }
    public function call(){
        $this->getOwner()->getServer()->getPluginManager()->registerEvents($this, $this->getOwner());
        $this->getScheduler()->scheduleRepeatingTask($this, 20);
    }
    public function onPlayerJoin(PlayerJoinEvent $event){
        if(isset($this->pendingPlayers[$event->getPlayer()->getName()])){
            $fetch = new CommandFetchTask($this->getOwner(), ["users" => [$event->getPlayer()->getName()], "offlineCommands" => false]);
            $fetch->call();
            unset($this->pendingPlayers[$event->getPlayer()->getName()]);
        }
    }
    public function resetPendingPlayers(){
        $this->pendingPlayers = [];
    }
}