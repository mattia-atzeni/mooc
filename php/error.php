<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Errore</title>
    </head>
    <body>
        <h1><?= $title ?></h1>
        <p>
            <?=
            $message
            ?>
            <br/><br/>
            <a href=<?=Settings::getApplicationPath() . "login"?>>Home</a>
        </p>
        
    </body>
</html>