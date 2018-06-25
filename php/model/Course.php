<?php

include_once 'Host.php';
include_once 'User.php';
include_once 'Category.php';

/**
 * Classe che rappresenta un generico corso
 */
class Course {
    private $id;
    private $name;
    private $link;
    private $category;
    private $host;
    private $owner;
    
    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getLink() {
        return $this->link;
    }

    public function getCategory() {
        return $this->category;
    }

    public function getHost() {
        return $this->host;
    }

    public function getOwner() {
        return $this->owner;
    }

    public function setId($id) {
        $intVal = filter_var($id, FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
        if (!isset($intVal)) {
            return false;
        }
        $this->id = $intVal;
        return true;
    }

    public function setName($name) {
        if ($name != "") {
            $this->name = $name;
            return true;
        }
        return false;
    }

    public function setLink($link) {
        $valid = filter_var($link, FILTER_VALIDATE_URL, FILTER_NULL_ON_FAILURE);
        if (isset($valid)) {
            $this->link = $valid;
            return true;
        }
        return false;
    }

    public function setCategory(Category $category) {
        $this->category = $category;
        return true;
    }

    public function setHost(Host $host) {
        $this->host = $host;
        return true;
    }

    public function setOwner(User $owner) {
        $this->owner = $owner;
        return true;
    }
}