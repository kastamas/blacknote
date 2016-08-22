<!--Left Top script-->
<!--Left Bottom-->
<script type='text/javascript' src='<?=CONFIG_DOMAIN_NAME?>/js/jquery-2.2.3.min.js'></script>
<script type="text/javascript" src="<?=CONFIG_DOMAIN_NAME?>/js/feedback_modal.js"></script>
<?php

//TODO:Баг бд. не удаляется, если удалить куки раньше конца демо
//TODO:Check Cookies
//TODO:Значения прыгают
?>
<!--<script>
    jQuery(function($){
        function timer(){
            var obj = document.getElementById('timer_inp');
            /*obj.innerHTML *=60;*/
            obj.innerHTML--;
            /* obj.innerHTML /=60;*/

            //When time is over
            if((obj.innerHTML==0)||(obj.innerHTML < 0)){
                $("#informator_left_top").css('display', 'none');//Скрываю блок
                alert('Демонстрация завершена :) \nСпасибо за участие!');setTimeout(function(){},1000);
                window.location = 'http://blacknotedemo/user/logout.php';
            }
            else{setTimeout(timer,1000);}
        }
        setTimeout(timer,1000);
    });
</script>-->
<?php
/*if(isset($_COOKIE['id'])):{
    $value = 1800 - (time() - $_COOKIE['demo_start_time']);
}*/
    ?>
    <!--<div id="informator_left_top"> <!--Left bottom angle-->
        <!--<p>Времени до конца демо осталось: <span id="timer_inp"><?/*=$value*/?></span> Секунд</p>-->
  <!--  </div>-->




<!--ОВЕРЛЕЙ БЕГИн-->
<div id="background_shadow"></div>
<!--ОВЕРЛЕЙ ЭНД-->

<!--ФОРМА ОТПРАВКИ СООБЩЕНИЯ В ОКНЕ-->
<div id="hidden_feedback_form" class="modal_div" style="">
    <span class="modal_close_dark" id="modal_close">✕</span>
    <form id="feedback_form" method="post">
        <label for="username"></label><br>
        <input type="text" name="username"  id='username' autocomplete="off" placeholder="Ваше имя" required>
        <br>
        <label for="subject"></label><br>
        <input type="text" name="subject" id='subject' autocomplete="off" placeholder="Тема" required>
        <br>
        <label for="email"></label><br>
        <input type="email" name="email" id='email' placeholder="email для обратной связи" autocomplete="off" required>
        <br><br>
        <label for="message">Ваше сообщение</label><br>
        <textarea id='message' name='message' placeholder=""></textarea>
        <br>
        <br>
        <input type="submit" class="action_button"   name="hidden_feedback_form" value="Отправить">
    </form>
</div>
<!--Форма ответа после отправки-->
<div id="hidden_response_form" class="modal_div" style=" color: #818182;
    font-size: 11pt;    min-height:inherit; padding:0;  ">
    <p style="color:#398abf;    text-align: center;">Сообщение успешно отправлено :)</p>
</div>


<a id="show_feedback"><div id="informator_left_bottom">
   <p>Связаться с разработчиком</p>
</div></a>


<!--Right Bottom-->
<div id="informator_right_bottom"> <!--Left bottom angle  -->
 <p>Alpha - july (Demo)</p>
</div>