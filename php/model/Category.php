<?php

/**
 * Classe che rappresenta le categorie associate ai corsi
 */

class Category {
    private $id;
    private $name;
    
    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
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
        $this->name = $name;
        return true;
    } 
}
