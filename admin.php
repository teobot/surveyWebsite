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

	//NEED TO CHANGE THIS TO THE ACCOUNTTYPE = ADMIN
	if ($_SESSION['username'] == "admin")
	{
	// Creating the table for all of the users information to be displayed in
	echo<<<_END
	<div id="error_message" style="display: none;" class="alert alert-danger" role="alert"></div>
		<div class="table-responsive">
			<table id="allUsersTable" class="table table-hover table-sm text-center" id="surveyTable">
				<thead>
					<tr>
						<th>username</th>
						<th>Account Type</th>
						<th>Delete User</th>
					</tr>
				</thead>
			</table>
		</div>
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

			$(document).on('click', '.deleteUser', function(){
				var usernameToDel = $(this).data('username');
				$.post('assets/api/deleteUserAccount.php', {toDeleteUsername: usernameToDel, username: '$username' })
				.done(function(data) {
					console.log(data);
				}).fail(function(error) {
					console.log(error.responseText);
				});
			});

			$(document).on('change', '.changeAccountType', function(){
				var accountType = $(this).val();
				var usernameToChange = $(this).data('user');
				$.post('assets/api/updateUserAccountType.php', {username: '$username', usernameToChange: usernameToChange, accountType: accountType })
				.done(function(data) {
					console.log(data);
				}).fail(function(error) {
					console.log(error.responseText);
				});
			});

			function getUsers() {
				$.post('assets/api/returnUsers.php', {username: '$username' })
					.done(function(data) {
						
						// remove the old table rows:
						$('.users').remove();

						console.log(data);
						
						// loop through what we got and add it to the table (data is already a JavaScript object thanks to getJSON()):
						$.each(data, function(index, value) {
							var rowMarkup = "";
							rowMarkup += "<tr class='users'>";

							rowMarkup += "<td>" + value.username + "</td>";

							rowMarkup += "<td>";
							rowMarkup += '<select data-user="'+value.username+'" class="changeAccountType custom-select custom-select-sm">';
							if (value.accountType === "default") { 
								rowMarkup += '<option value="default">Default</option>'; 
								rowMarkup += '<option value="admin">Admin</option>'; 
							}
							if (value.accountType === "admin") {
								rowMarkup += '<option value="admin">Admin</option>'; 
								rowMarkup += '<option value="default">Default</option>'; 
							}		
							rowMarkup += '</select>';
							rowMarkup += "</td>";

							rowMarkup += "<td> <button type='button' data-username='" + value.username + "' class='deleteUser btn btn-danger'>Delete</button></td>";

							rowMarkup += "</tr>";

							$('#allUsersTable').append(rowMarkup);
						});

						document.getElementById("error_message").style.display= 'none';
					})
					.fail(function(jqXHR) {
						// debug message to help during development:
						if (jqXHR.status = 400)
						{
							document.getElementById("error_message").style.display= 'block';
							document.getElementById("error_message").innerHTML = "There are no Users!";
						} else 
						{
							
						}
						
					});
						
					// call this function again after a brief pause:
				setTimeout(getUsers, 2000);       
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