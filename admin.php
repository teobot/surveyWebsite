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
require_once("header.php");

// Check if the user is logged in from the session variable
if (!isset($_SESSION['loggedIn']))
{
	// Display the message as the user is not logged in
	echo "<div class='col-md-6 offset-md-3 text-center'><div class='alert alert-success' role='alert'>You are already logged in, please log out first.</div></div>";
}
// The user is logged in so can view the page if them are a admin
else
{
	// The user has to be a admin to view this page, If they are allow access
	$isUserAdmin = false;

	// Get the username from the session
	$username = $_SESSION['username'];

	//Make a new connection to the database
	$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
	// If the connection fails then display the error
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	// Create a query to get the account type of the user
	$sql = "SELECT accountType FROM `users` WHERE `username` = '$username'";
	// send the query to the database and save the return
	$result = $conn->query($sql);
	// Get the first and only row back from the result
	$row = $result->fetch_assoc();

	// If the user is a admin then changed the boolean variable to true
	if($row['accountType'] === "admin") 
	{
		$isUserAdmin = true;
	}
	
	// Close the connection
	$conn->close();
	//----------------------------

	// If the user is a admin then they need to view the webpage,
	// This consists of a form to create a new user,
	// a search feature
	// and a table to get all the users information
	if ($isUserAdmin)
	{
		//Here I create the new user button and insert the form into a collapsable area
		echo<<<_END
		<div id="error_message" style="display: none;" class="alert alert-danger" role="alert"></div>


		<p>
			<button class="btn btn-success btn-lg" type="button" data-toggle="collapse" data-target="#create_new_user_form">
				Create New User Here
	  		</button>
		</p>

		<div class="collapse container"  style="border-left: 5px solid lightgreen;" id="create_new_user_form"><br>
_END;

		require_once("assets/PHPcomponents/admin_create_user_form.php");

		//This displays the search feature and the data table,
		echo<<<_END
		</div>
		
		<hr>

		<div style="border-left: 5px solid lightblue;" class="container">
			<input id="searchForUser" class="form-control mr-sm-2" type="search" placeholder="Search By Username"><br>
			<div id="displaySearchedUser">
				<div class="alert alert-info" role="alert">
					Start Searching For Users...
				</div>	
			</div>
		</div>

		<div class="table-responsive">
			<table id="allUsersTable" class="table table-hover table-sm text-center" id="surveyTable">
				<thead>
					<tr>
						<th>username</th>
						<th>Account Type</th>
						<th>Edit Account Information</th>
						<th>Delete User</th>
					</tr>
				</thead>
			</table>
		</div>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
		<script>
			$(document).ready(function() {	
				// start checking for updates:
				var users = [];
				getUsers();
			});

			$('#searchForUser').on('input', function() {

				//This is my search for user function

				var userToFind = $(this).val();

				$.each(users, function(index, value) {

					if(userToFind === value['username']) {

						var selectMarkup = "";
						selectMarkup += "<td>";
						selectMarkup += '<select data-user="'+value.username+'" class="changeAccountType custom-select custom-select-sm">';
						if (value.accountType === "default") { 
							selectMarkup += '<option value="default">Default</option>'; 
							selectMarkup += '<option value="admin">Admin</option>'; 
						}
						if (value.accountType === "admin") {
							selectMarkup += '<option value="admin">Admin</option>'; 
							selectMarkup += '<option value="default">Default</option>'; 
						}		
						selectMarkup += '</select>';
						selectMarkup += "</td>";

						document.getElementById("displaySearchedUser").innerHTML = '<div class="table-responsive table-sm"><table class="table table-hover table-sm text-center"><thead><tr><th>username</th><th>Account Type</th><th>Edit Account Information</th><th>Delete User</th></tr></thead><tbody><tr><td>'+value.username+'</td>'+selectMarkup+'<td><a class="btn btn-primary" href="admin_Account_edit.php?username='+value.username+'" >Edit Account Info</a></td><td><button type="button" data-username="' + value.username + '" class="deleteUser btn btn-danger">Delete</button></td></tr></tbody></table></div>'
						return false;
					} else if (userToFind === "") {
						document.getElementById("displaySearchedUser").innerHTML = '<div class="alert alert-info" role="alert">Start Searching For Users...</div>';
					} else {
						document.getElementById("displaySearchedUser").innerHTML = '<div class="alert alert-warning" role="alert">Searched User Does Not Exist!</div>';
					}

				});

			});

			$(document).on('click', '#adminCreateNewAccount', function(){
				// this is my create new account function

				event.preventDefault();
		
				$.post('assets/api/insertNewAccount.php', {username: '$username', accountInfoArray: $( this ).closest("form").serializeArray() })
				.done(function(data) {
					document.getElementById("adminCreateUserForm").reset();
					document.getElementById("accountCreationError").innerHTML = data;
				})
				.fail(function(error) {
					document.getElementById("accountCreationError").innerHTML = error.responseText;
				});
			});

			$(document).on('click', '.deleteUser', function(){
				// This is my delete user function
				var usernameToDel = $(this).data('username');
				$.post('assets/api/deleteUserAccount.php', {toDeleteUsername: usernameToDel, username: '$username' })
				.done(function(data) {
					console.log(data);
				}).fail(function(error) {
					console.log(error.responseText);
				});
			});

			$(document).on('change', '.changeAccountType', function(){
				//This is my change the account type function
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
				//Here I send my user to the API which returns all the users in a JSON array,
				//And then I insert these into the table with some extra functions.
				$.post('assets/api/returnUsers.php', {username: '$username' })
					.done(function(data) {
						
						$('.users').remove();

						users = data;
						
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

							rowMarkup += '<td><a class="btn btn-primary" href="admin_Account_edit.php?username='+value.username+'" >Edit Account Info</a></td>';

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
						}		
					});
						
					// call this function again after a brief pause:
				setTimeout(getUsers, 5000);       
			}
		</script>
_END;
	}
	// If they aren't a admin, then they cannot access the page
	else
	{
		// Telling the user that they do not have the correct user account
		echo "<div class='col-md-6 offset-md-3 text-center'><div class='alert alert-warning' role='alert'>You're not a admin!</div></div>";
	}
}

// Inserting the footer into the bottom of the webpage
require_once("footer.php");
?>