<?php

include_once 'Database.php';
include_once 'Course.php';
include_once 'UserFactory.php';
include_once 'CategoryFactory.php';
include_once 'HostFactory.php';

/**
 * Classe per la creazione dei corsi
 */
class CourseFactory {
    
    private function __construct() {
        
    }
    
    /**
     * Resituisce un corso a partire da una riga del database
     * @param type $array
     * @return \Course
     */
    private static function getCourseFromArray(&$array) {
        $course = new Course();
        $course->setId($array['id']);
        $course->setName($array['name']);
        $course->setLink($array['link']);
        $course->setCategory(CategoryFactory::getCategoryById($array['category_id']));
        $course->setOwner(UserFactory::getUserById($array['owner_id']));
        $course->setHost(HostFactory::getHostById($array['host_id']));
        
        return $course;
    }
    
    /**
     * Inserisce un corso nel db
     * @param Course $course il corso da inserire
     * @return boolean true se l'inserimento ha avuto successo, false altrimenti
     */
    public static function saveCourse(Course $course) {
        $query = "insert into courses (name, link, category_id, owner_id, host_id)
                  values (?, ?, ?, ?, ?)";
        
        $db = new Database();
        $db->connect();
        $db->prepare($query);
        $db->bind('ssiii', 
                $course->getName(),
                $course->getLink(),
                $course->getCategory()->getId(),
                $course->getOwner()->getId(),
                $course->getHost()->getId() );
        
        $db->execute();
        $db->close();
        return !$db->error();       
    }
    
    /**
     * @param id $owner_id id dell'utente che ha inserito i corsi cercati
     * @return array dei corsi inseriti dall'utente con id $owner_id
     */
    public static function &getCoursesByOwnerId($owner_id) {      
        $courses = array();
        $query = "select * from courses where owner_id = ?";
        $db = new Database();
        $db->connect();
        $db->prepare($query);
        $db->bind('i', $owner_id );
        
        while ($row = $db->fetch()) {
            $courses[] = self::getCourseFromArray($row);
        }
        $db->close();
        return $courses;
    }
    
    /**
     * 
     * @return i corsi nel db
     */
    public static function &getCourses() {
        $courses = array();
        $db = new Database();
        $db->connect();
        $query = "select * from courses";
        $db->prepare($query);
        
        while ($row = $db->fetch()) {
            $courses[] = self::getCourseFromArray($row);
        }
        $db->close();
        
        return $courses;
    }
    
    /**
     * 
     * @param int $id id del corso da cercare
     * @return \Course il corso con id $id se esite, NULL altrimenti
     */
    public static function getCourseById($id) {
        
        $row = Database::selectById("select * from courses where id = ?", $id);
        
        if (isset($row)) {
            return self::getCourseFromArray($row);
        } else {
            return null;
        }
    }
    /**
     * Gesitisce l'iscrizione dell'utente $user al corso $course
     * @param User $user
     * @param Course $course
     * @return true se l'iscrizione ha avuto successo, false altrimenti
     */
    public static function enroll(User $user, Course $course) {
        $query = "insert into courses_learners (learner_id, course_id) values (?, ?)";
        $db = new Database();
        $db->connect();
        $db->prepare($query);
        $db->bind('ii', $user->getId(), $course->getId());
        $db->execute();
        $db->close();
        return !$db->error();
    }
    
    /**
     * 
     * @param User $user
     * @param Course $course
     * @return true se $user è iscritto a $course, false altrimenti
     */
    public static function isEnrolled(User $user, Course $course) {
        $query = "select * from courses_learners where learner_id = ? and course_id = ?";
        $db = new Database();
        $db->connect();
        $db->prepare($query);
        $db->bind('ii', $user->getId(), $course->getId());
        $row = $db->fetch();
        $db->close();
        return isset($row);
    }
    
