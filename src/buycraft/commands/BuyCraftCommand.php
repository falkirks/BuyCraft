<?php
namespace buycraft\commands;

use buycraft\api\Actions;
use buycraft\BuyCraft;
use buycraft\task\AuthenticateTask;
use buycraft\task\RecentPaymentsTask;
use buycraft\task\ReloadCategoriesTask;
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
                            $sender->sendMessage("Scheduled authentication and package reload. If you don't get a success message try again.");
                            $auth = new AuthenticateTask($this->getPlugin(), [], ($sender instanceof Player ? $sender : false));
                            $auth->call();
                        }
                        else{
                            $sender->sendMessage("Not authenticated with BuyCraft.net.");
                        }
                        break;
                    case 'forcecheck':
                        if($this->getPlugin()->isAuthenticated()){
                            $this->getPlugin()->getPendingPlayerCheckerTask()->onRun(0, true);
                            $sender->sendMessage("Executed pending player check.");
                        }
                        else{
                            $sender->sendMessage("Not authenticated with BuyCraft.net.");
                        }
                        break;
                    case 'secret':
                        if(!$this->getPlugin()->getConfig()->get('disable-secret-command')){
                            if(isset($args[1])){
                                $this->getPlugin()->getConfig()->set('secret', $args[1]);
                                $this->getPlugin()->getConfig()->save();
                                $sender->sendMessage("Scheduled authentication. If you don't receive a success message an error will be available on the console.");
                                $auth = new AuthenticateTask($this->getPlugin(), [], ($sender instanceof Player ? $sender->getName() : false));
                                $this->getPlugin()->getServer()->getScheduler()->scheduleAsyncTask($auth);
                            }
                            else{
                                $sender->sendMessage("You must specify the secret to set.");
                            }
                        }
                        else{
                            $sender->sendMessage("Setting secret in game has been disabled.");
                        }
                        break;
                    case 'payments':
                        $recentPaymentTask = new RecentPaymentsTask($this->getPlugin(), (isset($args[1]) ? ["ign" => $args[1]] : []), ($sender instanceof Player ? $sender->getName() : false));
                        $recentPaymentTask->call();
                        break;
                    case 'report':
                        $sender->sendMessage("BuyCraft for PocketMine does not support report generation. If the plugin crashes just alert me on GitHub or the PocketMine forums with a link to the crash report.");
                        break;
                    default:
                        $sender->sendMessage($this->getUsage());
                        break;
                }
            }
            else{
                $sender->sendMessage("You don't has permission to use that command.");
            }
        }
        else{
            if($this->getPlugin()->isAuthenticated()){
                $buyCommand = "/" . $this->getPlugin()->getBuyCommand()->getMainAlias() . " ";
                $sender->sendMessage($buyCommand . ": View packages available for sale.");
                $sender->sendMessage($buyCommand . "page <ID> : Navigate through package pages");
                $sender->sendMessage($buyCommand . "<ID> : Purchase a specific package");
                if($sender->hasPermission('buycraft.admin')){
                    $sender->sendMessage("/buycraft reload : Reload the package cache");
                    $sender->sendMessage("/buycraft forcecheck : Check for pending commands");
                    if(!$this->getPlugin()->getConfig()->get('disable-secret-command')){
                        $sender->sendMessage("/buycraft secret <key> : Set the secret");
                    }
                    $sender->sendMessage("/buycraft payments [ign] : Get recent payments");
                    $sender->sendMessage("/buycraft report :  Gives instructions to report errors.");
                }
                $sender->sendMessage("Server ID: " . $this->getPlugin()->getAuthPayloadSetting('serverId'));
                $sender->sendMessage("Server URL: " . $this->getPlugin()->getAuthPayloadSetting('serverStore'));
                $sender->sendMessage("Version: " . $this->getPlugin()->getServer()->getPluginManager()->getPlugin('BuyCraft')->getDescription()->getVersion() . " implementing BuyCraft Bukkit " . Actions::PLUGIN_VERSION);
                $sender->sendMessage("Website: http://buycraft.net");

            }
            else{
                $sender->sendMessage("BuyCraft is not linked to buycraft.net and can't process purchases for you.");
                $sender->sendMessage("If you are the owner of this server you need to enter your secret key in the config.yml or using /buycraft secret <key>.");
            }
        }
    }
    public function getPlugin(){
        return $this->main;
    }
}