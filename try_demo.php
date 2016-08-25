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

 //Добавляю пользователя в таблицу kek
 $registration_datetime = time();
 mysqli_query($connect, "INSERT INTO `users` SET `reg_date`='$registration_datetime'");

//Создаю куки для демо-пользователя (400 минут) (На время действия сессии)
setcookie("demo_start_time", time(), 0);
setcookie("tutorial_over", 0, 0);
setcookie("id", $AI_value, 0);

//И редирект в кабинет
echo("<html><script>window.location = 'user/cabinet.php'</script></html>");