    /**
     * Permette all'utente $user di abbandonare il corso $course
     * @param User $user
     * @param Course $course
     * @return true in caso di successo, false altrimenti
     */
    public static function unenroll(User $user, Course $course) {
        $query = "delete from courses_learners where learner_id = ? and course_id = ?";
        $db = new Database();
        $db->connect();
        $db->prepare($query);
        $db->bind('ii', $user->getId(), $course->getId());
        $db->execute();
        $db->close();
        return !$db->error();
    }
    
    /**
     * 
     * @param int $id id di un utente
     * @return array dei corsi a cui l'utente con id $id è iscritto
     */
    public static function &getCoursesByLearnerId($id) {
        $courses = array();
        $query = "select courses.id, courses.name, courses.link, courses.category_id, courses.host_id, courses.owner_id "
                . "from courses_learners "
                . "join courses on courses.id = courses_learners.course_id "
                . "join users on users.id = courses_learners.learner_id "
                . "where courses_learners.learner_id = ?";
        
        $db = new Database();
        $db->connect();
        $db->prepare($query);
        $db->bind('i', $id);
        
        while ($row = $db->fetch()) {
            $courses[] = self::getCourseFromArray($row);
        }
        $db->close();
        return $courses;
    }
    
    /**
     * Rimuove un corso dal db
     * @param int $id id del corso da rimuovere
     * @return boolean true in caso di successo, false altrimenti
     */
    public static function removeCourseById($id) {
        $db = new Database();
        $db->connect();
        
        // Il corso rimosso non sarà più accessibile agli utenti iscritti
        $db->prepare("delete from courses_learners where course_id = ?");
        $db->bind('i', $id);
        $db->getMysqli()->autocommit(false); // inizio transazione
        $db->execute();
        
        if ($db->error()) {
            // In caso di errore le modifiche al database vengono annullate
            // I metodi della classe Database avrebbero rilevato l'errore comunque e la seconda query non
            // sarebbe stata eseguita
            $db->getMysqli()->rollback();
            $db->close();
            return false;
        }
        
        $db->prepare("delete from courses where id = ?");
        $db->bind('i', $id);
        $db->execute();
        
        if (!$db->error()) {
            if ($db->getStmt()->affected_rows == 1) {
                $db->getMysqli()->commit(); // rende definitive le modifiche
                $db->getMysqli()->autocommit(true);
                $db->close();
                return true;
            }      
        }
        
        $db->getMysqli()->rollback();
        $db->close();
        return false;
    }
    
    private static function buildOrCondition($length, $field) {
        if ($length > 0) {
            $condition = "";
            for ($i = 0; $i < $length - 1; $i++) {
                $condition .= "$field = ? OR ";
            }
            $condition .= "$field = ?";
            return $condition;
        }
        
        return null;
    }
    
    private static function buildBindString($length) {
        $string = "";
        if ($length > 0) {
            for ($i = 0; $i < $length; $i++) {
                $string .= 'i';
            }
        }
        return $string;
    }
    
    /**
     * 
     * @param string $name nome (o parte del nome) dei corsi da cercare
     * @param $categories categorie a cui i corsi cercati possono appartenere
     * @param $hosts host a cui i corsi associati possono appartenere
     * @return array dei corsi che rispettano le condizioni specificate
     */
    public static function &filter($name, &$categories, &$hosts) {
        $categoriesCondition = self::buildOrCondition(count($categories), "category_id");
        $hostsCondition = self::buildOrCondition(count($hosts), "host_id");
        $query = "select * from courses where name like ?";
        
        if (isset($categoriesCondition)) {
            $query .= " AND ($categoriesCondition)";
        }
        
        if (isset($hostsCondition)) {
            $query .= " AND ($hostsCondition)";
        }
        
        $bindString = "s" . self::buildBindString(count($categories)) . self::buildBindString(count($hosts));
        
   
        $args = array_merge(array($bindString, "%$name%"), $categories, $hosts);
        
        $db = new Database();
        $db->connect();
        $db->prepare($query);
        
        call_user_func_array(array($db, 'bind'), $args);
        
        while ($row = $db->fetch()) {
            $courses[] = self::getCourseFromArray($row);
        }
        $db->close();
        return $courses;
    }
}

