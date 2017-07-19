
$(document).ready(function(){
    /*
    zorgt dat de navbar agenda_items een andere css style meekrijgen ter duidelijkheid dat de muis erop staat
     */
    $('.navbar-item').hover(function () {
        $(this).addClass('navbar-selected');
    },function(){
        $(this).removeClass('navbar-selected');
    });
});

