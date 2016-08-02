jQuery(function($){
    //Trigger -> Hide
    $('body').on('click','#modal_close', function() {
        $('.modal_div').animate({opacity: 0}, 200, function(){
            $(this).css('display', 'none');
            $('#background_shadow').fadeOut(400);
        })
    });


    //Trigger -> Show
    $('body').on('click', '#show_feedback', function() {
        $("#background_shadow").fadeIn(320, function(){
            $("#hidden_feedback_form").css('display', 'block')
                .animate({opacity: 1}, 320);
        });
    });


    //Обработчик отправки сообщения
    $('body').on('submit','#feedback_form', function(e){
        e.preventDefault();
        var feedback_data ={
            'username': $("#username").val(),
            'subject': $("#subject").val(),
            'email': $("#email").val(),
            'message': $("#message").val()
        };
        $.ajax({
            type: 'POST',
            url: 'http://blacknotedemo/request_handler.php',//Как это задать переменной? $config_domain_name
            dataType: 'json',
            data: "submit_feedback="+JSON.stringify(feedback_data),
            success: function(sup) {
                if(sup['res'] == 'mail_suc') {

                    //Скрываю окно с формой обратной связи
                    $("#hidden_feedback_form").css('display', 'none')


                    //Показываю сообщение об успехе
                    $("#hidden_response_form").fadeIn(500);


                    function closer(){
                        $("#feedback_form").trigger('reset');
                        $("#hidden_response_form").fadeOut(500)
                        $("#background_shadow").fadeOut(500)
                            //.css('display', 'none')
                            //.animate({opacity: 0}, 320)
                             ;

                    }
                    setTimeout(closer,5000);
                }
                else {
                    alert("Сообщение не отправлено");
                }
                //TODO: callback
            }
        });
    });

});