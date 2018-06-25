<?php
include_once 'BaseController.php';
include_once 'php/model/CourseFactory.php';
include_once 'php/model/Course.php';
include_once 'php/model/CategoryFactory.php';
include_once 'php/model/HostFactory.php';

class ProviderController extends BaseController {
      
    public function __construct() {
        parent::__construct();
    }
    
    public function handleInput() {
        $user = null;
        if ($this->loggedIn()) {
            $user = UserFactory::getUserById($_SESSION[BaseController::User]);

            if (isset($_REQUEST['cmd'])) {
                switch ($_REQUEST['cmd']) {
                    case "save_course": $this->handleSaveCourseCmd(); break;    // comando per inserire un nuovo corso
                    case "remove":      $this->handleRemoveCmd(); break;        // comando per rimuovere un corso
                }
            }
               
            $courses = CourseFactory::getCoursesByOwnerId($user->getId());
            $categories = CategoryFactory::getCategories();
        }
        
        $vd = $this->vd;
        $hosts = HostFactory::getHosts(5);
        $this->preparePage($user);
        require "php/view/master.php";
    }
    
    /* Gestisce la richesta di inserimento di un nuovo corso */
    private function handleSaveCourseCmd() {
        $course = $this->getCourse();
        if (isset($course)) {
            if (!CourseFactory::saveCourse($course)) {
                $this->vd->addErrorMessage("Impossibile salvare il corso");
            }
        }
    }
    
    /* Gstisce la richiesta di rimozione di un corso */
    private function handleRemoveCmd() {
        if (isset($_REQUEST['course_id'])) {
            $course_id = $_REQUEST['course_id'];
            if (CourseFactory::removeCourseById($course_id)) {
                return;
            }
        }
        
        $this->vd->addErrorMessage("Errore durante la rimozione del corso");
    }
    
    /**
     * Crea un corso a partire dai parametri forniti dall'utente
     * @return \Course il corso creato in caso di successo, NULL altrimenti
     */
    private function getCourse() {
        $course = new Course();
        $valid = array(
            "name" => false,
            "link" => false,
            "category" => false
        );
        
        if ( isset($_REQUEST['name']) ) {
            $name = trim($_REQUEST['name']);
            if ($name != '') {
                if ($course->setName(htmlentities($name))) {
                     $valid['name'] = true;
                }
            }
        }
        
        if (isset($_REQUEST['link'])) {
            if ($course->setLink($_REQUEST['link'])) {
                $valid['link'] = true;
            }
        }
        
        if (isset($_REQUEST['category'])) {
            $category = CategoryFactory::getCategoryById($_REQUEST['category']);
            if (isset($category)) {
                $valid['category'] = true;
                $course->setCategory($category);
            }
        }
        
        if ($valid['name'] && $valid['link'] && $valid['category']) {
            $host = HostFactory::getHostByLink($_REQUEST['link']);
            $course->setHost($host);
            
            $course->setOwner(UserFactory::getUserById($_SESSION[BaseController::User]));
            
            return $course;
                    
        } else {
            if (!$valid['name']) {
                $this->vd->addErrorMessage('Nome non valido');
            }

            if (!$valid['link']) {
                $this->vd->addErrorMessage('Link non valido');
            }

            if (!$valid['category']) {
                $this->vd->addErrorMessage('Categoria non valida');
            }
            
            return null;
        }   
    }
}
