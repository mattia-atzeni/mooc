<?php


/**
 * Classe che rappresenta un generico utente
 */
class User {
    
    const Learner = '1';
    const Provider = '2';
    
    private $id;
    private $firstName;
    private $lastName;
    private $email;
    private $username;
    private $password;
    private $role;
   
    public function getId() {
        return $this->id;
    }
    
    public function getFirstName() {
        return $this->firstName;
    }

    public function getLastName() {
        return $this->lastName;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getUsername() {
        return $this->username;
    }

    public function getPassword() {
        return $this->password;
    }
    
    public function getRole() {
        return $this->role;
    }
    
    public function setId($id) {
        $intVal = filter_var($id, FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
        if (!isset($intVal)) {
            return false;
        }
        $this->id = $intVal;
        return true;
    }

    public function setFirstName($firstName) {
        $this->firstName = $firstName;
        return true;
    }

    public function setLastName($lastName) {
        $this->lastName = $lastName;
        return true;
    }

    public function setEmail($email) {
        $valid = filter_var($email, FILTER_VALIDATE_EMAIL, FILTER_NULL_ON_FAILURE);
        if (isset($valid)) {
            $this->email = $valid;
            return true;
        }
        return false;
           
    }

    public function setUsername($username) {
        if (!filter_var($username, FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => '/[a-zA-Z]{5,}/')))) {
             return false;
         }
         $this->username = $username;
         return true;
    }

    public function setPassword($password) {
        $this->password = $password;
        return true;
    }
    
    public function setRole($role) {
        switch($role) {
            case self::Learner:
            case self::Provider:
                $this->role = $role;
                return true;
            default: return false;
        }
    }
}
