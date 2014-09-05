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
        $data["onlineMode"] = true; //Not supported?
        $data["playersMax"] = $main->getServer()->getMaxPlayers();
        $data["version"] = Actions::PLUGIN_VERSION;
        $this->setData($data);
    }
    public function onRun(){
        $this->output = $this->send();
    }
    public function onOutput(BuyCraft $main, Player $player = null){
        //Send output back to plugin
    }
}