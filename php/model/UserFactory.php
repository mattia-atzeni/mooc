<?php
include_once 'Database.php';
include_once 'User.php';

/**
 * Classe per la creazione degli utenti. 
 */
class UserFactory {
    
    private function __construct() {
        
    }
    
    /**
     * Crea uno studente da una riga del database
     * @param type $row
     * @return \User
     */
    private static function getUserFromArray($row) {
        $user = new User();
        $user->setId($row["id"]);
        $user->setFirstName($row['first_name']);
        $user->setLastName($row['last_name']);
        $user->setEmail($row['email']);
        $user->setUsername($row['username']);
        $user->setPassword($row['password']);
        
        if ($row['isProvider']) {
            $user->setRole(User::Provider);
        } else {
            $user->setRole(User::Learner);
        }
        return $user;
    }
    
    /**
     * Carica un utente tramite username e password
     * @param string $username
     * @param string $password
     * @return User nel caso sia stato trovato un utente, null altriementi
     */
    public static function login($username, $password) {
        $db = new Database();
        $db->connect();
        $query = "select * from users "
                  . "where username = ? and password = ?";
 
        $db->prepare($query);
        $db->bind('ss', $username, $password);
        $row = $db->fetch();
        $db->close();
        if (isset($row)) {
            return self::getUserFromArray($row);
        } else {
            return null;
        }
    }
    
    /**
     * seleziona un utente per id
     * @param int $id
     * @return User un utente nel caso sia stato trovato, NULL altrimenti
     */
    public static function getUserById($id) {
        
        $row = Database::selectById("select * from users where id = ?", $id);
        
        if (isset($row)) {
            return self::getUserFromArray($row);
        } else {
            return null;
        }       
    }
    
}

