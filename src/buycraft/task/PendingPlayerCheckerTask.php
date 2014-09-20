<?php
namespace buycraft\task;

use buycraft\api\Actions;
use buycraft\api\ApiTask;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\scheduler\PluginTask;
use pocketmine\scheduler\TaskHandler;

/*
 * Does NOT perform any API communication, uses another task.
 */
class PendingPlayerCheckerTask extends PluginTask implements Listener{
    private $pendingPlayers = [];
    /** @var  TaskHandler */
    private $handler;
    public function onRun($tick, $manual = false){
        if($this->getOwner()->getConfig()->get('commandChecker') || $manual){
            $task = new PendingUsersTask($this->getOwner());
            $task->call();
        }
    }
    public function call(){
        $this->getOwner()->getServer()->getPluginManager()->registerEvents($this, $this->getOwner());
        $this->handler = $this->getOwner()->getServer()->getScheduler()->scheduleRepeatingTask($this, 40);
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
    public function addPendingPlayer($name){
        $this->pendingPlayers[] = $name;
    }
    public function setUpdateInterval($interval){
        $this->handler->cancel();
        $this->handler = $this->getOwner()->getServer()->getScheduler()->scheduleRepeatingTask($this, $interval);
    }
}