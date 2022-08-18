$(document).ready(function(){
    setTimeout(() => {
        $('.error-msg').fadeOut();
    }, 5000);
    /* BUTTON FOR HREF */
    // $('header a').click(function(){
    //    $.get($(this).attr('href'), $(this).serialize(), function(res){
    //         $('body').html(res);
           
    //      });
    //     // return false;
    // });
    $('.div_end').hide();
    $('.a_end').show();
    $('.store_page').click(function(){
        $('.a_end').hide();
        $('.div_end').show();   
    });
    $('.log_reg').click(function(){
        $('.div_end').hide();
        $('.a_end').show();
    });
});