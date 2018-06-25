<?php

include_once 'php/model/UserFactory.php';
include_once 'php/view/ViewDescriptor.php';
include_once 'php/model/User.php';
include_once 'php/model/CourseFactory.php';
include_once 'php/model/HostFactory.php';
include_once 'php/model/CategoryFactory.php';

/**
 * Controller per la gestione degli utenti non autenticati 
 */
class BaseController {

    const User = 'user';
    const Role = 'role';
    protected $vd;

    public function __construct() {
        $this->vd = new ViewDescriptor(); // creazione del descrittore della vista
    }

    public function handleInput() {
        
        if (isset($_REQUEST["cmd"])) {
            switch ($_REQUEST["cmd"]) {
                case 'login': // ricezione del comando di login
                    $username = isset($_REQUEST['username']) ? $_REQUEST['username'] : '';
                    $password = isset($_REQUEST['password']) ? $_REQUEST['password'] : '';
                    if ( !$this->login($username, $password) ) {
                        // login fallito, imposto il messaggio di errore
                        $this->vd->addErrorMessage("Utente sconosciuto o password errata");
                    }
                    break;
                case "logout":
                    $this->logout();
                    break;
            }
        }
        
        $user = null;
        if ($this->loggedIn()) {
            $user = UserFactory::getUserById($_SESSION[self::User]);
        }
        
        $this->preparePage($user);
        $this->showPage($user);
        
    }
    /**
     * Effettua il login
     * @param string $username
     * @param string $password
     * @return boolean true in caso di successo, false in caso di errore
     */
    protected function login($username, $password) {
        $user = UserFactory::login($username, $password);
        if (isset($user)) {
            $_SESSION[self::User] = $user->getId();
            $_SESSION[self::Role] = $user->getRole();
            return true;
        }
        return false; 
    }
    
    /**
     * effettua il logout
     */
    protected function logout() {
        // reset array $_SESSION
        $_SESSION = array();
        // termino la validita' del cookie di sessione
        if (session_id() != '' || isset($_COOKIE[session_name()])) {
            // imposto il termine di validita' al mese scorso
            setcookie(session_name(), '', time() - 2592000, '/');
        }
        // distruggo il file di sessione
        session_destroy();
    }
    
    /**
     * Verifica che l'utente sia autenticato correttamente
     */
    protected function loggedIn() {
        return isset($_SESSION) && array_key_exists(self::User, $_SESSION);
    }
    
    /**
     * Mostra la home per l'utente amministratore
     */
    private function showProviderPage($user) {
        $courses = CourseFactory::getCoursesByOwnerId($user->getId());
        $categories = CategoryFactory::getCategories();
        $vd = $this->vd;
        $hosts = HostFactory::getHosts(5);
        require 'php/view/master.php';
    }
    
    /**
     * Mostra la home per l'utente
     */
    private function showLearnerPage($user) {
        $courses = CourseFactory::getCoursesByLearnerId($user->getId());  
        $vd = $this->vd;
        $hosts = HostFactory::getHosts(5);
        require 'php/view/master.php';
    }
    
    /**
     * Prepara la pagina impostando i parametri del descrittore della vista
     */
    protected function preparePage($user) {
        $this->vd->setTitle("mooc");
        $path = "php/view/";
        if (isset($user)) {
            switch ($user->getRole()) {
                case User::Learner:
                    $this->vd->setPage("learner");
                    break;
                case User::Provider:
                    $this->vd->setPage("provider");
                    $this->vd->addScript("js/jquery-2.1.1.min.js");
                    $this->vd->addScript("js/new_course_form.js");
                    break;
            }
        } else {
            $this->vd->setContent("php/view/login/content.php");
            return;
        }
        
        $path .= $this->vd->getPage();
        $this->vd->setContent($path . "/content.php");
        $this->vd->setNavigationBar($path . "/navigationBar.php");
    }
    
    /**
     * Mostra la pagina richiesta
     */
    private function showPage($user) {
        if (isset($user)) {
            switch ($user->getRole()) {
                case User::Learner:
                    $this->showLearnerPage($user);
                    return;
                case User::Provider:
                    $this->showProviderPage($user);
                    return;
            }
         } else {
             $vd = $this->vd;
             $hosts = HostFactory::getHosts(5);
             require "php/view/master.php";
         }
    }
    
    public function showInfoPage() {
        $this->vd->setTitle("mooc");
        $this->vd->setContent("php/view/info/content.php");
        if ($this->loggedIn()) {
            if ($_SESSION[self::Role] == User::Learner) {
                $this->vd->setNavigationBar('php/view/learner/navigationBar.php');
            } else {
                $this->vd->setNavigationBar('php/view/provider/navigationBar.php');
            }
        }
        $vd = $this->vd;
        $hosts = HostFactory::getHosts(5);
        require 'php/view/master.php';
    }
}
