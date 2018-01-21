<?php

// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;

// Return if PMS is not active
if( ! defined( 'PMS_VERSION' ) )
    return;


/**
 * Add Billing Details fields to forms
 *
 */
function pms_inv_register_extra_form_sections( $sections = array(), $form_location = '' ) {

	// Exit if the billing details sections is already added
	if( ! empty( $sections['billing_details'] ) )
		return $sections;

    // Add a new field section that will contain the "Billing Details" fields
    $sections[] = array(
        'name'    => 'billing_details',
        'element' => 'ul',
        'class'	  => 'pms-section-billing-details pms-billing-details'
    );

    return $sections;

}
add_filter( 'pms_extra_form_sections', 'pms_inv_register_extra_form_sections', 50, 2 );


/**
 * Add the extra fields to the Billing Details section
 *
 * @return array
 *
 */
function pms_inv_get_invoice_fields() {

    $invoice_fields = array();
    $user_id        = 0;
    $user_meta      = array();

    if( is_user_logged_in() )
        $user_id = pms_get_current_user_id();

    if( ! empty( $user_id ) ) {
        $user      = get_userdata( $user_id );
        $user_meta = get_user_meta( $user_id );
    }

    $invoice_fields['pms_billing_details_heading'] = array(
        'section'         => 'billing_details',
        'type'            => 'heading',
        'default'         => '<h3>' . __( 'Billing Details', 'pms-add-on-invoices' ) . '</h3>',
        'element_wrapper' => 'li',
    );

    $invoice_fields['pms_billing_first_name'] = array(
        'section'         => 'billing_details',
        'type'            => 'text',
        'name'            => 'pms_billing_first_name',
        'default'         => '',
        'value'           => ( isset( $_POST['pms_billing_first_name'] ) ? $_POST['pms_billing_first_name'] : ( !(empty($user_meta['pms_billing_first_name'])) ? $user_meta['pms_billing_first_name'][0] : ( ! empty( $user->first_name ) ? $user->first_name : '' ) ) ),
        'label'           => __( 'Billing First Name', 'pms-add-on-invoices' ),
        'description'     => '',
        'element_wrapper' => 'li',
        'required'        => 1
    );

    $invoice_fields['pms_billing_last_name'] = array(
        'section'         => 'billing_details',
        'type'            => 'text',
        'name'            => 'pms_billing_last_name',
        'default'         => '',
        'value'           => ( isset( $_POST['pms_billing_last_name'] ) ? $_POST['pms_billing_last_name'] : ( !(empty($user_meta['pms_billing_last_name'])) ? $user_meta['pms_billing_last_name'][0] : ( ! empty( $user->last_name ) ? $user->last_name : '' ) ) ),
        'label'           => __( 'Billing Last Name', 'pms-add-on-invoices' ),
        'description'     => '',
        'element_wrapper' => 'li',
        'required'        => 1
    );

    $invoice_fields['pms_billing_email'] = array(
        'section'         => 'billing_details',
        'type'            => 'text',
        'name'            => 'pms_billing_email',
        'default'         => '',
        'value'           => ( isset( $_POST['pms_billing_email'] ) ? $_POST['pms_billing_email'] : ( !(empty($user_meta['pms_billing_email'])) ? $user_meta['pms_billing_email'][0] : ( ! empty( $user->user_email ) ? $user->user_email : '' ) ) ),
        'label'           => __( 'Billing Email', 'pms-add-on-invoices' ),
        'description'     => '',
        'element_wrapper' => 'li',
        'required'        => 1
    );

    $invoice_fields['pms_billing_company'] = array(
        'section'         => 'billing_details',
        'type'            => 'text',
        'name'            => 'pms_billing_company',
        'default'         => '',
        'value'           => ( isset( $_POST['pms_billing_company'] ) ? $_POST['pms_billing_company'] : ( !(empty($user_meta['pms_billing_company'])) ? $user_meta['pms_billing_company'][0] : '') ),
        'label'           => __( 'Billing Company', 'pms-add-on-invoices' ),
        'description'     => __( 'If entered, this will appear on the invoice, replacing the First and Last Name.', 'pms-add-on-invoices' ),
        'element_wrapper' => 'li',
    );

    $invoice_fields['pms_billing_address'] = array(
        'section'         => 'billing_details',
        'type'            => 'text',
        'name'            => 'pms_billing_address',
        'default'         => '',
        'value'           => ( isset( $_POST['pms_billing_address'] ) ? $_POST['pms_billing_address'] : ( !(empty($user_meta['pms_billing_address'])) ? $user_meta['pms_billing_address'][0] : '') ),
        'label'           => __( 'Billing Address', 'pms-add-on-invoices' ),
        'description'     => '',
        'element_wrapper' => 'li',
        'required'        => 1
    );

    $invoice_fields['pms_billing_city'] = array(
        'section'         => 'billing_details',
        'type'            => 'text',
        'name'            => 'pms_billing_city',
        'default'         => '',
        'value'           => ( isset( $_POST['pms_billing_city'] ) ? $_POST['pms_billing_city'] : ( !(empty($user_meta['pms_billing_city'])) ? $user_meta['pms_billing_city'][0] : '') ),
        'label'           => __( 'Billing City', 'pms-add-on-invoices' ),
        'description'     => '',
        'element_wrapper' => 'li',
        'required'        => 1
    );

    $invoice_fields['pms_billing_zip'] = array(
        'section'         => 'billing_details',
        'type'            => 'text',
        'name'            => 'pms_billing_zip',
        'default'         => '',
        'value'           => ( isset( $_POST['pms_billing_zip']) ? $_POST['pms_billing_zip'] : ( !(empty($user_meta['pms_billing_zip'])) ? $user_meta['pms_billing_zip'][0] : '') ),
        'label'           => __( 'Billing Zip / Postal Code', 'pms-add-on-invoices' ),
        'description'     => '',
        'element_wrapper' => 'li'
    );

    $invoice_fields['pms_billing_country'] = array(
        'section'         => 'billing_details',
        'type'            => 'select',
        'name'            => 'pms_billing_country',
        'default'         => ( isset( $_POST['pms_billing_country'] ) ? $_POST['pms_billing_country'] : ( !(empty($user_meta['pms_billing_country'])) ? $user_meta['pms_billing_country'][0] : '') ),
        'label'           => __( 'Billing Country', 'pms-add-on-invoices' ),
        'options'         => function_exists( 'pms_get_countries' ) ? pms_get_countries() : array(),
        'description'     => '',
        'element_wrapper' => 'li',
        'required'        => 1
    );

    $invoice_fields['pms_billing_state'] = array(
        'section'         => 'billing_details',
        'type'            => 'text',
        'name'            => 'pms_billing_state',
        'default'         => '',
        'value'           => ( isset( $_POST['pms_billing_state'] ) ? $_POST['pms_billing_state'] : ( !(empty($user_meta['pms_billing_state'])) ? $user_meta['pms_billing_state'][0] : '') ),
        'label'           => __( 'Billing State / Province', 'pms-add-on-invoices' ),
        'description'     => '',
        'element_wrapper' => 'li',
        'required'        => 1

    );

    /**
     * Filter the invoice fields
     *
     * @param array $invoice_fields
     *
     */
    return apply_filters( 'pms_inv_get_invoice_fields', $invoice_fields );

}


