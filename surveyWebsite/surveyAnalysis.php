<?php

// execute the header script:
require_once "header.php";
echo '<link rel="stylesheet" type="text/css" href="assets/style/analysisStyle.css">';

// checks the session variable named 'loggedIn'
if (!isset($_SESSION['loggedIn']))
{
	// user isn't logged in, display a message saying they must be:
    echo "<div class='col-md-6 offset-md-3 text-center'><div class='alert alert-success' role='alert'>You are already logged in, please log out first.</div></div>";
}

// the user must be signed-in, show them suitable page content
else
{
    echo '<div id="error_message" style="display: none;" class="alert alert-danger" role="alert"></div>';
    echo<<<_END
    <div id='surveyResponses' class='col-md-8 offset-md-2'>
    </div>
_END;

    $username = $_SESSION['username'];
    $surveyID = $_GET['surveyID'];
	echo<<<_END
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
		<script>
			$(document).ready(function() {	
				// start checking for updates:
                getResponses();	
			});

            function getResponses() {

                $.post('assets/api/returnSurveyData.php', {surveyID: '$surveyID'})
                    .done(function(surveyData){

				        $.post('assets/api/returnResponses.php', {surveyID: '$surveyID', username: '$username' })
					        .done(function(responseData) {

                                $('.responses').remove();

                                var responseNum = responseData[Object.keys(responseData)[0]].length

                                console.log(surveyData);
                                console.log("END---------^survey data^---------END");
                                console.log(responseData);
                                console.log("END---------^responseData^---------END");                               
                                
                                $('#responseNum').remove();
                                $('#surveyResponses').append("<div id='responseNum' style='display: block;' class='text-left'><div class='display-4' id='responseNumText'>"+responseNum + " responses </div><small class='form-text text-muted'>Title : "+surveyData.survey_title+"</small></div>");

                                var counter = 0;
                                $.each(surveyData.survey_JSON, function(index, value) {

                                    console.log("START---------------START");
                                    surveyArray = value;
                                    responsesArray = responseData[Object.keys(responseData)[counter]];

                                    console.log(surveyArray);
                                    console.log("^surveyarray - value^");
                                    console.log(responsesArray);
                                    console.log("^responsesarray^");
                                    console.log(counter);
                                    console.log("^counter - value^");

                                    counter++;

                                    if(typeof responsesArray != 'undefined') 
                                    {
                                    
                                        if (value.inputType == "number") {
                                            console.log("found number");

                                            var responseSection = document.createElement("div");
                                            responseSection.className = "responses text-center";
                                            var divLocation = value.title;
                                            $(responseSection).append('<div class="d-flex justify-content-center" id="'+divLocation+'"></div>');
                                            $( "#surveyResponses" ).append(responseSection); 

                                            google.charts.load('current', {'packages':['corechart']});
                                            google.charts.setOnLoadCallback(drawChart);

                                            function drawChart() {

                                                // Create the data table.
                                                var data = google.visualization.arrayToDataTable([
                                                    ['Task', 'Hours per Day'],
                                                    ['Work',     11],
                                                    ['Eat',      2],
                                                    ['Commute',  2],
                                                    ['Watch TV', 2],
                                                    ['Sleep',    7]
                                                ]);
                                        
                                                // Set chart options
                                                var options = {
                                                    'title':value.title,
                                                };
                                        
                                                // Instantiate and draw our chart, passing in some options.
                                                var chart = new google.visualization.PieChart(document.getElementById(divLocation));
                                                chart.draw(data, options);
                                
                                            }                                        

                                        } else if (value.inputType == "text") { 
                                            console.log("found text");

                                            var responseSection = document.createElement("div");
                                            responseSection.className = "responses text-center";
                
                                            $(responseSection).append("<div><p class='lead text-left'>"+surveyArray.title+" : <small>"+surveyArray.label+"</small></p>");  
                
                                            $.each(responsesArray, function(index, response) {
                                                $(responseSection).append("<div class='resText text-left'>"+response+"</div>");      
                                            });
                
                                            $( "#surveyResponses" ).append(responseSection);    
    
                                        } else {
                                            console.log("found text based data");

                                            var responseSection = document.createElement("div");
                                            responseSection.className = "responses text-center";
                
                                            $(responseSection).append("<div><p class='lead text-left'>"+surveyArray.title+" : <small>"+surveyArray.label+"</small></p>");  
                
                                            $.each(responsesArray, function(index, response) {
                                                $(responseSection).append("<div class='resText text-left'>"+response+"</div>");      
                                            });
                
                                            $( "#surveyResponses" ).append(responseSection);
                                        }
                                    }

                                    console.log("END OF EACH--------------------END OF EACH");
                                });

                                document.getElementById("error_message").style.display= 'none';
                                document.getElementById("surveyResponses").style.display= 'block';

                                console.log("FINISHED");

                        })
                        .fail(function(jqXHR) {
                            document.getElementById("error_message").style.display= 'block';
                            document.getElementById('error_message').innerHTML = jqXHR.responseJSON[0];
                        });
                            
					})
					.fail(function(jqXHR) {
                        document.getElementById("error_message").style.display= 'block';
                        document.getElementById('error_message').innerHTML = jqXHR.responseJSON[0];
                    });
				//setTimeout(getResponses, 1000);
            }
        </script>
_END;
}

// finish off the HTML for this page:
require_once "footer.php";

?>