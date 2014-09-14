<?php
namespace buycraft\commands;

use buycraft\BuyCraft;
use buycraft\task\AuthenticateTask;
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
            if($sender->hasPermission('buycraft.admin')){
                switch($args[0]){
                    case 'reload':
                        if($this->getPlugin()->isAuthenticated()){
                            $auth = new AuthenticateTask($this->getPlugin());
                            $auth->call();
                        }
                        else{
                            $sender->sendMessage("Not authenticated with BuyCraft.net.");
                            return true;
                        }
                        break;
                    case 'forcecheck':
                        if($this->getPlugin()->isAuthenticated()){
                            $this->getPlugin()->getPendingPlayerCheckerTask()->onRun(0, true);
                            $sender->sendMessage("Executed pending player check.");
                        }
                        else{
                            $sender->sendMessage("Not authenticated with BuyCraft.net.");
                            return true;
                        }
                        break;
                    case 'secret':
                        if(!$this->getPlugin()->getConfig()->get('disable-secret-command')){
                            //TODO set key and call auth task
                        }
                        else{
                            $sender->sendMessage("Setting secret in game has been disabled.");
                            return true;
                        }
                        break;
                    case 'payments':

                        break;
                    case 'report':
                        $sender->sendMessage("BuyCraft for PocketMine does not support report generation.");
                        return true;
                        break;
                }
            }
            else{
                $sender->sendMessage("You don't has permission to use that command.");
                return true;
            }
        }
        else{

        }
    }
    public function getPlugin(){
        return $this->main;
    }
}