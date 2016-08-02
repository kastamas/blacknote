<?php
require_once("config.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Blacknote</title>
    <link rel="stylesheet" type="text/css" href="/templates/main_style.css" media="all">
</head>
<body>
<?/*Informator*/ include("/main_scripts/informator.php")?>
<div id="container_mini">
    <div id="logotype_mini"></div>

    <h1 style='width: 381px;'>Ошибка 404</h1>
    <p style='width: 80%; text-align: left;'>Искомая вами страница была удалена, либо никогда не существовала..</p>
    <br>
    <a href="<?=CONFIG_DOMAIN_NAME?>/index.php"><div class="action_button" style="   box-sizing: border-box;
    width:80%;
    text-align: center;
   ">Вернуться на главную</div></a>
</div>
</body>
</html>