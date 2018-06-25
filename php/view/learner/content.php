<?php

    if ($vd->getSubpage() != null && $vd->getSubpage() != "home") {
        include $vd->getSubpage() . ".php";
    }
    else {   
        ?>
        <h2>Ciao, <?=$user->getFirstName()?>!</h2>
        <?php
            if (count($courses) > 0) {
                ?>
                <h3>I tuoi corsi</h3>
                <?php
                require 'php/view/coursesList.php';
            } else {
                ?>
                <p class="no-courses">Non sei iscritto ad alcun corso</p>
                <?php
            }
        ?>
        <a class="button action" href="learner/catalog">Catalogo</a>
        <a class="button action" href="learner/filter">Cerca</a>
        <?php
    }
?>

