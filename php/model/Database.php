<?php
include_once 'php/Settings.php';

/**
 * Classe per la gestione del database.
 * In caso di errore la connessione viene terminata e 
 * viene chiamata la funzione error_log per registrare l'errore. Il messaggio di errore registrato 
 * contiene l'indicazione della classe e del metodo in cui l'errore si è verificato (ovvero il metodo che ha 
 * a sua volta chiamato i metodi di questa classe).
 * Ogni metodo, prima di eseguire le operazioni richieste verifica la presenza di errori
 */
class Database {
    
    private $mysqli;
    private $stmt;
    private $error = true;
    private $executed = false;
    private $stackCounter = 3;  // posizione nello stack del metodo che ha richiesto l'interazione con il database
                                // al momento della registrazione del messaggio di errore
        
    public function connect() {
        $this->error = false;
        $this->mysqli = new mysqli();
        $this->mysqli->connect(Settings::$db_host, Settings::$db_user, Settings::$db_password, Settings::$db_name);
        if ($this->mysqli->connect_errno != 0) {
            $header = $this->buildErrorMessageHeader();
            error_log("$header errore nella connessione al database - {$this->mysqli->connect_errno} : {$this->mysqli->connect_error}");
            $this->error = true;
        }
        return !$this->error;
    }
    
    // inizializza il prepared statement
    public function prepare($query) {
        if (!$this->error ) {
            $this->stmt = $this->mysqli->stmt_init();
            $this->stmt->prepare($query);
            if (!$this->stmt) {
                $this->manageError("Impossibile inizializzare il prepared statement");
            }
            $this->executed = false;
        }
        return !$this->error;
    }
    
    // binding dei parametri in input
    public function bind() {
        if (!$this->error) {
            try {
                $args = func_get_args();
                $stmtClass = new ReflectionClass('mysqli_stmt');
                $method = $stmtClass->getMethod("bind_param");
                if (!$method->invokeArgs($this->stmt, $args ) ) {
                    throw new Exception();
                }
            } catch (Exception $e) {
                $this->manageError("Impossibile effettuare il binding in input");
            }
            $this->executed = false;
        }
        return !$this->error;
       
    }
    /* 
    if ( !call_user_func_array(array($this->stmt, "bind_param"), self::toReference($args)) ) {
        $header = $this->buildErrorMessageHeader();
        error_log("$header impossibile effettuare il binding in input");
        return false;
    }
     
    private static function toReference(&$array) {
        $result = array();
        
        foreach ($array as $key => $value) {
            $result[$key] = &$array[$key];
        }
        
        return $result;
    }*/

    // esecuzione della query
    public function execute() {
        if (!$this->error) {
            if (!$this->stmt->execute()) {
                $this->manageError("Impossibile eseguire lo statement");
            }  
        }
        $this->executed = true;
        return !$this->error;
    }
    
    // fetch dei risultati
    // restituisce null in caso di errore
    public function fetch() {
        
        $outputBind = false;
        
        if (!$this->error) {
            if (!$this->executed) {
                if (!$this->execute()) {
                    return null;
                }
                $outputBind = false;
            }
            
            $result = array();
            if (!$outputBind) {
                if (!$this->outputBind($result)) {
                    return null;
                }
                $outputBind = true;
            }

            if (!$this->stmt->fetch()) {
                return null;
            }
            
            if (count($result) > 0) {
                return $result;
            }
        }
        return null;
    }
    
    // binding in output: viene creato un array associativo in cui le chiavi sono i nomi dei campi della tabella richiesti 
    private function outputBind(&$row) {
        $result = $this->stmt->result_metadata();
        if ($result) {
            $temp = array();

            while( $field = $result->fetch_field() ) {
                $row[$field->name] = &$temp[$field->name];
            }

            $result->close();

            try {
                $stmtClass= new ReflectionClass('mysqli_stmt');
                $method = $stmtClass->getMethod("bind_result");
                if (!$method->invokeArgs($this->stmt, $row)) {
                    throw new Exception();
                }
            } catch (Exception $e) {
                $this->manageError("Impossibile eseguire il binding in output");
                return false;
            }
        
            return true;
        } else {
            $this->manageError("Impossibile determinare i campi per il binding in output");
        }
    }
    
    public function error() {
        return $this->error;
    }
    
    // gestione degli errori
    private function manageError($message) {
        $this->close();
        $this->error = true;
        $header = $this->buildErrorMessageHeader();
        error_log("$header $message");
    }
    
    // chiude la connessione
    public function close() {
        if (!$this->error) {
            if (isset($this->mysqli)) {
                $this->mysqli->close();
            }

            if (isset($this->stmt)) {
                $this->stmt->close();
            }
        }
    }
    
    // costruisce l'intestazione del messaggio di errore riportando nome della classe (se esiste) e metodo che hanno richiesto
    // l'interazione con il database
    private function buildErrorMessageHeader() {
        $trace = debug_backtrace();
        $caller = $trace[$this->stackCounter];
        $class = "";

        if (isset($caller['class'])) {
            $class = "{$caller['class']} : ";
        }
        
        $header = "[$class{$caller['function']}]";
        return $header;
    }
    
    public function getMysqli() {
        return $this->mysqli;
    }
    
    public function getStmt() {
        return $this->stmt;
    }
    
    // seleziona un elemento per id
    public static function selectById($query, $id) {
        $db = new Database();
        $db->stackCounter++;
        $db->connect();
        $db->prepare($query);
        $db->bind('i', $id);
        $row = $db->fetch();
        $db->close();
        return $row; // vale null in caso di errore
    }
}

