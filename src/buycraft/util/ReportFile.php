<?php
namespace buycraft\util;


use pocketmine\Server;

class ReportFile{
    protected $content;
    protected $path;
    public function __construct($path){
        $this->path = $path;
        $this->content = [];
    }
    public function generate(){
        $server = Server::getInstance();
        $this->content["pocketmine"] = ["version" => $server->getPocketMineVersion(), "api" => $server->getApiVersion()];
    }
    public function save() {
        file_put_contents($this->path, json_encode($this->content));
    }
}