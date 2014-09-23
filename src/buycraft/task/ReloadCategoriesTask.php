<?php
namespace buycraft\task;


use buycraft\api\Actions;
use buycraft\api\ApiAsyncTask;
use buycraft\BuyCraft;
use pocketmine\command\CommandSender;

class ReloadCategoriesTask extends ApiAsyncTask{
    public function onConfig(BuyCraft $plugin){
        $data = $this->getData();
        $data['action'] = Actions::CATEGORIES;
        $this->setData($data);
    }
    public function onRun(){
        $this->send();
    }
    public function onOutput(BuyCraft $main, CommandSender $sender){
        $out = $this->getOutput();
        if($out["code"] === 0){
            $main->getPackageManager()->reset();
            foreach($out["payload"] as $category){
                $main->getPackageManager()->addCategory((isset($category["id"]) ? $category["id"] : 0), $category["name"], $category["shortDescription"], $category["guiItemId"]);
            }
            $sender->sendMessage("Loaded categories.");
            $fetch = new ReloadPackagesTask($main, [], ($player !== null ? $player : false));
            $fetch->call();
        }
        else{
            $sender->sendMessage("An error occurred during category reload.");
        }
    }
}