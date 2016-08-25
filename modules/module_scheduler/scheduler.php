<?php
/**
 * Created by PhpStorm.
 * User: админ
 * Date: 02.04.2016
 * Time: 18:49
 */
/* FUNCTIONS */
function go_back(){
    echo("<html><script>window.location = 'scheduler.php'</script></html>");
    exit();
}

if(!isset($_COOKIE['id'])) {
    echo("<html><script>window.location = '../../login.php'</script></html>");
    die;
}

require_once('../../config.php');

include "scripts/TimeHelper.php";
use \korytoff\helpers\TimeHelper;

//Подключение к базе данных конкретного пользователя
$connect_user_db = mysqli_connect(CONFIG_HOST_NAME, CONFIG_DB_USER_NAME, CONFIG_DB_USER_PASSWORD, CONFIG_MAIN_DB_NAME);
if (!$connect_user_db){
    die('Ошибка подключения (' . mysqli_connect_errno() . ') '
        . mysqli_connect_error());
}
else{
    $STYLES = " <link href='../../templates/main_style.css' media='screen' rel='stylesheet'>
                <link href='templates/module_scheduler.css' media='screen' rel='stylesheet'>
                <script type='text/javascript' src='js/jquery-2.2.3.min.js'></script>
                 <script type='text/javascript' src='js/floating_button_row.js'></script>
                ";
 if($_GET['act']) {
    include("functions.php");
    }
    else {
?>
<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title>Ежедневник</title>
    <link href='../../templates/main_style.css' media='screen' rel='stylesheet'>
   <link href='templates/module_scheduler.css' media='screen' rel='stylesheet'>
    <script type='text/javascript' src='js/jquery-2.2.3.min.js'></script>
    <script type='text/javascript' src='js/floating_button_row.js'></script>
</head>
<body>
<?/*Informator*/include("/../../main_scripts/informator.php")?>
    <div id="container_maxi">

        <div id="content_box">
                  <h2>Ежедневник</h2>
                <div class="buttons_row" id="buttons_row">
                    <div>
                        <a href="scheduler.php?act=add">
                            <p>Заполнить день</p>
                        </a>
                    </div>
                    <div>
                        <a href="#today">
                            <p>Сегодня</p>
                        </a>
                    </div>

                </div>
                <div style="margin-right:40px; margin-left:40px;">
                <?php
                $info_query = mysqli_query($connect_user_db ,"SELECT COUNT(*) FROM `module_scheduler`");
                $info = mysqli_fetch_array($info_query);
                $content_number = $info[0];


                if($content_number == 0){

                    echo("<br><p class='message'>nothing to show</p>");
                }else{
                    $result = mysqli_query($connect_user_db,'SELECT *,DATE_FORMAT(date,"%w %e %b")as newDateShort ,DATE_FORMAT(date,"%d.%m.%Y")as newDateLong  FROM `module_scheduler`  ORDER BY `date` DESC ');

                    ?>
                    <br>
                    <?
                    while($row = mysqli_fetch_array($result)){

                        $date = TimeHelper::create($row['date']." 00:00:00")->today();
                        $weekday = Timehelper::create($row['date']." 00:00:00")->dayStr();
                        if (strcmp($date,"Сегодня")==0){
                            $day = "<a id='today' style='display: block; position: absolute;     margin-top: -240px;'></a>";
							$date = "<span style='font-weight:600;'>".$date."</span>";
						}
                        if ((strcmp($weekday,"Сб")==0)||(strcmp($weekday,"Вс")==0)){
                            $week_class = 'weekend';
                            $header_style = 'border-bottom:1px solid #c0f0d4;';
                        }else{
                            $week_class = 'weekday';
                            $header_style = '';
                        }
							
                        ?>
                        <div class="day">
                            <h5 style="<?=$header_style?>">
                                <span class="<?=$week_class?>"><?=$weekday?></span> <span title="<?=$row['newDateLong']?>">  <?=$date?></span> <a href="scheduler.php?act=edit&id=<?=$row['id']?>">редактировать</a>
                            </h5>  <?=$day?>
                            <pre class="content"><?=$row['content']?></pre>
                            <br>
                        </div>
                    <?
                    }
                }
                ?>
            </div>
        </div>
        <? include("../../user/sidebar.php") ?><!--SIDEBAR PLACE-->

    </div>
</body>
</html>
<?
    }
}
?>