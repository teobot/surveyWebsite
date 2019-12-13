<?php
//    Page Name - || account.php
//                --
// Page Purpose - || This is the account information page
//                || From here the user can edit their own information and set a a new password
//                --
//        Notes - ||
//         		  ||
//                --

// Insert the header
require_once("header.php");

// Create my variables to be inserted into the form
$password = "";
$firstname = "";
$surname = "";
$telephoneNumber = "";
$dateOfBirth = "";
$email = "";

// Create my error variables so any errors with the variables are shown
$password_err = "";
$firstname_err = "";
$surname_err = "";
$telephoneNumber_err = "";
$dateOfBirth_err = "";
$email_err = "";

// Combined error message variable
$message_err = "";

//If this is true, then the form for inputting data is shown
$show_account_form = false;

// Check if the user is logged in from the session variable
if (!isset($_SESSION['loggedIn']))
{
	// Display the message as the user is not logged in
	echo "<div class='col-md-6 offset-md-3 text-center'><div class='alert alert-success' role='alert'>You are already logged in, please log out first.</div></div>";
}
// If the user has clicked submit check if all data is present
elseif ( (isset($_POST['password'])) || (isset($_POST['firstname'])) || (isset($_POST['surname'])) || (isset($_POST['telephoneNumber'])) || (isset($_POST['dateOfBirth'])) || (isset($_POST['email'])) )
{
	//Get the username of the logged in user
	$username = $_SESSION["username"];
	
	// Connect to the database
	$connection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
	
	// Check if the connection fails
	if (!$connection)
	{
		die("Connection failed: " . $mysqli_connect_error);
	}

	// Get the posted variables and save them
	$password = $_POST['password'];
	$firstname = $_POST['firstname'];
	$surname = $_POST['surname'];
	$telephoneNumber = $_POST['telephoneNumber'];
	$dateOfBirth = $_POST['dateOfBirth'];
	$email = $_POST['email'];

	// Sanitize the user inputted data
	$password = sanitise($password, $connection);
	$firstname = sanitise($firstname, $connection);
	$surname = sanitise($surname, $connection);
	$telephoneNumber = sanitise($telephoneNumber, $connection);
	$dateOfBirth = sanitise($dateOfBirth, $connection);
	$email = sanitise($email, $connection);
	// ...
	
	// Check if the sanitized data is valid to be inserted into the db
	$password_err = validateString($password, 1, 16, "Password");
	$firstname_err = validateString($firstname, 1, 32, "Firstname");
	$surname_err = validateString($surname, 1, 64, "Surname");
	$telephoneNumber_err = validateTelephoneNumber($telephoneNumber, 9);
	$dateOfBirth_err = validateDate($dateOfBirth);
	$email_err = validateEmail($email, 1, 64, "Email");
    // ...
	
	// Combine all the error messages
	$message_err = $password_err . $firstname_err . $surname_err . $telephoneNumber_err . $dateOfBirth_err . $email_err;
	
	// If there are no errors then insert the data
	if ($message_err == "")
	{		
	
		//Create my sql query to see if the user exists
		$query = "SELECT `username` FROM `users` WHERE username='$username'";
		
		// This executes the sql query and returns the result to $result
		$result = mysqli_query($connection, $query);
		
		// This returns the number of rows back, telling me if the user exists
		$numberOfRows = mysqli_num_rows($result);
			
		// The user exists so update the information
		if ($numberOfRows > 0)
		{
			// Encrypt the password
			$password = sha1($password);
			// Create the update query
			$query = "UPDATE `users` SET `password` = '$password', `firstname` = '$firstname', `surname` = '$surname', `email` = '$email', `dob` = '$dateOfBirth', `telephoneNumber` = '07$telephoneNumber' WHERE `users`.`username` = '$username'";
			// Execute the update query to the database
			$result = mysqli_query($connection, $query);		
		}
	
		// If the Update is successful then tell the user it worked, otherwise tell the user it failed
		if ($result) 
		{
			// show a successful update message:
			$message_err = '<div class="alert alert-success" role="alert"><h4 class="alert-heading">Account Information Updated!</h4><p>You have successfully update your account, Go back to the <a href="account.php">account!</a></p></div>';
		}
		else
		{
			// show an unsuccessful update message:
			$message_err = '<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Account Update Unsuccessful!</h4><p>Something went wrong with the update!, Please Go back to the <a href="account.php">account</a> and try again!</p></div>';
		}

	}
	else
	{
		// validation failed, show the form again with the new error messages
		$show_account_form = true;
	}
	
	// we're finished with the database, close the connection:
	mysqli_close($connection);

}
// The user is logged in and didn't update the information, display their data to be edited
else
{
    // Get the username of the logged in user
	$username = $_SESSION["username"];
	
	// Create a new database connection
	$connection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
	
	// Check if the connection fails
	if (!$connection)
	{
		die("Connection failed: " . $mysqli_connect_error);
	}
	
	// Create a new query, this gets all the users information
	$query = "SELECT `username`, `firstname`, `surname`, `email`, `dob`, `telephoneNumber` FROM `users` WHERE username='$username'";
	
	// This gets the sql query and uses that to get the users information from the database
	$result = mysqli_query($connection, $query);
	
	// Get the number of rows from the database result, to see if the query worked
	$numberOfRows = mysqli_num_rows($result);

	// If the user exists then format the returned data
	if ($numberOfRows > 0)
	{
		// Get the first and only row of data and set own variables to the returned info
		$row = mysqli_fetch_assoc($result);
		$firstname = $row['firstname'];
		$surname = $row['surname'];
		$telephoneNumber = substr($row['telephoneNumber'], 2);
		$dateOfBirth = $row['dob'];
		$email = $row['email'];
		// ...
	}

	// show the set profile form:
	$show_account_form = true;
	
	// we're finished with the database, close the connection:
	mysqli_close($connection);
}
		
// Display any errors messages we have
echo $message_err;

// This displays the account form, it includes client side validation and the input areas have placeholder values of the users data for easy editing.
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

// finish off the HTML for this page:
require_once("footer.php");
?>