/**
 * Gets the Billing Fields required for the invoice and adds them to all the forms
 *
 * @param array $fields
 *
 * @return array
 *
 */
function pms_inv_register_extra_form_fields( $fields = array(), $form_location = '' ) {

	$invoice_fields      = pms_inv_get_invoice_fields();
    $invoice_fields_keys = array_keys( $invoice_fields );

    $new_fields          = array();

    foreach( $fields as $field_key => $field ) {

        // Add each field again
        $new_fields[$field_key] = $field;

        // If the field is also in the invoice fields, merge the values
        if( in_array( $field_key, $invoice_fields_keys ) )
            $new_fields[$field_key] = array_merge( $field, $invoice_fields[$field_key] );

        // Add billing email and billing company after billing last name
        if( $field_key == 'pms_billing_last_name' ) {

            if( ! isset( $fields['pms_billing_email'] ) )
                $new_fields['pms_billing_email']   = $invoice_fields['pms_billing_email'];

            if( ! isset( $fields['pms_billing_company'] ) )
                $new_fields['pms_billing_company'] = $invoice_fields['pms_billing_company'];

        }

    }

    $new_fields_keys = array_keys( $new_fields );

    // If there are other invoice fields add them at the end
    foreach( $invoice_fields as $invoice_field_key => $invoice_field ) {

        if( ! in_array( $invoice_field_key, $new_fields_keys ) ) {
            $new_fields[$invoice_field_key] = $invoice_field;
        }

    }

    return $new_fields;

}
add_filter( 'pms_extra_form_fields', 'pms_inv_register_extra_form_fields', 50, 2 );


