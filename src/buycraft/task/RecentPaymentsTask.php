<?php
namespace buycraft\task;


use buycraft\api\Actions;
use buycraft\api\ApiAsyncTask;
use buycraft\BuyCraft;
use pocketmine\Player;

class RecentPaymentsTask extends ApiAsyncTask{
    public function onConfig(BuyCraft $plugin){
        $data = $this->getData();
        $data["action"] = Actions::PAYMENTS;
        if(!isset($data["limit"])) $data["limit"] = 10;
        $this->setData($data);
    }
    public function onRun(){
        $this->send();
    }
    public function onOutput(BuyCraft $main, Player $player = null){
        $data = $this->getData();
        $out = $this->getOutput();
        if(isset($data["ign"])){
            $this->sendMessage("Displaying recent payments from the user " . $data["ign"] . ":", $player, $main);
        }
        else{
            $this->sendMessage("Displaying recent payments over all users: ", $player, $main);
        }
        if($out["payload"] !== null && count($out["payload"]) > 0){
            foreach($this->getOutput()["payload"] as $entry){
                $this->sendMessage("[" . $entry["humanTime"] . "] " . $entry["ign"] . " (" . $entry["price"] . " " . $entry["currency"] . ")", $player, $main);
            }
        }
        else{
            $this->sendMessage("No recent payments to display.", $player, $main);
        }
    }
    public function sendMessage($str, Player $player = null, BuyCraft $main){
        if($player instanceof Player){
            $player->sendMessage($str);
        }
        else{
            $main->getLogger()->info($str);
        }
    }
}