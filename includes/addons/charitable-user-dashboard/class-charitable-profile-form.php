<?php
/**
 * Class that manages the display and processing of the profile form.
 *
 * @package		Charitable/Classes/Charitable_Profile_Form
 * @version 	1.0.0
 * @author 		Eric Daams
 * @copyright 	Copyright (c) 2014, Studio 164a
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License  
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'Charitable_Profile_Form' ) ) : 

/**
 * Charitable_Profile_Form
 *
 * @since 		1.0.0
 */
class Charitable_Profile_Form extends Charitable_Form {

	/**
	 * Shortcode parameters. 
	 *
	 * @var 	array
	 * @access  protected
	 */
	protected $shortcode_args;

	/**
	 * @var 	string
	 */
	protected $nonce_action = 'charitable_user_profile';

	/**
	 * @var 	string
	 */
	protected $nonce_name = '_charitable_user_profile_nonce';

	/**
	 * Action to be executed upon form submission. 
	 *
	 * @var 	string
	 * @access  protected
	 */
	protected $form_action = 'update_profile';

	/**
	 * Create class object.
	 * 
	 * @param 	array 		$args 		User-defined shortcode attributes.
	 * @access 	public
	 * @since	1.0.0
	 */
	public function __construct( $args ) {	
		$this->id = uniqid();	
		$this->shortcode_args = $args;		
		$this->attach_hooks_and_filters();	
	}

	/**
	 * Profile fields to be displayed.  	
	 *
	 * @return 	array
	 * @access  public
	 * @since 	1.0.0
	 */
	public function get_fields() {
		$donor = new Charitable_Donor( wp_get_current_user() );

		$user_fields = apply_filters( 'charitable_user_fields', array(
			'first_name' => array( 
				'label' 	=> __( 'First name', 'charitable' ), 
				'type'		=> 'text', 
				'priority'	=> 2, 
				'required'	=> true, 
				'value'		=> $donor->first_name
			),
			'last_name' => array( 
				'label' 	=> __( 'Last name', 'charitable' ), 				
				'type'		=> 'text', 
				'priority'	=> 4, 
				'required'	=> true, 
				'value'		=> $donor->last_name
			),
			'user_email' => array(
				'label' 	=> __( 'Email', 'charitable' ), 
				'type'		=> 'email',
				'required' 	=> true, 
				'priority'	=> 6, 
				'value' 	=> $donor->user_email
			),
			'company' => array(
				'label' 	=> __( 'Company', 'charitable' ), 				
				'type'		=> 'text', 
				'priority'	=> 8, 
				'required'	=> false, 
				'value' 	=> $donor->get( 'donor_company' )
			)
		) );

		uasort( $user_fields, 'charitable_priority_sort' );

		$address_fields = apply_filters( 'charitable_user_address_fields', array(
			'address' => array( 
				'label' 	=> __( 'Address', 'charitable' ), 				
				'type'		=> 'text', 
				'priority'	=> 22, 
				'required'	=> false, 
				'value' 	=> $donor->get( 'donor_address' )
			),
			'address_2' => array( 
				'label' 	=> __( 'Address 2', 'charitable' ), 
				'type'		=> 'text', 
				'priority' 	=> 24, 
				'required'	=> false,			
				'value' 	=> $donor->get( 'donor_address_2' )
			),
			'city' => array( 
				'label' 	=> __( 'City', 'charitable' ), 			
				'type'		=> 'text', 
				'priority'	=> 26, 
				'required'	=> false, 
				'value' 	=> $donor->get( 'donor_city' )
			),
			'state' => array( 
				'label' 	=> __( 'State', 'charitable' ), 				
				'type'		=> 'text', 
				'priority'	=> 28, 
				'required'	=> false, 
				'value' 	=> $donor->get( 'donor_state' )
			),
			'postcode' => array( 
				'label' 	=> __( 'Postcode / ZIP code', 'charitable' ), 				
				'type'		=> 'text', 
				'priority'	=> 30, 
				'required'	=> false, 
				'value' 	=> $donor->get( 'donor_postcode' )
			),
			'country' => array( 
				'label' 	=> __( 'Country', 'charitable' ), 				
				'type'		=> 'select', 
				'options' 	=> charitable_get_location_helper()->get_countries(), 
				'priority'	=> 32, 
				'required'	=> false, 
				'value' 	=> $donor->get( 'donor_country' )
			),
			'phone' => array( 
				'label' 	=> __( 'Phone', 'charitable' ), 				
				'type'		=> 'text', 
				'priority'	=> 34, 
				'required'	=> false, 
				'value'		=> $donor->get( 'donor_phone' )
			)
		) );

		uasort( $address_fields, 'charitable_priority_sort' );

		$social_fields = apply_filters( 'charitable_user_social_fields', array(
			'twitter' => array( 
				'label' 	=> __( 'Twitter', 'charitable' ), 				
				'type'		=> 'text', 
				'priority'	=> 42, 
				'required'	=> false, 
				'value'		=> $donor->get( 'twitter' )
			),
			'facebook' => array( 
				'label' 	=> __( 'Facebook', 'charitable' ), 				
				'type'		=> 'text', 
				'priority'	=> 44, 
				'required'	=> false, 
				'value'		=> $donor->get( 'facebook' )
			)
		) );

		uasort( $social_fields, 'charitable_priority_sort' );

		/** 
		 * Combine all fields together. 
		 */
		$fields = apply_filters( 'charitable_user_profile_fields', array(
			'user_fields' => array(
				'legend'	=> __( 'Your Details', 'charitable' ),
				'type'		=> 'fieldset',
				'fields'	=> $user_fields, 
				'priority'	=> 0
			), 
			'address_fields' => array(
				'legend'	=> __( 'Your Address', 'charitable' ),
				'type'		=> 'fieldset',
				'fields'	=> $address_fields, 
				'priority' 	=> 20
			),
			'social_fields'	=> array(
				'legend'	=> __( 'Your Social Profiles', 'charitable' ),
				'type'		=> 'fieldset',
				'fields'	=> $social_fields, 
				'priority' 	=> 40
			)
		) );		

		uasort( $fields, 'charitable_priority_sort' );

		return $fields;
	}

	/**
	 * Update profile after form submission. 
	 *
	 * @return 	void
	 * @access  public
	 * @static
	 * @since 	1.0.0
	 */
	public static function update_profile() {
		
		$form = new Charitable_Profile_Form();

		if ( ! $form->validate_nonce() ) {
			return;
		}

		echo '<pre>';
		print_r( $_POST );
		die;

	}
}

endif; // End class_exists check