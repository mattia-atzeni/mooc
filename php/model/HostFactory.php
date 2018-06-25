<?php

include_once 'Host.php';
include_once 'Database.php';
/**
 * Classe per la creazione degli host associati ai corsi
 */
class HostFactory {
    /**
     * 
     * @param string $link il link di un corso
     * @return \Host host associato al link in ingresso
     */
    public static function getHostByLink($link) {
        $host = parse_url($link, PHP_URL_HOST);
        if (!$host ) {
            $link = "http://" . $link;
            $host = parse_url($link, PHP_URL_HOST);
        }
        
        $db = new Database();
        $db->connect();
        $db->prepare("select * from hosts where link like ?");
        $db->bind("s", "%$host%");

        $row = $db->fetch();
        $db->close();
        
        if (isset($row)) {
            return self::getHostFromArray($row);
        } else {
            $result = new Host();
            $result->setName("altro");
            return $result;
        }
    }
    
    /**
     * 
     * @param int $id
     * @return \Host se esiste, l'host associato all'id in ingresso, NULL altrimenti
     */
    public static function getHostById($id) {
        
        if (!isset($id)) {
            $result = new Host();
            $result->setName("altro");
            return $result;
        }
        
        $row = Database::selectById("select * from hosts where id = ?", $id);
        
        if (isset($row)) {
            return self::getHostFromArray($row);
        } else {
            return null;
        }
    }
    
    /**
     * restituisce un host a partire da una riga del db
     * @param type $array
     * @return \Host
     */
    private static function getHostFromArray($array) {
        $host = new Host();
        
        if (!isset($array)) {
            return null;
        }
        
        if (isset($array['name']) && isset($array['id'])) {
            $host->setName($array['name']);
            
            if (!$host->setId($array['id'])) {
                return null;
            }
            
        } else {
            return null;
        }
        
        if (isset($array['link'])) {
            $host->setLink($array['link']);
        }
        
        return $host;
    }
    
    /**
     * 
     * @param int $limit numero massimo di host da caricare
     * @return un array contentente i primi $limit host nel database.
     */
    public static function &getHosts($limit=null) {
        $hosts = array();
        $db = new Database();
        $db->connect();
        
        if (isset($limit)) {
            $db->prepare("select * from hosts limit ?");
            $db->bind('i', $limit);
        } else {
            $db->prepare("select * from hosts");
        }
        
        while ($row = $db->fetch()) {
            $hosts[] = self::getHostFromArray($row);
        }
        $db->close();
        
        return $hosts;
    }
}