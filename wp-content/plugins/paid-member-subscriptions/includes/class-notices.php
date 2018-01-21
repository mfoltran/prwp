<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Class that adds notices
 *
 * @since v.1.0
 *
 * @return void
 */
class PMS_Add_General_Notices{

    /**
     * Notification id
     *
     * @access public
     * @var string
     */
    public $notificationId = '';

    /**
     * Notification message
     *
     * @access public
     * @var string
     */
    public $notificationMessage = '';

    /**
     * Notification class
     *
     * @access public
     * @var string
     */
    public $notificationClass = '';

    /**
     * Start date
     *
     * @access public
     * @var string
     */
    public $startDate = '';

    /**
     * End date
     *
     * @access public
     * @var string
     */
    public $endDate = '';

    function __construct( $notificationId, $notificationMessage, $notificationClass = 'updated' , $startDate = '', $endDate = '' ){
        $this->notificationId = $notificationId;
        $this->notificationMessage = $notificationMessage;
        $this->notificationClass = $notificationClass;

        if( !empty( $startDate ) && time() < strtotime( $startDate ) )
            return;

        if( !empty( $endDate ) && time() > strtotime( $endDate ) )
            return;

        add_action( 'admin_notices', array( $this, 'add_admin_notice' ) );
        add_action( 'admin_init', array( $this, 'dismiss_notification' ) );
    }


    // Display a notice that can be dismissed
    function add_admin_notice() {
        global $current_user ;
        global $pagenow;

        $user_id = $current_user->ID;
        do_action( $this->notificationId.'_before_notification_displayed', $current_user, $pagenow );

        if ( current_user_can( 'manage_options' ) ){
            // Check that the user hasn't already clicked to ignore the message
            if ( ! get_user_meta($user_id, $this->notificationId.'_dismiss_notification' ) ) {
                echo $finalMessage = apply_filters($this->notificationId.'_notification_message','<div class="'. $this->notificationClass .'" >'.$this->notificationMessage.'</div>', $this->notificationMessage);
            }
            do_action( $this->notificationId.'_notification_displayed', $current_user, $pagenow );
        }
        do_action( $this->notificationId.'_after_notification_displayed', $current_user, $pagenow );
    }

    function dismiss_notification() {
        global $current_user;

        $user_id = $current_user->ID;

        do_action( $this->notificationId.'_before_notification_dismissed', $current_user );
        
        // If user clicks to ignore the notice, add that to their user meta
        if ( isset( $_GET[$this->notificationId.'_dismiss_notification']) && '0' == $_GET[$this->notificationId.'_dismiss_notification'] )
            add_user_meta( $user_id, $this->notificationId.'_dismiss_notification', 'true', true );

        do_action( $this->notificationId.'_after_notification_dismissed', $current_user );
    }
}