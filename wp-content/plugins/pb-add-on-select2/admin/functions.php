<?php

/*
 * Function that saves the Select2 (Multiple) fields when email confirmation is on
 *
 * @since v.1.0.3
 *
 */
function wppb_sl2m_add_meta_on_user_activation( $user_id, $password, $meta, $field = 'not_set' ){
    if ( $field == 'not_set' ){
        // backwards compatibilty with PB versions <= 2.4.9
        $manage_field_options = get_option('wppb_manage_fields');

        //for every Select2 (Multiple) meta-name
        foreach( $manage_field_options as $field ) {
                if($field['field'] == 'Select2 (Multiple)' ) {
                    $meta_name = sanitize_text_field( $field['meta-name'] );
                    if (isset($meta[$meta_name])) {
                        $selected_values = wppb_process_multipl_select2_value($field, $meta);
                        update_user_meta($user_id, $meta_name, trim($selected_values, ','));
                    }
                }
        }
    }else{
        if($field['field'] == 'Select2 (Multiple)' ){
            $meta_name = $field['meta-name'];
            if ( !empty ( $meta[$meta_name] ) ) {
                $selected_values = wppb_process_multipl_select2_value( $field, $meta );
                update_user_meta( $user_id, sanitize_text_field( $meta_name ), trim( $selected_values, ',' ) );
            }
        }
    }
}
add_action( 'wppb_add_meta_on_user_activation_select2-multiple', 'wppb_sl2m_add_meta_on_user_activation', 10, 4 );