<?php
namespace buycraft\task;


use buycraft\api\Actions;
use buycraft\api\ApiAsyncTask;
use buycraft\BuyCraft;
use pocketmine\command\CommandSender;

/*
 * This is an Async task which allows PendingPlayerCheckerTask to
 * send requests outside the main thread.
 */
class PendingUsersTask extends ApiAsyncTask{
    public function onConfig(BuyCraft $plugin){
        $data = $this->getData();
        $data["action"] = Actions::PENDING_PLAYERS;
        $this->setData($data);
    }
    public function onRun(){
        $this->send();
    }
    public function onOutput(BuyCraft $main, CommandSender $sender){
        $res = $this->getOutput();
        if($res !== false && is_array($res["payload"]["pendingPlayers"])){
            $playersToFetch = [];
            foreach($res["payload"]["pendingPlayers"] as $player){
                $p = $main->getServer()->getPlayerExact($player);
                if($p !== null && $p->isOnline()){
                    $playersToFetch[] = $p->getName();
                }
                else{
                    $main->getPendingPlayerCheckerTask()->addPendingPlayer($player);
                }
            }
            if($res["payload"]["offlineCommands"] || count($playersToFetch) > 0){
                $fetch = new CommandFetchTask($main, ["users" => $playersToFetch, "offlineCommands" => $res["payload"]["offlineCommands"]]);
                $fetch->call();
            }
        }
    }
}