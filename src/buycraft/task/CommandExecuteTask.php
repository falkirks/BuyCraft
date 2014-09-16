<?php
namespace buycraft\task;


/*
 * Executes commands from command queue and marks them for deletion.
 *
 * Also retains credited commands and unsuccessful commands for reporting to user.
 */

use buycraft\api\ApiTask;
use buycraft\util\PackageCommand;

class CommandExecuteTask extends ApiTask{
    /** @var PackageCommand[] */
    private $commandQueue = [];
    /** @var PackageCommand[] */
    private $creditedCommands = [];
    /** @var PackageCommand[] */
    private $needsInventorySpace = [];
    public function onRun($tick){
        if(count($this->commandQueue) > 0){
            foreach($this->commandQueue as $command){
                if($command->requiresInventorySlots()){
                    //TODO calculate inventory stuff
                }
                else{
                    $this->getOwner()->getServer()->dispatchCommand($this->getOwner()->getCommandSender(), $command->getReplacedCommand());
                    $this->getOwner()->getCommandDeleteTask()->deleteCommand($command->getCommandID());
                    $this->creditedCommands[] = $command;
                }
            }
            $this->commandQueue = [];
        }
        else{
            foreach($this->creditedCommands as $i => $command){
                $p = $this->getOwner()->getServer()->getPlayerExact($command->getUser());
                if($p !== null && $p->isOnline()){
                    $p->sendMessage($this->getOwner()->getConfig()->get('commandsExecuted'));
                    unset($this->creditedCommands[$i]);
                }
            }
            foreach($this->needsInventorySpace as $i => $command){
                $p = $this->getOwner()->getServer()->getPlayerExact($command->getUser());
                if($p !== null && $p->isOnline()){
                    $p->sendMessage(sprintf($this->getOwner()->getConfig()->get('commandExecuteNotEnoughFreeInventory'), $command->getRequiredInventorySlots())); //Not sure if that is right
                    $p->sendMessage($this->getOwner()->getConfig()->get('commandExecuteNotEnoughFreeInventory2'));
                    unset($this->needsInventorySpace[$i]);
                }
            }
        }
    }
    public function call(){
        $this->getScheduler()->scheduleRepeatingTask($this, 20);
    }
    public function queueCommand(PackageCommand $command){
        if(!$this->getOwner()->getCommandDeleteTask()->isQueued($command->getCommandID()) && !in_array($command, $this->commandQueue)){
            $this->commandQueue[] = $command;
            return true;
        }
        else{
            return false;
        }
    }
}