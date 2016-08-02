
    function Dialog_Message_Create(trigger_id,form_name,form_content){
    //Trigger -> Show
    $('body').on('click', '#'+trigger_id, function() {
        $("#background_shadow").fadeIn(320, function(){
            $("#"+form_name).css('display', 'block')
                .animate({opacity: 1}, 320);
            $( "#focus_please" ).focus();

        });
    });

    //Trigger -> Hide
    $('body').on('click','#'+form_name+'_close', function() {
        $("#background_shadow,#"+form_name).fadeOut(400);
        $('#error_message').html("");
    });


    <!--HTML part-->
    var Container_Maxi = document.getElementById('container_maxi');
    Container_Maxi.insertAdjacentHTML("beforeBegin","<div id=\'background_shadow\'></div><div id=\'"+form_name+"\' class=\'modal_div_center\'><span class=\'modal_close\' id=\'"+form_name+"_close\'>x</span>"+form_content+"</div>");
}