/**
 * Validate the fields from the Billing Details section
 *
 */
function pms_inv_validate_extra_form_fields() {

	/**
	 * For checkout purposes, if the subscription is free, we don't need to validate the invoice
	 * fields, as they are not needed
	 *
	 */
	if( current_filter() !== 'pms_edit_profile_form_validation' ) {

		if( empty( $_POST['pay_gate'] ) )
			return;

	}


	/**
	 * Validate the billing fields for the invoice
	 *
	 */
    $invoice_fields = pms_inv_get_invoice_fields();

    foreach ( $invoice_fields as $field ) {

        if ( empty( $field['required'] ) )
            continue;

        if ( empty( $_POST[$field['name']] ) )
            pms_errors()->add( $field['name'], __( 'This field is required.', 'pms-add-on-invoices' ) );

    }


    /**
     * Validate billing email address
     *
     */
    if( ! empty( $_POST['pms_billing_email'] ) ) {

        if( ! is_email( $_POST['pms_billing_email'] ) )
            pms_errors()->add( 'pms_billing_email', __( 'The e-mail address doesn\'t seem to be valid.', 'pms-add-on-invoices' ) );

    }

}
add_action( 'pms_process_checkout_validations', 'pms_inv_validate_extra_form_fields' );
add_action( 'pms_register_form_validation', 'pms_inv_validate_extra_form_fields' );
add_action( 'pms_edit_profile_form_validation','pms_inv_validate_extra_form_fields' );


/**
 * Saves Billing Details fields
 *
 * @param array $data
 *
 */
function pms_inv_save_extra_form_fields( $data ) {

    $hook 		= current_action();
    $user_id 	= 0;
    $payment_id = 0;

    switch ( $hook ) {

        case 'pms_register_payment' :
            // $data stores payment gateway data
            $user_id 	= $data['user_id'];
            $payment_id = $data['payment_id'];
            break;

        case 'pms_edit_profile_form_update_user' :
            // $data stores user information
            $user_id = $data;
            break;
    }


    if( ! empty( $user_id ) ) {

        $invoice_fields = pms_inv_get_invoice_fields();

        // Update payment meta and user meta
        foreach ( $invoice_fields as $field ) {

            // Exclude fields without a name
            if( empty( $field['name'] ) )
                continue;

            if( empty( $_POST[$field['name']] ) )
                continue;

            // Save invoice billing details in usermeta
            update_user_meta( $user_id, $field['name'], sanitize_text_field( $_POST[$field['name']] ) );

            // Save invoice details in payment meta
            if( function_exists( 'pms_add_payment_meta' ) && ( ! empty( $payment_id ) ) ) {
                pms_add_payment_meta( $payment_id, $field['name'], sanitize_text_field( $_POST[$field['name']] ), true );
            }

        }

    }
}
add_action( 'pms_register_payment', 'pms_inv_save_extra_form_fields', 10, 1 );  // here we send $payment_gateway_data
add_action( 'pms_edit_profile_form_update_user', 'pms_inv_save_extra_form_fields', 10, 1 ); // here we send $user_data


