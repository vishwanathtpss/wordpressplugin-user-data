<?php
/*
Plugin Name: user-data
*/ ?>

<?php

register_activation_hook(__FILE__, 'mytable_activation_function');

// callback function to create table
function mytable_activation_function()
{
	global $wpdb;

	if ($wpdb->get_var("show tables like '" . create_my_table() . "'") != create_my_table()) {

		$mytable = 'CREATE TABLE `' . create_my_table() . '` (
                            `id` int(11) NOT NULL AUTO_INCREMENT,
                            `firstname` varchar(100) NOT NULL,
							`laststname` varchar(100) NOT NULL,
                            `email` varchar(50) NOT NULL,
                            `subject` varchar(100) NOT NULL,
                            `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                            PRIMARY KEY (`id`)
                          ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;';

		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($mytable);
	}
}

// returns table name
function create_my_table()
{
	global $wpdb;
	return $wpdb->prefix . "contact_us";
}

function delete_table()
{
	global $wpdb;
	$table_name = $wpdb->prefix . 'contact_us';
	$sql = "DROP TABLE IF EXISTS $table_name";
	$wpdb->query($sql);
}
register_deactivation_hook(__FILE__, 'delete_table');
// preg_match("/^([a-zA-Z' ]+)$/",$givenName)

global $wpdb;
function form_code()
{
	if ($_POST['submit']) {
		$firstname = trim($_POST['firstname']);
		$lastname = trim($_POST['laststname']);
		$email = trim($_POST['email']);
		$subject = trim($_POST['subject']);
		if(!$firstname || $firstname == "" || !preg_match("/^[a-zA-Z ]*$/",$firstname)) {
			$name = "FirstName is required or You have entered numrics";
			$firstname = "";
		}

		if(!$lastname || $lastname == "" || !preg_match("/^[a-zA-Z ]*$/",$lastname)) {
			echo "Last Name is required or You have entered numrics";
			$lastname = "";
		}

		if(!$email || $email == "" || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
			echo "Email is required or You have entered envalid email id";
			$email = "";
		}

		if(!$subject || $subject == "" || !preg_match("/^[a-zA-Z ]*$/",$subject)) {
			echo "Subject is required or Numerics are not alloed in subject.";
			$subject = "";
		}
	
		if ($firstname != '' && $lastname != '' && $email != '' && $subject  != '') {
			global $wpdb;
			$table_name = $wpdb->prefix . 'contact_us';

			$data = array(
				'firstname' => $firstname,
				'laststname' => $lastname,
				'email' => $email,
				'subject' => $subject,
			);

			$insert = $wpdb->insert(
				$table_name,
				$data
			);
			  
			if ($insert) {
				
				echo "inseted successfully";

			} else {
				echo "not inserted";
			}
		} else {
			echo  "All fields are required";
		}
?>
	<?php
	}

	//endif ..............
	//header("Refresh:0");
	?>
	<form id="form" name="reg_form" method="post">
		<div class="firstname">
			<label for="firstname">First name:</label>
			<input name="firstname" value="<?php echo $_POST['firstname']?>">

			<span class="error"> <?php echo $name ; ?></span>
		</div>
		<div class="lastname">
			<label for="lastname">Last name:</label>
			<input name="laststname" id="lastnames"  value="<?php echo $_POST['laststname']?>">

		</div>
		<div class="Email">
			<label for="email">Email:</label>
			<input name="email" id="emails"  value="<?php echo $_POST['email']?>">

		</div>
		<div class="subject">
			<label for="subject">Subject:</label>
			<input name="subject" id="subjects"  value="<?php echo $_POST['subject']?>">

		</div>
		<input type="submit" name="submit" value="submit" />
	</form>
	<script>
    if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
    }
    </script>
 <!-- <script>
		function validate(event) {
			event.preventDefault();
        var fname = document.reg_form.firstname.value;
        var lname = document.reg_form.laststname.value;
		var email = document.reg_form.email.value;
		var subject = document.reg_form.subject.value;
		var regEmail=/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/g; 
		var regName = /^[a-zA-Z]+$/;
		// console.log(regName.test(fname))
		if (fname == "" || !regName.test(fname)) {
                window.alert("Please enter your first-name properly.");
                return false;
            }
			if (lname == "" || !regName.test(lname)) {preg_match("/^([a-zA-Z' ]+)$/",$givenName)
                window.alert("Please enter your Lastname properly.");
                return false;
            }
			if (email == "" || !regEmail.test(email)) {
                window.alert("Please enter a valid e-mail address.");
                return false;
            }
			if (subject == "" || !regName.test(subject)) {
                window.alert("Please enter a valid subject.");
                return false;
            }
       
	}
	</script>  -->
<?php
	//header("Refresh:0");
}

add_shortcode('contact_forms', 'form_code');


function my_admin_menu()
{

	add_menu_page(

		__('Contact-page'),

		__('contact-form'),

		'manage_options',

		'custom-user-page',

		'admin_page_contents',

		'dashicons-admin-users',

		7

	);
}

add_action('admin_menu', 'my_admin_menu');

function admin_page_contents()
{
	global $wpdb;
	$table_name = $wpdb->prefix . 'contact_us';
	//echo"<pre>";print_r($table_name);echo"<pre>";

	$query = "SELECT * FROM $table_name;";
	//echo"<pre>";print_r($query);echo"<pre>";
	$details = $wpdb->get_results($query);
	//echo"<pre>";print_r($wpdb);echo"<pre>";
?>
	<table width='80%' border=0>
		<tr bgcolor='#CCCCCC'>
			<td>ID</td>
			<td>FirstName</td>
			<td>LastName</td>
			<td>Email</td>
			<td>Subject</td>
			<td>date</td>
		</tr>
		<?php
		foreach ($details as $detail) { ?>

			<?php echo "<tr>"; ?>
			<?php echo "<td>$detail->id</td>"; ?>
			<?php echo "<td>$detail->firstname</td>"; ?>
			<?php echo "<td>$detail->laststname</td>"; ?>
			<?php echo "<td>$detail->email</td>"; ?>
			<?php echo "<td>$detail->subject</td>"; ?>
			<?php echo "<td>$detail->created_at</td>"; ?>
			<?php echo "</tr>"; ?>
	<?php }
	}
	?>
	</table>