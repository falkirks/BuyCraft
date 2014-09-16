<?php
namespace buycraft\util;

use pocketmine\command\ConsoleCommandSender;

class BuycraftCommandSender extends ConsoleCommandSender{
    public function getName(){
        return "BUYCRAFT";
    }
    public function sendMessage($message){

    }
}