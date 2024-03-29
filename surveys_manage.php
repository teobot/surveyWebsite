<?php
//    Page Name - || surveys_manage.php
//                --
// Page Purpose - || This is the survey manager 
//                --
//        Notes - || If the user is a default account they can only manipulate their own surveys and the surveys marked everyone
//                --

// execute the header script:
require_once("header.php");

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
	// Making a API call to the returnUsers.php API, asking for all the usernames in the database,
	// And also posting the current username of the logged in user to make sure they're a admin.
	$username = $_SESSION['username'];

	echo<<<_END

	<a class="btn btn-success btn-lg btn-block" href="survey_Creator.php" role="button">+ Click to create new survey!</a><br>

	<div style="border-left: 5px solid lightblue;" class="container">
		<input id="searchForSurvey" class="form-control mr-sm-2" type="search" placeholder="Search By Survey Title"><br>
		<div id="displaySearchedSurvey">
			<div class="alert alert-info" role="alert">
				Start Searching For Surveys...
			</div>	
		</div>
	</div><br>

	<div class="table-responsive">
		<table class="table table-hover table-sm text-center" id="surveyTable">
			<thead>
				<tr>
					<th>Survey ID</th>
					<th>Creator</th>
					<th>Survey Title</th>
					<th>Survey Edit</th>
					<th>Survey View</th>
					<th>Analytics</th>
					<th>Responses</th>
					<th>Delete</th>
				</tr>
			</thead>
		</table>
	</div>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
		<script>
			$(document).ready(function() {	
				// start checking for updates:
				var surveyData = [];
				getSurveys();	
			});

			$('#searchForSurvey').on('input', function() {
				var surveyToFind = $(this).val();

				$.each(surveyData, function(index, value) {

					if(surveyToFind === value['survey_title']) {

						document.getElementById("displaySearchedSurvey").innerHTML = '<div class="table-responsive table-sm"><table class="table table-hover table-sm text-center"><thead><tr><th>Survey ID</th><th>Creator</th><th>Survey Title</th><th>Survey Edit</th><th>Survey View</th><th>Analytics</th><th>Responses</th><th>Delete</th></tr></thead><tbody><tr><td>'+value.survey_id+'</td><td>'+value.creator+'</td><td>'+value.survey_title+'</td><td><a href="survey_Creator.php?surveyID='+value.survey_id+'">Edit Survey<a></td><td><a href="survey_view.php?surveyID=' + value.survey_id + '">View Survey</a></td><td><a href="survey_Analysis.php?surveyID='+value.survey_id+'">View Analytics</a></td><td><div class="text-center"><span class="badge badge-primary badge-pill">'+value.responseCount+'</span></div></td><td><button type="button" data-surveyid="'+value.survey_id+'" class="deleteSurvey btn btn-outline-danger btn-sm">Delete Survey</button></td></tr></tbody></table></div>'
						return false;

					} else if (surveyToFind === "") {
						document.getElementById("displaySearchedSurvey").innerHTML = '<div class="alert alert-info" role="alert">Start Searching For Surveys...</div>';
					} else {
						document.getElementById("displaySearchedSurvey").innerHTML = '<div class="alert alert-warning" role="alert">Searched Survey Does Not Exist!</div>';
					}

				});
			});

			$(document).on('click', '.deleteSurvey', function(){
				var surveyToDel = $(this).data('surveyid');
				$.post('assets/api/deleteSurvey.php', {surveyid: surveyToDel, username: '$username' })
				.done(function(data) {
					console.log(data);
				}).fail(function(error) {
					console.log(error);
				});
			});

			function getSurveys() {
				$.post('assets/api/returnUsersSurveys.php', {username: '$username' })
					.done(function(data) {
						surveyData = data;
						
						// remove the old table rows:
						$('.surveys').remove();
						
						// loop through what we got and add it to the table (data is already a JavaScript object thanks to getJSON()):
						$.each(data, function(index, value) {
							var insideHTML = "";
							insideHTML += "<tr class='surveys'>";
							insideHTML += "<td>"+value.survey_id+"</td>";
							insideHTML += "<td>"+value.creator+"</td>";
							insideHTML += "<td>"+value.survey_title+"</td>";
							insideHTML += "<td><a href='survey_Creator.php?surveyID="+value.survey_id+"'>Edit Survey<a></td>";
							insideHTML += "<td><a href='survey_view.php?surveyID=" + value.survey_id + "'>View Survey</a></td>";
							insideHTML += "<td><a href='survey_Analysis.php?surveyID="+value.survey_id+"'>View Analytics</a></td>";
							insideHTML += "<td><div class='text-center'><span class='badge badge-primary badge-pill'>"+value.responseCount+"</span></div></td>";
							insideHTML += "<th><button type='button' data-surveyid='"+value.survey_id+"' class='deleteSurvey btn btn-outline-danger btn-sm'>Delete Survey</button></th>";
							insideHTML += "</td>";
							
							$('#surveyTable').append(insideHTML);
						});

						console.log(data);
					}).fail(function(jqXHR) {
						// remove the old table rows:
						$('.surveys').remove();
					}) 
					
				setTimeout(getSurveys, 2000);
			}
		</script>
_END;   
}

// finish off the HTML for this page:
require_once("footer.php");

?>