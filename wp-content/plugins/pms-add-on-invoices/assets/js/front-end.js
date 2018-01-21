// Hide "Billing Details" section if selected subscription plan is free (price == 0), or selected payment gateway is CC
jQuery( function($) {

    var $section_billing_details = $('.pms-section-billing-details');

    // Subscription plan and payment gateway selector
    var subscription_plan_selector = 'input[name=subscription_plans]';
    var paygate_selector 		   = 'input[name=pay_gate]';

    $(document).ready( function() {

        $(document).on( 'click', paygate_selector, function() {

	    	$section_billing_details.show();

	    });

	    $(document).on( 'click', subscription_plan_selector, function() {

	    	$(paygate_selector + ':checked').trigger('click');

	    });

        $section_billing_details.show();
        
        $(subscription_plan_selector + ':checked').trigger('click');

    });


    /**
     * Add a class "pms-has-value" to the billing email address if it is not empty
     * This will be used to not autocomplete the field when the user_email is being introduced
     *
     */
    $(document).ready( function() {

        $('input[name=pms_billing_email]').each( function() {

            if( $(this).val() != '' )
                $(this).addClass( 'pms-has-value' );

        });

    });

    /**
     * Fill in billing email address when typing the email address
     *
     */
    $(document).on( 'keyup', '#pms_user_email, .wppb-form-field input[name=email]', function() {

        if( $(this).closest('form').find('[name=pms_billing_email]').length == 0 )
            return false;

        if( $(this).closest('form').find('[name=pms_billing_email]').hasClass('pms-has-value') )
            return false;

        $(this).closest('form').find('[name=pms_billing_email]').val( $(this).val() );

    });

});