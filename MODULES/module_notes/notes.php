<?php
/**
 * Created by PhpStorm.
 * User: Kastiel
 * Date: 10.02.2016
 * Time: 23:54
 * Version: 1.0
 */
 
if(!isset($_COOKIE['id'])) {
    echo("<html><script>window.location = '../../index.php'</script></html>");
    //die();
    }

    include ("../../config.php");
    include ("functions.php");
	
	//Подключение к базе данных конкретного пользователя
	$connect_user_db = mysqli_connect(CONFIG_HOST_NAME, CONFIG_DB_USER_NAME, CONFIG_DB_USER_PASSWORD, CONFIG_MAIN_DB_NAME);
	if (!$connect_user_db){
		die('Ошибка подключения (' . mysqli_connect_errno() . ') ' . mysqli_connect_error());
	}
	
	$STYLES = " <link href='../../templates/main_style.css' media='screen' rel='stylesheet'>
				<link href='templates/module_notes.css' media='screen' rel='stylesheet'>
				<script type='text/javascript' src='js/jquery-2.2.3.min.js'></script>
				<script type='text/javascript' src='js/floating_button_row.js'></script>
				<script type='text/javascript' src='../../main_scripts/modal_dialog_message.js'></script>
				";


    if(isset($_GET['act'])){
        $action = $_GET['act'];
        //$folder_id = $_GET['folder_id'];

        switch($action){
            case'view':{
                if(isset($_GET['id'])) $id = $_GET['id'];
                    else  go_back();
                //Запрос по содержимому заметки
                $result = mysqli_query($connect_user_db,"SELECT * FROM `module_notes` WHERE `id`='$id'  AND `user_id`='$config_cookie_id' LIMIT 1");
                $row = mysqli_fetch_array($result);
                if($row['user_id'] != $config_cookie_id)
                    go_back();

                if($row['name']){
                  $note_name = $row['name'];
                } else if ($row['content']) {
                    $note_name = $row['content'];
                  } else $note_name = "Пустая заметка";
                $creation_datetime = date("d.m.y H:i:s",$row['creation_datetime']);

                if($row['modification_datetime']){
                    $modification_datetime = date("d.m.y H:i:s",$row['modification_datetime']);
                } else  $modification_datetime = 'Не редактировалось';

                //Заголовок заметки
                if(($row['folder']))
                    $header = "<span>".$row['folder_name']."/</span> ".$note_name;
                    else $header = $note_name;

                //Вывод nothing to show, если поле content пустое
                if(($row['content']))
                    $content = "<br><pre>".$row['content']."</pre><br>";
                    else $content = "<br><p class='message'>Заметка пуста</p>";

                //Работа с папками
                $result = mysqli_query($connect_user_db, "SELECT * FROM `module_notes_folders` WHERE `user_id`='$config_cookie_id' ORDER BY `name` ASC");
                $folders_list = "";
                while($frow = mysqli_fetch_array($result)){
                    $folders_list .= "<option value='".$frow['id']."'>".$frow['name']."</option>";
                }
                ?>
                    <!DOCTYPE html>
                    <html>
                    <head lang="en">
                        <meta charset="UTF-8">
                        <?=$STYLES?>
                        <title id="folder_name"><?=$note_name?></title>
                    </head>
                    <body>
                    <?/*Informator*/include("/../../main_scripts/informator.php");?>
                    <script>
                        jQuery(function($) {
                            //Вызываю функцию окна удаления заметки
                            var trigger_id = 'show_delete_note_form',
                                form_name = 'delete_note_form',
                                form_method = "POST",
                                form_content = "<div class=\'title\'><h2>Удаление</h2></div><form method=\'POST\' id=\'delete_note\'><p>Вы действиетельно хотите удалить<br>«<?=$note_name?>»?<br>Это действие необратимо.</p><hr><div style=\'display: flex;\'><input type=\'hidden\' id=\'hidden_id\' value=\'<?=$row['id']?>\' name=\'hidden_id\' > <input type=\'submit\' id=\'delete_note\' name=\'delete_note\' class=\'action_button\' style=\'box-sizing: border-box;text-align: center;\' value=\'Удалить\'> <div class=\'cancel_button\' id=\'"+form_name+"_close\' style=\'MARGIN-Left: 20px; box-sizing: border-box;text-align: center;\'>Отмена</div></div></form> ";
                            Dialog_Message_Create(trigger_id,form_name,form_content);

                            $('body').on('submit','#delete_note', function(e){
                                e.preventDefault();
                                var delete_note_data ={
                                    'id': $("#hidden_id").val()
                                };
                                $.ajax({
                                    type:'POST',
                                    url:'functions.php',
                                    dataType:'json',
                                    data:"submit_delete_note="+JSON.stringify(delete_note_data),
                                    success: function (sup) {
                                        if(sup['res'] == '1' ){
                                            if(sup['folder'] == '0'){
                                            window.location = 'notes.php';
                                            } else {
                                                window.location ='notes.php?act=view_folder&id='+sup['folder'];

                                            }
                                        }else {
                                            alert("ERROR!");
                                        }
                                    }
                                });
                            });
                        });
                    </script>
                    <script>
                        jQuery(function($) {
                            //Вызываю функцию окна -> перемещение заметки
                            var trigger_id = 'show_move_note_form',
                                form_name = 'move_note_form',
                                form_method = "POST"

                            if(<?=$row['folder']?> != 0){
                               var  form_content = "<div class=\'title\'>" +
                                   "<h2>Перемещение</h2>" +
                                   "</div>" +
                                   "<form method=\'POST\' id=\'move_note\'><br>" +
                                   "<input type=\'radio\' name =\'action_type\' id=\'action_type_in_folder\' value=\'in_folder\' required>"+
                                   "<label for='action_type_in_folder'>В другую папку</label><br>"+
                                        "<select style=\'display:none;\' name=\'folder\' id=\'folders_list\'>" +
                                        "<?=$folders_list?>"+
                                        "</select>"+

                                   "<input type=\'radio\' name =\'action_type\' id=\'action_type_on_main_page\' value=\'on_main_page\'>"+
                                   "<label for='action_type_on_main_page'>Вынести из папки</label>"+

                                   "<hr><div style=\'display: flex;\'><input type=\'hidden\' id=\'hidden_id\' value=\'<?=$row['id']?>\' name=\'hidden_id\' > <input type=\'submit\' id=\'delete_note\' name=\'delete_note\' class=\'action_button\' style=\'box-sizing: border-box;text-align: center;\' value=\'Переместить\'> <div class=\'cancel_button\' id=\'"+form_name+"_close\' style=\'MARGIN-Left: 20px; box-sizing: border-box;text-align: center;\'>Отмена</div></div>" +
                                   "</form> ";
                                //Для показа скрытия select листа
                                $('body').on('click','#action_type_in_folder',function(){
                                    $("#folders_list").css('display', 'block');
                                });
                                $('body').on('click','#action_type_on_main_page',function(){
                                    $("#folders_list").css('display', 'none');
                                });
                            }

                            else{ var form_content = "<div class=\'title\'>" +
                                "<h2>Перемещение</h2>" +
                                "</div>" +
                                "<form method=\'POST\' id=\'move_note\'>" +
                                "<p>В папку <select name=\'folder\' id=\'folders_list\'><br>" +
                                "<?=$folders_list?>"+
                                "</select></p><hr><div style=\'display: flex;\'><input type=\'hidden\' id=\'hidden_id\' value=\'<?=$row['id']?>\' name=\'hidden_id\' > <input type=\'submit\' id=\'delete_note\' name=\'delete_note\' class=\'action_button\' style=\'box-sizing: border-box;text-align: center;\' value=\'Переместить\'> <div class=\'cancel_button\' id=\'"+form_name+"_close\' style=\'MARGIN-Left: 20px; box-sizing: border-box;text-align: center;\'>Отмена</div></div>" +
                                "</form> ";}

                            Dialog_Message_Create(trigger_id,form_name,form_content);



                            $('body').on('submit','#move_note', function(e) {
                                e.preventDefault();
                                var move_note_data={
                                    'id':$("#hidden_id").val(),
                                    'folder_id':$("#folders_list option:selected").val(),
                                    'action_type': $('input[name="action_type"]:checked').val()
                                };
                               /* var action_type = $('input[name="action_type"]:checked').val();

                                alert(action_type);*/
                               /* var folders_mthf = $("#folders_list option:selected").val();
                                var id_mtfk = $("#hidden_id").val();

                                alert(folders_mthf);
                                alert(id_mtfk);*/
                            $.ajax({
                                type: 'POST',
                                url: 'functions.php',
                                dataType: 'json',
                                data: "submit_move_note="+JSON.stringify(move_note_data),
                                success:function(cb){
                                    if(cb['res'] == 1){
                                        window.location = 'notes.php';

                                    }else{
                                        alert("Возникла ошибка");
                                    }
                                }
                                });
                            });
                        });
                    </script>


                    <div id="container_maxi">
                        <div id="content_box">
                            <h2 id="folder_name"><?=$header?></h2>
                            <!--<hr>-->
                            <!--<div class="buttons_row" id="buttons_row">-->
                            <div class="buttons_row" id="buttons_row">
                                <div>
                                    <? if($row['folder']==0)
                                        echo("<a href='notes.php'><p style='margin-top: 6px;
    line-height: 15px;'>На главную «Заметок»</p></a>");
                                    else echo("<a href='notes.php?act=view_folder&id=".$row['folder']."'><p  style='
    '>Вернуться в папку</p></a>");
                                    ?>
                                 </div>
                                <div>
                                    <a href="notes.php?act=edit&id=<?=$row['id']?>">
                                        <p>Редактировать</p>
                                    </a>
                                </div>

                                <div>
                                    <a id="show_move_note_form">
                                        <p>Переместить</p>
                                    </a>
                                </div>
                               <!-- <div>
                                    <?/* if(($row['folder']))
                                        echo("<a href ='notes.php?act=move_from_folder&id=".$row['id']."'><p>Вынести из папки</p></a>");
                                    else echo("<a id='show_move_in_or_from_folder'



                                    ><p style='    margin-top: 6px;
    line-height: 15px;'>Переместить в папку</p></a>");
                                    */?>
                                </div>-->

                                <div class="button_delete">
                                    <a id="show_delete_note_form">
                                        <p>Удалить</p>
                                    </a>
                                </div>

                            </div>

                            <?=$content?>



                            <div id="infobar">
                                <span>Время создания: <?=$creation_datetime?></span>
                                <br>
                                <span>Последнее изменение: <?=$modification_datetime?> </span>
                            </div>
                            <br>


                        </div>
                        <? include("../../user/sidebar.php") ?><!--SIDEBAR PLACE-->
                    </div>
                    </body>
                    </html>
                <?
                break;}
            case'add':{
                if (isset($_GET['folder_id'])){
                        $folder_id = $_GET['folder_id'];
                        $folder_name_query = mysqli_query($connect_user_db,"SELECT `name` FROM `module_notes_folders` WHERE `id`='$folder_id'");
                        $row = mysqli_fetch_array($folder_name_query);
                        $folder_name   = mysqli_real_escape_string($connect_user_db, $row['name']);
                    }
                else{
                    $folder_id = 0;
                    $folder_name = '';
                }


                ?>
                    <!DOCTYPE html>
                    <html>
                    <head lang="en">
                        <meta charset="UTF-8">
                        <?=$STYLES?>
                        <title>Новая заметка</title>
                    </head>
                    <body>
                    <?/*Informator*/include("/../../main_scripts/informator.php")?>
                    <div id="container_maxi">
                        <div id="content_box">
                            <div id="shell">
                            <h3>Новая заметка</h3>
                            <form method="post">
                                <label for="name">Название</label><br>
                                <input type="text" name="name" id="name" autofocus>
                                <br><br>
                                <label for="content"></label>

                                <textarea name="content" id="content" ></textarea>

                                <br><br>
                                <div class="buttons_row_bottom">
                                    <input type="submit" name="add_note" class="action_button" value="Добавить заметку">
                                  <!--  <div class="action_button">Отмена</div>-->
                                </div>
                            </form>
                             </div>
                          </div>
                         <? include("../../user/sidebar.php") ?><!--SIDEBAR PLACE-->
                        </div>
                    </body>
                    </html>
                <?
                if(isset($_POST['add_note']) && !empty($_POST['add_note'])){


                    if(!empty($_POST['name'])){
                        $name = mysqli_real_escape_string($connect_user_db,$_POST['name']);
                        /*$check_query = mysqli_query($connect_user_db,"SELECT `name` FROM `module_notes` WHERE `name` = '$name'");
                        $check_row = mysqli_fetch_array($check_query);
                        if($check_row)
                            //echo("<html><script>window.location = 'notes.php?act=add'</script></html>");
                            exit("Заметка с таким именем уже существует. Выберите другое имя.");*/
                    }
                    else {
                        $name = '';
                        //Поиск по первой новой заметке
                        /*$check_query = mysqli_query($connect_user_db,"SELECT `name` FROM  `module_notes` WHERE  `name` =  'Новая заметка' LIMIT 0,1");
                        $check_row = mysqli_fetch_array($check_query);
                        if(!$check_row)
                            $name = 'Новая заметка';
                        else{
                        //Поиск по последующим новым заметкам типа 'Новая заметка ($s)'
                            $s = 2;
                            while($check_row){
                                $check_query = mysqli_query($connect_user_db,"SELECT `name` FROM  `module_notes` WHERE  `name` =  'Новая заметка ($s)' LIMIT 0,1");
                                $check_row = mysqli_fetch_array($check_query);
                                $s++;
                            }
                            $s = $s-1;
                            $name = "Новая заметка (".$s.")";
                        }*/
                    }

                    if(!empty($_POST['content'])){
                        $content = $_POST['content'];
                        $content = mysqli_real_escape_string($connect_user_db, $content);
                    }
                    else $content = "";

                    //Работа со временем
                    $creation_datetime = time();



                    $query = mysqli_query($connect_user_db,"INSERT INTO `module_notes` SET `name` = '$name', `content` = '$content', `folder`='$folder_id',`folder_name`='$folder_name', `creation_datetime` ='$creation_datetime',`user_id`='$config_cookie_id' ");
                    //редирект
                   // echo("notes.php?act=view_folder&id=$folder_id");
                    if ($folder_id)echo("<html><script>window.location= 'notes.php?act=view_folder&id=$folder_id'</script></html>");
                    else go_back();

           }
                break;}
            case'edit':{
                if($_GET['id']) $id = $_GET['id'];
                    else  go_back();
                $info = mysqli_query($connect_user_db,"SELECT * FROM `module_notes` WHERE `id`='$id'");
                $row = mysqli_fetch_array($info);
                if($row['user_id'] != $config_cookie_id)
                    go_back();
                ?>
                <!DOCTYPE html>
                <html>
                <head lang="en">
                    <meta charset="UTF-8">
                    <?=$STYLES?>
      <!--              <script type='text/javascript' src='js/jquery-2.1.1.js'></script>
                    <script type='text/javascript' src='js/autoresize.js'></script>
                    <script type="text/javascript">
                        jQuery(function()
                        {
                            jQuery('textarea').autoResize();
                        });
                    </script>-->
                    <title>Редактирование</title>
                </head>
                <body>
                <?/*Informator*/include("/../../main_scripts/informator.php")?>
                <div id="container_maxi">
                    <div id="content_box">
                        <div id="shell">
                            <h2>Редактирование</h2>
                            <form method="post">
                                <label for="name">Название</label><br>
                                <input type="text" name="name" id="name" value="<?=$row['name']?>">
                                <br><br>
                                <label for="content"></label>
                                <textarea name="content" id="content" autofocus><?=$row['content']?></textarea>
                                <br><br>
                                <input type="submit" class="action_button" name="edd_note" value="Сохранить изменения">
                            </form>
                        </div>
                    </div>
                    <? include("../../user/sidebar.php") ?><!--SIDEBAR PLACE-->
                </div>
                </body>
                </html>
                <?
                if(isset($_POST['edd_note'])&& !empty($_POST['edd_note'])){
                    if(!empty($_POST['name'])){
                        $name = $_POST['name'];
                        $name =  mysqli_real_escape_string($connect_user_db, $name);
                    }
                    else $name = "";


                    if(!empty($_POST['content'])){
                        $content = $_POST['content'];
                        $content = mysqli_real_escape_string($connect_user_db, $content);
                    }
                    else $content = "";

                    //работа со временем
                    $modification_datetime = time();

                    $query = mysqli_query($connect_user_db,"UPDATE  `module_notes` SET `name` = '$name', `content` = '$content', `modification_datetime`='$modification_datetime' WHERE `id`='$id'");
                    echo("<html><script>window.location = 'notes.php?act=view&id=$id'</script></html>");
                }
                break;}

            //По части папок
            case'view_folder':{
                if($_GET['id']) $id = $_GET['id'];
                else  go_back();
                    $result = mysqli_query($connect_user_db,"SELECT * FROM `module_notes_folders` WHERE `id`='$id'");
                    $row = mysqli_fetch_array($result);
                    if($row['user_id'] != $config_cookie_id)
                        go_back();
                    $info_query = mysqli_query($connect_user_db ,"SELECT COUNT(*) FROM `module_notes` WHERE `user_id`='$config_cookie_id' AND `folder`='$id'");
                    $info = mysqli_fetch_array($info_query);
                    $content_number = $info[0];
                ?>

                <!DOCTYPE html>
                <html>
                <head lang="en">
                    <meta charset="UTF-8">
                    <?=$STYLES?>
                    <title><?=$row['name']?></title>
                </head>
                <body>
                <?/*Informator*/include("/../../main_scripts/informator.php")?>

                <script>
                    jQuery(function($) {
                        //Вызываю функцию окна -> удаления папки
                        var trigger_id = 'show_delete_folder_form',
                            form_name = 'delete_folder_form',
                            form_method = 'POST',
                            form_content = "<div class=\'title\'>" +
                                            " <h2>Удаление</h2>" +
                                            "</div>" +
                                             "<form method=\'POST\' id=\'delete_folder\'>" +
                                             "<p>Вы действиетельно хотите удалить<br>«<?=$row['name']?>»?<br>Это действие необратимо.<br>Если в папке есть заметки, они будут перенесены на главуню</p><hr><div style=\'display: flex;\'><input type=\'hidden\' id=\'hidden_id\' value=\'<?=$row['id']?>\' name=\'hidden_id\' > <input type=\'submit\' id=\'delete_note\' name=\'delete_note\' class=\'action_button\' style=\'box-sizing: border-box;text-align: center;\' value=\'Удалить\'> <div class=\'cancel_button\' id=\'"+form_name+"_close\' style=\'MARGIN-Left: 20px; box-sizing: border-box;text-align: center;\'>Отмена</div></div>" +
                                            "</form> ";
                        Dialog_Message_Create(trigger_id,form_name,form_content);

                        $('body').on('submit','#delete_folder', function(e){
                            e.preventDefault();
                            var delete_folder_data ={
                                'id': $("#hidden_id").val()
                            };
                            $.ajax({
                                type:'POST',
                                url:'functions.php',
                                dataType:'json',
                                data:"submit_delete_folder="+JSON.stringify(delete_folder_data),
                                success: function (cb) {
                                    if(cb['res'] == '1'){
                                        $('#delete_folder_form_close').trigger('click');
                                        window.location = "notes.php";
                                    }else {
                                        alert("ERROR!");
                                    }
                                }
                            });
                        });
                    });
                </script>
                <script>
                    jQuery(function($){
                        //Вызываю функцию окна переименования папки
                        var trigger_id ='show_rename_folder_form',
                            form_name = 'rename_folder_form',
                            form_method = "POST",
                            form_content ="<div class=\'title\'><h2>Переименование папки</h2></div>" +
                                "<form method=\'post\' id=\'rename_folder\'><br><label for=\'name\'></label><input type=\'text\' name=\'name\'  id=\'focus_please\' placeholder=\'<?=$row['name']?>\' > <br> <br> <p id=\'error_message\'></p>" +
                                " <hr> <div style=\'display: flex;\'> <input type=\'submit\' id=\'rename_folder\' name=\'add_folder\' class=\'action_button' style=\'   box-sizing: border-box;text-align: center;\' value=\'Переименовать\'> <div class=\'cancel_button\' id=\'"+form_name+"_close\' style='MARGIN-Left: 20px;   box-sizing: border-box;width:30%;text-align: center;\'>Отмена</div> </div> </form>";

                        Dialog_Message_Create(trigger_id,form_name,form_content);

                        $('body').on('submit','#rename_folder', function(e){
                            e.preventDefault();
                            var rename_folder_data ={
                                'folder_new_name' : $("#focus_please").val(),
                                'id' : $("#hidden_id").val()
                            };
                            /*var test_var = $("#hidden_id").val();
                            alert(test_var);*/
                            $.ajax({
                                type:'POST',
                                url:'functions.php',
                                dataType:'json',
                                data:"submit_rename_folder="+JSON.stringify(rename_folder_data),
                                success: function (cb) {
                                    if(cb['res'] == '1'){
                                        //Закрываю формочку :3
                                        $('#rename_folder_form_close').trigger('click');
                                        //Обновляю содержимое
                                        var folder = cb['new_folder_name'];
                                        $('#folder_name').html(folder);
                                        //Очищаю формочку :3
                                        setTimeout("$('#focus_please').trigger('reset')",1000);
                                    }else {
                                        $('#error_message').html(cb['content']);
                                    }
                                }
                            });
                        });
                    });
                </script>
                <div id="container_maxi">
                    <div id="content_box">
                        <h2 id="folder_name"><?=$row['name']?></h2>
                        <div class="buttons_row" id="buttons_row">
                            <div>
                                <a href="notes.php">
                                    <p style='margin-top: 6px;
    line-height: 15px;'>На главную «Заметок»</p>
                                </a>
                            </div>
                            <div>
                                <a href="notes.php?act=add&folder_id=<?=$id?>">
                                    <p style="">Добавить заметку</p>
                                </a>
                            </div>
                            <div>
                                <a id='show_rename_folder_form'">
                                    <p style="margin-top: 6px;
    line-height: 15px;">Переименовать папку</p>
                                </a>
                            </div>
                            <div class="button_delete">
                                <a id="show_delete_folder_form">
                                    <p>Удалить папку</p>
                                </a>
                            </div>
                        </div>


                            <?php
                            if($content_number == 0){
                               /* echo("<br><p class='message'>nothing to show</p>");*/
                                emptiness_inside($message='Папка пуста.');
                            }
                        else{


                            $result = mysqli_query($connect_user_db, "SELECT * FROM `module_notes` WHERE `folder`='$id' AND `user_id`='$config_cookie_id' ORDER BY `name` ASC");
                            $notes = '';
                            while($row = mysqli_fetch_array($result)){
                                if($row['name']){
                                    $note_name = $row['name'];
                                } elseif($row['content']) {
                                    $note_name = $row['content'];
                                } else $note_name = 'Пустая заметка';
                                $notes .= "<div class='note' title='".$note_name."'><a href='notes.php?act=view&id=".$row['id']."'><div class='decor'></div><p>".$note_name."</p></a></div>";
                            }

                        ?>
                            <div id='content' style='display: flex;    -webkit-flex-wrap: wrap; flex-wrap: wrap;width: 640px; margin-left:30px; margin-right: 30px;'>
                            <?=$notes?>
                            </div>
                    <?
                        }
                      ?>


                    </div>
                    <? include("../../user/sidebar.php") ?><!--SIDEBAR PLACE-->
                </div>
                </body>
                </html>

                <?

                break;}


            default: echo("<html><script>window.location = 'notes.php'</script></html>");
        }
    }
    else {
        ?>
        <!DOCTYPE html>
        <html>
        <head lang="en">
            <meta charset="UTF-8">
            <title>Заметки</title>
            <?=$STYLES?>
        </head>
        <body>
        <?/*Informator*/include("../../main_scripts/informator.php");?>

        <script>
            jQuery(function($) {
                var trigger_id = "show_create_new_folder",
                    form_name = "new_folder_form",
                    form_method ="POST",
                    form_content ="<div class=\'title\'><h2>Новая папка</h2></div><form method=\'post\' id=\'add_folder\'><br><label for=\'name\'></label><input type=\'text\' name=\'name\'  id=\'focus_please\' placeholder=\'Название папки\' > <br> <br> <p id=\'error_message\'></p> <hr> <div style=\'display: flex;\'> <input type=\'submit\' id=\'add_folder\' name=\'add_folder\' class=\'action_button' style=\'   box-sizing: border-box;text-align: center;\' value=\'Создать\'> <div class=\'cancel_button\' id=\'"+form_name+"_close\' style='MARGIN-Left: 20px;   box-sizing: border-box;width:30%;text-align: center;\'>Отмена</div> </div> </form>";
                    Dialog_Message_Create(trigger_id,form_name,form_content);


                $('body').on('submit','#add_folder', function(e){

                    e.preventDefault();//Отключаю действие браузера по умолчанию при отправке формы
                    var add_folder_data ={
                        'folder_name': $("#focus_please").val()
                    };

                    $.ajax({
                        type: 'POST',
                        url:'functions.php',
                        dataType: 'json',
                        data: "submit_create_new_folder="+JSON.stringify(add_folder_data),
                        success:function(sup){
                            if(sup['res'] == '1'){
                                //Закрываю формочку :3
                                $('#new_folder_form_close').trigger('click');

                                if($("p").is(".message")){
                                    $('#message_of_emptiness').css("display","none");
                                }
                                //Отрисовываю по новому всё
                                $('#content').html(sup['content']);
                                //Очищаю формочку :3
                                setTimeout("$('#add_folder').trigger('reset')",1000);
                            }
                            else{
                                $('#error_message').html(sup['content']);
                            }
                        }

                    });
                });
            });
        </script>
        <div id="container_maxi">
            <div id="content_box">

                <h2 style="text-align: center">Заметки</h2>
                <div class="buttons_row" id="buttons_row">
                    <div>
                        <a href="notes.php?act=add">
                            <p>Создать заметку</p>
                        </a>
                    </div>
                    <div>
                        <!--<a href="notes.php?act=add_folder">-->
                        <a id="show_create_new_folder">
                            <p>Создать папку</p>
                        </a>
                    </div>

                </div>
                <?php
                $info_query = mysqli_query($connect_user_db ,"SELECT COUNT(*) FROM `module_notes` WHERE `user_id`='$config_cookie_id' ");
                $info = mysqli_fetch_array($info_query);
                $content_number = $info[0];

                $info_query = mysqli_query($connect_user_db ,"SELECT COUNT(*) FROM `module_notes_folders` WHERE `user_id`='$config_cookie_id'");
                $info = mysqli_fetch_array($info_query);
                $folders_number = $info[0];

                if($content_number == 0 && $folders_number ==0){
                    emptiness_inside($message="Сейчас здесь пусто<br>Создайте заметку или папку и это изменится ;)");
                }
                else{

                    //Запрос по папкам
                    $result = mysqli_query($connect_user_db, "SELECT * FROM `module_notes_folders`WHERE `user_id`='$config_cookie_id'  ORDER BY `name` ASC");
                    //Вывод папок
                    $folders = '';
                    while($row = mysqli_fetch_array($result)){
                        $folders .= "<div class='folder' title='".$row["name"]."'><a href='notes.php?act=view_folder&id=".$row["id"]."'><div class='decor'></div><p>".$row['name']."</p></a></div>";
                    }

                    //Запрос по заметкам
                    $result = mysqli_query($connect_user_db, "SELECT * FROM `module_notes` WHERE `user_id`='$config_cookie_id' AND `folder`='0' ORDER BY `name` ASC");
                    //Вывод заметок
                    $notes = '';
                    while($row = mysqli_fetch_array($result)){
                        if($row['name']){
                            $note_name = $row['name'];
                        } elseif($row['content']) {
                            $note_name = $row['content'];
                        } else $note_name = 'Пустая заметка';
                        $notes .= "<div class='note' title='".$note_name."'><a href='notes.php?act=view&id=".$row['id']."'><div class='decor'></div><p>".$note_name."</p></a></div>";
                    }
                    $content = $folders.$notes;
                }
                ?>
                <div id='content' style='display: flex;    -webkit-flex-wrap: wrap; flex-wrap: wrap;width: 640px; margin-left:30px; margin-right: 30px;'>
                    <?=$content?>
                </div>
            </div>
            <?include("../../user/sidebar.php")?><!--SIDEBAR PLACE-->
        </div>
        </body>
        </html>
    <?php

    }


?>