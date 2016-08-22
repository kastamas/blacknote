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

if(!isset($_COOKIE['id']))
    echo("<html><script>window.location = '../../login.php'</script></html>");
include "scripts/TimeHelper.php";
use \korytoff\helpers\TimeHelper;

if(1==1){
    echo("lol");
}
else{




echo("test");



echo TimeHelper::create('2014-11-07 22:12:00')->today();
}
?>