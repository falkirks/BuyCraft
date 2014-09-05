<?php
namespace buycraft\api;
use buycraft\BuyCraft;
use pocketmine\scheduler\AsyncTask;
use pocketmine\Server;

/**
 * Class ApiAsyncTask
 * @package buycraft\api
 */
abstract class ApiAsyncTask extends AsyncTask{
    /**
     * @param array $data
     * @param BuyCraft $main
     */
    public function __construct(BuyCraft $main, $data = []){
        $this->data = $data;
        $this->server = $main->getServer();
    }

    /**
     * @return array
     */
    public function getData(){
        return $this->data;
    }

    /**
     * @return \pocketmine\scheduler\ServerScheduler
     */
    public function getScheduler(){
        return $this->server->getScheduler();
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
    abstract public function onOutput(BuyCraft $main);

    /**
     * @param Server $server
     */
    public function onCompletion(Server $server){
        $plugin = $server->getPluginManager()->getPlugin("HTTPServer");
        if($plugin != null && $plugin->isEnabled()){
            $this->onOutput($plugin);
        }
    }
}