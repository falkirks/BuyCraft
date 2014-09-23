<?php
namespace buycraft\task;


use buycraft\api\Actions;
use buycraft\api\ApiAsyncTask;
use buycraft\BuyCraft;
use pocketmine\command\CommandSender;

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
    public function onOutput(BuyCraft $main, CommandSender $sender){
        $data = $this->getData();
        $out = $this->getOutput();
        if(isset($data["ign"])){
            $sender->sendMessage("Displaying recent payments from the user " . $data["ign"] . ":");
        }
        else{
            $sender->sendMessage("Displaying recent payments over all users: ");
        }
        if($out["payload"] !== null && count($out["payload"]) > 0){
            foreach($this->getOutput()["payload"] as $entry){
                $sender->sendMessage("[" . $entry["humanTime"] . "] " . $entry["ign"] . " (" . $entry["price"] . " " . $entry["currency"] . ")");
            }
        }
        else{
            $sender->sendMessage("No recent payments to display.");
        }
    }
}