<?php

// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;

// Return if PMS is not active
if( ! defined( 'PMS_VERSION' ) ) return;

Class PMS_Payment_Gateway_Stripe extends PMS_Payment_Gateway_Stripe_Legacy {

    /**
     * The features supported by the payment gateway
     *
     * @access public
     * @var array
     *
     */
    public $supports;


    /**
     * Initialisation
     *
     */
    public function init() {

        parent::init();

        $this->supports = array(
            'plugin_scheduled_payments',
            'recurring_payments',
            'subscription_sign_up_fee',
            'subscription_free_trial',
            'change_subscription_payment_method_admin'
        );

        // Add the needed sections for the checkout forms
        add_filter( 'pms_extra_form_sections', array( __CLASS__, 'register_form_sections' ), 25, 2 );

        // Add the needed form fields for the checkout forms
        add_filter( 'pms_extra_form_fields', array( __CLASS__, 'register_form_fields' ), 25, 2 );

    }


    /**
     * Register the Credit Card and Billing Details sections
     *
     * @param array  $sections
     * @param string $form_location
     *
     */
    public static function register_form_sections( $sections = array(), $form_location = '' ) {

        if( ! in_array( $form_location, array( 'register', 'new_subscription', 'upgrade_subscription', 'renew_subscription', 'retry_payment' ) ) )
            return $sections;

        // Add the credit card details if it does not exist
        if( empty( $sections['credit_card_information'] ) ) {

            $sections['credit_card_information'] = array(
                'name'    => 'credit_card_information',
                'element' => 'ul',
                'class'   => 'pms-credit-card-information pms-section-credit-card-information'
            );

        }

        // make sure there is no other "Billing Details" section added, probably by a payment gateway
        // add a new field section that will contain the "Billing Details" fields
        if( empty( $sections['billing_details'] ) ) {

            $sections['billing_details'] = array(
                'name'    => 'billing_details',
                'element' => 'ul',
                'class'   => 'pms-billing-details pms-section-billing-details'
            );

        }

        return $sections;

    }


    /**
     * Register the Credit Card and Billing Fields to the checkout forms
     *
     * @param array $fields
     *
     * @return array
     *
     */
    public static function register_form_fields( $fields = array(), $form_location = '' ) {

        if( ! in_array( $form_location, array( 'register', 'new_subscription', 'upgrade_subscription', 'renew_subscription', 'retry_payment' ) ) )
            return $fields;


        /**
         * Add the Credit Card fields
         *
         */
        $fields['pms_credit_card_heading'] = array(
            'section'         => 'credit_card_information',
            'type'            => 'heading',
            'default'         => '<h4>' . __( 'Credit Card Information', 'pms-add-on-stripe' ) . '</h4>',
            'element_wrapper' => 'li',
        );

        $fields['pms_card_number'] = array(
            'section'         => 'credit_card_information',
            'type'            => 'text',
            'name'            => 'pms_card_number',
            'value'           => '',
            'label'           => __( 'Card Number', 'pms-add-on-stripe' ),
            'element_wrapper' => 'li',
            'required'        => 1,
            'element_attributes' => array( 'size' => 4, 'maxlength' => 4 )
        );

        $fields['pms_card_cvv'] = array(
            'section'         => 'credit_card_information',
            'type'            => 'text',
            'name'            => 'pms_card_cvv',
            'value'           => '',
            'label'           => __( 'Card CVV', 'pms-add-on-stripe' ),
            'element_wrapper' => 'li',
            'required'        => 1,
            'element_attributes' => array( 'size' => 20, 'maxlength' => 20 )
        );

        $fields['pms_card_exp_date'] = array(
            'section'         => 'credit_card_information',
            'type'            => 'card_expiration_date',
            'label'           => __( 'Expiration Date', 'pms-add-on-stripe' ),
            'element_wrapper' => 'li',
            'required'        => 1
        );
        


        /**
         * Add the Billing Fields
         *
         */
        $fields['pms_billing_details_heading'] = array(
            'section'         => 'billing_details',
            'type'            => 'heading',
            'default'         => '<h4>' . __( 'Billing Details', 'pms-add-on-stripe' ) . '</h4>',
            'element_wrapper' => 'li',
        );

        $fields['pms_billing_first_name'] = array(
            'section'         => 'billing_details',
            'type'            => 'text',
            'name'            => 'pms_billing_first_name',
            'default'         => '',
            'value'           => ( isset( $_POST['pms_billing_first_name'] ) ? $_POST['pms_billing_first_name'] : '' ),
            'label'           => __( 'Billing First Name', 'pms-add-on-stripe' ),
            'description'     => '',
            'element_wrapper' => 'li',
            'required'        => 1
        );

        $fields['pms_billing_last_name'] = array(
            'section'         => 'billing_details',
            'type'            => 'text',
            'name'            => 'pms_billing_last_name',
            'default'         => '',
            'value'           => ( isset( $_POST['pms_billing_last_name'] ) ? $_POST['pms_billing_last_name'] : '' ),
            'label'           => __( 'Billing Last Name', 'pms-add-on-stripe' ),
            'description'     => '',
            'element_wrapper' => 'li',
            'required'        => 1
        );

        $fields['pms_billing_address'] = array(
            'section'         => 'billing_details',
            'type'            => 'text',
            'name'            => 'pms_billing_address',
            'default'         => '',
            'value'           => ( isset( $_POST['pms_billing_address'] ) ? $_POST['pms_billing_address'] : '' ),
            'label'           => __( 'Billing Address', 'pms-add-on-stripe' ),
            'description'     => '',
            'element_wrapper' => 'li',
            'required'        => 1
        );

        $fields['pms_billing_city'] = array(
            'section'         => 'billing_details',
            'type'            => 'text',
            'name'            => 'pms_billing_city',
            'default'         => '',
            'value'           => ( isset( $_POST['pms_billing_city'] ) ? $_POST['pms_billing_city'] : '' ),
            'label'           => __( 'Billing City', 'pms-add-on-stripe' ),
            'description'     => '',
            'element_wrapper' => 'li',
            'required'        => 1
        );

        $fields['pms_billing_zip'] = array(
            'section'         => 'billing_details',
            'type'            => 'text',
            'name'            => 'pms_billing_zip',
            'default'         => '',
            'value'           => ( isset( $_POST['pms_billing_zip'] ) ? $_POST['pms_billing_zip'] : '' ),
            'label'           => __( 'Billing Zip / Postal Code', 'pms-add-on-stripe' ),
            'description'     => '',
            'element_wrapper' => 'li',
            'required'        => 1
        );

        $fields['pms_billing_country'] = array(
            'section'         => 'billing_details',
            'type'            => 'select',
            'name'            => 'pms_billing_country',
            'default'         => ( isset( $_POST['pms_billing_country'] ) ? $_POST['pms_billing_country'] : '' ),
            'label'           => __( 'Billing Country', 'pms-add-on-stripe' ),
            'options'         => function_exists( 'pms_get_countries' ) ? pms_get_countries() : array(),
            'description'     => '',
            'element_wrapper' => 'li',
            'required'        => 1
        );

        $fields['pms_billing_state'] = array(
            'section'         => 'billing_details',
            'type'            => 'text',
            'name'            => 'pms_billing_state',
            'default'         => '',
            'value'           => ( isset( $_POST['pms_billing_state'] ) ? $_POST['pms_billing_state'] : '' ),
            'label'           => __( 'Billing State / Province', 'pms-add-on-stripe' ),
            'description'     => '',
            'element_wrapper' => 'li',
            'required'        => 1

        );

        return $fields;

    }


    /**
     * Create the customer and save the customer's card id in Stripe and also save their ids as metadata
     * for the provided subscription as the payment method metadata needed for future payments
     *
     * @param int $member_subscription_id
     *
     * @return bool
     *
     */
    public function register_automatic_billing_info( $member_subscription_id = 0 ) {

        if( empty( $this->secret_key ) )
            return false;

        if( empty( $member_subscription_id ) )
            return false;

        // Set API key
        \Stripe\Stripe::setApiKey( $this->secret_key );

        // Verify API key
        try {

            \Stripe\Account::retrieve();

        } catch( Exception $e ) {

            $error = __( 'Stripe API key is not valid.', 'pms-add-on-stripe' );

        }

        if( ! empty( $errors ) )
            return false;


        /**
         * Grab the Stripe customer, if it doesn't exist create it and return it
         *
         */
        $new_customer = false;

        if( false === ( $customer = $this->get_customer( $this->user_id ) ) ) {

            $customer     = $this->create_customer();
            $new_customer = true;

        }


        /**
         * We want to save the customer's id and the card the customer used in the
         * subscription's meta table for future uses
         *
         */
        if( $customer ) {

            $card_id = '';

            if( true === $new_customer ) {

                $card_id = $customer->default_source;

            } elseif( false === $new_customer && ! empty( $this->stripe_token ) ) {

                // Add the token as the card for the customer
                $card = $customer->sources->create( array(
                    'source' => $this->stripe_token
                ));

                $card_id = $card->id;

            }

            // Save the customer and card to the subscription
            if( function_exists( 'pms_update_member_subscription_meta' ) ) {

                // Save the customer id
                pms_update_member_subscription_meta( $member_subscription_id, '_stripe_customer_id', $customer->id );

                // Save the card id
                if( ! empty( $card_id )  )
                    pms_update_member_subscription_meta( $member_subscription_id, '_stripe_card_id', $card_id );

            }

            return true;

        }

        return false;

    }


    /**
     * Processes a one time payment for the amount set in the passed payment
     *
     * @param int $payment_id               - the id of the payment that should be processed
     * @param int $member_subscription_id   - (optional) the id of the member subscription for which the payment
     *                                        is made
     *
     * @return bool
     *
     */
    public function process_payment( $payment_id = 0, $member_subscription_id = 0 ) {

        if( empty( $this->secret_key ) )
            return false;

        if( empty( $payment_id ) )
            return false;
        

        // Set API key
        \Stripe\Stripe::setApiKey( $this->secret_key );

        // Verify API key
        try {

            \Stripe\Account::retrieve();

        } catch( Exception $e ) {

            $error = __( 'Stripe API key is not valid.', 'paid-member-subscriptions' );

        }

        if( ! empty( $errors ) )
            return false;

        
        // Get the payment
        $payment = pms_get_payment( $payment_id );

        // Set subscription plan
        if( empty( $this->subscription_plan ) )
            $this->subscription_plan = pms_get_subscription_plan( $payment->subscription_id );


        // Get the customer and card id from the database
        if( function_exists( 'pms_get_member_subscription_meta' ) && ! empty( $member_subscription_id ) ) {

            $customer_id = pms_get_member_subscription_meta( $member_subscription_id, '_stripe_customer_id', true );
            $card_id     = pms_get_member_subscription_meta( $member_subscription_id, '_stripe_card_id', true );

        }

        // If we don't have a saved customer id or card id get the customer directly from Stripe
        if( empty( $customer_id ) || empty( $card_id ) ) {

            $customer = $this->get_customer( $payment->user_id );

            if( $customer ) {
                $customer_id = $customer->id;
                $card_id     = $customer->default_source;
            }

        }

        if( $payment->amount > 0 ) {

            // Make the payment
            try {

                if( ! empty( $customer_id ) && ! empty( $card_id ) ) {

                    $charge = \Stripe\Charge::create(
                        array(
                            'amount'        => $payment->amount * 100, // because cents
                            'currency'      => $this->currency,
                            'customer'      => $customer_id,
                            'source'        => $card_id,
                            'description'   => $this->subscription_plan->name
                        )
                    );

                    // Complete payment
                    $payment->update( array( 'transaction_id' => $charge->id, 'status' => 'completed' ) );

                } else {

                    $error = __( 'Something went wrong. Please try again.', 'paid-member-subscriptions' );

                }

            } catch( Exception $e ) {

                $error = __( 'Something went wrong. Please try again.', 'paid-member-subscriptions' );

            }

        } else {

            $payment->update( array( 'status' => 'completed' ) );

        }
        

        // Add error if it exists
        if( ! empty( $error ) ) {

            pms_errors()->add( 'failed_payment', $error );

            return false;

        } else
            return true;

    }

}
