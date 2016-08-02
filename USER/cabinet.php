<?php
if(!isset($_COOKIE['id'])) {
    echo("<html><script>window.location = '../index.php'</script></html>");
    die;
}

//define("user_rights", "");
/*
if (!$connect_user_db)
    die('Ошибка подключения (' . mysqli_connect_errno() . ') ' . mysqli_connect_error());*/
require_once("../config.php");
?>
<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title>Кабинет</title>
    <link href='../templates/main_style.css' media='screen' rel='stylesheet'>
    <link href='../MODULES/module_notes/templates/module_notes.css'  media='screen' rel='stylesheet'>
    <script type='text/javascript' src='../js/jquery-2.2.3.min.js'></script>
    <script type='text/javascript' src='../js/modal_message.js'></script>
</head>
<body>
<?/*Informator*/include("../main_scripts/informator.php")?>
<div id="background_shadow"></div>
 <div id="modal_message_greeting" class="modal_div_center">
            <span class="modal_close_dark" id="modal_message_greeting_close">x</span>
            <p class="modal_message" style="">
                Добро пожаловать в демо-версию Блэкнота<br>
                В данный момент активен только модуль «Заметки»<br>
                Перейти в него можно через сайдбар в левой части сайта<br>
            </p>
            <br>
            <div class="action_button" id="modal_message_greeting_close" style="   box-sizing: border-box;width:30%;text-align: center;">Хорошо</div>
        </div>
<div id="container_maxi">
    <div id="content_box">
        <!--ФОРМА ОТПРАВКИ СООБЩЕНИЯ В ОКНЕ-->
       <!-- <div id="modal_message_greeting" class="modal_div_center">
            <span class="modal_close" id="modal_message_greeting_close">x</span>
            <p class="modal_message" style="">
                Добро пожаловать в демо-версию Блэкнота<br>
                В данный момент активен только модуль «Заметки»<br>
                Перейти в него можно через сайдбар в левой части сайта<br>
            </p>
            <br>
            <div class="action_button" id="modal_message_greeting_close" style="   box-sizing: border-box;width:30%;text-align: center;">Хорошо</div>
        </div>-->

        <h2 style="text-align: center;     ">Кабинет</h2>
        <?php
         //Виджет заметок
          $connect_user_db = mysqli_connect(CONFIG_HOST_NAME, CONFIG_DB_USER_NAME, CONFIG_DB_USER_PASSWORD, CONFIG_MAIN_DB_NAME);
          if (!$connect_user_db){
             die('Ошибка подключения (' . mysqli_connect_errno() . ') '
                . mysqli_connect_error());
          }

          $info_query = mysqli_query($connect_user_db ,"SELECT COUNT(*) FROM `module_notes` WHERE `user_id`='$config_cookie_id'");
          $info = mysqli_fetch_array($info_query);
          $content_number = $info[0];

          /*$info_query = mysqli_query($connect_user_db ,"SELECT COUNT(*) FROM `module_notes_folders`");
          $info = mysqli_fetch_array($info_query);
          $folders_number = $info[0];*/
        ?>
        <div class="cabinet_widget">
            <h4>Заметки</h4>
        <?php
          if($content_number != 0):{
              $result = mysqli_query($connect_user_db, "SELECT * FROM `module_notes` WHERE `user_id`='$config_cookie_id' ORDER BY `creation_datetime` DESC LIMIT 4");
              $notes = '';
              while($row = mysqli_fetch_array($result)){
                  if($row['name']){
                      $note_name = $row['name'];
                  } elseif($row['content']) {
                      $note_name = $row['content'];
                  } else $note_name = 'Пустая заметка';

                  $notes = $notes."<div class='note' title='".$note_name."'><a href='" . CONFIG_DOMAIN_NAME . "/MODULES/module_notes/notes.php?act=view&id=".$row['id']."'><div class='decor'></div><p>".$note_name."</p></a></div>";
                  //echo("<div class='note' title='".$row["name"]."'><a href='notes.php?act=view&id=".$row['id']."'><div class='decor'></div><p>".$row['name']."</p></a></div>");
              }

          }?>


              <p>Последние добавленные:</p>

               <div style='display: flex;    -webkit-flex-wrap: wrap; flex-wrap: wrap;width: 640px; margin-left:30px; margin-right: 30px;'>
                   <?=$notes?>
              </div>

              <?php else:?>
              <br><p class='message' style='position: relative;'>Сейчас здесь пусто<br>Создайте заметки, и последние из них будут отображаться здесь</p>

          <?php endif;?>
        </div>
        </div>
    <?include("../user/sidebar.php")?><!--SIDEBAR PLACE-->
</div>



</body>
</html>

