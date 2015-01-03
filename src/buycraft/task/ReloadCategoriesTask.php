<?php
namespace buycraft\task;


use buycraft\api\Actions;
use buycraft\api\ApiAsyncTask;
use buycraft\BuyCraft;
use pocketmine\command\CommandSender;
use pocketmine\Player;

class ReloadCategoriesTask extends ApiAsyncTask{
    public function onConfig(BuyCraft $plugin){
        $data = $this->getData();
        $data['action'] = Actions::CATEGORIES;
        $this->setData($data);
    }
    public function onProcess(){

    }
    public function onOutput(BuyCraft $main, CommandSender $sender){
        $out = $this->getOutput();
        if($out['code'] === 0){
            $main->getPackageManager()->reset();
            foreach($out['payload'] as $category){
                $main->getPackageManager()->addCategory((isset($category['id']) ? $category['id'] : 0), $category['name'], $category['shortDescription'], $category['guiItemId']);
            }
            $sender->sendMessage("Loaded " . count($out['payload']) . " categories.");
            $fetch = new ReloadPackagesTask($main, [], ($sender instanceof Player ? $sender : false));
            $fetch->call();
        }
        else{
            $sender->sendMessage("An error occurred during category reload.");
        }
    }
}