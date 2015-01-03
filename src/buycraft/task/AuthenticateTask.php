<?php
namespace buycraft\task;

use buycraft\api\Actions;
use buycraft\api\ApiAsyncTask;
use buycraft\BuyCraft;
use pocketmine\command\CommandSender;

class AuthenticateTask extends ApiAsyncTask{
    public function onConfig(BuyCraft $main){
        $data = $this->getData();
        $data["action"] = Actions::AUTHENTICATE;
        $data["serverPort"] = $main->getServer()->getPort();
        $data["onlineMode"] = false; //Not supported?
        $data["playersMax"] = $main->getServer()->getMaxPlayers();
        $data["version"] = Actions::PLUGIN_VERSION;
        $this->setData($data);
    }
    public function onProcess(){
    }
    public function onOutput(BuyCraft $main, CommandSender $sender){
        $out = $this->getOutput(); //Limit unserialize() calls
        if($out["code"] === 0){
            $sender->sendMessage("BuyCraft authentication complete.");
            $main->setAuthenticated();
            $main->setAuthPayload($out["payload"]);
            $fetch = new ReloadCategoriesTask($main);
            $fetch->call();
            $main->getPendingPlayerCheckerTask()->setUpdateInterval($out["payload"]["updateUsernameInterval"]);
        }
        elseif($out["code"] === 101){
            $sender->sendMessage("The specified Secret key could not be found.");
            $main->setUnAuthenticated();
        }
        else{
            $sender->sendMessage("An error occured during authentication.");
            $main->setUnAuthenticated();
        }
    }
}