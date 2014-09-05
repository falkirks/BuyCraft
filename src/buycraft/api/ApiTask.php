<?php
namespace buycraft\api;
use buycraft\BuyCraft;
use pocketmine\scheduler\PluginTask;

/**
 * Class ApiTask
 * @package buycraft\api
 */
abstract class ApiTask extends PluginTask{
    /**
     * @param BuyCraft $plugin
     * @param array $data
     */
    public function __construct(BuyCraft $plugin, $data = []){
        parent::__construct($plugin);
        $this->data = serialize($data);
    }

    /**
     * @return array
     */
    public function getData(){
        return unserialize($this->data);
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