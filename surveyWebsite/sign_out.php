<?php

// Things to notice:
// The main job of this script is to end the user's session
// Meaning we want to destroy the current session data

// execute the header script:
require_once "header.php";

echo '<div class="col-md-6 offset-md-3 text-center">';

// checks the session variable named 'loggedIn'
// take note that of the '!' (NOT operator) that precedes the 'isset' function
if (!isset($_SESSION['loggedIn']))
{
	// user isn't logged in, display a message saying they must be:
	echo "<div class='col-md-6 offset-md-3 text-center'><div class='alert alert-success' role='alert'>You are already logged in, please log out first.</div></div>";
}

// the user must be signed-in, proceed with ending the session
// and clearing session cookies and any others you may have added
else
{
	// user just clicked to logout, so destroy the session data:
	// first clear the session superglobal array:
	$_SESSION = array();
	// then the cookie that holds the session ID:
	setcookie(session_name(), "", time() - 2592000, '/');
	// then the session data on the server:
	session_destroy();

	echo "<div class='alert alert-success' role='alert'>You have successfully logged out, please <a href='sign_in.php'>click here</a></div>";
}

echo "</div>";
// finish of the HTML for this page:
require_once "footer.php";

?>