<?
require_once('../../config.php');

$action = $_GET['act'];

switch($action){
    case'add':{


?>

<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <?=$STYLES?>
    <title>Заполнение дня</title>
</head>
<body>
<?/*Informator*/include("/../../main_scripts/informator.php")?>
<div id="container_maxi">
    <div id="content_box">
        <div id="shell">
            <h3>Заполнить день</h3>
            <form method="post">



                <label for="date">Выберите дату</label><br>
                <input type="date" name="date" id="date" required>
                <br><br>
                <label for="content"></label>
                <textarea name="content" id="content" autofocus required></textarea>
                <br><br>
                <input type="submit" name="add" class="action_button" value="Сохранить">
            </form>
        </div>
    </div>
    <? include("../../user/sidebar.php") ?><!--SIDEBAR PLACE-->
</div>
</body>
</html>
<?
if(isset($_POST['add'])&& !empty($_POST['add'])){

    /*if(!empty($_POST['date'])){

    }*/
    //TODO:DateChecker



    if(!empty($_POST['content'])){
        $content = $_POST['content'];
        $content = mysqli_real_escape_string($connect_user_db, $content);
    }
    else $content = "";

    //Работа со временем
    $creation_datetime = time();

    $query = mysqli_query($connect_user_db,"INSERT INTO `module_scheduler` SET `content`='$content', `user_id`='$config_cookie_id',`date`='".$_POST['date']."', `creation_datetime` ='$creation_datetime', `modification_datetime` ='$creation_datetime'  ");
    echo("<html><script>window.location = 'scheduler.php'</script></html>");
}
}break;


case'edit':{
    if($_GET['id']) $id = $_GET['id'];
               else go_back();
    $info = mysqli_query($connect_user_db,"SELECT * FROM `module_scheduler` WHERE `user_id`='$config_cookie_id' AND `id`='$id' ");
    $row = mysqli_fetch_array($info);
    ?>
    <!DOCTYPE html>
    <html>
    <head lang="en">
        <meta charset="UTF-8">
        <?=$STYLES?>
        <title></title>
    </head>
    <body>
    <?/*Informator*/include("/../../main_scripts/informator.php")?>
    <div id="container_maxi">
        <div id="content_box">
            <div id="shell">
                 <h3>Редактирование</h3>
                 <form method="post">
                     <label for="content"></label>
                     <textarea name="content" id="content" autofocus><?=$row['content']?></textarea>
                     <br><br>
                     <input type="submit" name="edd" class="action_button" value="Сохранить изменения">
                 </form>
                </div>
            </div>
        <? include("../../user/sidebar.php") ?><!--SIDEBAR PLACE-->
        </div>
    </body>
    <?
    if(isset($_POST['edd'])&& !empty($_POST['edd'])){
        if(!empty($_POST['content'])){
            $content = $_POST['content'];
            $content = mysqli_real_escape_string($connect_user_db, $content);
            $modification_datetime = time();
            $query = mysqli_query($connect_user_db,"UPDATE  `module_scheduler` SET  `content` = '$content', `modification_datetime`='$modification_datetime' WHERE `user_id`='$config_cookie_id' AND `id`='$id'");
        }
        else $query = mysqli_query($connect_user_db,"DELETE FROM `module_scheduler` WHERE `user_id`='$config_cookie_id' AND `id`='$id' LIMIT 1");



        echo("<html><script>window.location = 'scheduler.php'</script></html>");
    }
} break;

default:echo("<html><script>window.location = 'scheduler.php'</script></html>");
}
?>