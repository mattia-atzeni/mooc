<h2>Ciao, <?=$user->getFirstName()?>!</h2>
<?php
if (count($courses) > 0) {
    ?>
    <h3>I tuoi corsi</h3>
    <?php
    require 'php/view/coursesList.php';
} else {
    ?>
    <p class="no-courses">Non hai inserito alcun corso</p>
    <?php
}
?>
<a id="new_course_button" href="provider/new_course" class="button action" id="new_course">Nuovo Corso</a>

<div id="new_course_form">
    <form method="post" action="provider">
        <label for="name">Nome del corso</label>
        <br/>
        <input id="name" type="text" name="name" class="textbox">
        <br/>
        <label for="link">Link al corso</label>
        <br/>
        <input type="text" id="link" name="link" class="textbox">
        <br/>
        <label for="category">Categoria</label>
        <br/>
        <select name="category" id="category">
            <?php foreach ($categories as $category) { ?>
                <option value="<?= $category->getId()?>" ><?= $category->getName() ?></option>
            <?php } ?>
        </select>
        <br/>
        <button id="save_course_button" type="submit" name="cmd" value="save_course">OK</button>
        <button class="back_button" id="cancel_button" name="cmd" value="cancel">Annulla</button>
    </form>
</div>
