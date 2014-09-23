<?php
namespace buycraft\packages;

class Category{
    /** @var  Package[] */
    private $packages;
    private $id;
    private $name;
    private $desc;
    private $item;

    public function __construct($id, $name, $desc, $item){
        $this->desc = $desc;
        $this->id = $id;
        $this->item = $item;
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getDescription(){
        return $this->desc;
    }

    /**
     * @return mixed
     */
    public function getId(){
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getItem(){
        return $this->item;
    }

    /**
     * @return mixed
     */
    public function getName(){
        return $this->name;
    }

    /**
     * @return Package[]
     */
    public function getPackages(){
        return $this->packages;
    }
    public function addPackage(Package $p){
        $this->packages[] = $p;
    }
}
