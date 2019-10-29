<?php

//    Page Name - || Sign_up.php
//                --
// Page Purpose - || Allows the user to make new accounts using their own data, This data gets santitised and checked
//                || if its valid. Once the data is checked its then pushed into a database and the user can login.
//                --
//        Notes - ||
//         		  ||
//                --

// execute the header script:
require_once "header.php";

// default values we show in the form:
$username_val = "";
$password_val = "";
$firstname_val = "";
$surname_val = "";
$dob_val = "";
$telephoneNumber_val = "";
$email_val = "";

// strings to hold any validation error messages:
$username_err = "";
$password_err = "";
$firstname_err = "";
$surname_err = "";
$dob_err = "";
$telephoneNumber_err = "";
$email_err = "";
$errors = "";

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

elseif ( isset($_POST['username']) )
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
	$username_val = sanitise($_POST['username'], $connection);
	$password_val = sanitise($_POST['password'], $connection);
	$firstname_val = sanitise($_POST['firstname'], $connection);
	$surname_val = sanitise($_POST['surname'], $connection);
	$dob_val = sanitise($_POST['dob'], $connection);
	$telephoneNumber_val = sanitise($_POST['telephoneNumber'], $connection);
	$email_val = sanitise($_POST['email'], $connection);
	

	// VALIDATION (see validationChecker.php for the function definitions)
	// now validate the data (both strings must be between 1 and 16 characters long):
	// (reasons: we don't want empty credentials, and we used VARCHAR(16) in the database table for username and password)
    // firstname is VARCHAR(32) and lastname is VARCHAR(64) in the DB
    // email is VARCHAR(64) and telephone is VARCHAR(16) in the DB
    //the following line will validate the email as a string, but maybe you can do a better job...
	$username_err = validateUsername($username_val, 1, 16, $connection, "Username");
	$password_err = validateString($password_val, 1, 16, "Password");
	$firstname_err = validateString($firstname_val, 1, 64, "firstname");
	$surname_err = validateString($surname_val, 1, 32, "Surname");
	$dob_err = validateDate($dob_val);
	$telephoneNumber_err = validateString($telephoneNumber_val, 11, 13, "Phone Number");
	$email_err = validateEmail($email_val, 1, 64, "Email");
	
	// concatenate all the validation results together ($errors will only be empty if ALL the data is valid):
	$errors = $username_err . $password_err . $email_err . $firstname_err . $surname_err . $dob_err . $telephoneNumber_err;
	
	// check that all the validation tests passed before going to the database:
	if ($errors == "")
	{
		
		// try to insert the new details:
		$query = "INSERT INTO users (username, password, firstname, surname, email, dob, telephoneNumber) VALUES ('$username_val', '$password_val', '$firstname_val', '$surname_val', '$email_val', '$dob_val', '$telephoneNumber_val');";

		$result = mysqli_query($connection, $query);
		
		// no data returned, we just test for true(success)/false(failure):
		if ($result) 
		{
			// show a successful signup message:
			$message = '<div class="alert alert-success" role="alert">Signup was successful, Please sign in <a href="sign_in.php">here!</a></div><br>';
			
		} 
		else 
		{
			// show the form:
			$show_signup_form = true;
			// show an unsuccessful signup message:
			$message = '<div class="alert alert-danger" role="alert">Sign up failed, please try again</div><br>';
		}
			
	}

	else
	{
		// validation failed, show the form again with guidance:
		$show_signup_form = true;
		// show an unsuccessful signin message:
		$message = '<div class="alert alert-danger" role="alert">Sign up failed, please check the errors shown above and try again</div><br>';
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

if ($errors != "") {
	echo <<<_END
	<div class="alert alert-danger" role="alert">
  		{$errors}
	</div>
_END;
}

echo <<<_END
<form action="sign_up.php" method="post">
	<h5>Account Details</h5>
	<div class="row">
		<div class="col">
			<label>Username</label>
				<input type="text" name="username" maxlength="16" min="1" class="form-control" value="{$username_val}" required>{$username_err}
				<small class="form-text text-muted">This is your display name.</small>
		</div>
		<div class="col">
			<label>Password</label>
				<input type="password" name="password" maxlength="16" min="1" class="form-control" value="{$password_val}" required>{$password_err}
				<small class="form-text text-muted">Enter your account password.</small>
		</div>
  	</div><br><br><br>

	<h5>Personal Details</h5>
	<div class="row">
		<div class="col">
			<label>Firstname</label>
				<input type="text" name="firstname" maxlength="32" min="1" class="form-control" value="{$firstname_val}" required>{$firstname_err}
				<small class="form-text text-muted">Enter your firstname.</small>
		</div>
		<div class="col">
			<label>Surname</label>
				<input type="text" name="surname" maxlength="64" min="1" class="form-control" value="{$surname_val}" required>{$surname_err}
				<small class="form-text text-muted">Enter your surname.</small>
		</div>
  	</div><br>
	<div class="row">
		<div class="col">
			<label>Date of Birth</label>
				<input name="dob" type="date" min="1919-01-01" max="2018-01-01" class="form-control" value="{$dob_val}" required>{$dob_err}
				<small class="form-text text-muted">Enter your date of birth.</small>
		</div>
		<div class="col">
 	   		<label>Phone Number</label>
				<input name="telephoneNumber" type="text" max="13" min="11" class="form-control" value="{$telephoneNumber_val}" required>{$telephoneNumber_err}
				<small class="form-text text-muted">Enter your phone number.</small>
		</div>
	</div><br>

	<div class="row">
			<div class="col">
			<label>Email</label>
				<input type="email" name="email" maxlength="64" min="7" class="form-control" value="{$email_val}" required>{$email_err}
				<small class="form-text text-muted">Enter your email address.</small>
		</div>
	</div><br>

  <button type="submit" class="btn btn-primary">Submit</button>
</form>
<br>
_END;
}

// display our message to the user:
echo $message;

// finish off the HTML for this page:
require_once "footer.php";

?>