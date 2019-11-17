<?php

// Things to notice:
// This is the page where each user can MANAGE their surveys
// As a suggestion, you may wish to consider using this page to LIST the surveys they have created
// Listing the available surveys for each user will probably involve accessing the contents of another TABLE in your database
// Give users options such as to CREATE a new survey, EDIT a survey, ANALYSE a survey, or DELETE a survey, might be a nice idea
// You will probably want to make some additional PHP scripts that let your users CREATE and EDIT surveys and the questions they contain
// REMEMBER: Your admin will want a slightly different view of this page so they can MANAGE all of the users' surveys

// execute the header script:
require_once "header.php";

// checks the session variable named 'loggedIn'
// take note that of the '!' (NOT operator) that precedes the 'isset' function
if (!isset($_SESSION['loggedIn']))
{
	// user isn't logged in, display a message saying they must be:
	echo "<div class='col-md-6 offset-md-3 text-center'><div class='alert alert-success' role='alert'>You are already logged in, please log out first.</div></div>";
}

// the user must be signed-in, show them suitable page content
else
{
	echo '<a class="btn btn-primary btn-lg btn-block" href="surveyCreator.php" role="button">Click to create new survey!</a><br>';
	echo<<<_END
	<div class="table-responsive">
		<table class="table table-hover table-sm text-center" id="surveyTable">
			<thead>
				<tr>
					<th>Survey ID</th>
					<th>Survey Title</th>
					<th>Survey Edit</th>
					<th>Survey View</th>
					<th>Analytics</th>
					<th>Responses</th>
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
				getSurveys();	
			});

			function getSurveys() {
				$.post('assets/api/returnUsersSurveys.php', {username: '$username' })
					.done(function(data) {
						
						// remove the old table rows:
						$('.surveys').remove();
						
						// loop through what we got and add it to the table (data is already a JavaScript object thanks to getJSON()):
						$.each(data, function(index, value) {
							$('#surveyTable').append("<tr class='surveys'> <td>"+value.survey_id+"</td> <td> "+ value.survey_title+" </td><td><a href='#'>Edit Survey</a></td> <td> <a href='survey_view.php?surveyID=" + value.survey_id + "'>View Survey</a> </td><td><a href='surveyAnalysis.php?surveyID="+value.survey_id+"'>View Analytics</a></td><td><div class='text-center'><span class='badge badge-primary badge-pill'>"+value.responseCount+"</span></div></td></tr>");
						});
					})
					.fail(function(jqXHR) {
						// debug message to help during development:
						console.log('request returned failure, HTTP status code ' + jqXHR.status);
					}) 
				//setTimeout(getSurveys, 1000);
			}
		</script>
_END;
    
    // a little extra text that only the admin will see:
	if ($_SESSION['username'] == "admin")
	{
		echo "[admin sees more!]<br>";
	}
    
}

// finish off the HTML for this page:
require_once "footer.php";

?>