<?php
/**
 * Created by PhpStorm.
 * User: Neo-pc
 * Date: 19.07.2016
 * Time: 2:54
 */


   if($_POST['submit_feedback']){
    $feedback_data = json_decode($_POST['submit_feedback']);


      $to = "kastamas13@gmail.com";
      $subject = $feedback_data->subject;
      $body = '';

       $message = "$feedback_data->username\n
                    $feedback_data->message\n\n
                    Email отправителя:$feedback_data->email";

    mail($to, $subject, $message);
    echo json_encode(array("res" => "mail_suc"));
    exit();
    }




