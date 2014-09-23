<?php
namespace buycraft\commands;

use buycraft\BuyCraft;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginIdentifiableCommand;

class BuyCommand extends Command implements PluginIdentifiableCommand{
    private $main;
    public function __construct(BuyCraft $main){
        parent::__construct($main->getConfig()->get('buyCommand'), "Buy command!", "/buy", []);
        $this->main = $main;
    }
    public function execute(CommandSender $sender, $label, array $args){
        if(!$this->getPlugin()->getConfig()->get('disableBuyCommand')){
            $pageToView = 0;
            $categoryToView = 0;
            if(count($args) > 0){
                if($args[0] == "page" && count($args) == 2 || count($args) == 3){
                    if(count($args) == 2){
                        $pageToView = $args[1];
                    }
                    else{
                        $categoryToView = $args[1];
                        $pageToView = $args[2];
                    }
                }
                else{
                    if(count($args) == 1 && is_numeric($args[0])){
                        //TODO show package
                        return true;
                    }
                    else{
                        $sender->sendMessage($this->getPlugin()->getConfig()->get('invalidBuyCommand'));
                        return true;
                    }
                }
            }
            if(is_numeric($pageToView) && is_numeric($categoryToView)){
                //TODO show category
            }
            else{
                $sender->sendMessage($this->getPlugin()->getConfig()->get('invalidBuyCommand'));
                return true;
            }
        }
        else{
            $sender->sendMessage("Buy command is disabled on this server.");
            return true;
        }
    }
    public function getPlugin(){
        return $this->main;
    }
    public function updateCommand($name){
        $arr = $this->getAliases();
        if(!in_array($name, $arr)){
            $arr[] = $name;
            $this->setAliases($arr);
            return true;
        }
        else{
            return false;
        }
    }
    public function getMainAlias(){
        return (count($this->getAliases()) > 0 ? end($this->getAliases()) : $this->getName());
    }
}