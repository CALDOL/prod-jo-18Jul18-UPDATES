<?php
/*
 Plugin Name: CALDOL Custom Email Plugin
Plugin URI: https://pl.army.mil
Description: Used to customize the emails sent by WordPress
Version: 1.0
Author: Thomas O. Morel
Author URI: https://platoonleader.net
*/


// set the site url

	$siteURL = site_url();

// change the email type to html
add_filter ("wp_mail_content_type", "caldol_mail_content_type");
function caldol_mail_content_type() {
	return "text/html";
}

// change the email type to plaintext
//add_filter ("wp_mail_content_type", "caldol_mail_send_plaintext");
function caldol_mail_send_plaintext() {
	return "text/plain";
}

// change the FROM address of the emails sent
// this address needs to change to whatever email
// address we finally decide to use
add_filter ("wp_mail_from", "caldol_mail_from");
function caldol_mail_from() {
	return "no-reply@juniorofficer.army.mil";
}

// change the FROM address DISPLAY name
add_filter ("wp_mail_from_name", "caldol_mail_from_name");
function caldol_mail_from_name() {
	return "Junior Officer Forum";
}

// change the subject of the email the user receives once 
// their request to join has been approved by admin
add_filter("new_user_approve_approve_user_subject", "caldol_new_user_approve_approve_user_subject", 10, 1);
function caldol_new_user_approve_approve_user_subject($subject = ''){
	
	//add_filter("new_user_approve_approve_user_subject", $subject);
	
	
	return "Welcome to the Junior Officer Forum!";
}


// this contains the text of the email message that the user receives
// when the user has been actually approved by an admin
add_filter("new_user_approve_approve_user_message", "caldol_new_user_approve_approve_user_message", 10, 2);

function caldol_new_user_approve_approve_user_message($message, $user){


	//add_filter('new_user_approve_approve_user_subject', 'new subject');//, $subject
	//caldol_new_user_approve_approve_user_subject("coming from function");



	$approvalMessage = "<p>On behalf of the Junior Officer Team, welcome to the JO Forum.</p><p>Your request to join the Junior Officer Forum has been approved!</p><p>Thank you for joining the junior officer community. We’re glad to have you and are excited to see what you will learn as well as what you will contribute to the rest of our community.</p>";

	$approvalMessage .= "<p>You may find it helpful to take our digital terrain walk of the forums. This brief exercise will help you explore different parts of the forum and quickly get familiar with its content and capabilities. (https://www.surveymonkey.com/r/JOTerrainWalk)</p>";

	$approvalMessage .= "<p>Username: " . $user->user_login . "<br/>Password: *******<br/>(Your password is set to what you chose when you registered. Please keep it secret and keep it safe!)</p>";
	
	$approvalMessage .= "<p>Note:  If you happen to forget your password, you can reset it on the <a href='https://juniorofficer.army.mil/wp-login.php?action=lostpassword'>Lost Password</a> page. (https://juniorofficer.army.mil/wp-login.php?action=lostpassword)</p>";

	$approvalMessage .= "<p><a href='https://juniorofficer.army.mil/wp-login.php'>Login Now!</a>  (https://juniorofficer.army.mil/wp-login.php)</p>";
	

	$approvalMessage .= "<p>If you have any problems, questions, opinions, praise, comments, suggestions, please feel free to <a href='mailto:8caldol@usma.edu'>contact us</a> at any time.</p>";

	$approvalMessage .= "<p>Leadership counts,<br/>    The JO Team</p>";

	$approvalMessage .= "<p><br/><br/>You have received this email because you are a member of the <a href='https://juniorofficer.army.mil'>Junior Officer Forum</a></p>";
	return $approvalMessage;
}

add_filter('new_user_approve_email_admins', 'caldol_approve_email_admins');

function caldol_approve_email_admins(){
	
	
return array('leromt@gmail.com', '8CALDOL@usma.edu');

	 //return array('leromt@gmail.com', 'thomas.morel@usma.edu');
	//return array('leromt@gmail.com', 'thomas.morel@usma.edu');
}


// THIS FUNCTION SENDS A NOTIFICATION TO THE USER THAT THEIR REQUEST
// FOR MEMBERSHIP WAS DENIED.

function caldol_new_user_approve_deny_user_message($message, $user=''){

$message = "<p>" . $user->user_login . ", Your request to join the Junior Officer Forum has been denied.</p>";
	$message .= "<p>Membership to the JO Forum is limited to Officer Candidates, Cadets, and Junior Officers (LTs and CPTs). If you disagree with our decision regarding your membership, please contact us at <a hef='mailto:jo.team@usma.edu'>JO.Team@usma.edu</a>";
	return $message;
	
}

add_filter('new_user_approve_deny_user_message', 'caldol_new_user_approve_deny_user_message', 10, 2);

