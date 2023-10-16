jQuery('.single_add_to_cart_button').attr('disabled', true);
jQuery( document ).ready(function() {
jQuery('.single_add_to_cart_button').attr('disabled', true);
setTimeout(function() { 
    jQuery('.single_add_to_cart_button').attr('disabled', false);
}, 600);
});
setInterval(function()
{  
    jQuery.ajax({
    type: 'POST',
    url: obj.ajaxurl,
    dataType: "html", // add data type
    data: { action : 'get_ajax_posts' },
    success: function( response ) {
        // console.log( parseInt(response) );
        if(isNaN(parseInt(response))) {
            var response = 0;
        }
        var waiting = parseInt(response) + parseInt(1);
        //var waiting = parseInt(response) + parseInt(1);
        jQuery( '#waitingnumber' ).html( waiting ); 
        jQuery( '#waiting_number' ).val( waiting ); 
        jQuery( '#wpwc_custom_registration_field_85' ).val( waiting ); 
        // jQuery('.single_add_to_cart_button').attr('disabled', false);
    }
});
}, 300);

