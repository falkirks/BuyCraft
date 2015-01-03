<?php
namespace buycraft\task;

use buycraft\api\Actions;
use buycraft\api\ApiAsyncTask;
use buycraft\BuyCraft;
use buycraft\util\PackageCommand;
use pocketmine\command\CommandSender;

/*
 * Fetches commands from buycraft and adds them to the execution queue.
 */
class CommandFetchTask extends ApiAsyncTask{
    public function onConfig(BuyCraft $plugin){
        $data = $this->getData();
        $data["action"] = Actions::COMMANDS;
        $data["do"] = Actions::DO_LOOKUP;
        if(is_array($data["users"])){
            $data["users"] = json_encode($data["users"]); //I think that's right
        }
        $data["offlineCommandLimit"] = $plugin->getConfig()->get('commandThrottleCount');
        $this->setData($data);
    }
    public function onProcess(){

    }
    public function onOutput(BuyCraft $main, CommandSender $sender){
        $out = $this->getOutput();
        if(isset($out["payload"]["commands"])){
            foreach($this->getOutput()["payload"]["commands"] as $cmd){
                $p = ($cmd["requireOnline"] ? $main->getServer()->getPlayer($cmd["ign"]) : null);
                if(!$cmd["requireOnline"] || $p !== null){
                    $main->getCommandExecuteTask()->queueCommand(new PackageCommand($cmd));
                }
            }
        }
    }
}