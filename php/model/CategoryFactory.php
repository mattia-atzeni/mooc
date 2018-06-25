<?php

include_once 'Category.php';
include_once 'Database.php';

/**
 * Classe per la creazione delle categorie
 */
class CategoryFactory {
    
    private function __construct() {
        
    }
    
    /**
     * 
     * @return array delle categorie presenti nel db
     */
    public static function &getCategories() {
            
        $categories = array();
        $db = new Database();
        $db->connect();
        $query = "select * from categories";
        $db->prepare($query);
        
        while ($row = $db->fetch()) {
            $categories[] = self::getCategoryFromArray($row);
        }
        $db->close();
        
        return $categories;
    }
    
    
    /**
     * Restituisce un oggetto Categoria da una riga del db
     * @param type $array
     * @return \Category
     */
    private static function getCategoryFromArray(&$array) {   
        $category = new Category();
        $category->setId($array['id']);        
        $category->setName($array['name']);
        return $category;
    }
    
    /**
     * 
     * @param int $id id della categoria da cercare
     * @return la categoria cercata se esite, NULL altrimenti
     */
    public static function getCategoryById($id) {
        $query = "select id, name from categories
                  where id = ?";
        
        $row = Database::selectById($query, $id);
        
        if (isset($row)) {
            return self::getCategoryFromArray($row);
        } else {
            return null;
        }   
    }
}

