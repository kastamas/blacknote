/**
 * Created by Neo-pc on 03.05.2016.
 */
//Фиксированное меню
$(document).ready(function()
{
    var $top1= $('#buttons_row').offset().top + 0;

    $(window).scroll(function()
    {

    if ($(window).scrollTop()>$top1)
    {
    $('#buttons_row').addClass('floater');
    }
    else
    {
    $('#buttons_row').removeClass('floater');

    }
    });
});