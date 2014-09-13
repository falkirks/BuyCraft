<?php
namespace buycraft\api;
use buycraft\BuyCraft;
use pocketmine\Player;
use pocketmine\scheduler\PluginTask;
use pocketmine\utils\Utils;

/**
 * Class ApiTask
 * @package buycraft\api
 */
abstract class ApiTask extends PluginTask{
    /**
     * @param BuyCraft $main
     * @param Player $manual
     * @param array $data
     */
    public function __construct(BuyCraft $main, $data = []){
        parent::__construct($main);
        if($main->getConfig()["https"]){
            $this->apiUrl = "https://api.buycraft.net/v4";
        }
        else{
            $this->apiUrl = "http://api.buycraft.net/v4";
        }
        $this->data = $data;
    }

    /**
     * @return array
     */
    public function getData(){
        return $this->data;
    }
    /**
     * @return mixed
     */
    public function send(){
        if($this->getOwner()->isAuthenticated()){
            $data["secret"] = $this->getOwner()->getConfig()["secret"];
            $data["playersOnline"] = count($this->getOwner()->getServer()->getOnlinePlayers());
            return json_decode(Utils::getURL($this->apiUrl . "?" . http_build_query($this->getData())));
        }
        else{
            return false;
        }
    }
    /**
     * @return \pocketmine\scheduler\ServerScheduler
     */
    public function getScheduler(){
        return $this->getOwner()->getServer()->getScheduler();
    }

    /**
     * @return mixed
     */
    abstract public function call();
}