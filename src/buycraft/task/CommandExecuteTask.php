<?php
namespace buycraft\task;


/*
 * Executes commands from command queue and marks them for deletion.
 *
 * Also retains credited commands and unsuccessful commands for reporting to user.
 */

use buycraft\api\ApiTask;
use buycraft\util\PackageCommand;
use pocketmine\item\Block;

class CommandExecuteTask extends ApiTask{
    /** @var PackageCommand[] */
    private $commandQueue = [];
    private $creditedCommands = [];
    /** @var PackageCommand[] */
    private $needsInventorySpace = [];
    public function onRun($tick){
        if(count($this->commandQueue) > 0){
            foreach($this->commandQueue as $i => $command){
                if($command->getDelay() === 0){
                    if($command->requiresInventorySlots()){
                        if($command->getRequiredInventorySlots($this->getOwner()->getServer()->getPlayerExact($command->getUser())) > 0){
                            $this->needsInventorySpace[] = $command;
                            $this->commandQueue[$i]->setDelay(10);
                            continue;
                        }
                    }
                    $this->getOwner()->getServer()->dispatchCommand($this->getOwner()->getCommandSender(), $command->getReplacedCommand());
                    $this->getOwner()->getCommandDeleteTask()->deleteCommand($command->getCommandID());
                    $this->creditedCommands[$command->getUser()] = true;
                    unset($this->commandQueue[$i]);
                }
                else{
                    $command->delayTick();
                }
            }
        }
        foreach($this->creditedCommands as $i => $command){
            $p = $this->getOwner()->getServer()->getPlayerExact($i);
            if($p !== null && $p->isOnline()){
                $p->sendMessage($this->getOwner()->getConfig()->get('commandsExecuted'));
                unset($this->creditedCommands[$i]);
            }
        }
        foreach($this->needsInventorySpace as $i => $command){
            $p = $this->getOwner()->getServer()->getPlayerExact($command->getUser());
            if($p !== null && $p->isOnline()){
                $p->sendMessage(sprintf($this->getOwner()->getConfig()->get('commandExecuteNotEnoughFreeInventory'), $command->getRequiredInventorySlots($p))); //Not sure if that is right
                $p->sendMessage($this->getOwner()->getConfig()->get('commandExecuteNotEnoughFreeInventory2'));
                unset($this->needsInventorySpace[$i]);
            }
        }
    }
    public function call(){
        $this->getScheduler()->scheduleRepeatingTask($this, 20);
    }
    public function queueCommand(PackageCommand $command){
        if(!$this->getOwner()->getCommandDeleteTask()->isQueued($command->getCommandID()) && !$this->isQueued($command->getCommandID())){
            $this->commandQueue[$command->getCommandID()] = $command;
            return true;
        }
        else{
            return false;
        }
    }
    public function isQueued($cid){
        foreach($this->commandQueue as $command){
            if($command->getCommandID() === $cid){
                return true;
            }
        }
        return false;
    }
}