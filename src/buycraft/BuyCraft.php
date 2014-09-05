<?php
namespace buycraft;

use pocketmine\plugin\PluginBase;

class BuyCraft extends PluginBase{
    public function onEnable(){
        $this->saveDefaultConfig();
        $this->saveResource("README.md");
        $this->getConfig(); //Fetch the config so no blocking later
    }
}