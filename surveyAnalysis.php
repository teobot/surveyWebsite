<?php

// execute the header script:
require_once "header.php";
echo '<link rel="stylesheet" type="text/css" href="assets/style/analysisStyle.css">';

// checks the session variable named 'loggedIn'
if (!isset($_SESSION['loggedIn']))
{
	// user isn't logged in, display a message saying they must be:
	echo "You must be logged in to view this page.<br>";
}

// the user must be signed-in, show them suitable page content
else
{
    echo '<div id="error_message" style="display: none;" class="alert alert-danger" role="alert"></div>';
    echo<<<_END
    <div id='surveyResponses' class='col-md-8 offset-md-2'>
        <div id='responseNum' style="display: block;" class='text-left'></div>
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
        
                                $('#responseNum').append("<div class='display-4' id='responseNumText'>"+responseNum + " responses </div><small class='form-text text-muted'>Title : "+surveyData.survey_title+"</small>");

                                $.each(surveyData.survey_JSON, function(index, value) {
                                    surveyArray = value;
                                    responsesArray = responseData[Object.keys(responseData)[index]];

                                    console.log(surveyArray);
                                    console.log(responsesArray);	

                                    if (value.inputType == "number") {
                                        console.log("found number");

                                        google.charts.load('current', {'packages':['corechart']});

                                        google.charts.setOnLoadCallback(drawChart);

                                        var responseSection = document.createElement("div");
                                        responseSection.className = "responses text-center";

                                        $(responseSection).append('<div class="d-flex justify-content-center" id="chart_div"></div>'); 
                                        
                                        $( "#surveyResponses" ).append(responseSection);  


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
                                        console.log("found unknown");
                                    }

                                    console.log("--------------------");
                                });

                                document.getElementById("error_message").style.display= 'none';
                                document.getElementById("numberOfResponses").style.display= 'block';

                                console.log("FINISHED");

                            });
					})
					.fail(function(jqXHR) {
                        document.getElementById("error_message").style.display= 'block';
                        document.getElementById('error_message').innerHTML = jqXHR.responseJSON[0];
                    });
				//setTimeout(getResponses, 1000);
            }

            function drawChart() {

                // Create the data table.
                var data = new google.visualization.DataTable();
                data.addColumn('string', 'Topping');
                data.addColumn('number', 'Slices');
                data.addRows([
                  ['Mushrooms', 1],
                  ['Onions', 3],
                  ['Olives', 5],
                  ['Zucchini', 1],
                  ['Pepperoni', 2]
                ]);
        
                // Set chart options
                var options = {'title':'How Much Pizza I Ate Last Night',
                               'width':400,
                               'height':300};
        
                // Instantiate and draw our chart, passing in some options.
                var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
                chart.draw(data, options);
              }
        </script>
_END;
}

// finish off the HTML for this page:
require_once "footer.php";

?>