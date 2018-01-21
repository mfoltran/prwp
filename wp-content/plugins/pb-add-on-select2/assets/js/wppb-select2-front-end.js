
/**
 * Function that initializes select2 on fields
 *
 * @since v.1.0.6
 */
function wppb_initialize_select2() {
    jQuery ( '.custom_field_select2' ).each( function(){
        var selectElement = jQuery( this );
        var arguments = selectElement.attr('data-wppb-select2-arguments');
        arguments = JSON.parse( arguments );
        selectElement.select2( arguments );
    });
}

jQuery( document ).ready(function() {
    wppb_initialize_select2();
});