/**
 * Copies the billing information saved in the user_meta to the payment_meta if the status of the payment 
 * changes to "completed" and the payment doesn't have the billing fields
 *
 * @param int   $payment_id
 * @param array $data 
 *
 */
function pms_inv_payment_complete_copy_billing_fields( $payment_id = 0, $data = array() ) {

	if( empty( $payment_id ) )
		return;

	if( empty( $data ) )
		return;

	if( empty( $data['status'] ) || $data['status'] != 'completed' )
		return;

	$payment = pms_get_payment( $payment_id );

	if( is_null( $payment ) )
		return;

	// Get all invoice fields
	$invoice_fields = pms_inv_get_invoice_fields();

	// Will contain the invoice fields values from the user_meta
	$invoice_user_meta_values = array();

	foreach( $invoice_fields as $field ) {

		if( empty( $field['name'] ) )
			continue;

		$payment_billing_meta = pms_get_payment_meta( $payment_id, $field['name'], true );

		/**
		 * Exit early from the function if we find invoice field details for the payment
		 * This is done to prevent extra queries to the DB and also to preserve existing
		 * payment meta invoice information
		 *
		 */
		if( ! empty( $payment_billing_meta ) )
			return;

		$invoice_user_meta_values[$field['name']] = get_user_meta( $payment->user_id, $field['name'], true );

	}

	// Get rid of the empty values
	$invoice_user_meta_values = array_filter( $invoice_user_meta_values );

	// If we have invoice field values from the user_meta, add them to the payment meta
	if( ! empty( $invoice_user_meta_values ) ) {

		foreach( $invoice_user_meta_values as $field_name => $field_value ) {

			pms_add_payment_meta( $payment_id, $field_name, $field_value, true );

		}

	}

}
add_action( 'pms_payment_insert', 'pms_inv_payment_complete_copy_billing_fields', 100, 2 );
add_action( 'pms_payment_update', 'pms_inv_payment_complete_copy_billing_fields', 100, 2 );


/**
 * Generate invoice number when the payment is completed
 *
 * @param int   $payment_id
 * @param array $data 
 *
 */
function pms_inv_payment_complete_generate_invoice_number( $payment_id = 0, $data = array() ) {

	if( empty( $payment_id ) )
		return;

	if( empty( $data ) )
		return;

	if( empty( $data['status'] ) || $data['status'] != 'completed' )
		return;

	// Check to see if invoice number has already been set
	$payment_invoice_number = pms_get_payment_meta( $payment_id, 'pms_inv_invoice_number', true );

	if( ! empty( $payment_invoice_number ) )
		return;

	// For sanity sake we will take into account only payments that have been made after 
	// the add-on has been installed
	$payment = pms_get_payment( $payment_id );

	// This should be a timestamp
	$pms_inv_first_activation = get_option( 'pms_inv_first_activation', '' );

	if( empty( $pms_inv_first_activation ) )
		return;

	if( strtotime( $payment->date ) < $pms_inv_first_activation )
		return;
	
	// Get current general invoice number
	$invoice_number = (int)get_option( 'pms_inv_invoice_number', '' );

	// Add the invoice number to the payment
	pms_add_payment_meta( $payment_id, 'pms_inv_invoice_number', $invoice_number, true );

	// Increment invoice number
	$invoice_number = $invoice_number + 1;

	// Increment the general invoice number
	update_option( 'pms_inv_invoice_number', $invoice_number );

}
add_action( 'pms_payment_insert', 'pms_inv_payment_complete_generate_invoice_number', 105, 2 );
add_action( 'pms_payment_update', 'pms_inv_payment_complete_generate_invoice_number', 105, 2 );