<?php
namespace buycraft\task;


use buycraft\api\Actions;
use buycraft\api\ApiAsyncTask;
use buycraft\BuyCraft;
use pocketmine\command\CommandSender;

class ReloadPackagesTask extends ApiAsyncTask{
    public function onConfig(BuyCraft $plugin){
        $data = $this->getData();
        $data['action'] = Actions::PACKAGES;
        $this->setData($data);
    }
    public function onProcess(){

    }
    public function onOutput(BuyCraft $main, CommandSender $sender){
        $out = $this->getOutput();
        if($out['code'] === 0){
            foreach($out['payload'] as $package){
                if($package !== null){
                    $main->getPackageManager()->addPackage((isset($package['category']) ? $package['category'] : 0), $package['id'], $package['guiItemId'], $package['name'], $package['shortDescription'], $package['price']);
                }
            }
            $main->getPackageManager()->cleanCategories();
            $sender->sendMessage("Loaded " . count($out["payload"]) . " (approx) packages into cache.");
        }
        else{
            $sender->sendMessage("An error occurred during package reload.");
        }
    }
}