<?php
namespace buycraft;

use pocketmine\command\CommandExecutor;
use pocketmine\plugin\PluginBase;

class BuyCraft extends PluginBase implements CommandExecutor{
    public function onEnable(){
        $this->saveDefaultConfig();
        $this->saveResource("README.md");
        $this->getConfig(); //Fetch the config so no blocking later
    }
}