<?php
//    Page Name - || admin.php
//                --
// Page Purpose - || This is the admin tool page, from here the administrator can:
//                || access a list of all the current users, updating in real time using a API call
//                || View the users account information and edit/delete that user
//                || Create new account
//                || access a list of all surveys, updating in real time using a API call
//                --
//        Notes - || This is the admin section for managing the website
//         		  ||
//                --

// Inserts the header into the webpage
require_once "header.php";

// Check if the user is logged in, if not then tell them to sign in
if (!isset($_SESSION['loggedIn']))
{
	// The user is not logged in, Tell them to go sign in
	echo "<div class='col-md-6 offset-md-3 text-center'><div class='alert alert-success' role='alert'>You are already logged in, please log out first.</div></div>";
}
// The user is logged in so can view the page if them are a admin
else
{
	// The user has to be a admin to view this page, If they are allow access
	if ($_SESSION['username'] == "admin")
	{
	// Creating the table for all of the users information to be displayed in
	echo<<<_END
		<table class="table" id="allUsersTable">
			<tr>
				<th>username</th>
			</tr>
		</table>
_END;

	// Making a API call to the returnUsers.php API, asking for all the usernames in the database,
	// And also posting the current username of the logged in user to make sure they're a admin.
	$username = $_SESSION['username'];
	echo<<<_END
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
		<script>
			$(document).ready(function() {	
				// start checking for updates:
				getUsers();	
			});

			function getUsers() {
				$.post('assets/api/returnUsers.php', {username: '$username' })
					.done(function(data) {
						
						// remove the old table rows:
						$('.users').remove();
						
						// loop through what we got and add it to the table (data is already a JavaScript object thanks to getJSON()):
						$.each(data, function(index, value) {
							$('#allUsersTable').append("<tr class='users'><td>" + value.username + "</td></tr>");
						});
					})
					.fail(function(jqXHR) {
						// debug message to help during development:
						console.log('request returned failure, HTTP status code ' + jqXHR.status);
					})
						
					// call this function again after a brief pause:
				setTimeout(getUsers, 1000);       
			}
		</script>
_END;
	}
	// If they aren't a admin, then they cannot access the page
	else
	{
		// Telling the user that they do not have the correct user account
		echo "You don't have permission to view this page...<br>";
	}
}



// Inserting the footer into the bottom of the webpage
require_once "footer.php";
?>