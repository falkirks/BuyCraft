<?php
namespace buycraft\api;
use buycraft\BuyCraft;
use pocketmine\Player;
use pocketmine\scheduler\AsyncTask;
use pocketmine\Server;
use pocketmine\utils\Utils;

/**
 * Class ApiAsyncTask
 * @package buycraft\api
 */
abstract class ApiAsyncTask extends AsyncTask{
    /**
     * @param BuyCraft $main
     * @param array $data
     * @param bool $player
     */
    public function __construct(BuyCraft $main, $data = [], $player = false){
        if($main->getConfig()->get("https")){
            $this->apiUrl = "https://api.buycraft.net/v4";
        }
        else{
            $this->apiUrl = "http://api.buycraft.net/v4";
        }
        $data["secret"] = $main->getConfig()->get("secret");
        $data["playersOnline"] = count($main->getServer()->getOnlinePlayers());
        $this->data = serialize($data);
        $this->player = $player;
        $this->onConfig($main);
    }
    /**
     * @return array
     */
    public function getData(){
        return unserialize($this->data);
    }

    /**
     * @param array $data
     */
    public function setData(array $data){
        $this->data = serialize($data);
    }
    /**
     * @return mixed
     */
    /*
     * This function is called from a task and can't interact with the API.
     */
    /**
     * @return mixed
     */
    public function send(){
        return json_decode(Utils::getURL($this->apiUrl . "?" . http_build_query($this->getData())));
    }
    /**
     * @return \pocketmine\scheduler\ServerScheduler
     */
    public function getScheduler(){
        return Server::getInstance()->getScheduler();
    }

    /**
     *
     */
    public function call(){
        $this->getScheduler()->scheduleAsyncTask($this);
    }

    /**
     * @param BuyCraft $main
     * @return mixed
     */
    abstract public function onOutput(BuyCraft $main, Player $p = null);

    /**
     * @param BuyCraft $main
     * @return mixed
     */
    abstract public function onConfig(BuyCraft $main);
    /**
     * @param Server $server
     */
    public function onCompletion(Server $server){
        $plugin = $server->getPluginManager()->getPlugin("BuyCraft");
        if($plugin != null && $plugin->isEnabled()){
            if($this->player !== false){
                $player = $server->getPlayer($this->player);
                if($player !== null && $player->isOnline()){
                    $this->onOutput($plugin, $player);
                }
            }
            else{
                $this->onOutput($plugin);
            }
        }

    }
}