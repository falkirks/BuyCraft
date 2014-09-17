<?php
namespace buycraft\task;

use buycraft\api\Actions;
use buycraft\api\ApiTask;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\scheduler\TaskHandler;

/*
 * Looks for players with commands pending
 */
class PendingPlayerCheckerTask extends ApiTask implements Listener{
    private $pendingPlayers = [];
    /** @var  TaskHandler */
    private $handler;
    public function onRun($tick, $manual = false){
        var_dump("I run.");
        if($this->getOwner()->getConfig()->get('commandChecker') || $manual){
            $this->data["action"] = Actions::PENDING_PLAYERS;
            $res = $this->send();
            if($res !== false && is_array($res["payload"]["pendingPlayers"])){
                $playersToFetch = [];
                foreach($res["payload"]["pendingPlayers"] as $player){
                    $p = $this->getOwner()->getServer()->getPlayerExact($player);
                    if($p !== null && $p->isOnline()){
                        $playersToFetch[] = $p->getName();
                    }
                    else{
                        $this->pendingPlayers[] = $player;
                    }
                }
                if($res["payload"]["offlineCommands"] || count($playersToFetch) > 0){
                    $fetch = new CommandFetchTask($this->getOwner(), ["users" => $playersToFetch, "offlineCommands" => $res["payload"]["offlineCommands"]]);
                    $fetch->call();
                }
            }
        }
    }
    public function call(){
        $this->getOwner()->getServer()->getPluginManager()->registerEvents($this, $this->getOwner());
        $this->handler = $this->getScheduler()->scheduleRepeatingTask($this, 40);
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
    public function setUpdateInterval($interval){
        $this->handler->cancel();
        $this->handler = $this->getScheduler()->scheduleRepeatingTask($this, $interval);
    }
}