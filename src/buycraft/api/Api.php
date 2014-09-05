<?php
namespace buycraft\api;
use buycraft\BuyCraft;

class Api{
    public function __construct(BuyCraft $main){
        $this->plugin = $main;
        $this->apiKey = $main->getConfig();
    }
}