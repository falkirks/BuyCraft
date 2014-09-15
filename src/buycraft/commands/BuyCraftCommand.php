<?php
namespace buycraft\commands;

use buycraft\BuyCraft;
use buycraft\task\AuthenticateTask;
use buycraft\task\RecentPaymentsTask;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginIdentifiableCommand;
use pocketmine\Player;

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
                            $sender->sendMessage("Scheduled authentication. If you don't receive a success message an error will be available on the console.");
                            $auth = new AuthenticateTask($this->getPlugin(), [], ($sender instanceof Player ? $sender : false));
                            $auth->call();
                            return true;
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
                            if(isset($args[1])){
                                $this->getPlugin()->getConfig()->set('secret', $args[1]);
                                $this->getPlugin()->getConfig()->save();
                                $sender->sendMessage("Scheduled authentication. If you don't receive a success message an error will be available on the console.");
                                $auth = new AuthenticateTask($this->getPlugin(), [], ($sender instanceof Player ? $sender : false));
                                $auth->call();
                                return true;
                            }
                            else{
                                $sender->sendMessage("You must specify the secret to set.");
                                return true;
                            }
                        }
                        else{
                            $sender->sendMessage("Setting secret in game has been disabled.");
                            return true;
                        }
                        break;
                    case 'payments':
                        $recentPaymentTask = new RecentPaymentsTask($this->getPlugin(), (isset($args[1]) ? ["ign" => $args[1]] : []), ($sender instanceof Player ? $sender : false));
                        $recentPaymentTask->call();
                        return true;
                        break;
                    case 'report':
                        $sender->sendMessage("BuyCraft for PocketMine does not support report generation. If the plugin crashes just alert me on GitHub or the PocketMine forums with a link to the crash report.");
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