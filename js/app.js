jQuery(document).ready(function(){
    jQuery('#urlat_in_wp_menu').parent().addClass('urlat_in_wp_menu_parent')
    .mouseenter(function(){
        jQuery(this).addClass('urlat_in_wp_menu_parent_over');
        jQuery('#urlat_in_wp_widget').show();
        jQuery('#urlat_in_wp_url').focus();
    })
    .click(function(){
        jQuery(this).toggleClass('urlat_in_wp_menu_parent_over');
        jQuery('#urlat_in_wp_widget').toggle()
        });
    jQuery('#urlat_in_wp_menu').show();

    jQuery('#urlat_in_wp_url').keyup(function(event) {

        if (event.ctrlKey && event.keyCode == '86') {
            event.preventDefault();
            jQuery('#urlat_in_wp_widget form').submit();
        }

    });

    jQuery('#urlat_in_wp_shortly input').click(function(){
        jQuery(this).select()
        });
    jQuery('#urlat_in_wp_shortly span').click(function(){
        jQuery('#urlat_in_wp_shortly').hide();
        jQuery('#urlat_in_wp_url').focus()
        });

    jQuery('#urlat_in_wp_widget form').submit(function(_event){
        _event.preventDefault();
        jQuery('#urlat_in_wp_shortly').hide();
        jQuery('#urlat_in_wp_url').animate({
            width:'hide'
        },'fast');

        jQuery.ajax({
            url: urlat_in_url_proxy+'?user='+encodeURIComponent(urlat_in_user)+'&pass='+encodeURIComponent(urlat_in_pass),
            type: 'POST',
            data: jQuery(this).serialize(),
            dataType: 'json',
            success: function (_data) {

                if (_data.status == 'ok') {
                    jQuery('#urlat_in_wp_shortly').show();
                    jQuery('#urlat_in_wp_shortly input').val(_data.shortly).focus().select();					  
                    jQuery('#urlat_in_wp_url').val('');
                }
                else jQuery('#urlat_in_wp_url').focus();
            },
            complete: function () {
                jQuery('#urlat_in_wp_url').animate({
                    width:'show'
                },'fast');
            }
        });
    });
   
});

var urlat_in_minify = function(_url){
        
    var jqxhr = jQuery.ajax({
        url: urlat_in_url_proxy+'?user='+encodeURIComponent(urlat_in_user)+'&pass='+encodeURIComponent(urlat_in_pass),
        type: 'POST',
        async: false,
        data: {
            url: _url
        },
        dataType: 'json'
    });
            
    return jQuery.parseJSON(jqxhr.responseText).shortly;
}
