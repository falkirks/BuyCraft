<?php
namespace buycraft\task;


use buycraft\api\Actions;
use buycraft\api\ApiAsyncTask;
use buycraft\BuyCraft;
use pocketmine\command\CommandSender;

class VisitLinkTask extends ApiAsyncTask{
    public function onConfig(BuyCraft $plugin){
        $data = $this->getData();
        $data['action'] = Actions::URL;
        $this->setData($data);
    }
    public function onRun(){
        $this->send();
    }
    public function onOutput(BuyCraft $main, CommandSender $sender){
        $out = $this->getData();
        if($out !== null && $out !== false){
            if(isset($out['url']) && $out['url'] !== null){
                $sender->sendMessage($main->getConfig()->get('pleaseVisit') . ": " . $out['url']);
            }
            else{
                $sender->sendMessage($out['errormessage']);
            }
        }
        else{
            $sender->sendMessage("HTTP request error during url shortening.");
        }
    }
}