<?php
namespace buycraft\commands;

use buycraft\BuyCraft;
use buycraft\task\VisitLinkTask;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginIdentifiableCommand;
use pocketmine\Player;

class BuyCommand extends Command implements PluginIdentifiableCommand{
    private $main;
    public function __construct(BuyCraft $main){
        parent::__construct($main->getConfig()->get('buyCommand'), "Buy command!", "/buy", []);
        $this->main = $main;
    }
    public function execute(CommandSender $sender, $label, array $args){
        if(!$this->getPlugin()->getConfig()->get('disableBuyCommand')){
            $pageToView = 0;
            $categoryToView = false;
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
                        $package = $this->getPlugin()->getPackageManager()->getPackage($args[0]);
                        if($package !== false){
                            if($this->getPlugin()->getConfig()->get('directPay')){
                                $link = $this->getPlugin()->getAuthPayloadSetting('serverStore') . "/checkout/packages?popup=true&action=add&direct=true&package=" . $package->getId() . "&ign=" . $sender->getName();
                            }
                            else{
                                $link = $this->getPlugin()->getAuthPayloadSetting('serverStore') . "/checkout/packages?action=add&package=" . $package->getId() . "&ign=" . $sender->getName();
                            }
                            $linkTask = new VisitLinkTask($this->getPlugin(), ['url' => $link], ($sender instanceof Player ? $sender->getName() : false));
                            $linkTask->call();
                        }
                        else{
                            $sender->sendMessage($this->getPlugin()->getConfig()->get('packageNotFound'));
                        }
                        return true;
                    }
                    else{
                        $sender->sendMessage($this->getPlugin()->getConfig()->get('invalidBuyCommand'));
                        return true;
                    }
                }
            }
            if(is_numeric($pageToView) && is_numeric($categoryToView) || $categoryToView === false){
                $packages = $this->getPlugin()->getPackageManager()->getPage($pageToView, $categoryToView);
                if($packages !== false){
                    if(count($packages) > 0){
                        foreach($packages as $package){
                            $sender->sendMessage($this->getPlugin()->getConfig()->get('packageId') . ": " . $package->getNiceId());
                            $sender->sendMessage($this->getPlugin()->getConfig()->get('packageName') . ": " . $package->getName());
                            $sender->sendMessage($this->getPlugin()->getConfig()->get('packagePrice') . ": " . $package->getPrice() . ' ' . $this->getPlugin()->getAuthPayloadSetting('serverCurrency'));
                            $sender->sendMessage("--------");
                        }
                    }
                    else{
                        $sender->sendMessage($this->getPlugin()->getConfig()->get('pageNotFound'));
                    }
                }
                else{
                    $sender->sendMessage($this->getPlugin()->getConfig()->get('noPackagesForSale'));
                }
            }
            else{
                $sender->sendMessage($this->getPlugin()->getConfig()->get('invalidBuyCommand'));
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