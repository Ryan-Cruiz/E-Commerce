$(document).ready(function(){
    setTimeout(() => {
        $('.error-msg').fadeOut();
    }, 5000);
    /* BUTTON FOR HREF */
    $('header a,.details').click(function(){
        $.get($(this).attr('href'), $(this).serialize(), function(res) {
            $('body').html(res);
        });
        // return false;
    });

});