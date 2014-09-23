<?php
namespace buycraft;

use buycraft\commands\BuyCommand;
use buycraft\commands\BuyCraftCommand;
use buycraft\packages\PackageManager;
use buycraft\task\AuthenticateTask;
use buycraft\task\CommandDeleteTask;
use buycraft\task\CommandExecuteTask;
use buycraft\task\PendingPlayerCheckerTask;
use buycraft\util\BuycraftCommandSender;
use pocketmine\plugin\PluginBase;

class BuyCraft extends PluginBase{
    private $isAuthenticated = false;
    /** @var  CommandExecuteTask */
    private $commandExecuteTask;
    /** @var  PendingPlayerCheckerTask */
    private $pendingPlayerCheckerTask;
    /** @var  CommandDeleteTask */
    private $commandDeleteTask;
    /** @var BuyCraftCommand */
    private $buycraftCommand;
    /** @var  BuyCommand */
    private $buyCommand;
    /** @var  BuycraftCommandSender */
    private $commandSender;
    /** @var  PackageManager */
    private $packageManager;
    /** @var array  */
    private $authPayload = [];
    public function onEnable(){
        $this->saveDefaultConfig();
        $this->saveResource("README.md");
        $this->getConfig(); //Fetch the config so no blocking later

        if($this->getConfig()->get('secret') !== ""){
            $auth = new AuthenticateTask($this);
            $auth->call();
        }
        else{
            $this->getLogger()->info("You still need to configure BuyCraft. Use /buycraft secret or the config.yml to set your secret.");
        }
        $this->commandSender = new BuycraftCommandSender;
        $this->commandExecuteTask = new CommandExecuteTask($this);
        $this->pendingPlayerCheckerTask = new PendingPlayerCheckerTask($this);
        $this->commandDeleteTask = new CommandDeleteTask($this);
        $this->commandExecuteTask->call();
        $this->pendingPlayerCheckerTask->call();
        $this->commandDeleteTask->call();

        $this->packageManager = new PackageManager($this);

        $this->buyCommand = new BuyCommand($this);
        $this->buycraftCommand = new BuyCraftCommand($this);

        $this->getServer()->getCommandMap()->register("buycraft", $this->buycraftCommand);
        $this->getServer()->getCommandMap()->register("buycraft", $this->buyCommand);
    }
    public function onDisable(){
        $this->commandDeleteTask->onRun(0);
    }
    /**
     * @return CommandDeleteTask
     */
    public function getCommandDeleteTask(){
        return $this->commandDeleteTask;
    }

    /**
     * @return CommandExecuteTask
     */
    public function getCommandExecuteTask(){
        return $this->commandExecuteTask;
    }

    /**
     * @return PendingPlayerCheckerTask
     */
    public function getPendingPlayerCheckerTask(){
        return $this->pendingPlayerCheckerTask;
    }
    /**
     * @return BuyCommand
     */
    public function getBuyCommand(){
        return $this->buyCommand;
    }

    /**
     * @return BuyCraftCommand
     */
    public function getBuycraftCommand(){
        return $this->buycraftCommand;
    }

    /**
     * @return BuycraftCommandSender
     */
    public function getCommandSender(){
        return $this->commandSender;
    }

    /**
     * @return PackageManager
     */
    public function getPackageManager(){
        return $this->packageManager;
    }

    /**
     * @return bool
     */
    public function isAuthenticated(){
        return $this->isAuthenticated;
    }
    public function setAuthenticated(){
        $this->isAuthenticated = true;
    }
    public function setUnAuthenticated(){
        $this->isAuthenticated = false;
    }
    public function setAuthPayload(array $authPayload){
        if(isset($authPayload["buyCommand"])){
            $this->buyCommand->updateCommand($authPayload["buyCommand"]);
        }
        $this->authPayload = $authPayload;
    }
    public function getAuthPayload(){
        return $this->authPayload;
    }
    public function getAuthPayloadSetting($name){
        return (isset($this->authPayload[$name]) ? $this->authPayload[$name] : false);
    }
}