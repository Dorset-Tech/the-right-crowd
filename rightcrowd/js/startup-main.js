/*global $:false */
jQuery(document).ready(function($) {
    'use strict';


    /* -------------------------------------- */
    //      RTL Support Visual Composer
    /* -------------------------------------- */
    var delay = 100;

    function themeum_rtl() {
        if (jQuery("html").attr("dir") == 'rtl') {
            if (jQuery(".entry-content > div").attr("data-vc-full-width") == 'true') {
                jQuery('.entry-content > div').css({
                    'left': 'auto',
                    'right': jQuery('.entry-content > div').css('left')
                });
            }
        }
    }
    setTimeout(themeum_rtl, delay);

    jQuery(window).resize(function() {
        setTimeout(themeum_rtl, 1);
    });

    /* ************************************* */
    /* ****** Form Editable ********* */
    /* ************************************* */
    $('.edit-form').on("click", function(e) {
        $('input,textarea,button').removeAttr("disabled");
        e.preventDefault();
    });

    /* ************************************* */
    /* ****** General Editing Form ********* */
    /* ************************************* */
    function general_form(e) {
        var postdata = $(this).serializeArray();
        var formURL = $(this).attr("action");
        $.ajax({
            url: formURL,
            type: "POST",
            data: postdata,
            success: function(data, textStatus, jqXHR) {
                $('#dashboard-msg').modal();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                $("#dashboard-msg-err").html('<span style="color:red">AJAX Request Failed<br/> textStatus=' + textStatus + ', errorThrown=' + errorThrown + '</span>');
            }
        });
        e.preventDefault(); //STOP default action
    }
    $("#general-form").submit(general_form); //SUBMIT FORM



    /* ************************************* */
    /* ****** Contact Editing Form ********* */
    /* ************************************* */
    function contact_form(e) {
        var postdata = $(this).serializeArray();
        var formURL = $(this).attr("action");
        $.ajax({
            url: formURL,
            type: "POST",
            data: postdata,
            success: function(data, textStatus, jqXHR) {
                $('#dashboard-msg').modal();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                $("#dashboard-msg-err").html('<span style="color:red">AJAX Request Failed<br/> textStatus=' + textStatus + ', errorThrown=' + errorThrown + '</span>');
            }
        });
        e.preventDefault(); //STOP default action
    }
    $("#contact-form").submit(contact_form); //SUBMIT FORM



    /* ************************************* */
    /* ****** Password Editing Form ******** */
    /* ************************************* */
    function password_form(e) {
        var postdata = $(this).serializeArray();
        var formURL = $(this).attr("action");
        $.ajax({
            url: formURL,
            type: "POST",
            data: postdata,
            success: function(data, textStatus, jqXHR) {
                $('#dashboard-msg').modal();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                $("#dashboard-msg-err").html('<span style="color:red">AJAX Request Failed<br/> textStatus=' + textStatus + ', errorThrown=' + errorThrown + '</span>');
            }
        });
        e.preventDefault(); //STOP default action
    }
    $("#password-form").submit(password_form); //SUBMIT FORM



    /* ************************************* */
    /* ***********	Sticky Nav ************* */
    /* ************************************* */
    $(window).on('scroll', function() {
        'use strict';
        if ($(window).scrollTop() > 130) {
            $('#masthead').addClass('sticky');
        } else {
            $('#masthead').removeClass('sticky');
        }
    });



    /* ************************************* */
    /* ***********	Menu Fix *************** */
    /* ************************************* */
    // ------------- Menu Start ----------------------
    $('#showmenu').on("click", function() {
        $('.main-nav').slideToggle("fast", "linear");
    });
    //add and remove class
    var $window = $(window),
        $ul = $('ul.main-nav');

    if ($window.width() < 768) {
        $ul.removeClass('slideRight');
    };



    /* ************************************* */
    /* ********** Carousel Setup ********** */
    /* ************************************* */
    $(window).on('resize', function() {
        if ($window.width() < 768) {
            $ul.removeClass('slideRight');
        } else {
            $ul.addClass('slideRight')
        }
    });
    // Video Carosuel
    var owlrtl = false;
    if (jQuery("html").attr("dir") == 'rtl') {
        owlrtl = true;
    }

    //setup owl-carousel for partners
    $('.popular-ideas').owlCarousel({
        items: 3,
        // itemsCustom: [[0,1], [768,3], [992,3]],
        dots: false,
        nav: false,
        rtl: owlrtl,
        responsive: {
            0: {
                items: 1,
                margin: 30
            },
            992: {
                items: 3,
                margin: 30
            }
        }
    });
    // scroll animation initialize
    new WOW().init();


    //Window-size div
    $(window).resize(function() {
        $('#comming-soon').height($(window).height());
    });

    $(window).trigger('resize');


    $(window).resize(function() {
        $('#error-page').height($(window).height());
    });
    $(window).trigger('resize');


    $(".youtube a[data-rel^='prettyPhoto']").prettyPhoto();
    $(".vimeo a[data-rel^='prettyPhoto']").prettyPhoto();

    $("a[data-rel]").prettyPhoto();

    /*--------------------------------------------------------------
     * 					Personal Profile Form AJAX
     *-------------------------------------------------------------*/
    function profile_form(e) {
        var postdata = $(this).serializeArray();
        var formURL = $(this).attr("action");
        $.ajax({
            url: formURL,
            type: "POST",
            data: postdata,
            success: function(data, textStatus, jqXHR) {
                $('#profile-msg').modal();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                $("#simple-msg-err").html('<span style="color:red">AJAX Request Failed<br/> textStatus=' + textStatus + ', errorThrown=' + errorThrown + '</span>');
            }
        });
        e.preventDefault(); //STOP default action
        //e.unbind(); //Remove a previously-attached event handler
    }
    $("#profile-form").submit(profile_form); //SUBMIT FORM




    /*--------------------------------------------------------------
     * 					Paypal User Email Form AJAX
     *-------------------------------------------------------------*/
    function paypal_user_form(e) {
        var postdata = $(this).serializeArray();
        var formURL = $(this).attr("action");
        $.ajax({
            url: formURL,
            type: "POST",
            data: postdata,
            success: function(data, textStatus, jqXHR) {
                $('#profile-msg').modal();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                $("#simple-msg-err").html('<span style="color:red">AJAX Request Failed<br/> textStatus=' + textStatus + ', errorThrown=' + errorThrown + '</span>');
            }
        });
        e.preventDefault(); //STOP default action
        //e.unbind(); //Remove a previously-attached event handler
    }
    $("#paypal-user-form").submit(paypal_user_form); //SUBMIT FORM




    /*--------------------------------------------------------------
     * 					Ratting Submit Form AJAX
     *-------------------------------------------------------------*/
    function ratting_submit_form(e) {
        var postdata = $(this).serializeArray();
        var formURL = $(this).attr("action");
        $.ajax({
            url: formURL,
            type: "POST",
            data: postdata,
            success: function(data, textStatus, jqXHR) {
                $('#ratting-msg').modal();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                $("#simple-msg-err").html('<span style="color:red">AJAX Request Failed<br/> textStatus=' + textStatus + ', errorThrown=' + errorThrown + '</span>');
            }
        });
        e.preventDefault(); //STOP default action
        // e.unbind(); //Remove a previously-attached event handler
    }
    $("#ratting-submit-form").submit(ratting_submit_form); //SUBMIT FORM

    $('#ratting-close').on('click', function() {
        window.location.href = $('#redirect_url_ratting').val();
    });



    /*--------------------------------------------------------------
     * 					Project Submit Form AJAX
     *-------------------------------------------------------------*/
    $('#project-submit').mousedown(function() {
        //tinyMCE.triggerSave();
    });

    function project_submit_form(e) {

        function ValidURL(str) {
            var message;
            var myRegExp = /^(?:(?:https?|ftp):\/\/)(?:\S+(?::\S*)?@)?(?:(?!10(?:\.\d{1,3}){3})(?!127(?:\.\d{1,3}){3})(?!169\.254(?:\.\d{1,3}){2})(?!192\.168(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z\u00a1-\uffff0-9]+-?)*[a-z\u00a1-\uffff0-9]+)(?:\.(?:[a-z\u00a1-\uffff0-9]+-?)*[a-z\u00a1-\uffff0-9]+)*(?:\.(?:[a-z\u00a1-\uffff]{2,})))(?::\d{2,5})?(?:\/[^\s]*)?$/i;
            if (!myRegExp.test(str)) {
                return false;
            } else {
                return true;
            }
        }

        var title = $('#project-title').val();
        var video = $('#project-video').val();
        var amount = $('#investment-amount').val();
        var start = $('#start-date').val();
        var end = $('#end-date').val();

        if ((title != "") && (amount != "") && (start != "") && (end != "") && (!isNaN(amount))) {

            var postdata = $(this).serializeArray();
            var formURL = $(this).attr("action");
            $.ajax({
                url: formURL,
                type: "POST",
                data: postdata,
                success: function(data, textStatus, jqXHR) {
                    //$('#project-submit-form')[0].reset();
                    $('#welcome-msg').modal();
                    $('#dashboard-msg').modal();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    $("#simple-msg-err").html('<span style="color:red">AJAX Request Failed<br/> textStatus=' + textStatus + ', errorThrown=' + errorThrown + '</span>');
                }
            });
        } else {
            $('#error-msg').modal();
        }
        e.preventDefault(); //STOP default action
        //e.unbind(); //Remove a previously-attached event handler
    }

    function project_submit_form_3(e) {
        e.preventDefault();

        function ValidURL(str) {
            var message;
            var myRegExp = /^(?:(?:https?|ftp):\/\/)(?:\S+(?::\S*)?@)?(?:(?!10(?:\.\d{1,3}){3})(?!127(?:\.\d{1,3}){3})(?!169\.254(?:\.\d{1,3}){2})(?!192\.168(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z\u00a1-\uffff0-9]+-?)*[a-z\u00a1-\uffff0-9]+)(?:\.(?:[a-z\u00a1-\uffff0-9]+-?)*[a-z\u00a1-\uffff0-9]+)*(?:\.(?:[a-z\u00a1-\uffff]{2,})))(?::\d{2,5})?(?:\/[^\s]*)?$/i;
            if (!myRegExp.test(str)) {
                return false;
            } else {
                return true;
            }
        }
        $("#project-submit-loader").removeClass('hidden');
        $('#project-submit').hide();
        var title = $('#project-title').val();
        var video = $('#project-video').val();
        var amount = $('#investment-amount').val();
        var start = $('#start-date').val();
        var end = $('#end-date').val();

        //var desc = tinyMCE.activeEditor.getContent({format: 'text'});
        /*if(desc.length < 500){
        	$('#desc-error-msg .modal-body p span').html((500 - desc.length) + ' characters needed more.');
        	$('#desc-error-msg').modal();
        	$("#project-submit-loader").addClass('hidden');
        	$('#project-submit').show();
        }else*/
        if ((title.trim() != "")) {

            var postdata = $(this).serializeArray();
            var formURL = $(this).attr("action");

            $.ajax({
                url: formURL,
                type: "POST",
                data: postdata,
                success: function(data, textStatus, jqXHR) {
                    //$('#project-submit-form')[0].reset();
                    $("#project-submit-loader").addClass('hidden');
                    $('#welcome-msg').modal();
                    //$('#dashboard-msg').modal();
                    $('#project-submit').show();

                    // redirect to dashboard 
                    window.location.href = "/dashboard";

                },
                error: function(jqXHR, textStatus, errorThrown) {
                    $("#project-submit-loader").addClass('hidden');
                    $('#project-submit').show();
                    $("#simple-msg-err").html('<span style="color:red">AJAX Request Failed<br/> textStatus=' + textStatus + ', errorThrown=' + errorThrown + '</span>');
                }
            });
        } else {
            $('#error-msg').modal();
            $('#project-submit').show();
            $("#project-submit-loader").addClass('hidden');
        }
        e.preventDefault(); //STOP default action
        //e.unbind(); //Remove a previously-attached event handler
    }

    $("#project-submit-form").submit(project_submit_form); //SUBMIT FORM
    $("#project-submit-form-3").submit(project_submit_form_3); //SUBMIT FORM 3

    $('#start-date, #end-date').datepicker({
        dateFormat: 'mm/dd/yy'
    });




    $('#form-submit-close').on('click', function() {
        window.location.href = $('#redirect_url_add').val();
    });

    $('#edit-submit-close').on('click', function() {
        window.location.href = $('#redirect_url_edit').val();
    });


    /*--------------------------------------------------------------
     * 					Update Submit Form AJAX
     *-------------------------------------------------------------*/
    function update_submit_form(e) {
        var postdata = $(this).serializeArray();
        var formURL = $(this).attr("action");
        $.ajax({
            url: formURL,
            type: "POST",
            data: postdata,
            success: function(data, textStatus, jqXHR) {
                //$('#project-submit-form')[0].reset();
                $('#dashboard-msg').modal();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                $("#simple-msg-err").html('<span style="color:red">AJAX Request Failed<br/> textStatus=' + textStatus + ', errorThrown=' + errorThrown + '</span>');
            }
        });
        e.preventDefault(); //STOP default action
        e.unbind(); //Remove a previously-attached event handler
    }
    $("#update-submit-form").submit(update_submit_form); //SUBMIT FORM



    $('#update-close').on('click', function() {
        window.location.href = $('#redirect_url').val();
    });




    /*--------------------------------------------------------------
     * 					Project Money Withdraw
     *-------------------------------------------------------------*/
    function withdraw_submit_form(e) {
        var postdata = $(this).serializeArray();
        var formURL = $(this).attr("action");
        $.ajax({
            url: formURL,
            type: "POST",
            data: postdata,
            success: function(data, textStatus, jqXHR) {
                //$('#project-submit-form')[0].reset();
                $('#withdraw-msg').modal();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                $("#simple-msg-err").html('<span style="color:red">AJAX Request Failed<br/> textStatus=' + textStatus + ', errorThrown=' + errorThrown + '</span>');
            }
        });
        e.preventDefault(); //STOP default action
        e.unbind(); //Remove a previously-attached event handler
    }
    $("#withdraw-submit-form").submit(withdraw_submit_form); //SUBMIT FORM


    $('#withdraw-close').on('click', function() {
        window.location.href = $('#redirect_url_withdraw').val();
    });




    /*--------------------------------------------------------------
     * 					Project Submit Form
     *-------------------------------------------------------------*/

    // Color Change When Text Input
    function color_changer(ids, classs) {
        if ($('#' + ids).val() == "") {
            $('.' + classs).css('color', '#FF0000');
        } else {
            $('.' + classs).css('color', '#16DA00');
        }
    }
    color_changer('project-title', 'title-color');
    color_changer('project-tag', 'tag-color');
    color_changer('location', 'location-color');
    color_changer('project-about', 'about-color');

    $('#project-title').on('keyup', function(event) {
        $('#auto-title').html($('#project-title').val());
        color_changer('project-title', 'title-color');
    });

    $('#location').on('keyup', function(event) {
        $('#auto-location').html($('#location').val());
        color_changer('location', 'location-color');
    });

    $('#project-about').on('keyup', function(event) {
        color_changer('project-about', 'about-color');
    });

    $('#project-tag').on('keyup', function(event) {
        $('#auto-tag').html($('#project-tag').val());
        color_changer('project-tag', 'tag-color');
    });

    $('#investment-amount').on('keyup', function(event) {
        $('#auto-investment').html($('#investment-amount').val());
    });

    if (typeof tinymce != 'undefined') {
        setTimeout(function() {
            for (var i = 0; i < tinymce.editors.length; i++) {
                tinymce.editors[i].onChange.add(function(ed, e) {
                    tinyMCE.triggerSave();
                    $('#auto-description').html($("#description").val());
                });
            }
        }, 1000);
    }




    /*--------------------------------------------------------------
     * 					Project Submit Form Back,Next & Submit
     *-------------------------------------------------------------*/
    var i = 0;
    $("#back,#back-3,#project-submit").hide();

    // on load form step
    var tmpI = 0; //$("#form_step").val();  
    if (tmpI > 0) {
        var i = tmpI;
        if (tmpI == 1) {
            $("#back").show();
        }
        if (tmpI == 2) {
            $("#project-submit").show();
        }
        // update step value
        $('#form_step').val(i);

        $(".project-submit-form").removeClass('form-show').addClass('form-hide');
        $(".project-submit-form:eq(" + i + ")").removeClass('form-hide').addClass('form-show');

        $(".step-01:eq(" + i + ")").addClass('active');
        $(".step-01:eq(" + (i - 1) + ")").removeClass('active').addClass('completed');
    }

    $('#next').on('click', function() {
        var title = $("#project-title").val();
        var amount = $("#investment-amount").val();
        var minamount = $("#minimum-investment-amount").val();
        //var description = $("#project-description").val();
        //alert( $('#tinymce').html() );
        //$('#description').val( 'anik' );

        if (i == 0) {
            if (!title || !amount) {
                $('#error-msg').modal();
            } else if (minamount && minamount < 1) {
                alert("Minimum Investment should be more than 0.50 ");
            } else {
                var postdata = $('#project-submit-form').serializeArray();
                var formURL = $('#project-submit-form').attr("action");
                $.ajax({
                    url: formURL,
                    type: "POST",
                    data: postdata,
                    success: function(data, textStatus, jqXHR) {
                        //$('#project-submit-form')[0].reset();
                        var d = JSON.parse(data);
                        if (d.post_id) {
                            $('#postID').val(d.post_id);
                            $("#back").show();
                            if (i == 1) {
                                $("#project-submit").show();
                                $(this).hide();
                                i++;
                            } else {
                                i++;
                            }

                            // update step value
                            $('#form_step').val(i);

                            $(".project-submit-form").removeClass('form-show').addClass('form-hide');
                            $(".project-submit-form:eq(" + i + ")").removeClass('form-hide').addClass('form-show');

                            $(".step-01:eq(" + i + ")").addClass('active');
                            $(".step-01:eq(" + (i - 1) + ")").removeClass('active').addClass('completed');
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        $("#simple-msg-err").html('<span style="color:red">AJAX Request Failed<br/> textStatus=' + textStatus + ', errorThrown=' + errorThrown + '</span>');
                    }
                });

            }
        } else {
            if (!title || !amount) {
                $('#error-msg').modal();
            } else {
                $("#back").show();
                if (i == 1) {
                    var postdata = $('#project-submit-form').serializeArray();
                    var formURL = $('#project-submit-form').attr("action");
                    var curObj = $(this);
                    $.ajax({
                        url: formURL,
                        type: "POST",
                        data: postdata,
                        success: function(data, textStatus, jqXHR) {
                            //$('#project-submit-form')[0].reset();
                            var d = JSON.parse(data);
                            if (d.post_id) {
                                $('#postID').val(d.post_id);
                                $("#project-submit").show();
                                curObj.hide();
                                i++;

                                // update step value
                                $('#form_step').val(i);

                                $(".project-submit-form").removeClass('form-show').addClass('form-hide');
                                $(".project-submit-form:eq(" + i + ")").removeClass('form-hide').addClass('form-show');

                                $(".step-01:eq(" + i + ")").addClass('active');
                                $(".step-01:eq(" + (i - 1) + ")").removeClass('active').addClass('completed');
                            }
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            $("#simple-msg-err").html('<span style="color:red">AJAX Request Failed<br/> textStatus=' + textStatus + ', errorThrown=' + errorThrown + '</span>');
                        }
                    });

                } else {
                    i++;
                }


            }
        }

    });


    // Project form Back button actionlistener 
    $('#back').on('click', function() {
        $("#next").show();
        $("#project-submit").hide();
        if (i == 1) {
            $(this).hide();
        }
        i--;

        // update step value
        $('#form_step').val(i);
        $(".project-submit-form").removeClass('form-show').addClass('form-hide');
        $(".project-submit-form:eq(" + i + ")").removeClass('form-hide').addClass('form-show');

        $(".step-01:eq(" + (i + 1) + ")").removeClass('active');
        $(".step-01:eq(" + i + ")").removeClass('completed').addClass('active');

    });

    var i = 0;
    // for project form 3 - sharehub rightmarket company form 
    $('#next-3').on('click', function() {
        var title = $("#project-title").val();
        $(this).hide();
        $("#project-submit-loader").removeClass('hidden');
        if (i == 0) {
            //var desc = tinyMCE.activeEditor.getContent({format: 'text'});
            if (title.trim() == '') {
                $('#error-msg').modal();
                $("#project-submit-loader").addClass('hidden');
                $(this).show();
            }
            /*else if(desc.length < 300){
            	$('#desc-error-msg .modal-body p span').html((300 - desc.length) + ' characters needed more.');
            	$('#desc-error-msg').modal();
            	$("#project-submit-loader").addClass('hidden');
            	$(this).show();
            }*/
            else {
                var postdata = $('#project-submit-form-3').serializeArray();
                var formURL = $('#project-submit-form-3').attr("action");

                $.ajax({
                    url: formURL,
                    type: "POST",
                    data: postdata,
                    success: function(data, textStatus, jqXHR) {
                        $("#project-submit-loader").addClass('hidden');
                        $("#next-3").show();
                        //$('#project-submit-form')[0].reset();
                        var d = JSON.parse(data);
                        console.log(d);
                        if (d.post_id) {
                            $('#postID').val(d.post_id);
                            $("#back-3").show();
                            if (i == 1) {
                                $("#project-submit").show();
                                $(this).hide();
                                i++;
                            } else {
                                i++;
                            }

                            // update step value
                            $('#form_step').val(i);

                            $(".project-submit-form").removeClass('form-show').addClass('form-hide');
                            $(".project-submit-form:eq(" + i + ")").removeClass('form-hide').addClass('form-show');

                            $(".step-01:eq(" + i + ")").addClass('active');
                            $(".step-01:eq(" + (i - 1) + ")").removeClass('active').addClass('completed');
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        $("#next-3").show();
                        $("#project-submit-loader").addClass('hidden');
                        $("#simple-msg-err").html('<span style="color:red">AJAX Request Failed<br/> textStatus=' + textStatus + ', errorThrown=' + errorThrown + '</span>');
                    }
                });

            }
        } else {
            //var desc = tinyMCE.activeEditor.getContent({format: 'text'});
            if (title.trim() == '') {
                $('#error-msg').modal();
                $("#project-submit-loader").addClass('hidden');
            }
            /*else if(desc.length < 500){
            	$('#desc-error-msg .modal-body p span').html((500 - desc.length) + ' characters needed more.');
            	$('#desc-error-msg').modal();
            	$("#project-submit-loader").addClass('hidden');
            }*/
            else {
                $("#back-3").show();
                if (i == 1) {
                    var fm = $('#project-submit-form-3')[0];
                    var postdata = new FormData(fm); //$('#project-submit-form').serializeArray();
                    var formURL = $('#project-submit-form-3').attr("action");
                    var curObj = $(this);
                    $.ajax({
                        url: formURL,
                        type: "POST",
                        enctype: 'multipart/form-data',
                        processData: false,
                        contentType: false,
                        data: postdata,
                        success: function(data, textStatus, jqXHR) {
                            //$('#project-submit-form')[0].reset();
                            $("#project-submit-loader").addClass('hidden');
                            var d = JSON.parse(data);
                            if (d.status === 'false') {
                                $("#next-3").show();
                                alert("Validation Error : " + d.msg);
                                return false;
                            }
                            if (d.post_id) {
                                $('#postID').val(d.post_id);
                                $("#project-submit").show();
                                curObj.hide();
                                i++;

                                // update step value
                                $('#form_step').val(i);

                                $(".project-submit-form").removeClass('form-show').addClass('form-hide');
                                $(".project-submit-form:eq(" + i + ")").removeClass('form-hide').addClass('form-show');

                                $(".step-01:eq(" + i + ")").addClass('active');
                                $(".step-01:eq(" + (i - 1) + ")").removeClass('active').addClass('completed');
                            }
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            $("#simple-msg-err").html('<span style="color:red">AJAX Request Failed<br/> textStatus=' + textStatus + ', errorThrown=' + errorThrown + '</span>');
                            $("#next-3").show();
                        }
                    });

                } else {
                    i++;
                }


            }
        }

    });


    // Project form Back button actionlistener 
    $('#back-3').on('click', function() {
        $("#next-3").show();
        $("#project-submit").hide();
        if (i == 1) {
            $(this).hide();
        }
        i--;

        // update step value
        $('#form_step').val(i);
        $(".project-submit-form").removeClass('form-show').addClass('form-hide');
        $(".project-submit-form:eq(" + i + ")").removeClass('form-hide').addClass('form-show');

        $(".step-01:eq(" + (i + 1) + ")").removeClass('active');
        $(".step-01:eq(" + i + ")").removeClass('completed').addClass('active');

    });


    /* --------------------------------------
     *		Shortcode hover color effect 
     *  -------------------------------------- */
    var clr = '';
    var clr_bg = '';
    var clr_border = '';
    $(".thm-color").on({
        mouseenter: function() {
            clr = $(this).css('color');
            clr_bg = $(this).css('backgroundColor');
            clr_border = $(this).css('border-color');
            $(this).css("color", $(this).data("hover-color"));
            $(this).css("background-color", $(this).data("hover-bg-color"));
            $(this).css("border-color", $(this).data("hover-border-color"));
        },
        mouseleave: function() {
            $(this).css("color", clr);
            $(this).css("background-color", clr_bg);
            $(this).css("border-color", clr_border);
        }
    });



    $("#amount").on('keypress', function(e) {
        // if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
        //     return false;
        // }
        if (e.which != 8 && e.which != 0 && e.which != 46 && (e.which < 48 || e.which > 57)) {
            return false;
        }
    });


    var $spc = 2;
    var html = $('#clone-form').html();

    $('#add-more').on('click', function() {
        var final_html = html;
        final_html = final_html.replace("min1", "min" + $spc);
        final_html = final_html.replace("max1", "max" + $spc);
        final_html = final_html.replace("reward1", "reward" + $spc);
        $('#clone-form').append(final_html);
        $spc++;

    });
});


