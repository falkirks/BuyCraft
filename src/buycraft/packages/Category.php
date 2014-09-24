<?php
namespace buycraft\packages;

class Category{
    /** @var  Package[] */
    private $packages;
    private $id;
    private $name;
    private $desc;
    private $item;
    private $niceId;

    public function __construct($id, $name, $desc, $item, $niceId){
        $this->desc = $desc;
        $this->id = $id;
        $this->item = $item;
        $this->name = $name;
        $this->niceId = $niceId;
    }

    /**
     * @param mixed $niceId
     */
    public function setNiceId($niceId){
        $this->niceId = $niceId;
    }

    /**
     * @return mixed
     */
    public function getNiceId(){
        return $this->niceId;
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
