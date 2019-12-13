<?php

// Things to notice:
// The main job of this script is to execute a SELECT statement to find the user's profile information (then display it)

// execute the header script:
require_once("header.php");

$password = "";
$firstname = "";
$surname = "";
$telephoneNumber = "";
$dateOfBirth = "";
$email = "";

$password_err = "";
$firstname_err = "";
$surname_err = "";
$telephoneNumber_err = "";
$dateOfBirth_err = "";
$email_err = "";

$message_err = "";

$show_account_form = false;

// checks the session variable named 'loggedIn'
// take note that of the '!' (NOT operator) that precedes the 'isset' function
if (!isset($_SESSION['loggedIn']))
{
	// user isn't logged in, display a message saying they must be:
	echo "<div class='col-md-6 offset-md-3 text-center'><div class='alert alert-success' role='alert'>You are already logged in, please log out first.</div></div>";
}
else
{

$isUserAdmin = false;

$returnAccountInfo = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

if ($returnAccountInfo->connect_error) {
	die("Connection failed: " . $returnAccountInfo->connect_error);
}

$checkIfAdminSQL = "SELECT `accountType` FROM `users` WHERE `username` = '$username'";
$checkIfAdminResult = mysqli_query($returnAccountInfo, $checkIfAdminSQL);

if (!empty($checkIfAdminResult))
{
	$userAccountType = $checkIfAdminResult->fetch_assoc();

	if ($userAccountType['accountType'] == "admin")
	{
		$isUserAdmin = true;
	}	
}

$returnAccountInfo->close();

if (!$isUserAdmin)
{
	echo "<div class='col-md-6 offset-md-3 text-center'><div class='alert alert-warning' role='alert'>You're not a admin!</div></div>";
}
else 
{

if ( (isset($_POST['password'])) || (isset($_POST['firstname'])) || (isset($_POST['surname'])) || (isset($_POST['telephoneNumber'])) || (isset($_POST['dateOfBirth'])) || (isset($_POST['email'])) )
{
	// user just tried to update their profile
	$username = $_GET["username"];
	
	// connect directly to our database (notice 4th argument) we need the connection for sanitisation:
	$connection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
	
	// if the connection fails, we need to know, so allow this exit:
	if (!$connection)
	{
		die("Connection failed: " . $mysqli_connect_error);
	}

	$password = $_POST['password'];
	$firstname = $_POST['firstname'];
	$surname = $_POST['surname'];
	$telephoneNumber = $_POST['telephoneNumber'];
	$dateOfBirth = $_POST['dateOfBirth'];
	$email = $_POST['email'];

	// SANITISATION CODE MISSING:
    // ...
	$password = sanitise($password, $connection);
	$firstname = sanitise($firstname, $connection);
	$surname = sanitise($surname, $connection);
	$telephoneNumber = sanitise($telephoneNumber, $connection);
	$dateOfBirth = sanitise($dateOfBirth, $connection);
	$email = sanitise($email, $connection);
	// ...
	
	// SERVER-SIDE VALIDATION CODE MISSING:
    // ...
	$password_err = validateString($password, 1, 16, "Password");
	$firstname_err = validateString($firstname, 1, 32, "Firstname");
	$surname_err = validateString($surname, 1, 64, "Surname");
	$telephoneNumber_err = validateTelephoneNumber($telephoneNumber, 9);
	$dateOfBirth_err = validateDate($dateOfBirth);
	$email_err = validateEmail($email, 1, 64, "Email");
    // ...
	
	$message_err = $password_err . $firstname_err . $surname_err . $telephoneNumber_err . $dateOfBirth_err . $email_err;
	
	// check that all the validation tests passed before going to the database:
	if ($message_err == "")
	{		
		// read their username from the session:
		$username = $_GET["username"];
		
		// check to see if this user already had a favourite:
		$query = "SELECT `username`, `firstname`, `surname`, `email`, `dob`, `telephoneNumber` FROM `users` WHERE username='$username'";
		
		// this query can return data ($result is an identifier):
		$result = mysqli_query($connection, $query);
		
		// how many rows came back? (can only be 1 or 0 because username is the primary key in our table):
		$n = mysqli_num_rows($result);
			
		// if there was a match then UPDATE their profile data, otherwise INSERT it:
		if ($n > 0)
		{
			// we need an UPDATE:
			$password = sha1($password);
			$query = "UPDATE `users` SET `password` = '$password', `firstname` = '$firstname', `surname` = '$surname', `email` = '$email', `dob` = '$dateOfBirth', `telephoneNumber` = '07$telephoneNumber' WHERE `users`.`username` = '$username'";
			$result = mysqli_query($connection, $query);		
		}
	

		// no data returned, we just test for true(success)/false(failure):
		if ($result) 
		{
			// show a successful update message:
			$message_err = '<div class="alert alert-success" role="alert"><h4 class="alert-heading">Account Information Updated!</h4><p>You have successfully update your account, Go back to the <a href="admin.php">account!</a></p></div>';
		}
		else
		{
			// show an unsuccessful update message:
			$message_err = '<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Account Update Unsuccessful!</h4><p>Something went wrong with the update!, Please Go back to the <a href="admin.php">account</a> and try again!</p></div>';
		}
	}
	else
	{
		// validation failed, show the form again with guidance:
		$show_account_form = true;
	}
	
	// we're finished with the database, close the connection:
	mysqli_close($connection);

}
// the user must be signed-in, show them suitable page content
else
{
    // user is already logged in, read their username from the session:
    $username = $_GET["username"];
	
	// now read their account data from the table...
	// connect directly to our database (notice 4th argument - database name):
	$connection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
	
	// if the connection fails, we need to know, so allow this exit:
	if (!$connection)
	{
		die("Connection failed: " . $mysqli_connect_error);
	}
	
	// check for a row in our profiles table with a matching username:
	$query = "SELECT `username`, `firstname`, `surname`, `email`, `dob`, `telephoneNumber` FROM `users` WHERE username='$username'";
	
	// this query can return data ($result is an identifier):
	$result = mysqli_query($connection, $query);
	
	// how many rows came back? (can only be 1 or 0 because username is the primary key in our table):
	$n = mysqli_num_rows($result);

	if ($n > 0)
	{
		// use the identifier to fetch one row as an associative array (elements named after columns):
		$row = mysqli_fetch_assoc($result);
		// extract their profile data for use in the HTML:
		$firstname = $row['firstname'];
		$surname = $row['surname'];
		$telephoneNumber = substr($row['telephoneNumber'], 2);
		$dateOfBirth = $row['dob'];
		$email = $row['email'];
	}

	// show the set profile form:
	$show_account_form = true;
	
	// we're finished with the database, close the connection:
	mysqli_close($connection);
}
}
		
	// if there was a match then extract their profile data:

echo $message_err;
if ($show_account_form)	
{
// display their profile data:
echo<<<_END
	<div class="container">

		<h4>Account Information</h4>
		<small>Here you can view/edit account information. Simply edit the information and click Update!</small>
		<hr>
		<form class="container" method="post">
			<div class="row">
				<div class="col">
					<label><p class="h6">Username:</p></label>
					<input type="text" class="form-control" placeholder="$username" disabled>
				</div>
				<div class="col">
					<label><p class="h6">Password:</p></label>
					<input maxlength="16" min="1" type="text" required name="password" class="form-control">
				</div>
			</div><hr>
			<div class="row">
				<div class="col">
					<label><p class="h6">Firstname:</p></label>
					<input maxlength="32" min="1" type="text" required name="firstname" class="form-control" value="$firstname">
				</div>
				<div class="col">
					<label><p class="h6">Surname:</p></label>
					<input maxlength="64" min="1" type="text" required name="surname" class="form-control" value="$surname">
				</div>
			</div><br>
			<div class="row">
				<div class="col">
					<label><p class="h6">Telephone number:</p></label>
					<div class="input-group mb-2">
						<div class="input-group-prepend">
							<div class="input-group-text">07</div>
						</div>
						<input maxlength="9" min="9" type="text" required name="telephoneNumber" class="form-control" value="$telephoneNumber">
					</div>		
				</div>
				<div class="col">
					<label><p class="h6">Date of birth:</p></label>
					<input type="date" max="2019-01-01" min="1919-01-01" name="dateOfBirth" class="form-control" value="$dateOfBirth">
				</div>
			</div><br>
			<div class="row">
				<div class="col">
					<label><p class="h6">Email:</p></label>
					<input maxlength="64" min="7" type="email" name="email" class="form-control" value="$email">
				</div>
			</div>

			<hr>

			<div class="row">
				<div class="col">
					<button style="float: right;" type="submit" value="Submit" class="btn btn-primary btn-lg">Update</button>
				</div>
			</div>
			
			<br>

		</form>
	</div>
_END;
}
}

// finish off the HTML for this page:
require_once("footer.php");
?>