// payment form submit
jQuery(document).ready(function($) {
    'use strict';

    $('#submitbtn').on('click', function(event) {
        event.preventDefault();

        var $paymentForm = $('#fund_paymet_form'),
            gatewayType = 'paypal';

        var validator = $("#fund_paymet_form").validate({
                rules: {
                    first_name: "required",
                    last_name: "required",
                    email: {
                        required: true,
                        email: true
                    },
                    address1: "required",
                    //address2: "required",
                    city: "required",
                    state: "required",
                    zip: "required",
                    country: "required",
                    amount: {
                        required: true,
                        //number: true,
                        //min:1
                    },
                    ni_number: {
                        required: true
                    }
                },
            }),
            validate_status = validator.form();

        if (!validate_status) {
            return false;
        }
        if ($("#tandc").is(":not(:checked)") || $("#amc_tandc").is(":not(:checked)")) {
            alert("Please accept terms and conditions.");
        } else if ($("#factsheet").is(":not(:checked)")) {
            alert("Please confirm if you have read the factsheets/IM of the company.");
        } else if ($("#pp_application_form") && $("#pp_application_form").is(":not(:checked)")) {
            alert("Please confirm if you have read application form.");
        } else {
            $('.donate-project-page > .container').block({
                message: '<h1>Processing</h1>',
                css: {
                    padding: '30px 0',
                    margin: 0,
                    width: '30%',
                    top: '40%',
                    left: '35%',
                    textAlign: 'center',
                    color: '#ccc!important',
                    border: '2px solid #f5f5f5',
                    backgroundColor: '#ffffff',
                    cursor: 'wait'
                },
                overlayCSS: {
                    backgroundColor: '#000',
                    opacity: 0.3,
                    cursor: 'wait'
                },
            });

            gatewayType = $('input[name=gateway_type]').val(); //$('input[name=gateway_type]:checked').val();

            if (gatewayType == 'paypal') {
                submitPaymentForm();
            } else if (gatewayType == 'card') {
                stripePaymentForm();
            } else if (gatewayType == 'bank') {
                //bankTransferSubmit();
                var sbmtBtn = "<input type='button' class='donate-now' onClick='bankTransferSubmit();' id='paybybank' value='Complete Investment' >";
                $('#bank-transfer-modal-body').append(sbmtBtn);
                $('#bank-transfer-popup-modal').modal();
                $('#paybybank').click();
            } else {
                alert('Please select a payment method first.');
            }
        }

    });

    //$('#paybybank').click(function(){
    //bankTransferSubmit();
    //});
});



