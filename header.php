<!DOCTYPE html>
<html>
	<head>
		<title>A Survey Website</title>
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	</head>
<body class="container">

<?php

// Things to notice:
// This script is called by every other script (via require_once)
// It begins the HTML output, with the customary tags, that will produce each of the pages on the web site
// It starts the session and displays a different set of menu links depending on whether the user is logged in or not...
// ... And, if they are logged in, whether or not they are the admin
// It also reads in the credentials for our database connection from credentials.php

// database connection details:
require_once "credentials.php";

// our helper functions:
require_once "validationChecker.php";

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
	  <li class="nav-item">
	    <a class="nav-link" href="sign_out.php">Sign Out ({$_SESSION['username']})</a>
	  </li>
	</ul>
</nav>

_END;

    // add an extra menu option if this was the admin:
    // this allows us to display the admin tools to them only
	if ($_SESSION['username'] == "admin")
	{
		echo " |||| <a href='admin.php'>Admin Tools</a>";
	}
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
<br>
<h1>2CWK50: A Survey Website</h1>
<br>
_END;
?>