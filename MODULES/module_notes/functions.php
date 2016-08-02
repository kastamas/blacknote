<?php
/* FUNCTIONS */
require_once('../../config.php');

function go_back(){
    echo("<html><script>window.location = 'notes.php'</script></html>");
/*    exit();*/
}

function emptiness_inside($message){
    echo("<br><p class='message' id='message_of_emptiness'>".$message."</p>");
}

//С подключением
$connect_user_db = mysqli_connect(CONFIG_HOST_NAME, CONFIG_DB_USER_NAME, CONFIG_DB_USER_PASSWORD, CONFIG_MAIN_DB_NAME);
if (!$connect_user_db){
    die('Ошибка подключения (' . mysqli_connect_errno() . ') ' . mysqli_connect_error());
}

//Обработчик перемещения заметки
if(isset($_POST['submit_move_note'])){
    //TODO: Заделать отдельную функцию
    $move_note_data = json_decode($_POST['submit_move_note']);

    $id = $move_note_data -> id;
    $folder_id = $move_note_data -> folder_id;

    if($move_note_data -> action_type){//Если есть параметр типа действия
        if($move_note_data -> action_type == "in_folder"){
            $folder_name_query = mysqli_query($connect_user_db,"SELECT `name` FROM `module_notes_folders` WHERE `user_id`='$config_cookie_id' AND `id`='$folder_id'");
            $row = mysqli_fetch_array($folder_name_query);
            $folder_name = mysqli_real_escape_string($connect_user_db, $row['name']);

            $query = mysqli_query($connect_user_db,"UPDATE `module_notes` set `folder`= '$folder_id', `folder_name`='$folder_name'  WHERE `user_id`='$config_cookie_id' AND `id`='$id'");
            if($query) {
                echo json_encode(array("res" => '1'));
                exit();
            }
        }
        else if($move_note_data -> action_type == "on_main_page"){
            $query = mysqli_query($connect_user_db,"UPDATE `module_notes` SET `folder`='0', `folder_name`='' WHERE `user_id`='$config_cookie_id' AND `id`='$id'");
            if($query) {
                echo json_encode(array("res" => '1'));
                exit();
            }
        }

    }

    $folder_name_query = mysqli_query($connect_user_db,"SELECT `name` FROM `module_notes_folders` WHERE `user_id`='$config_cookie_id' AND `id`='$folder_id'");
    $row = mysqli_fetch_array($folder_name_query);
    $folder_name = mysqli_real_escape_string($connect_user_db, $row['name']);

    $query = mysqli_query($connect_user_db,"UPDATE `module_notes` set `folder`= '$folder_id', `folder_name`='$folder_name'  WHERE `user_id`='$config_cookie_id' AND `id`='$id'");
    if($query){
        /*if($move_note_data -> action_type)
            file_put_contents("Lal.txt", $move_note_data -> id);
        else  file_put_contents("Lal.txt", "как и должно быть");*/
        echo json_encode(array("res" => '1'));
        exit();
    }else{
        echo json_encode(array("res" => '0'));
        exit();
    }
}

//Обработчик удаления заметки
if(isset($_POST['submit_delete_note'])){
    $delete_note_data = json_decode($_POST['submit_delete_note']);
    $id = $delete_note_data -> id;
    //Проверка на наличие папки
    $check_query = mysqli_query($connect_user_db,"SELECT `folder` FROM `module_notes` WHERE `user_id`='$config_cookie_id' AND `id`='$id' LIMIT 1");
    $row = mysqli_fetch_array($check_query);

    $query = mysqli_query($connect_user_db,"DELETE FROM `module_notes` WHERE `id`='$id' AND `user_id` = '$config_cookie_id' LIMIT 1");
    if($query){
        if($row['folder']!=0){
            echo json_encode(array("res" => '1', "folder" => $row['folder']));
            exit();
        }else{
             echo json_encode(array("res" => '1', "folder" => '0'));
             exit();
        }
    }
    else{
        echo json_encode(array("res" => '0'));
         exit();
    }
}

