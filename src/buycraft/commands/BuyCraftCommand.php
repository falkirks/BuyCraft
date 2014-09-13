<?php
namespace buycraft\commands;

use buycraft\BuyCraft;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginIdentifiableCommand;

class BuyCraftCommand extends Command implements PluginIdentifiableCommand{
    private $main;
    public function __construct(BuyCraft $main){
        parent::__construct("buycraft", "Buycraft command!", "/<command> <reload/forcecheck/secret/payments <ign>>", ["bc"]);
        $this->main = $main;
    }
    public function execute(CommandSender $sender, $label, array $args){
        if(isset($args[0])){

        }
        else{

        }
    }
    public function getPlugin(){
        return $this->main;
    }
}