/* *** NOT USED

function registration_email_alert($user_id) {
    $message = strip_tags($_POST['user_login']). ' - ' . strip_tags($_POST['user_email']) . ' Has Registered To Your Website (custom-email: registration_email_alert';
    wp_mail( 'leromt@gmail.com', 'New User Has Registered', $message );
}
add_action('user_register', 'registration_email_alert');
*/


function caldol_notify_admins($blogname, $user){

		$message1  = sprintf(__('New user registration on %s:'), $blogname) . "<br/>\r\n\r\n";
		$message1 .= sprintf(__('Username: %s'), $user->user_login) . "<br/>\r\n\r\n";
		$message1 .= sprintf(__('E-mail: %s'), $user->user_email) . "<br/>\r\n";
		
		//@wp_mail(get_option('admin_email'), sprintf(__('[%s] New User Registration'), $blogname), $message1);
		@wp_mail(caldol_approve_email_admins(), sprintf(__('[%s] New User Registration'), $blogname), $message1);


}

// THIS FUNCTION SENDS A NOTIFICATION TO THE SITE ADMIN(S) WHEN A NEW USER
// REQUESTS TO JOIN

if (!function_exists('wp_new_user_notification')) {
	function wp_new_user_notification($user_id, $plaintext_pass) {

		$user = new WP_User($user_id);
		
		// The blogname option is escaped with esc_html on the way into the database in sanitize_option
		// we want to reverse this for the plain text arena of emails.
		$blogname =get_bloginfo('name');
		
		caldol_notify_admins($blogname, $user);

		$user_login = stripslashes($user->user_login);
		$user_email = stripslashes($user->user_email);

		$email_subject = "Junior Officer Forum Membership Request";

		ob_start();

		include("email_header.php");

		?>

<p>
	Welcome to the JO Team, 
	<?php echo $user_login ?>
	. Thank you for joining JO!
</p>
<p>Your application will be reviewed and approved within the next 24 hours (possibly 48 hours on weekends).  You will receive
an approval email once your application is complete.</p>
<p>
	Your password is set to what you chose when you registered. <br> Please keep it secret and keep it safe!
	If you happen to forget your password, you can reset it on the <a href="https://juniorofficer.army.mil/wp-login.php?action=lostpassword">Lost Password</a> page.
</p>

<p> If you have any problems, questions, opinions, praise, comments, suggestions, please feel free to <a href="mailto:thomas.morel@usma.edu">contact us</a> at any time. </p>


<?php
include("email_footer.php");

$message = ob_get_contents();
ob_end_clean();


//if ( empty($plaintext_pass) )
//	return;


   wp_mail($user_email, $email_subject, $message);
	}
}
add_filter ("retrieve_password_title", "caldol_retrieve_password_title");

function caldol_retrieve_password_title() {
	return "Password Reset for Junior Officer Forum";
}


add_filter ("retrieve_password_message", "caldol_retrieve_password_message", 10, 2);
function caldol_retrieve_password_message($content, $key) {
	global $wpdb;
	
	
	
if ( empty( $_POST['user_login'] ) ) {
		$errors->add('empty_username', __('<strong>ERROR</strong>: Enter a username or e-mail address.'));
	} else if ( strpos( $_POST['user_login'], '@' ) ) {
		$user_data = get_user_by( 'email', trim( $_POST['user_login'] ) );
		if ( empty( $user_data ) )
			$errors->add('invalid_email', __('<strong>ERROR</strong>: There is no user registered with that email address.'));
	} else {
		$login = trim($_POST['user_login']);
		$user_data = get_user_by('login', $login);
		
	}

	$user_login = $user_data->user_login;
	$user_email = $user_data->user_email;
	//error_log("post user login on line 150, caldol custom is " . $login);
	// Now insert the key, hashed, into the DB.
/*
 * 
 *
 *
 if ( empty( $wp_hasher ) ) {
		require_once ABSPATH . 'wp-includes/class-phpass.php';
		$wp_hasher = new PasswordHash( 8, true );
    error_log("key is " . $wp_hasher->HashPassword($key));
		}
	$user_login = $wpdb->get_var("SELECT user_login FROM $wpdb->users WHERE user_activation_key = '$key'");
*/
	ob_start();

	$email_subject = caldol_retrieve_password_title();

	include("email_header.php");
	?>
	
	<p>
		It looks likes like you (hopefully) want to reset your password for your JuniorOfficer.army.mil account.
	</p>

	<p>
		To reset your password, visit the following address, otherwise just ignore this email and nothing will happen.
		<br>
		<?php echo wp_login_url(); ?>?action=rp&key=<?php echo $key ?>&login=<?php echo $user_login ?>			
	<p>
	
<?php 
	
	include("email_footer.php");
	
	$message = ob_get_contents();

	ob_end_clean();
  
	return $message;
}
