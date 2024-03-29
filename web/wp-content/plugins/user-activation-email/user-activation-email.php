<?php

/**
 *	Plugin Name: User Activation Email
 *	Plugin URI: https://github.com/NateJacobs/User-Activation-Email
 *	Description: Add an activation code to the new user email sent once a user registers. The user must enter this activation code in addition to a username and password to log in successfully the first time.
 *	Version: 0.4
 *	License: GPL V2
 *	Author: Nate Jacobs <nate@natejacobs.org>
 *	Author URI: http://natejacobs.org
 */

class UserActivationEmail
{
	CONST user_meta = "uae_user_activation_code";
	
	// hook into actions and filters
	public function __construct()
	{
		// since 0.3
		add_action('init', array( __CLASS__, 'localization' ) );
		// since 0.1
		add_filter( 'authenticate', array( __CLASS__, 'check_user_activation_code' ), 11, 3 );
		add_action( 'login_form', array( __CLASS__, 'add_login_field' ) );
		add_action( 'user_register', array( __CLASS__, 'add_activation_code' ) );
		add_action( 'wp_login', array( __CLASS__, 'update_activation_code' ) );
		// since 0.2
		add_action( 'show_user_profile', array( __CLASS__, 'add_user_profile_fields' ) );
		add_action( 'edit_user_profile', array( __CLASS__, 'add_user_profile_fields' ) );
		add_action( 'personal_options_update', array( __CLASS__, 'save_user_profile_fields' ) );
		add_action( 'edit_user_profile_update', array( __CLASS__, 'save_user_profile_fields' ) );
		// since 0.3
		register_activation_hook( __FILE__, array( __CLASS__, 'activate' ) );
	}
	
	public function localization() {
  		load_plugin_textdomain( 'user-activation-email', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' ); 
	}
	
	/** 
	 *	Activation
	 *
	 *	Upon plugin activation create a custom user meta key of uae_user_activation_code
	 *	for all users and set the value to active (user is already active). Also registers
	 *	uninstall hook.
	 *
	 *	@author		Nate Jacobs
	 *	@since		0.3
	 */
	public function activate()
	{
		// limit user data returned to just the id
		$args = array( 'fields' => 'ID' );
		$users = get_users( $args );
		// loop through each user
		foreach ( $users as $user )
		{
			// add the custom user meta to the wp_usermeta table
			add_user_meta( $user, self::user_meta, 'active' );
		}
		
		register_uninstall_hook( __FILE__, array( __CLASS__, 'uninstall' ) );
	}
	
	/** 
	 *	Uninstall
	 *
	 *	Loops through all users and removes the user meta of uae_user_activation_code
	 *
	 *	@author		Nate Jacobs
	 *	@since		0.3
	 */
	public function uninstall()
	{
		// limit user data returned to just the id
		$args = array( 'fields' => 'ID' );
		$users = get_users( $args );
		// loop through each user
		foreach ( $users as $user )
		{
			// delete the custom user meta in the wp_usermeta table
			delete_user_meta( $user, self::user_meta );
		}
	}
		
	/** 
	 *	Check Activation Code
	 *
	 *	Compares the user entered activation code with the code created upon registration.
	 *	If the activation code is the same allow access. If the user is already activated,
	 *	open the gates.
	 *
	 *	@author		Nate Jacobs
	 *	@since		0.1
	 *
	 *	@param	string	$user
	 *	@param	string	$user_login
	 *	@param	string	$password
	 */
	public function check_user_activation_code( $user, $user_login, $password )
	{
		$activation_code = '';
		
		// get user data by login
		$user_info = get_user_by( 'login', $user_login );

		// if the user has entered something in the user name box
		if ( $user_info )
		{
			// get the custom user meta defined during registration
			$activation_code = get_user_meta( $user_info->ID, self::user_meta, true );
		}
		if ( empty( $user_login ) || empty($password) )
		{
			if ( empty($username) )
				$user = new WP_Error( 'empty_username', __( '<strong>ERROR</strong>: The username field is empty.', 'user-activation-email' ) );
	
			if ( empty($password) )
				$user = new WP_Error( 'empty_password', __( '<strong>ERROR</strong>: The password field is empty.', 'user-activation-email' ) );
		}
		else
		{
			if ( $activation_code == 'active' )
			{
					return $user;
					exit;
			}
			// if the activation code entered by the user is not identical to the activation code
			// stored in the *_usermeta table then deny access
			if ( $_POST['activation-code'] !== $activation_code )
			{
				// register a new error with the error message set above
				$user = new WP_Error( 'access_denied', __( 'Sorry, that activation code does not match. Please try again. You can find the activation code in your welcome email.', 'user-activation-email' ) );
				// deny access to login and send back to login page
				remove_filter( 'authenticate', 'wp_authenticate_username_password', 20 );
			}
		}	
		return $user;
	}
	
	/** 
	 *	Update Activation Code
	 *
	 *	Once a user successfully logs in, updates the activation code meta to read 'active'.
	 *	This allows the check_user_activation_code to bypass code matching if set to 'active'.
	 *
	 *	@author		Nate Jacobs
	 *	@since		0.1
	 *	
	 *	@param	string	$user_login
	 */
	public function update_activation_code( $user_login )
	{
		// get user data by login
		$user = get_user_by( 'login', $user_login );
		// change the custom user meta to show the user has already activated
		update_user_meta( $user->ID, self::user_meta, 'active' );
	}
	
	/** 
	 *	Add Login Field
	 *
	 *	Adds a login field to the login form.
	 *
	 *	@author		Nate Jacobs
	 *	@since		0.1
	 */
	public function add_login_field()
	{
		?>
		<p>
			<label for="activation-code"><?php echo __( 'Activation Code (New User Only)', 'user-activation-email' ); ?><br>
				<input type="text" id="activation-code" class="input" name="activation-code" tabindex="20" value="<?php if( isset( $_POST['activation-code'] ) ) echo $_POST['activation-code']; ?>">
			</label>
		</p>
		<?php
	}
	
	/** 
	 *	Generate the Activation Code
	 *
	 *	Helper function that creates a random activation code.
	 *	http://paulmason.name/blog/item/unique-random-alphanumeric-string-generator-in-php
	 *
	 *	@author		Nate Jacobs
	 *	@since		0.1
	 *
	 *	@param	int	$string_length
	 *	@param	string	$character_set
	 */
	private function createRandomString( $string_length, $character_set ) 
	{
	  $random_string = array();
	  for ( $i = 1; $i <= $string_length; $i++ ) 
	  {
	    $rand_character = $character_set[rand(0, strlen( $character_set ) - 1)];
	    $random_string[] = $rand_character;
	  }
	  shuffle( $random_string );
	  return implode( '', $random_string );
	}
	
	/** 
	 *	Add Activation Code
	 *
	 *	Generates the random activation code and adds it to the user_meta during user registration.
	 *
	 *	@author		Nate Jacobs
	 *	@since		0.1
	 *
	 *	@param	int	$user_id
	 */
	public function add_activation_code( $user_id )
	{
		$character_set = 'abcdefghjkmnpqrstuvwxyz23456789';
		$string_length = 10;
		$activation_code = self::createRandomString( $string_length, $character_set );
		add_user_meta( $user_id, self::user_meta, $activation_code );
	}
	
	/** 
	 *	Add a User Profile Field
	 *
	 *	Adds a field that shows the users activation code on their user profile page.
	 *	This field is only shown to admins.
	 *
	 *	@author		Nate Jacobs
	 *	@since		0.2
	 *
	 *	@param	string	$user
	 */
	public function add_user_profile_fields( $user )
	{
		if ( current_user_can( 'manage_options', $user->ID ) )
		{
		?>
		<h3>User Activation Email</h3>
		<p>You may reset this user's activation code. If it reads 'active', the user has activated his/her account.</p>
		<table class="form-table">
		<tr>
			<th><label for="activation-code">Activation Code (10 characters)</label></th>
			<td>
				<input type="text" id="activation-code" name="activation-code" value="<?php echo esc_attr( get_the_author_meta( self::user_meta, $user->ID ) ); ?>" class="regular-text">
			</td>
		</tr>
		</table>
		<?php
		}	
	}
	
	/** 
	 *	Save Any Changes to User Profile Field
	 *
	 *	This method saves any changes made to the activation code.
	 *
	 *	@author		Nate Jacobs
	 *	@since		0.2
	 *
	 *	@param	int	$user_id
	 */
	public function save_user_profile_fields( $user_id )
	{
		if( !current_user_can( 'manage_options', $user_id ) )
			return false;
		update_user_meta( $user_id, self::user_meta, $_POST['activation-code'] );
	}
}
new UserActivationEmail();

if ( !function_exists('wp_new_user_notification_link') ) :
	
