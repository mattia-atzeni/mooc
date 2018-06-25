<?php

class Settings {
    public static $db_host = 'localhost';
    public static $db_user = 'atzeniMattia';
    public static $db_password = 'gabbiano1608';
    public static $db_name= 'amm15_atzeniMattia';
    
    private static $applicationPath;

    public static function getApplicationPath() {
        if (!isset(self::$applicationPath)) {
            switch ($_SERVER['HTTP_HOST']) {
                case 'localhost':
                    self::$applicationPath = 'http://' . $_SERVER['HTTP_HOST'] . '/mooc/';
                    break;
                case 'spano.sc.unica.it':
                    self::$applicationPath = 'http://' . $_SERVER['HTTP_HOST'] . '/amm2015/atzeniMattia/';
                    break;

                default:
                    self::$applicationPath = '';
                    break;
            }
        }
        
        return self::$applicationPath;
    }  
}

