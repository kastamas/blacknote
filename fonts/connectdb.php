<?php


$link = mysql_connect('localhost', 'kastamas_bnmain', 'ramp2010', 'kastamas_bnmain');

if (!$link) {
    die('Ошибка подключения (' . mysql_connect_errno() . ') '
            . mysqli_connect_error());
}

echo 'Соединение установлено... ' . mysql_get_host_info($link) . "\n";



if(isset($_SESSION['session_username'])){

$userSESS = ($_SESSION['session_username']);
$userDBname = (bnuser_. $userSESS);

$userlink = mysqli_connect('localhost', 'kastamas_bnmain', 'ramp2010','kastamas_'$userDBname);

if (!$userlink) {
    die('Ошибка подключения (' . mysqli_connect_errno() . ') '
            . mysqli_connect_error());
}

echo 'Соединение установлено... ' . mysqli_get_host_info($link) . "\n";

}


if(isset($_GET['server_root'])){$server_root = $_GET['server_root'];unset($server_root);}
if(isset($_POST['server_root'])){$server_root = $_POST['server_root'];unset($server_root);}
	
$server_root = "http://black-note.ru/";
?>

