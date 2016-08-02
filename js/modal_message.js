/**
 * Created by Neo-pc on 20.07.2016.
 */
jQuery(function($){
    //Функция установки cookies
    function setCookie(name, value, options) {
        options = options || {};

        var expires = options.expires;

        if (typeof expires == "number" && expires) {
            var d = new Date();
            d.setTime(d.getTime() + expires * 1000);
            expires = options.expires = d;
        }
        if (expires && expires.toUTCString) {
            options.expires = expires.toUTCString();
        }

        value = encodeURIComponent(value);

        var updatedCookie = name + "=" + value;

        for (var propName in options) {
            updatedCookie += "; " + propName;
            var propValue = options[propName];
            if (propValue !== true) {
                updatedCookie += "=" + propValue;
            }
        }

        document.cookie = updatedCookie;
    }
 //Функция получения значения cookies
 function getCookie(name) {
     var matches = document.cookie.match(new RegExp(
         "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
     ));
     return matches ? decodeURIComponent(matches[1]) : undefined;
 }
 //Функция удаления
 function deleteCookie(name) {
     setCookie(name, "", {
         expires: -1
     })
 }


    $(document).ready(function() {
        var tutorial_status = getCookie("tutorial_over");
        if ( tutorial_status != "1")
        {
         $("#background_shadow,#modal_message_greeting").fadeIn(500);
        }
    });


    //Trigger -> Hide
    $('body').on('click','#modal_message_greeting_close', function() {

            deleteCookie("tutorial_over");
            document.cookie = "tutorial_over=1; path=/; expires=0";//Новый куки
            $('#background_shadow,#modal_message_greeting').fadeOut(400);
            //Записываю в куки

    });
});