var submitPaymentForm = function() {
    var formData = jQuery('#fund_paymet_form').serialize();

    var data = {
        'action': 'fund_paymet_form_submit',
        'wpnonce': paymentAjax.paymentNonce,
        'data': formData
    };

    jQuery.ajax({
            url: paymentAjax.ajaxurl,
            type: 'POST',
            dataType: 'json',
            data: data,
        })
        .done(function(data) {
            console.log(data);
            if (data.status == true) {
                window.location = data.redirect;
            }
        })
        .fail(function(data) {
            console.log("error");
            console.log(data);
            //window.location = data.redirect;//temp for today
        });
};

jQuery(function() {
    jQuery('body').on('click', '#save_child_businesses', function() {
        var formData = jQuery('#update_businesses_child').serialize();
        var ajaxURL = jQuery(this).attr('data-ajurl');
        var data = {
            'action': 'display_business_child',
            'data': formData
        };

        jQuery.ajax({
                url: paymentAjax.ajaxurl,
                type: 'POST',
                dataType: 'json',
                data: data,
            })
            .done(function(data) {
                console.log(data);
                //var res = JSON.parse(data);
                if (data.status == "true") {
                    //alert("Saved");
                    //console.log(jQuery('#business-display-save-msg'));
                    jQuery('#business-display-save-msg').html("Changes saved!");
                    jQuery('#business-display-save-msg').css("color", "green");
                    jQuery('#business-display-save-msg').show();
                    //jQuery("#data-save-msg").delay(3000).hide();
                    setTimeout(function() {
                        $('#business-display-save-msg').hide('slow');
                    }, 3000);
                }
            })
            .fail(function() {
                console.log("error");
            });
        return false;
    });

    jQuery('body').on('click', '#upload-proofs-btn', function(e) {
        e.preventDefault();
        var form = jQuery('#upload-proofs-form')[0];
        var postdata = new FormData(form);
        postdata.append('action', 'upload_user_proofs');
        console.log(postdata);

        var thisEl = jQuery(this);
        var parentel = thisEl.parent();
        //thisEl.hide();
        jQuery.ajax({
            async: false,
            url: paymentAjax.ajaxurl,
            type: "POST",
            data: postdata,
            processData: false,
            contentType: false,
            enctype: 'multipart/form-data',
            success: function(response) {

                var response = JSON.parse(response);
                if (response.idproof_status === "true") {
                    thisEl.show();
                    var message = jQuery("<span />", {
                        "class": "flash_message",
                        text: "ID Proof Saved!"
                    }).fadeIn("fast");
                    jQuery("input[name=idproof]").parent().append(message);
                    message.delay(2000).fadeOut("normal", function() {
                        jQuery(this).remove();
                    });
                } else {
                    if (response.idproof_error) {
                        jQuery("#idproof_error").html(response.idproof_error);
                    }
                }

                if (response.addresspr_status === "true") {
                    thisEl.show();
                    var message = jQuery("<span />", {
                        "class": "flash_message",
                        text: "Addpress proof Saved!"
                    }).fadeIn("fast");
                    jQuery("input[name=addressproof]").parent().append(message);
                    message.delay(2000).fadeOut("normal", function() {
                        jQuery(this).remove();
                    });
                } else {
                    if (response.addressproof_error) {
                        jQuery("#addressproof_error").html(response.addressproof_error);
                    }
                }

            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log(errorThrown);
                var message = jQuery("<span />", {
                    "class": "flash_message flash_error",
                    text: "Something went wrong!"
                }).fadeIn("fast");
                parentel.append(message);
                message.delay(2000).fadeOut("normal", function() {
                    jQuery(this).remove();
                });
            }
        });

    });

    jQuery('body').on('click', '#bank-details-btn', function(e) {
        e.preventDefault();
        var thisEl = jQuery(this);
        var parentel = thisEl.parent();
        var bankdetails = jQuery("#bank_details").val();
        var coupon_interval = jQuery("#coupons_interval").val();
        thisEl.hide();
        jQuery.ajax({
            url: paymentAjax.ajaxurl,
            type: "POST",
            data: {
                bankdetails: bankdetails,
                coupon_interval: coupon_interval,
                action: 'save_user_bankdetails'
            },
            success: function(response) {
                console.log(response);
                var res = JSON.parse(response);
                if (res.status == "true") {
                    console.log("m here");
                    thisEl.show();
                    var message = jQuery("<span />", {
                        "class": "flash_message",
                        text: "Saved!"
                    }).fadeIn("fast");
                    parentel.append(message);
                    message.delay(2000).fadeOut("normal", function() {
                        jQuery(this).remove();
                    });
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log(errorThrown);
                var message = jQuery("<span />", {
                    "class": "flash_message flash_error",
                    text: "Something went wrong!"
                }).fadeIn("fast");
                parentel.append(message);
                message.delay(2000).fadeOut("normal", function() {
                    jQuery(this).remove();
                });
            }
        });
    });

    jQuery('body').on('click', '.cancel-investment-btn', function() {
        var invID = jQuery(this).data('invid');
        var thisEl = jQuery(this);
        var parentel = thisEl.parent();
        thisEl.hide();
        jQuery.ajax({
            url: paymentAjax.ajaxurl,
            type: "POST",
            data: {
                invid: invID,
                action: 'cancel_my_investment'
            },
            success: function(response) {
                var res = JSON.parse(response);
                if (res.status == "true") {
                    /*thisEl.show();
                    var message = jQuery("<span />", {
                    		 "class": "flash_message",
                    		 text: "Saved!"
                    	  }).fadeIn("fast");
                    	parentel.append(message);
                    	message.delay(2000).fadeOut("normal", function() {
                    	 jQuery(this).remove();
                      });*/
                    window.location.reload();
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log(errorThrown);
                var message = jQuery("<span />", {
                    "class": "flash_message flash_error",
                    text: "Something went wrong!"
                }).fadeIn("fast");
                parentel.append(message);
                message.delay(2000).fadeOut("normal", function() {
                    jQuery(this).remove();
                });
            }
        });
    });

    // change investment status by Custodian
    jQuery('body').on('change', '.change-investment-status', function() {
        var invID = jQuery(this).data('invid');
        var thisEl = jQuery(this);
        var parentel = thisEl.parent();
        jQuery.ajax({
            url: paymentAjax.ajaxurl,
            type: "POST",
            data: {
                invid: invID,
                action: 'change_investment_status',
                st: thisEl.val()
            },
            success: function(response) {
                var res = JSON.parse(response);
                if (res.status == "true") {
                    var message = jQuery("<span />", {
                        "class": "flash_message",
                        text: "Status changed!"
                    }).fadeIn("fast");
                    parentel.append(message);
                    message.delay(2000).fadeOut("normal", function() {
                        jQuery(this).remove();
                    });

                } else {
                    var message = jQuery("<span />", {
                        "class": "flash_message flash_error",
                        text: "Something went wrong! Try again."
                    }).fadeIn("fast");
                    parentel.append(message);
                    message.delay(2000).fadeOut("normal", function() {
                        jQuery(this).remove();
                    });
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log(errorThrown);
                var message = jQuery("<span />", {
                    "class": "flash_message flash_error",
                    text: "Something went wrong!"
                }).fadeIn("fast");
                parentel.append(message);
                message.delay(2000).fadeOut("normal", function() {
                    jQuery(this).remove();
                });
            }
        });
    });

    jQuery('body').on('click', '#upload-aml-btn', function(e) {
        e.preventDefault();
        var form = jQuery('#upload-aml-form')[0];
        var postdata = new FormData(form);
        postdata.append('action', 'upload_aml_docs');
        console.log(postdata);

        var thisEl = jQuery(this);
        var parentel = thisEl.parent();
        thisEl.hide();
        jQuery.ajax({
            async: false,
            url: paymentAjax.ajaxurl,
            type: "POST",
            data: postdata,
            processData: false,
            contentType: false,
            enctype: 'multipart/form-data',
            success: function(response) {

                var response = JSON.parse(response);
                if (response.status === "true") {
                    thisEl.show();
                    window.location.reload();
                } else {
                    thisEl.show();
                    if (response.error) {
                        jQuery("#aml_error").html(response.error);
                    }
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log(errorThrown);
                thisEl.show();
                var message = jQuery("<span />", {
                    "class": "flash_message flash_error",
                    text: "Something went wrong!"
                }).fadeIn("fast");
                parentel.append(message);
                message.delay(2000).fadeOut("normal", function() {
                    jQuery(this).remove();
                });
            }
        });

    });


    // change ref code 
    jQuery('body').on('click', '#ref_code_btn', function(e) {
        e.preventDefault();
        var form = jQuery('#new_ref_code_form')[0];
        var postdata = new FormData(form);
        var thisEl = jQuery(this);
        var parentel = thisEl.parent();
        thisEl.hide();
        jQuery.ajax({
            async: false,
            url: paymentAjax.ajaxurl,
            type: "POST",
            data: postdata,
            processData: false,
            contentType: false,
            enctype: 'multipart/form-data',
            success: function(response) {

                var response = JSON.parse(response);
                if (response.status === "true") {
                    thisEl.show();
                    jQuery("#col_refcode" + response.invID).text(response.refcode);
                    var message = jQuery("<span />", {
                        "class": "flash_message flash_error",
                        text: "Code updated successfully."
                    }).fadeIn("fast");
                    parentel.append(message);
                    message.delay(2000).fadeOut("normal", function() {
                        jQuery(this).remove();
                    });
                } else {
                    thisEl.show();
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log(errorThrown);
                thisEl.show();
                var message = jQuery("<span />", {
                    "class": "flash_message flash_error",
                    text: "Something went wrong!"
                }).fadeIn("fast");
                parentel.append(message);
                message.delay(2000).fadeOut("normal", function() {
                    jQuery(this).remove();
                });
            }
        });

    });

    // update amount of an investment
    jQuery('body').on('click', '#update_inv_amount_btn', function(e) {
        e.preventDefault();
        var form = jQuery('#new_inv_amount_form')[0];
        var postdata = new FormData(form);
        var thisEl = jQuery(this);
        var parentel = thisEl.parent();
        thisEl.hide();
        jQuery.ajax({
            async: false,
            url: paymentAjax.ajaxurl,
            type: "POST",
            data: postdata,
            processData: false,
            contentType: false,
            enctype: 'multipart/form-data',
            success: function(response) {

                var response = JSON.parse(response);
                if (response.status === "true") {
                    thisEl.show();
                    jQuery("#col_invamount" + response.invID).text(response.amount);
                    var message = jQuery("<span />", {
                        "class": "flash_message flash_error",
                        text: "Amount updated successfully."
                    }).fadeIn("fast");
                    parentel.append(message);
                    message.delay(2000).fadeOut("normal", function() {
                        jQuery(this).remove();
                    });
                } else {
                    thisEl.show();
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log(errorThrown);
                thisEl.show();
                var message = jQuery("<span />", {
                    "class": "flash_message flash_error",
                    text: "Something went wrong!"
                }).fadeIn("fast");
                parentel.append(message);
                message.delay(2000).fadeOut("normal", function() {
                    jQuery(this).remove();
                });
            }
        });

    });

    // update type of an investment
    jQuery('body').on('click', '#update_inv_type_btn', function(e) {
        e.preventDefault();
        var form = jQuery('#new_inv_type_form')[0];
        var postdata = new FormData(form);
        var thisEl = jQuery(this);
        var parentel = thisEl.parent();
        thisEl.hide();
        jQuery.ajax({
            async: false,
            url: paymentAjax.ajaxurl,
            type: "POST",
            data: postdata,
            processData: false,
            contentType: false,
            enctype: 'multipart/form-data',
            success: function(response) {

                var response = JSON.parse(response);
                if (response.status === "true") {
                    thisEl.show();
                    jQuery("#col_invtype" + response.invID).text(response.type);
                    var message = jQuery("<span />", {
                        "class": "flash_message flash_error",
                        text: "Type updated successfully."
                    }).fadeIn("fast");
                    parentel.append(message);
                    message.delay(2000).fadeOut("normal", function() {
                        jQuery(this).remove();
                    });
                } else {
                    thisEl.show();
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log(errorThrown);
                thisEl.show();
                var message = jQuery("<span />", {
                    "class": "flash_message flash_error",
                    text: "Something went wrong!"
                }).fadeIn("fast");
                parentel.append(message);
                message.delay(2000).fadeOut("normal", function() {
                    jQuery(this).remove();
                });
            }
        });

    });
});