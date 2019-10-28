<?php

// Things to notice:
// The main job of this script is to execute an INSERT statement to add the submitted username, password and email address
// However, the assignment specification tells you that you need more fields than this for each user.
// So you will need to amend this script to include them. Don't forget to update your database (create_data.php) in tandem so they match
// This script does client-side validation using "password","text" inputs and "required","maxlength" attributes (but we can't rely on it happening!)
// we sanitise the user's credentials - see validationChecker.php (included via header.php) for the sanitisation function
// we validate the user's credentials - see validationChecker.php (included via header.php) for the validation functions
// the validation functions all follow the same rule: return an empty string if the data is valid...
// ... otherwise return a help message saying what is wrong with the data.
// if validation of any field fails then we display the help messages (see previous) when re-displaying the form

// execute the header script:
require_once "header.php";

// default values we show in the form:
$username = "";
$password = "";
$firstname = "";
$surname = "";
$dob = "";
$telephoneNumber = "";
$email = "";

// strings to hold any validation error messages:
$username_err = "";
$password_err = "";
$firstname_err = "";
$surname_err = "";
$dob_err = "";
$phoneNumber_err = "";
$email_err = "";

// should we show the signup form?:
$show_signup_form = false;
// message to output to user:
$message = "";

// checks the session variable named 'loggedIn'
if (isset($_SESSION['loggedIn']))
{
	// user is already logged in, just display a message:
	echo "You are already logged in, please log out if you wish to create a new account<br>";
	
}

elseif ( isset($_POST['username']) && isset($_POST['password']) && isset($_POST['firstname']) && isset($_POST['surname']) && isset($_POST['dob']) && isset($_POST['phoneNumber']) && isset($_POST['email']) )
{
	// user just tried to sign up:
	
	// connect directly to our database (notice 4th argument) we need the connection for sanitisation:
	$connection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
	
	// if the connection fails, we need to know, so allow this exit:
	if (!$connection)
	{
		die("Connection failed: " . $mysqli_connect_error);
	}	
	
	// SANITISATION (see validationChecker.php for the function definition)
	
	// take copies of the credentials the user submitted, and sanitise (clean) them:
	$username = sanitise($_POST['username'], $connection);
	$password = sanitise($_POST['password'], $connection);
	$firstname = sanitise($_POST['firstname'], $connection);
	$surname = sanitise($_POST['surname'], $connection);
	$dob = sanitise($_POST['dob'], $connection);
	$telephoneNumber = sanitise($_POST['phoneNumber'], $connection);
	$email = sanitise($_POST['email'], $connection);
	

	// VALIDATION (see validationChecker.php for the function definitions)
	// now validate the data (both strings must be between 1 and 16 characters long):
	// (reasons: we don't want empty credentials, and we used VARCHAR(16) in the database table for username and password)
    // firstname is VARCHAR(32) and lastname is VARCHAR(64) in the DB
    // email is VARCHAR(64) and telephone is VARCHAR(16) in the DB
	$username_val = validateString($username, 1, 16);
	$password_val = validateString($password, 1, 16);
    //the following line will validate the email as a string, but maybe you can do a better job...
    $email_val = validateString($email, 1, 64);
	
	// concatenate all the validation results together ($errors will only be empty if ALL the data is valid):
	$errors = $username_val . $password_val . $email_val;
	
	// check that all the validation tests passed before going to the database:
	if ($errors == "")
	{
		
		// try to insert the new details:
		$query = "INSERT INTO users (username, password, firstname, surname, email, dob, telephoneNumber) VALUES ('$username', '$password', '$firstname', '$surname', '$email', '$dob', '$telephoneNumber')";

		$result = mysqli_query($connection, $query);
		
		// no data returned, we just test for true(success)/false(failure):
		if ($result) 
		{
			// show a successful signup message:
			$message = "Signup was successful, please sign in<br>";
		} 
		else 
		{
			// show the form:
			$show_signup_form = true;
			// show an unsuccessful signup message:
			$message = "Sign up failed, please try again<br>";
		}
			
	}

	else
	{
		// validation failed, show the form again with guidance:
		$show_signup_form = true;
		// show an unsuccessful signin message:
		$message = "Sign up failed, please check the errors shown above and try again<br>";
	}
	
	// we're finished with the database, close the connection:
	mysqli_close($connection);

}

else
{
	// just a normal visit to the page, show the signup form:
	$show_signup_form = true;
	
}

if ($show_signup_form)
{
// show the form that allows users to sign up
// Note we use an HTTP POST request to avoid their password appearing in the URL:	
echo <<<_END
<form action="sign_up.php" method="post" class="needs-validation">

	<h5>Account Details</h5>
	<div class="row">
		<div class="col">
			<label>Username</label>
				<input type="text" name="username" maxlength="16" min="1" class="form-control" required>
				<small class="form-text text-muted">This is your display name.</small>
		</div>
		<div class="col">
			<label>Password</label>
				<input type="password" name="password" maxlength="16" min="1" class="form-control" required>
				<small class="form-text text-muted">Enter your account password.</small>
		</div>
  	</div><br><br><br>

	<h5>Personal Details</h5>
	<div class="row">
		<div class="col">
			<label>Firstname</label>
				<input type="text" name="firstname" maxlength="32" min="1" class="form-control"required>
				<small class="form-text text-muted">Enter your firstname.</small>
		</div>
		<div class="col">
			<label>Surname</label>
				<input type="text" name="surname" maxlength="64" min="1"  class="form-control" required>
				<small class="form-text text-muted">Enter your surname.</small>
		</div>
  	</div><br>
	<div class="row">
		<div class="col">
			<label>Date of Birth</label>
				<input name="dob" type="date" min="1919-01-01" max="2018-01-01" class="form-control" required>
				<small class="form-text text-muted">Enter your date of birth.</small>
		</div>
		<div class="col">
 	   		<label>Phone Number</label>
				<input name="telephoneNumber" type="text" min="13" min="11" class="form-control" required>
				<small class="form-text text-muted">Enter your phone number.</small>
		</div>
	</div><br>

	<div class="row">
			<div class="col">
			<label>Email</label>
				<input type="email" name="email" maxlength="64" min="7" class="form-control" required>
				<small class="form-text text-muted">Enter your email address.</small>
		</div>
	</div><br>

  <button type="submit" class="btn btn-primary">Submit</button>
</form>
_END;
}

// display our message to the user:
echo $message;

// finish off the HTML for this page:
require_once "footer.php";

?>