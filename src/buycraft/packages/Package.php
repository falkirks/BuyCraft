<?php
namespace buycraft\packages;

class Package{
    private $category;
    private $id;
    private $item;
    private $name;
    private $desc;
    private $price;
    private $niceId;

    public function __construct($id, $name, $desc, $price, $item, Category $c = null){
        $this->category = $c;
        $this->desc = $desc;
        $this->id = $id;
        $this->item = $item;
        $this->name = $name;
        $this->price = $price;
        if($this->category !== null){
            $this->category->addPackage($this);
        }
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
     * @return Category
     */
    public function getCategory(){
        return $this->category;
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
     * @return mixed
     */
    public function getPrice(){
        return $this->price;
    }
}