<?php
/**
 * Classe che rappesenta gli host inseriti nel sistema
 */
class Host {
    private $id;
    private $name;
    private $link;
    
    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getLink() {
        return $this->link;
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
    }

    public function setLink($link) {
        $valid = filter_var($link, FILTER_VALIDATE_URL, FILTER_NULL_ON_FAILURE);
        if (isset($valid)) {
            $this->link = $link;
            return true;
        }
        return false;
    }


}

