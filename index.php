<?php
/**
 * Created by PhpStorm.
 * User: Michael
 * Date: 30.12.2015
 * Time: 22:20
 */

//echo("<html><script>window.location = '/login.php'</script></html>");


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Blacknote</title>
    <link rel="stylesheet" type="text/css" href="templates/main_style.css" media="all">
</head>
<body>
<?/*Informator*/ include("/main_scripts/informator.php")?>
<div id="container_mini" style="">
    <div id="logotype_mini" style=""></div>

    <?php if(!isset($_COOKIE['id']))://Если демка не начата
        ?>
        <div style="margin: 0 auto; width:100%;" >
            <h1 style='width: auto; height: 60px;padding-top: 10px; '>Добро пожаловать</h1>
            <p style='width: auto;'>«Блэкнот» - это Web-сервис, основная цель которого - максимально простое и удобное хранение  пользовательской информации</p>
            <p style='width: auto;'>Первыми доступными модулями станут «Заметки», «Дневник» и «Ежедневник»</p>
            <p style='width: auto;'>В данный момент проект находится на стадии Альфа-версии</p>
            <p style='width: auto;'>Из наиболее законченной части проекта сделана демонстрационная версия.<br>
                Вы можете попробовать её, кликнув по кнопке ниже.</p>

            <hr>
            <!--<a href="try_demo.php">--><div class="action_button" style="   box-sizing: border-box;
            width:40%;
            text-align: center;
            min-width: 240px;
          ">Попробовать демо-версию</div><!--</a>-->
            <a href="login.php"><div class="action_button" style="   box-sizing: border-box;
            width:40%;
            text-align: center;
            min-width: 240px;
          ">Авторизоваться</div></a>
        </div>
    <?php else://Если демка начата?>
        <h1 style="    width: auto;
    height: 60px;
    padding-top: 10px;">Демо-версия активирована</h1>
         <hr>
        <a href="user/cabinet.php">

            <div class="action_button" style="   box-sizing: border-box;
        width:40%; min-width: 240px;
        text-align: center; ">Вернуться в демо-версию</div></a>
    <?php endif?>
</div>
</body>
</html>