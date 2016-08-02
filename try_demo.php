<?php
/**
 * Created by PhpStorm.
 * User: Neo-pc
 * Date: 17.07.2016
 * Time: 16:10
 */
//Запрос к бд
if(isset($_COOKIE['id'])){
    echo("<html><script>window.location = 'user/cabinet.php'</script></html>");
    die;
}
require_once('config.php');
$connect = mysqli_connect(CONFIG_HOST_NAME, CONFIG_DB_USER_NAME, CONFIG_DB_USER_PASSWORD, CONFIG_MAIN_DB_NAME);
if (!$connect) {
    die('Ошибка подключения (' . mysqli_connect_errno() . ') '
        . mysqli_connect_error());
}
//Запрос auto_increment'а
$query = mysqli_query($connect,"SHOW TABLE STATUS LIKE 'users'");
$array  = mysqli_fetch_array($query);
$AI_value = $array['Auto_increment'];

 //Добавляю пользователя в таблицу
 $registration_datetime = time();
 mysqli_query($connect, "INSERT INTO `users` SET `reg_date`='$registration_datetime'");

//Создаю куки для демо-пользователя (400 минут) (На время действия сессии)
setcookie("demo_start_time", time(), 0);
setcookie("tutorial_over", 0, 0);
setcookie("id", $AI_value, 0);

//И редирект в кабинет
echo("<html><script>window.location = 'user/cabinet.php'</script></html>");



/*$connect = mysqli_connect($config_host_name,$config_db_user_name,$config_db_user_password,'');
if (!$connect) {
    die('Ошибка подключения (' . mysqli_connect_errno() . ') '
        . mysqli_connect_error());
}
for($i=1,$flag=0;$i<9;$i++){
    $user_db_check = mysqli_query($connect,"SHOW DATABASES LIKE 'bn_demo_user_".$i."' ");
    $user_db_check_row = mysqli_fetch_array($user_db_check);
    if($user_db_check_row) {
        continue;//Если база есть, продолжаем
    }
    else{
        $flag = 1;
        break;//Если базы нет, прекращаем выполнение цикла
    }
}

if($flag){//Если есть свободное место под бд, то
    //Создаём базу данных
    mysqli_query($connect,"CREATE DATABASE IF NOT EXISTS `bn_demo_user_".$i."` CHARACTER SET utf8 COLLATE utf8_general_ci ");
    $connect_user_db = mysqli_connect($config_host_name, $config_db_user_name, $config_db_user_password, 'bn_demo_user_'.$i);
    if (!$connect_user_db) {
        die('Ошибка подключения (' . mysqli_connect_errno() . ') '
            . mysqli_connect_error());
    }
    //И ТАБЛИЦЫ:
    //Таблица модуля заметок
    mysqli_query($connect_user_db,"CREATE TABLE IF NOT EXISTS `module_notes` (
              `id` int(4) unsigned NOT NULL AUTO_INCREMENT,
              `content` text NOT NULL,
              `name` varchar(64) NOT NULL,
              `folder` int(2) unsigned NOT NULL,
              `folder_name` varchar(32) NOT NULL,
              `creation_datetime` int(10) unsigned NOT NULL,
              `modification_datetime` int(10) unsigned NOT NULL,
              PRIMARY KEY (`id`),
              UNIQUE KEY `name` (`name`)
            ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;");

    //Таблица папок модуля заметок
    mysqli_query($connect_user_db," CREATE TABLE IF NOT EXISTS `module_notes_folders` (
                `id` int(3) unsigned NOT NULL AUTO_INCREMENT,
          `name` varchar(32) NOT NULL,
          `creation_datetime` int(10) unsigned NOT NULL,
          PRIMARY KEY (`id`),
          UNIQUE KEY `name` (`name`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=utf8;");*/



/*    //Создаю куки для демо-пользователя (400 минут)
    setcookie("id", $i, time()+400*60);
    setcookie("demo_start_time", time(), time()+400*60);
    setcookie("tutorial_over", 0, time()+400*60);
    setcookie("tutorial_over", 0, time()+400*60);//UserId nnada

    //И редирект в кабинет
    echo("<html><script>window.location = 'user/cabinet.php'</script></html>");*/

/*} else{
    */?><!--

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Blacknote</title>
        <link rel="stylesheet" type="text/css" href="templates/main_style.css" media="all">
    </head>
    <body>
    <?/*/*Informator include("/main_scripts/informator.php")*/?>
    <div id="container_mini">
        <div id="logotype_mini"></div>

        <p style='width: 381px;'>К сожалению, в данный момент все места для демонстрации заняты, попробуйте ещё раз через 5-10 минут ;)</p>
        <br>

        <a href="try_demo.php"><div class="action_button" style="   box-sizing: border-box;
    width:80%;
    text-align: center;
   margin: 0 auto;">Попробовать ещё раз</div></a>
    </div>
  </body>
</html>
--><?php
/*}
*/?>