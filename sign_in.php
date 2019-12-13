<?php

// Things to notice:
// The main job of this script is to execute a SELECT statement to look for the submitted username and password in the appropriate database table
// If the submitted username and password is found in the table, then the following session variable is set: $_SESSION["loggedInSkeleton"]=true;
// All other scripts check for this session variable before loading (if it doesn't exist then the user isn't logged in and the page doesn't load)
// However... the database table isn't currently being queried (at the moment the code is only checking for a username of "barryg", "mandyb" or "admin") and it's your job to add this query in... 
//
// Other notes:
// client-side validation using "password","text" inputs and "required","maxlength" attributes (but we can't rely on it happening!)
// we sanitise the user's credentials - see validationChecker.php (included via header.php) for the sanitisation function
// we validate the user's credentials - see validationChecker.php (included via header.php) for the validation functions
// the validation functions all follow the same rule: return an empty string if the data is valid...
// ... otherwise return a help message saying what is wrong with the data.
// if validation of any field fails then we display the help messages (see previous) when re-displaying the form

// execute the header script:
require_once("header.php");

// default values we show in the form:
$username = "";
$password = "";
// strings to hold any validation error messages:
$username_err = "";
$password_err = "";

// should we show the signin form:
$show_signin_form = false;
// message to output to user:
$message = "";

// checks the session variable named 'loggedIn'
if (isset($_SESSION['loggedIn']))
{
	// user is already logged in, just display a message:
	echo "<div class='col-md-6 offset-md-3 text-center'><div class='alert alert-success' role='alert'>You are already logged in, please log out first.</div></div>";
}

elseif ( (isset($_POST['username'])) && (isset($_POST['password'])) )
{
	// user has just tried to log in:
	
	// connect directly to our database (notice 4th argument) we need the connection for sanitisation:
	$connection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
	
	// if the connection fails, we need to know, so allow this exit:
	if (!$connection)
	{
		die("Connection failed: " . $mysqli_connect_error);
	}	
	
	// SANITISATION (see validation_Checker.php for the function definition)
	
	// take copies of the credentials the user submitted and sanitise (clean) them:
	$username = sanitise($_POST['username'], $connection);
	$password = sanitise($_POST['password'], $connection);
	
	// VALIDATION (see validation_Checker.php for the function definitions)
	
	// now validate the data (both strings must be between 1 and 16 characters long):
	// (reasons: we don't want empty credentials, and we used VARCHAR(16) in the database table)
	$username_err = validateString($username, 1, 16, "Username");
	$password_err = validateString($password, 1, 16, "Password");
	
	// concatenate all the validation results together ($errors will only be empty if ALL the data is valid):
	$errors = $username_err . $password_err;
	
	// check that all the validation tests passed before going to the database:
	if ($errors == "")
	{

		$password = sha1($password);
		$query = "SELECT username, password FROM `users` WHERE `username` LIKE '$username' AND `password` LIKE '$password'";

		// this query can return data ($result is an identifier):
    	$result = mysqli_query($connection, $query);

	    // how many rows came back? (can only be 1 or 0 because usernames are the primary key in our table):
	    $userReturns = mysqli_num_rows($result);
			
		// if there was a match then set the session variables and display a success message:
		if ($userReturns > 0)
		{
			// set a session variable to record that this user has successfully logged in:
			$_SESSION['loggedIn'] = true;
			// and copy their username into the session data for use by our other scripts:
			$_SESSION['username'] = $username;
			
			// show a successful signin message:
			$message = "<div class='alert alert-success' role='alert'>Hi, $username, you have successfully logged in, please <a href='account.php'>click here</a></div>";
		}

		else
		{
			// no matching credentials found so redisplay the signin form with a failure message:
			$show_signin_form = true;
			// show an unsuccessful signin message:
			$message = '<div class="alert alert-danger" role="alert">Sign in failed, please try again!</div>';
		}
		
	}
	else
	{
		// validation failed, show the form again with guidance:
		$show_signin_form = true;
		// show an unsuccessful signin message:
		$message = '<div class="alert alert-danger" role="alert">Sign in failed, please try again!</div>';
	}
	
	// we're finished with the database, close the connection:
	mysqli_close($connection);

}
else
{
	// user has arrived at the page for the first time, just show them the form:
	// show signin form:
	$show_signin_form = true;
}
echo '<div class="col-md-6 offset-md-3 text-center">';
echo $message;

if ($show_signin_form)
{
// show the form that allows users to log in
// Note we use an HTTP POST request to avoid their password appearing in the URL:
echo <<<_END
	<form class="form container" action="sign_in.php" method="post">
		<div class="form-group">
			<label for="usernameInput">Username:</label>
			<input class="form-control" type="text" name="username" maxlength="16" min="1" value="$username" required> $username_err
		</div>
		<div class="form-group">
			<label for="InputPassword">Password:</label>
			<input class="form-control" type="password" name="password" maxlength="16" required> $password_err
		</div>
		<button type="submit" value="Submit" class="btn btn-primary">Submit</button>
	</form>
</div>
_END;
}

// finish off the HTML for this page:
require_once("footer.php");
?>