jQuery(document).ready(function($) {'use strict';

    // Perform AJAX login on form submit
    $('form#login').on('submit', function(e){
        $('form#login p.status').show().text(ajax_login_object.loadingmessage);
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: ajax_login_object.ajaxurl,
            data: { 
                'action': 'ajaxlogin', //calls wp_ajax_nopriv_ajaxlogin
                'username': $('form#login #username').val(), 
                'password': $('form#login #password').val(), 
                'security': $('form#login #security').val() },
            success: function(data){
                $('form#login div.login-error').text(data.message);
                if (data.loggedin == true){
                    document.location.href = ajax_login_object.redirecturl;
                }
                if($('.login-error').text() == ''){
                    $('form#login div.login-error').hide();
                }else{
                    $('form#login div.login-error').show();
                }
            }
        });
        e.preventDefault();


    });

    //$('#navigation ul li.nav-myacount a').attr("href", "#sign-in").attr("data-toggle", "modal").attr("data-target", "#sign-in");
    $('#navigation ul li.nav-myacount a').attr("href", "/sign-up");

    if($('.login-error').text() == ''){
        $('form#login div.login-error').hide();
    }else{
        $('form#login div.login-error').show();
    }

});