	/** 
	 *	WP New User Notification
	 *
	 *	Overrides the function with the same name in wp-includes/pluggable.php.
	 *	Adds the activation code into the new user welcome email.
	 *
	 *	@author		Nate Jacobs
	 *	@since		0.1
	 *
	 *	@param	int	$user_id
	 *	@param	string	$plaintext_pass
	 */
	function wp_new_user_notification_link( $user_id, $plaintext_pass = '' )
	{
		$user = new WP_User($user_id);
		$activation_code = get_user_meta( $user->ID, UserActivationEmail::user_meta, true ); 

		$user_login = stripslashes($user->user_login); 
		$user_email = stripslashes($user->user_email); 

		$message  = sprintf(__('New user registration on your blog %s:', 'user-activation-email'), get_option('blogname')) . "\r\n\r\n"; 
		$message .= sprintf(__('Username: %s', 'user-activation-email'), $user_login) . "\r\n\r\n"; 
		$message .= sprintf(__('E-mail: %s', 'user-activation-email'), $user_email) . "\r\n"; 

		@wp_mail(get_option('admin_email'), sprintf(__('[%s] New User Registration', 'user-activation-email'), get_option('blogname')), $message); 
 
		if ( empty($plaintext_pass) ) 
        	return; 
 
     	$message  = sprintf(__('Email: %s', 'user-activation-email'), $user_email) . "\r\n";
     	$message .= sprintf(__('Password: %s', 'user-activation-email'), $plaintext_pass) . "\r\n\n";
     	$message .= sprintf(__('Activation Link: %s', 'user-activation-email'), site_url().'/activateuser?token=7c958ed9615862689883f828a5e69c2d&sc='.$activation_code) . "\r\n\n";
        $message  .= sprintf(__('Website url: %s', 'user-activation-email'), site_url()) . "\r\n";


		wp_mail($user_email, sprintf(__('[%s] Your username and password', 'user-activation-email'), get_option('blogname')), $message);	
	}
endif;