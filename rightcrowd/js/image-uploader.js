jQuery(document).ready( function(){'use strict';
		if(jQuery('.add_media').length > 0){

    var _custom_media = true,
        _orig_send_attachment = wp.media.editor.send.attachment;

    jQuery(document).on('click','.add_media', function(){
        _custom_media = false;
    });
}


    function media_upload( button_class) {
        jQuery('body').on('click',button_class, function(e) {
            var button_id ='#'+jQuery(this).attr('id');
            var button_class ='.'+jQuery(this).attr('id');
            var site_url = jQuery(this).data('url');
            /* console.log(button_id); */
            var self = jQuery(button_id);
            var send_attachment_bkp = wp.media.editor.send.attachment;
            var button = jQuery(button_id);
            var id = button.attr('id').replace('_button', '');
            _custom_media = true;
            
            wp.media.editor.send.attachment = function(props, attachment){
                if ( _custom_media  ) {
                    var attachment_url = attachment.url;
                    //var attachment_url = attachment_url.replace(site_url,'');
                    //console.log(attachment);
                    jQuery('.upload_image_url').val(attachment_url);
                    jQuery('.upload_image_id').val(attachment.id);
                    jQuery('.image-view').attr('src',attachment.url).css('display','block');
                    jQuery(button_class).val(attachment_url); 
                    jQuery('img'+button_class).attr('src',attachment.url);
                } else {
                    return _orig_send_attachment.apply( button_id, [props, attachment] );
                }
            }
            wp.media.editor.open(button);

            return false;
        });
    }
    media_upload('.custom-upload');


    function media_upload_sub1( button_class) {
        jQuery('body').on('click',button_class, function(e) {
            var button_id ='#'+jQuery(this).attr('id');
            var button_class ='.'+jQuery(this).attr('id');
            var site_url = jQuery(this).data('url');
            /* console.log(button_id); */
            var self = jQuery(button_id);
            var send_attachment_bkp = wp.media.editor.send.attachment;
            var button = jQuery(button_id);
            var id = button.attr('id').replace('_button', '');
            _custom_media = true;
            
            wp.media.editor.send.attachment = function(props, attachment){
                if ( _custom_media  ) {
                    var attachment_url = attachment.url;
                    jQuery('.upload_sub1_image_url').val(attachment_url);
                    jQuery('.upload_image_id1').val(attachment.id);
                } else {
                    return _orig_send_attachment.apply( button_id, [props, attachment] );
                }
            }
            wp.media.editor.open(button);

            return false;
        });
    }
    media_upload_sub1('.custom-upload-sub1');



    function media_upload_sub2( button_class) {
        jQuery('body').on('click',button_class, function(e) {
            var button_id ='#'+jQuery(this).attr('id');
            var button_class ='.'+jQuery(this).attr('id');
            var site_url = jQuery(this).data('url');
            /* console.log(button_id); */
            var self = jQuery(button_id);
            var send_attachment_bkp = wp.media.editor.send.attachment;
            var button = jQuery(button_id);
            var id = button.attr('id').replace('_button', '');
            _custom_media = true;
            
            wp.media.editor.send.attachment = function(props, attachment){
                if ( _custom_media  ) {
                    var attachment_url = attachment.url;
                    jQuery('.upload_sub2_image_url').val(attachment_url);
                    jQuery('.upload_image_id2').val(attachment.id);
                } else {
                    return _orig_send_attachment.apply( button_id, [props, attachment] );
                }
            }
            wp.media.editor.open(button);

            return false;
        });
    }
    media_upload_sub2('.custom-upload-sub2');



    function media_upload_banner( button_class) {
        jQuery('body').on('click',button_class, function(e) {
            var button_id ='#'+jQuery(this).attr('id');
            var button_class ='.'+jQuery(this).attr('id');
            var site_url = jQuery(this).data('url');
            /* console.log(button_id); */
            var self = jQuery(button_id);
            var send_attachment_bkp = wp.media.editor.send.attachment;
            var button = jQuery(button_id);
            var id = button.attr('id').replace('_button', '');
            _custom_media = true;
            
            wp.media.editor.send.attachment = function(props, attachment){
                if ( _custom_media  ) {
                    var attachment_url = attachment.url;
                    jQuery('.profile_image_url').val(attachment_url);
                    jQuery('.profile_image_id').val(attachment.id);
                } else {
                    return _orig_send_attachment.apply( button_id, [props, attachment] );
                }
            }
            wp.media.editor.open(button);

            return false;
        });
    }
    media_upload_banner('.profile-upload');


/**************** TEAM MEMBER IMAGE UPLOAD - by GD *****************/
function media_upload_teamimage( button_class) {
        jQuery('body').on('click',button_class, function(e) {
            var button_id ='#'+jQuery(this).attr('id');
            var button_class ='.'+jQuery(this).attr('id');
            var site_url = jQuery(this).data('url');
            var team_order = jQuery(this).data('team-order');
            /* console.log(button_id); */
            var self = jQuery(button_id);
            var send_attachment_bkp = wp.media.editor.send.attachment;
            var button = jQuery(button_id);
            var id = button.attr('id').replace('_button', '');
            _custom_media = true;
            
            wp.media.editor.send.attachment = function(props, attachment){
                if ( _custom_media  ) {
                    var attachment_url = attachment.url;
                    jQuery('.team_image_url'+team_order).val(attachment_url);
                    jQuery('.team_upload_image_id'+team_order).val(attachment.id);
                } else {
                    return _orig_send_attachment.apply( button_id, [props, attachment] );
                }
            }
            wp.media.editor.open(button);

            return false;
        });
    }
    media_upload_teamimage('.team-image-upload');
    
    /**************** FILES UPLOAD JS ACTIONS - by GD *****************/
function media_upload_certificate( button_class) {
        jQuery('body').on('click',button_class, function(e) {
            var button_id ='#'+jQuery(this).attr('id');
            var button_class ='.'+jQuery(this).attr('id');
            var site_url = jQuery(this).data('url');
            
            /* console.log(button_id); */
            var self = jQuery(button_id);
            var send_attachment_bkp = wp.media.editor.send.attachment;
            var button = jQuery(button_id);
            var id = button.attr('id').replace('_button', '');
            _custom_media = true;
            
            wp.media.editor.send.attachment = function(props, attachment){
                if ( _custom_media  ) {
                    var attachment_url = attachment.url;
                    jQuery('.cert_inc_url').val(attachment_url);
                    jQuery('.cert_inc_id').val(attachment.id);
                } else {
                    return _orig_send_attachment.apply( button_id, [props, attachment] );
                }
            }
            wp.media.editor.open(button);

            return false;
        });
    }
    media_upload_certificate('.cert-inc-upload');
    
    function media_upload_article( button_class) {
        jQuery('body').on('click',button_class, function(e) {
            var button_id ='#'+jQuery(this).attr('id');
            var button_class ='.'+jQuery(this).attr('id');
            var site_url = jQuery(this).data('url');
            
            /* console.log(button_id); */
            var self = jQuery(button_id);
            var send_attachment_bkp = wp.media.editor.send.attachment;
            var button = jQuery(button_id);
            var id = button.attr('id').replace('_button', '');
            _custom_media = true;
            
            wp.media.editor.send.attachment = function(props, attachment){
                if ( _custom_media  ) {
                    var attachment_url = attachment.url;
                    jQuery('.article_inc_url').val(attachment_url);
                    jQuery('.article_inc_id').val(attachment.id);
                } else {
                    return _orig_send_attachment.apply( button_id, [props, attachment] );
                }
            }
            wp.media.editor.open(button);

            return false;
        });
    }
    media_upload_article('.article-inc-upload');
    
    function media_upload_memorandum( button_class) {
        jQuery('body').on('click',button_class, function(e) {
            var button_id ='#'+jQuery(this).attr('id');
            var button_class ='.'+jQuery(this).attr('id');
            var site_url = jQuery(this).data('url');
            
            /* console.log(button_id); */
            var self = jQuery(button_id);
            var send_attachment_bkp = wp.media.editor.send.attachment;
            var button = jQuery(button_id);
            var id = button.attr('id').replace('_button', '');
            _custom_media = true;
            
            wp.media.editor.send.attachment = function(props, attachment){
                if ( _custom_media  ) {
                    var attachment_url = attachment.url;
                    jQuery('.memorandum_url').val(attachment_url);
                    jQuery('.memorandum_id').val(attachment.id);
                } else {
                    return _orig_send_attachment.apply( button_id, [props, attachment] );
                }
            }
            wp.media.editor.open(button);

            return false;
        });
    }
    media_upload_memorandum('.memorandum-upload');
    
    /**************** BANK FILE UPLOAD - by GD *****************/
function media_upload_bankfile( button_class) {
        jQuery('body').on('click',button_class, function(e) {
            var button_id ='#'+jQuery(this).attr('id');
            var button_class ='.'+jQuery(this).attr('id');
            var site_url = jQuery(this).data('url');
            var team_order = jQuery(this).data('team-order');
            /* console.log(button_id); */
            var self = jQuery(button_id);
            var send_attachment_bkp = wp.media.editor.send.attachment;
            var button = jQuery(button_id);
            var id = button.attr('id').replace('_button', '');
            _custom_media = true;
            
            wp.media.editor.send.attachment = function(props, attachment){
                if ( _custom_media  ) {
                    var attachment_url = attachment.url;
                    jQuery('.bank_file_url'+team_order).val(attachment_url);
                    jQuery('.bank_file_id'+team_order).val(attachment.id);
                } else {
                    return _orig_send_attachment.apply( button_id, [props, attachment] );
                }
            }
            wp.media.editor.open(button);

            return false;
        });
    }
    media_upload_bankfile('.bank-file-upload');
    
     /**************** Financial FILE UPLOAD - by GD *****************/
function media_upload_financialfile( button_class) {
        jQuery('body').on('click',button_class, function(e) {
            var button_id ='#'+jQuery(this).attr('id');
            var button_class ='.'+jQuery(this).attr('id');
            var site_url = jQuery(this).data('url');
            var team_order = jQuery(this).data('team-order');
            /* console.log(button_id); */
            var self = jQuery(button_id);
            var send_attachment_bkp = wp.media.editor.send.attachment;
            var button = jQuery(button_id);
            var id = button.attr('id').replace('_button', '');
            _custom_media = true;
            
            wp.media.editor.send.attachment = function(props, attachment){
                if ( _custom_media  ) {
                    var attachment_url = attachment.url;
                    jQuery('.financial_file_url'+team_order).val(attachment_url);
                    jQuery('.financial_file_id'+team_order).val(attachment.id);
                } else {
                    return _orig_send_attachment.apply( button_id, [props, attachment] );
                }
            }
            wp.media.editor.open(button);

            return false;
        });
    }
    media_upload_financialfile('.financial-file-upload');
    
         /**************** Extra FILE UPLOAD - by GD *****************/
function media_upload_extrafile( button_class) {
        jQuery('body').on('click',button_class, function(e) {
            var button_id ='#'+jQuery(this).attr('id');
            var button_class ='.'+jQuery(this).attr('id');
            var site_url = jQuery(this).data('url');
            var team_order = jQuery(this).data('team-order');
            /* console.log(button_id); */
            var self = jQuery(button_id);
            var send_attachment_bkp = wp.media.editor.send.attachment;
            var button = jQuery(button_id);
            var id = button.attr('id').replace('_button', '');
            _custom_media = true;
            
            wp.media.editor.send.attachment = function(props, attachment){
                if ( _custom_media  ) {
                    var attachment_url = attachment.url;
                    jQuery('.extra_file_url'+team_order).val(attachment_url);
                    jQuery('.extra_file_id'+team_order).val(attachment.id);
                } else {
                    return _orig_send_attachment.apply( button_id, [props, attachment] );
                }
            }
            wp.media.editor.open(button);

            return false;
        });
    }
    media_upload_extrafile('.extra-file-upload');
    
    // END OF FILE UPLOAD JS ACTIONS
    
/* --------------------------------------------------------
*               Logo / Profile Upload button
----------------------------------------------------------- */
    function logo_upload_banner( button_class) {
        jQuery('body').on('click',button_class, function(e) {
            var button_id ='#'+jQuery(this).attr('id');
            var button_class ='.'+jQuery(this).attr('id');
            var site_url = jQuery(this).data('url');
            /* console.log(button_id); */
            var self = jQuery(button_id);
            var send_attachment_bkp = wp.media.editor.send.attachment;
            var button = jQuery(button_id);
            var id = button.attr('id').replace('_button', '');
            _custom_media = true;
            
            wp.media.editor.send.attachment = function(props, attachment){
                if ( _custom_media  ) {
                    var attachment_url = attachment.url;
                    jQuery('.banner_image_url').val(attachment_url);
                    jQuery('.banner_image_id').val(attachment.id);
                } else {
                    return _orig_send_attachment.apply( button_id, [props, attachment] );
                }
            }
            wp.media.editor.open(button);

            return false;
        });
    }
    logo_upload_banner('.banner-upload');


});
