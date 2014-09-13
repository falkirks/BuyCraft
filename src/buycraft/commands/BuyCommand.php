<?php
namespace buycraft\commands;

use buycraft\BuyCraft;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginIdentifiableCommand;

class BuyCommand extends Command implements PluginIdentifiableCommand{
    private $main;
    public function __construct(BuyCraft $main){
        parent::__construct("buycraft", "Buy command!", "/buy", []);
        $this->main = $main;
    }
    public function execute(CommandSender $sender, $label, array $args){

    }
    public function getPlugin(){
        return $this->main;
    }
}