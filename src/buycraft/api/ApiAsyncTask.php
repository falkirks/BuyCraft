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
     * @var
     */
    private $output;
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
        $this->isAuthenticated = $main->isAuthenticated();
        $this->onConfig($main);
    }
    /**
     * @return array
     */
    public function getData(){
        return unserialize($this->data);
    }

    /**
     * @return mixed
     */
    public function getOutput(){
        return unserialize($this->output);
    }

    /**
     * @param array $data
     */
    public function setData(array $data){
        $this->data = serialize($data);
    }
    /*
     * This function is called from a task and can't interact with the API.
     */
    /**
     *
     */
    public function send(){
        $data = $this->getData();
        if($this->isAuthenticated || $data["action"] === Actions::AUTHENTICATE){
            $this->output = serialize(json_decode(Utils::getURL($this->apiUrl . "?" . http_build_query($data)), true));
        }
        else{
            $this->output = false;
        }
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
     * @param Player $p
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
        if($plugin != null && $plugin->isEnabled() && $plugin instanceof BuyCraft){
            if($this->player !== false){
                $player = $server->getPlayerExact($this->player);
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