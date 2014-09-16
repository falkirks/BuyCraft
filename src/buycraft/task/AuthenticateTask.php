<?php
namespace buycraft\task;

use buycraft\api\Actions;
use buycraft\api\ApiAsyncTask;
use buycraft\BuyCraft;
use pocketmine\Player;

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
    public function onRun(){
        $this->send();
    }
    public function onOutput(BuyCraft $main, Player $player = null){
        $out = $this->getOutput(); //Limit unserialize() calls
        if($out["code"] === 0){
            $main->getLogger()->info("Connected to BuyCraft!");
            if($player instanceof Player){
                $player->sendMessage("BuyCraft authentication complete.");
            }
            $main->setAuthenticated();
            $main->setAuthPayload($out["payload"]);
        }
        elseif($out["code"] === 101){
            $main->getLogger()->critical("The specified Secret key could not be found.");
            $main->setUnAuthenticated();
        }
        else{
            $main->getLogger()->critical("An error occured during authentication.");
            $main->setUnAuthenticated();
        }
    }
}