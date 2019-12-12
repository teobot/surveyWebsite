<?php
echo<<<_END
<!DOCTYPE html>
<html>
	<head>
		<title>A Survey Website</title>
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">		<noscript><div class="p-3 mb-2 bg-danger text-white">Sorry, your browser does not support JavaScript!</div></noscript>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
	</head>
		<div class="container-fluid">
_END;

// Things to notice:
// This script is called by every other script (via require_once)
// It begins the HTML output, with the customary tags, that will produce each of the pages on the web site
// It starts the session and displays a different set of menu links depending on whether the user is logged in or not...
// ... And, if they are logged in, whether or not they are the admin
// It also reads in the credentials for our database connection from credentials.php

// database connection details:
require_once("credentials.php");

// our helper functions:
require_once("validationChecker.php");

// start/restart the session:
// this allows use to make use of session variables
session_start();

// checks the session variable named 'loggedIn'
if (isset($_SESSION['loggedIn']))
{
	// THIS PERSON IS LOGGED IN
	// show the logged in menu options:

echo <<<_END
<nav class="navbar navbar-light" style="background-color: #e3f2fd;">
	<ul class="nav justify-content-center">
	  <li class="nav-item">
	    <a class="nav-link" href="index.php">Home</a>
	  </li>
	  <li class="nav-item">
	    <a class="nav-link" href="account.php">My Account</a>
	  </li>
	  <li class="nav-item">
	    <a class="nav-link" href="surveys_manage.php">My Surveys</a>
	  </li>
	  <li class="nav-item">
	    <a class="nav-link" href="competitors.php">Design and Analysis</a>
	  </li>
_END;

    // Create a new connection to database
    $checkAdmin = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

    // Check if the connection worked otherwise return a error
    if (!$checkAdmin) 
    { 
		echo "could not check if the user is a admin!";
    }

    $username = $_SESSION['username'];
    
    //IF THE USER IS NOT A ADMIN THEN EXIT
    $sql = "SELECT * FROM `users` WHERE `username` = '$username' AND `accountType` = 'admin'";
    $checkAccountType = mysqli_query($checkAdmin, $sql);
	$checkAccountResult = mysqli_num_rows($checkAccountType);
	$checkAdmin->close();

    if (!empty($checkAccountResult))
	{
		echo '<li class="nav-item"><a class="nav-link" href="admin.php">Admin Tools</a></li>';
	}
	
echo<<<_END
		<li class="nav-item">
			<a class="nav-link" href="sign_out.php">Sign Out ({$_SESSION['username']})</a>
		</li>
	</ul>
</nav>
_END;
}

else
{
	// THIS PERSON IS NOT LOGGED IN
	// show the logged out menu options:
echo <<<_END
<nav class="navbar navbar-light" style="background-color: #e3f2fd;">
	<ul class="nav justify-content-center">
	  <li class="nav-item">
	    <a class="nav-link" href="index.php">Home</a>
	  </li>
	  <li class="nav-item">
	    <a class="nav-link" href="sign_in.php">Sign In</a>
	  </li>
	  <li class="nav-item">
	    <a class="nav-link" href="sign_up.php">Sign Up</a>
	  </li>
	</ul>
</nav>
_END;
}

echo <<<_END
</div>
	<body>
		<div class="container">

	<br>
	<div class="text-center">
		<h1>2CWK50: A Survey Website</h1>
	</div>
	<br>
_END;
?>