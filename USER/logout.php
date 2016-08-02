<?php
/**
 * Created by PhpStorm.
 * User: Kastiel
 * Date: 08.02.2016
 * Time: 6:05
 */
if(!isset($_COOKIE['id'])) {
    echo("<html><script>window.location = '../index.php'</script></html>");
    die;
}

require_once('../config.php');
$connect = mysqli_connect(CONFIG_HOST_NAME,CONFIG_DB_USER_NAME,CONFIG_DB_USER_PASSWORD,CONFIG_MAIN_DB_NAME);
if (!$connect) {
    die('Ошибка подключения (' . mysqli_connect_errno() . ') '. mysqli_connect_error());
}
/*mysqli_query($connect,"DROP DATABASE `bn_demo_user_".$_COOKIE['id']."` ");//*/

mysqli_query($connect,"DELETE FROM `module_notes` WHERE `user_id`='$config_cookie_id'");
mysqli_query($connect,"DELETE FROM `module_notes_folders` WHERE `user_id`='$config_cookie_id'");

setcookie("id", "", time() -400*60, "/");
setcookie("demo_start_time", "", time()-400*60, "/");
setcookie("tutorial_over", "", time()-400*60, "/");

//if ($_SERVER["REQUEST_URI"] != index.p
echo("<html><script>window.location = '../index.php'</script></html>"); exit();
