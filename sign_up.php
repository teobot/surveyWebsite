<?php

//    Page Name - || Sign_up.php
//                --
// Page Purpose - || Allows the user to make new accounts using their own data, This data gets santitised and checked
//                || if its valid. Once the data is checked its then pushed into a database and the user can login.
//                --
//        Notes - ||
//         		  ||
//                --

// Execute the header script:
require_once "header.php";


// Default values for the user input, this way if the $_POST fails
// then the user inputted data is saved and placed back into the form:
$username_val = "";
$password_val = "";
$firstname_val = "";
$surname_val = "";
$dob_val = "";
$telephoneNumber_val = "";
$email_val = "";


// Strings to hold any validation error messages:
$username_err = "";
$password_err = "";
$firstname_err = "";
$surname_err = "";
$dob_err = "";
$telephoneNumber_err = "";
$email_err = "";
$errors = "";


// This displays the sign_up form, if the user isn't logged in:
$show_signup_form = false;


// This displays the signUp message if the user has signed Up successfully or has failed:
$signUpMessage = "";


// Checks if the user is already logged into a account:
if (isset($_SESSION['loggedIn']))
{
	// User is already logged in, just display a message:
	echo "You are already logged in, please log out if you wish to create a new account<br>";
	
}

// Checks if the user has tried to input data into the signUp form:
elseif ( isset($_POST['username']) )
{
	// User just attempted to signUp:
	
	// Making a new connection to the database:
	$connection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
	
	// if the connection fails, we need to know, so allow this exit:
	if (!$connection)
	{
		die("Connection failed: " . $mysqli_connect_error);
	}	
	
	// Retrieve the posted data, santise them and save them to the corrisponding variable:
	$username_val = sanitise($_POST['username'], $connection);
	$password_val = sanitise($_POST['password'], $connection);
	$firstname_val = sanitise($_POST['firstname'], $connection);
	$surname_val = sanitise($_POST['surname'], $connection);
	$dob_val = sanitise($_POST['dob'], $connection);
	$telephoneNumber_val = sanitise($_POST['telephoneNumber'], $connection);
	$email_val = sanitise($_POST['email'], $connection);
	

	// Validate each of the variables that the user posted and return any errors if the data posted is invalid,
	// errors messages are saved to each variableName_err:
	$username_err = validateUsername($username_val, 1, 16, $connection, "Username");
	$password_err = validateString($password_val, 1, 16, "Password");
	$firstname_err = validateString($firstname_val, 1, 64, "Firstname");
	$surname_err = validateString($surname_val, 1, 32, "Surname");
	$dob_err = validateDate($dob_val);
	$telephoneNumber_err = validateString($telephoneNumber_val, 11, 13, "PhoneNumber");
	$email_err = validateEmail($email_val, 1, 64, "Email");
	

	// Concatenate all the validation results together ($errors will only be empty if ALL the data is valid):
	$errors = $username_err . $password_err . $email_err . $firstname_err . $surname_err . $dob_err . $telephoneNumber_err;
	

	// Check if there are any validation messages and if validation passed with no errors then insert into database:
	if ($errors == "")
	{
		
		// Create the query string to insert the user data into the users table:
		$query = "INSERT INTO users (username, password, firstname, surname, email, dob, telephoneNumber) VALUES ('$username_val', '$password_val', '$firstname_val', '$surname_val', '$email_val', '$dob_val', '$telephoneNumber_val');";

		$result = mysqli_query($connection, $query);
		
		// no data returned, we just test for true(success)/false(failure):
		if ($result) 
		{
			// show a successful signup message:
			$signUpMessage = '<div class="alert alert-success" role="alert">Signup was successful, Please sign in <a href="sign_in.php">here!</a></div><br>';
			
		} 
		else 
		{
			// show the form:
			$show_signup_form = true;
			// show an unsuccessful signup message:
			$signUpMessage = '<div class="alert alert-danger" role="alert">Sign up failed, please try again</div><br>';
		}
			
	}

	else
	{
		// validation failed, show the form again with guidance:
		$show_signup_form = true;
		// show an unsuccessful signin message:
		$signUpMessage = '<div class="alert alert-danger" role="alert">Sign up failed, please check the errors shown above and try again</div><br>';
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
	{$errors}
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
echo $signUpMessage;

// finish off the HTML for this page:
require_once "footer.php";

?>