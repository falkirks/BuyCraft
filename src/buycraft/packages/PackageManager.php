<?php
namespace buycraft\packages;

use buycraft\BuyCraft;

class PackageManager{
    /** @var  Category[] */
    private $categories;
    /** @var  Package[] */
    private $packages;
    public function __construct(BuyCraft $main){
        $this->main = $main;
        $this->categories = [];
        $this->packages = [];
    }
    public function addCategory($id, $name, $desc, $item){
        $this->categories[$id] = new Category($id, $name, $desc, $item);
    }
    public function addPackage($categoryId, $id, $item, $name, $desc, $price){
        if(isset($this->categories[$categoryId])){
            $this->packages[$id] = new Package($id, $name, $desc, $price, $item, $this->categories[$categoryId]);
        }
        else{
            $this->packages[$id] = new Package($id, $name, $desc, $price, $item);
        }
    }
    public function cleanCategories(){
        foreach($this->categories as $i => $c){
            if(count($c->getPackages()) == 0){
                unset($this->categories[$i]);
            }
        }
    }
    public function getCategories(){
        return $this->categories;
    }
    public function getCategory($id){
        return $this->categories[$id];
    }
    public function getPackages(){
        return $this->packages;
    }
    public function getPackage($id){
        return $this->packages[$id];
    }
    public function reset(){
        $this->categories = [];
        $this->packages = [];
    }
}