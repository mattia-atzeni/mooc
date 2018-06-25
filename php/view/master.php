<?php
include_once 'ViewDescriptor.php';

if (!$vd->isJson()) {
    ?>
    <!DOCTYPE html>
    <html>
        <head>
            <meta http-equiv="content-type" content="text/html; charset=utf-8" />
            <title><?= $vd->getTitle() ?></title>
            <base href="<?= Settings::getApplicationPath() ?>"/>
            <link rel="stylesheet"  type="text/css"  href="css/style.css">
            <?php
            foreach ($vd->getScripts() as $script) {
                ?>
                <script type="text/javascript" src="<?=$script?>"></script>
                <?php
            }
            ?>
        </head>

        <body>
            <nav>
                <ul>
                    <li><a href="login" class="logo"><img src="images/mooc.png" alt="mooc"></a></li>
                    <?php
                    if ($vd->getNavigationBar() != null) {
                        $bar = $vd->getNavigationBar();
                        require "$bar";
                    }
                    ?>
                    <li><a class="navbar-item" href="info">Info</a></li>
                </ul>
            </nav>
            <div id="page">
                <div id="sidebar">
                        <ul>
                            <?php
                            foreach ($hosts as $host) {
                                ?>
                                <li>
                                    <a href="<?=$host->getLink()?>" target="_blank" class="<?=$host->getName()?> host" title="<?=$host->getName()?>">
                                        <p><?=$host->getName()?></p>
                                    </a>
                                </li>
                                <?php
                            }
                            ?>
                        </ul>
                </div>
                <div id="content">
                        <?php
                        if (count($vd->getErrorMessages()) != 0) {
                            ?>
                            <div class="error">
                                <ul>
                                <?php
                                $errors = $vd->getErrorMessages();
                                foreach ($errors as $error) {
                                    ?>
                                    <li><?=$error?></li>
                                    <?php
                                }
                                ?>
                                </ul>
                            </div>
                            <?php
                        }
                        ?>
                        <?php
                        $content = $vd->getContent();
                        if (isset($content)) {
                            require "$content";
                        }
                        ?>
                    </div>
                <div id="af" class="clear"></div>
            </div>                
            <footer>
                <p>
                    Progetto di Amministrazione di Sistema 2015<br/>
                    Autore: Mattia Atzeni - Matricola: 48958
                </p>
                <div class="validator">
                    <p>
                        <a href="http://validator.w3.org/check/referer" class="xhtml" title="Questa pagina contiene HTML valido" target="_blank">
                            <abbr title="eXtensible HyperText Markup Language">HTML</abbr> Valido</a>
                        <a href="http://jigsaw.w3.org/css-validator/check/referer" class="css" title="Questa pagina ha CSS validi" target="_blank">
                            <abbr title="Cascading Style Sheets">CSS</abbr> Valido</a>
                    </p>
                </div>
            </footer>
        </body>
    </html>
    <?php
    
} else {
    
    header('Cache-Control: no-cache, must-revalidate');
    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
    header('Content-type: application/json');
    
    $content = $vd->getContent();
    require "$content";
    
}
?>