//Обработчик создания папки (Новый)
if (isset($_POST['submit_create_new_folder'])){

    $sign_in_data = json_decode($_POST['submit_create_new_folder']);

    if(!empty($sign_in_data->folder_name)) {
        $name = mysqli_real_escape_string($connect_user_db,$sign_in_data->folder_name);
        $check_query = mysqli_query($connect_user_db,"SELECT `name` FROM `module_notes_folders` WHERE `user_id`='$config_cookie_id' AND `name` = '$name'");
        $check_row = mysqli_fetch_array($check_query);
        if($check_row){
            echo json_encode(array("res" => "0","content" =>"Папка с таким именем уже существует.<br> Выберите другое имя."));
            exit();
        }
    }else {
        $check_query = mysqli_query($connect_user_db,"SELECT `name` FROM  `module_notes_folders` WHERE `user_id`='$config_cookie_id' AND  `name` =  'Новая папка' LIMIT 0,1");
        $check_row = mysqli_fetch_array($check_query);
        if(!$check_row)
            $name = 'Новая папка';
        else{
            $s = 2;
            while($check_row){
                $check_query = mysqli_query($connect_user_db,"SELECT `name` FROM  `module_notes_folders` WHERE `user_id`='$config_cookie_id' AND `name` =  'Новая папка ($s)' LIMIT 0,1");
                $check_row = mysqli_fetch_array($check_query);
                $s++;
            }
            $s = $s-1;
            $name = "Новая папка (".$s.")";
        }
    }
    //Работа со временем
    $creation_datetime = time();

    $query = mysqli_query($connect_user_db,"INSERT INTO `module_notes_folders` SET `name`='$name', `creation_datetime`='$creation_datetime',`user_id`='$config_cookie_id'");
    mysqli_insert_id($connect_user_db);// Id последней записи TODO: по-моему эта строка лишняя

    //Отрисовочка пошла
    //Это идентично тому - что находится на index.php Нужно убрать в отдельную функцию
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
    echo json_encode(array("res" => '1',"content" => $content));

}

//Обработчик удаления папки (Работает)
if(isset($_POST['submit_delete_folder'])){

    $id = json_decode($_POST['submit_delete_folder']) -> id;

    mysqli_query($connect_user_db,"DELETE FROM `module_notes_folders` WHERE `id`='$id'");//Удаляю запись из таблицы папок(удаляю папку)
    mysqli_query($connect_user_db,"UPDATE  `module_notes` SET `folder` = '0', `folder_name`='' WHERE `folder`='$id'");//Перемещаю заметки из удалённой папки на главную.
    echo json_encode(array("res" => '1'));
}


//Обработчик переименования папки
if (isset($_POST['submit_rename_folder'])){

      $rename_folder_data = json_decode($_POST['submit_rename_folder']);
      $id = $rename_folder_data->id;

      if(!empty($rename_folder_data->folder_new_name)) {


          $name = mysqli_real_escape_string($connect_user_db, $rename_folder_data->folder_new_name);
          $check_query = mysqli_query($connect_user_db, "SELECT `name` FROM `module_notes_folders` WHERE `user_id`='$config_cookie_id' AND `name` = '$name'");
          $check_row = mysqli_fetch_array($check_query);

              if ($check_row) {
                  echo json_encode(array("res" => "0", "content" => "Папка с таким именем уже существует.<br> Выберите другое имя."));
                  exit();
              }

          } else {
              $check_query = mysqli_query($connect_user_db, "SELECT `name` FROM  `module_notes_folders` WHERE `user_id`='$config_cookie_id' AND  `name` =  'Новая папка' LIMIT 0,1");
              $check_row = mysqli_fetch_array($check_query);
              if (!$check_row)
                  $name = 'Новая папка';
              else {

                  $s = 2;
                  while ($check_row) {
                      $check_query = mysqli_query($connect_user_db, "SELECT `name` FROM  `module_notes_folders` WHERE `user_id`='$config_cookie_id' AND `name` =  'Новая папка ($s)' LIMIT 0,1");
                      $check_row = mysqli_fetch_array($check_query);
                      $s++;
                  }
                  $s = $s-1;

                  $name = "Новая папка (".$s.")";
                  file_put_contents("sail.txt", $name);
                  $name = mysqli_real_escape_string($connect_user_db, $name);
                  }
              }

      mysqli_query($connect_user_db,"UPDATE `module_notes_folders` SET `name`='$name' WHERE `user_id`='$config_cookie_id' AND `id`='$id'");
      mysqli_query($connect_user_db,"UPDATE `module_notes` SET `folder_name`='$name' WHERE `user_id`='$config_cookie_id' AND `folder` = '$id'");

      echo json_encode(array("res" => '1', "new_folder_name" => $name));

}
?>