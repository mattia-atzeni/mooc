<?php

include_once 'BaseController.php';
include_once 'php/model/CourseFactory.php';
include_once 'php/model/Course.php';
include_once 'php/model/CategoryFactory.php';
include_once 'php/model/HostFactory.php';

class LearnerController extends BaseController {
      
    public function __construct() {
        parent::__construct();
    }
    
    public function handleInput() {
        $user = null;
        if ($this->loggedIn()) {
            $user = UserFactory::getUserById($_SESSION[BaseController::User]);
            $subpage = isset($_REQUEST['subpage']) ? $_REQUEST['subpage'] : "home";
            
            if (isset($_REQUEST['cmd'])) {
                switch ($_REQUEST['cmd']) {
                    case "join":     
                        $this->handleJoinCmd(); break;  //comando di iscrizione a un corso
                    case "unenroll": 
                        $this->handleUnenrollCmd(); break;   // comando per abbandonare un corso 
                    case "filter":   
                        if ( isset($subpage) && $subpage == "filter" ) {
                            $courses = $this->handleFilterCmd(); // comando per cercare i corsi
                        }
                        break;
                        
                }
            }

            if (isset($subpage)) {
                $this->vd->setSubpage($subpage);    // imposta la sottopagina corretta
                switch ($subpage) {
                    case "home":    $courses = CourseFactory::getCoursesByLearnerId($user->getId()); break;
                    case "catalog": $courses = CourseFactory::getCourses(); break;
                    case "filter":  $this->vd->addScript("js/jquery-2.1.1.min.js");
                                    $this->vd->addScript("js/filter.js");
                                    $categories = CategoryFactory::getCategories();
                                    if ($this->vd->isJson()) {
                                        $this->vd->setSubpage('courses_filter_json');
                                    }
                                    break;
                }
            }
        }
        
        $this->preparePage($user);
        $hosts = HostFactory::getHosts(5);
        $vd = $this->vd;
        require "php/view/master.php";
    }
    
    /**
     * Gestisce la richiesta di iscrizione a un corso
     */
    private function handleJoinCmd() {
        if (isset($_REQUEST['course_id'])) {
            $user = UserFactory::getUserById($_SESSION[BaseController::User]);
            $course = CourseFactory::getCourseById($_REQUEST['course_id']);
            if (isset($course)) {
                if (CourseFactory::enroll($user, $course)) {
                    return;
                } elseif (CourseFactory::isEnrolled($user, $course)) {
                    $this->vd->addErrorMessage("Sei già iscritto a questo corso");
                    return;
                }
            }
        }
        
        $this->vd->addErrorMessage("Impossibile completare l'iscrizione al corso");
    }
    
    /**
     * Gestisce la richiesta di abbandonare un corso
     */
    private function handleUnenrollCmd() {
        if (isset($_REQUEST['course_id'])) {
            $user = UserFactory::getUserById($_SESSION[BaseController::User]);
            $course = CourseFactory::getCourseById($_REQUEST['course_id']);
            if (isset($course)) {
                if (CourseFactory::unenroll($user, $course)) {
                    return;
                }
            }
        }
        
        $this->vd->addErrorMessage("Impossibile abbandonare il corso");
    }
    
    /**
     * Gestisce la ricerca dei corsi
     */
    private function &handleFilterCmd() {
        
        if (isset($_REQUEST['name']) ) {
            $name = htmlentities($_REQUEST['name']);
        } else {
            $name = '';
        }
        
        $categories = array();
        if (isset($_REQUEST['categories']) && is_array($_REQUEST['categories'])) {
            foreach ($_REQUEST['categories'] as $category) {
                $tmp = filter_var($category, FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
                if (isset($tmp)) {
                    $categories[] = $tmp;
                } else {
                    $this->vd->addErrorMessage("categorie non valide");
                    return array();
                }
            }
        }
        
        $hosts = array();
        if (isset($_REQUEST['hosts']) && is_array($_REQUEST['hosts'])) {
            foreach ($_REQUEST['hosts'] as $host) {
                $tmp = filter_var($host, FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
                if (isset($tmp)) {
                    $hosts[] = $tmp;
                } else {
                    $this->vd->addErrorMessage("host non validi");
                    return array();
                }
            }
        }
        
        $this->vd->toggleJson();
        return CourseFactory::filter($name, $categories, $hosts);               
    }
}
