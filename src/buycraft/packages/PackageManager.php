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
        $this->pageSize = $this->main->getConfig()->get('packagePageSize');
    }
    public function addCategory($id, $name, $desc, $item){
        $this->categories[] = new Category($id, $name, $desc, $item, count($this->categories));
    }
    public function addPackage($categoryId, $id, $item, $name, $desc, $price){
        $category = $this->getCategoryById($categoryId);
        if($category instanceof Category){
            $this->packages[] = new Package($id, $name, $desc, $price, $item, $category);
        }
        else{
            $this->packages[] = new Package($id, $name, $desc, $price, $item);
        }
    }
    public function cleanCategories(){
        foreach($this->categories as $i => $c){
            if(count($c->getPackages()) == 0){
                unset($this->categories[$i]);
            }
        }
        foreach($this->packages as $i => $p){
            $p->setNiceId($i);
        }
    }
    public function getCategories(){
        return $this->categories;
    }
    public function getCategory($niceId){
        return (isset($this->categories[$niceId]) ? $this->categories[$niceId] : false);
    }
    public function getCategoryById($id){
        foreach($this->getCategories() as $category){
            if($category->getId() === $id){
                return $category;
            }
        }
        return false;
    }
    public function getPackages(){
        return $this->packages;
    }
    public function getPackage($niceId){
        return (isset($this->packages[$niceId]) ? $this->packages[$niceId] : false);
    }
    public function getPage($page = 0, $category = 0){
        $start = $page * $this->pageSize;
        if($category === false){
            $outArray = array_slice($this->getPackages(), $start, $this->pageSize);
        }
        elseif($this->getCategory($category) instanceof Category){
            $outArray = array_slice($this->getCategory($category)->getPackages(), $start, $this->pageSize);
        }
        else{
            $outArray = false;
        }
        return $outArray;
    }
    public function reset(){
        $this->categories = [];
        $this->packages = [];
    }
}