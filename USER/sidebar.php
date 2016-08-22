<?php
$current_url = $_SERVER["REQUEST_URI"];

?>

<script type='text/javascript' src='<?=CONFIG_DOMAIN_NAME?>/js/jquery-2.2.3.min.js'></script>

<script>//Фиксированный сайдбар
$(document).ready(function()
{
var $top1= $('#sidebar').offset().top + 0;

$(window).scroll(function()
{

if ($(window).scrollTop()>$top1)
{
$('#sidebar').addClass('sidebar_floating');
}
else
{
$('#sidebar').removeClass('sidebar_floating');

}
});
});
</script>
<div id="sidebar" style="" class="sidebar_usual">

   <?php //Кабинет
   if(stripos($current_url, "/user/cabinet.php") === false)://если подстроки нет
       ?>
       <a href="<?=CONFIG_DOMAIN_NAME?>/user/cabinet.php">
           <div class="item" >
               <div class="wrap">
                   <div class="indicator"></div>
                   <div class="icon" id="ico_cabinet"></div>
               </div>
               Кабинет
           </div>
       </a>
       <?php else://если подстрока есть
           if(strcasecmp($current_url,"/user/cabinet.php")==0):?>
                <div class="item_active" style='border-top: 1px solid #e0e6e7;'>
                    <div class="wrap">
                        <div class="indicator" id="sb_cabinet"></div>
                        <div class="icon" id="ico_cabinet"></div>
                    </div>
                    Кабинет
                </div>
        <?php else://если строки не совпадают ?>
               <a href="<?=CONFIG_DOMAIN_NAME?>/user/cabinet.php">
                   <div class="item_selected" style='border-top: 1px solid #e0e6e7;'>
                       <div class="wrap">
                           <div class="indicator"></div>
                           <div class="icon" id="ico_cabinet"></div>
                       </div>
                       Кабинет
                   </div>
               </a>
        <?php  endif; endif;?>




        <?php //Ежедневник

        if(stripos($current_url, "/MODULES/module_scheduler/scheduler.php") === false)://если подстроки нет
        ?>
        <a href="<?=$config_domain_name?>/MODULES/module_scheduler/scheduler.php">
            <div class="item">
                <div class="wrap">
                    <div class="indicator" id="sb_scheduler"></div>
                  <div class="icon" id="ico_cabinet"></div>
                </div>
                Ежедневник</div>
        </a>
            <?php else://если подстрока есть
            if((strcasecmp($current_url,"/MODULES/module_scheduler/scheduler.php")==0) || (strcasecmp($current_url,"/MODULES/module_scheduler/scheduler.php#today")==0)):?>


            <div class="item_active">
                <div class="wrap">
                    <div class="indicator" id="sb_scheduler"></div>
                   <div class="icon" id="ico_cabinet"></div>
                </div>
                Ежедневник</div>

                <?php else://если строки не совпадают ?>
            <a href="<?=$config_domain_name?>/MODULES/module_scheduler/scheduler.php">
                <div class="item_selected">
                    <div class="wrap">
                        <div class="indicator" id="sb_scheduler"></div>
                      <div class="icon" id="ico_cabinet"></div>
                    </div>
                    Ежедневник</div>
            </a>
        <?php  endif;  endif;  ?>


    <?php //Заметки
    if(stripos($current_url, "/MODULES/module_notes/notes.php") === false)://если подстроки нет
        ?>
        <a href="<?=CONFIG_DOMAIN_NAME?>/MODULES/module_notes/notes.php">
            <div class="item" style="">
                <div class="wrap">
                    <div class="indicator" id="sb_notes"></div>
                    <div class="icon" id="ico_notes"></div>
                </div>
                Заметки</div>
        </a>
        <?php else://если подстрока есть
            if(strcasecmp($current_url, "/MODULES/module_notes/notes.php")==0)://если строки совпадают?>

            <div class="item_active">
                <div class="wrap">
                    <div class="indicator" id="sb_notes"></div>
                    <div class="icon" id="ico_notes"></div>
                </div>
                Заметки</div>

    <?php else://если строки не совпадают ?>
        <a href="<?=CONFIG_DOMAIN_NAME?>/MODULES/module_notes/notes.php">
            <div class="item_selected">
                <div class="wrap">
                    <div class="indicator" id="sb_notes"></div>
                    <div class="icon" id="ico_notes"></div>
                </div>
                Заметки</div>
        </a>
    <?php  endif; endif;?>









    <?php/* //Настройки
    if(stripos($current_url, "/user/settings.php") === false)://если подстроки нет
        ?>
        <a href="http://blacknote/user/settings.php">
            <div class="item">
                <div class="wrap">
                    <div class="indicator" id="sb_settings"></div>
                    <div class="icon" id="ico_cabinet"></div>
                </div>
                Настройки</div>
        </a>
    <?php else://если подстрока есть
        if(strcmp($current_url,"/user/settings.php")==0):?>

            <div class="item_active">
                <div class="wrap">
                    <div class="indicator" id="sb_settings"></div>
                    <div class="icon" id="ico_cabinet"></div>
                </div>
                Настройки</div>
        <?php else://если строки не совпадают ?>
        <a href="http://blacknote/user/settings.php">
            <div class="item_selected">
                <div class="wrap">
                    <div class="indicator" id="sb_settings"></div>
                    <div class="icon" id="ico_cabinet"></div>
                </div>
                Настройки</div>
        </a>
        <?php  endif;  endif; */ ?>

    <!--
    <a href="">
        <div class="item">
            <div class="wrap">
                <div class="indicator"></div>
                <div class="icon" id="ico_cabinet"></div>
            </div>
            Добавить модуль</div>
    </a>
    -->
    <a href="<?=CONFIG_DOMAIN_NAME?>/user/logout.php">
        <div class="item">
            <div class="wrap">
                <div class="indicator"></div>
<!--                <div class="icon" id="ico_cabinet"></div>-->
            </div>Выход</div>
    </a>
</div>