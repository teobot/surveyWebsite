<?php
//    Page Name - || survey_Analysis.php
//                --
// Page Purpose - || This collects the response data from a form and displays it for the users
//                --
//        Notes - ||
//         		  ||
//                --

// execute the header script:
require_once("header.php");
// Import custom styling
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
    $username = $_SESSION['username'];
    $surveyID = $_GET['surveyID'];

    //Here The data from the response is collected using APIS,
    // I use javascript to create graphs and section cards, based on the question type
    // e.g. a number question type will return a graph showing different stats as well as the raw information
    echo<<<_END
    <div id="error_message" style="display: none;" class="alert alert-danger" role="alert"></div>

    <div id='surveyResponses' class='col-md-8 offset-md-2'></div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript" src="assets/javascript/convertJSONToCsv.js"></script>
		<script>
			$(document).ready(function() {	
				// start checking for updates:
                getResponses();
                restUpdateCounter();	
            });
            
            function restUpdateCounter() {
                var counter = 15;

                timer();

                function timer() {
                    if(counter == 0) 
                    {
                        counter = 15;
                    }
                    $('#timeToNextUpdate').text("Updating in : " + counter);
                    counter--;
                    setTimeout(timer, 1000);
                }    
            }

            function getResponses() {

                $.post('assets/api/returnSurveyData.php', {surveyID: '$surveyID'})
                    .done(function(surveyData){

				        $.post('assets/api/returnResponses.php', {surveyID: '$surveyID', username: '$username' })
					        .done(function(responseData) {

                                $('.responses').remove();

                                var responseNum = responseData[Object.keys(responseData)[0]].length;

                                itemForExport = [];

                                $.each(responseData, function(index, value) {

                                    itemForExport.push({
                                        title: index,
                                        responses: value
                                    });

                                });
                                
                                $('#responseNum').remove();
                                var responses = "";
                                responses += "<div id='responseNum' style='display: block;' class='text-left'>";
                                responses += "<div class='display-4' id='responseNumText'>"+responseNum + " responses </div>";
                                responses += "<small class='form-text text-muted'>Title : "+surveyData.survey_title+" | Created By: "+surveyData.survey_creator+"  | <button class='btn btn-sm btn-outline-info' onclick='exportCSVFile(itemForExport)'>Download Data</button> | <small id='timeToNextUpdate'>Updating in : 15</small></small>";
                                responses += "</div>";
                                $('#surveyResponses').append(responses);

                                var counter = 0;
                                $.each(surveyData.survey_JSON, function(index, value) {

                                    surveyArray = value;
                                    responsesArray = responseData[Object.keys(responseData)[counter]];

                                    counter++;

                                    if(typeof responsesArray != 'undefined') 
                                    {
                                    
                                        if (value.inputType == "multipleChoice") {

                                            var divLocation = value.title;

                                            var responseSection = document.createElement("div");
                                            responseSection.className = "responses text-center";
                                            responseSection.id = "dashboard_" + divLocation;
                                            
                                            $(responseSection).append('<div class="d-flex justify-content-center" id="filter_'+divLocation+'"></div>');
                                            $(responseSection).append('<div class="d-flex justify-content-center" id="chart_'+divLocation+'"></div>');
                                            $( "#surveyResponses" ).append(responseSection); 

                                            google.charts.load('current', {'packages':['corechart', 'controls']});
                                            google.charts.setOnLoadCallback(drawChart);

                                            var choice1Count = 0;
                                            var choice2Count = 0;
                                            var num_of_resp = 0;

                                            $.each(responsesArray, function(index, response) {
                                                if(response !== "null")
                                                {
                                                    if (response === value.choice1) {
                                                        choice1Count++;
                                                        num_of_resp++;
                                                    } else if (response === value.choice2) {
                                                        choice2Count++;
                                                        num_of_resp++;
                                                    }      
                                                }         
                                            });

                                            function drawChart() {

                                                // Create the data table.
                                                var data = google.visualization.arrayToDataTable([
                                                    [value.title, 'Count'],
                                                    [value.choice1, choice1Count],
                                                    [value.choice2, choice2Count]
                                                ]);
                                        
                                                // Set chart options
                                                var options = {
                                                    'title':value.title + " | Total Respondents : " + num_of_resp,
                                                };
                                        
                                                // Instantiate and draw our chart, passing in some options.
                                                var chart = new google.visualization.PieChart(document.getElementById("chart_" + divLocation));
                                                chart.draw(data, options);

                                                var dashboard = new google.visualization.Dashboard(document.getElementById('dashboard_' + divLocation));

                                                var slider = new google.visualization.ControlWrapper({
                                                    'controlType': 'NumberRangeFilter',
                                                    'containerId': 'filter_' + divLocation,
                                                    'options': {
                                                        'filterColumnLabel': 'Count'
                                                    }
                                                });

                                                var pieChart = new google.visualization.ChartWrapper({
                                                    'chartType': 'PieChart',
                                                    'containerId': 'chart_' + divLocation,
                                                }); 

                                                dashboard.bind(slider, pieChart);
                                                dashboard.draw(data);
                                            }                                        

                                        } else if (value.inputType == "text") {

                                            var num_of_resp = 0;
                                            $.each(responsesArray, function(index, response) {
                                                if(response !== "null") 
                                                {
                                                    num_of_resp++;
                                                }
                                            });

                                            var responseSection = document.createElement("div");
                                            responseSection.className = "responses text-center";

                                            var responsesCount = responseData[Object.keys(responseData)[0]].length;
                
                                            $(responseSection).append("<div><p class='lead text-left'>"+surveyArray.title+" : <small>"+surveyArray.label+"</small><small style='font-size: 60%;' class='form-text text-muted'> Total Respondents : "+num_of_resp+"</small></p>");  
                
                                            $.each(responsesArray, function(index, response) {
                                                if(response !== "null")
                                                {
                                                    $(responseSection).append("<div class='resText text-left'>"+response+"</div>"); 
                                                }                
                                            });
                
                                            $( "#surveyResponses" ).append(responseSection);    
    
                                        } else if (value.inputType == "number") {

                                            var average = 0;
                                            var range = 0;
                                            var total = 0;
                                            var num_of_resp = 0;
                                            var highest = 0;
                                            var lowest = 99999999999;

                                            $.each(responsesArray, function(index, response) {
                                                if(response !== "null") 
                                                {
                                                    
                                                    each_number = parseInt(response);

                                                    if (each_number > highest) {
                                                        highest = each_number;
                                                    }

                                                    if (each_number < lowest) {
                                                        lowest = each_number;
                                                    }

                                                    total += each_number;
                                                    num_of_resp++;
                                                }
                                            });


                                            average = (total/num_of_resp).toFixed(2);
                                            range  = highest - lowest;

                                            var responseSection = document.createElement("div");
                                            responseSection.className = "responses text-center";
                                            responseSection.id = "dashboard_"+ value.title;

                                            $(responseSection).append("<div><p class='lead text-left'>"+surveyArray.title+" : <small>"+surveyArray.label+"</small><small style='font-size: 60%;' class='form-text text-muted'> Total Respondents : "+num_of_resp+"</small></p>");
                                            $(responseSection).append("<div class='text-center' id='filter_" + value.title +"'></div>");
                                            $(responseSection).append("<div class='text-center' id='chart_" + value.title +"'></div>");
                                            

                                            google.charts.load('current', {'packages':['corechart', 'controls']});
                                            google.charts.setOnLoadCallback(drawStuff);

                                            function drawStuff() {

                                                var data = new google.visualization.arrayToDataTable([
                                                    ['Statistics', 'Value'],
                                                    ["lowest", lowest],
                                                    ["Average", average],
                                                    ["Range", range],
                                                    ["total", total],
                                                    ["highest", highest]
                                                  
                                                ]);

                                                var options = {
                                                    title: 'Question Statistics',
                                                    width: '100%',
                                                    legend: { position: 'none' },
                                                    bars: 'horizontal',
                                                    axes: {
                                                      x: {
                                                        0: { side: 'top', label: 'Value'}
                                                      }
                                                    },
                                                  };
                                          
                                                var chart = new google.visualization.BarChart(document.getElementById("chart_" + value.title));
                                                chart.draw(data, options);

                                                var dashboard = new google.visualization.Dashboard(document.getElementById("dashboard_" + value.title));

                                                var slider = new google.visualization.ControlWrapper({
                                                    'controlType': 'NumberRangeFilter',
                                                    'containerId': 'filter_' + value.title,
                                                    'options': {
                                                    'filterColumnLabel': 'Value'
                                                    }
                                                });

                                                var BarChart = new google.visualization.ChartWrapper({
                                                    'chartType': 'BarChart',
                                                    'containerId': 'chart_' + value.title,
                                             }); 

                                                dashboard.bind(slider, BarChart);
                                                dashboard.draw(data);
                                              
                                            };

                                            $.each(responsesArray, function(index, response) {
                                                if(response !== "null")
                                                {
                                                    $(responseSection).append("<div class='resText text-left'>"+response+"</div>"); 
                                                }                
                                            });
                
                                            $( "#surveyResponses" ).append(responseSection);

                                        } else if (value.inputType == "date") {
                                            var responseSection = document.createElement("div");
                                            responseSection.className = "responses text-center";

                                            var num_of_resp = 0;
                                            $.each(responsesArray, function(index, response) {
                                                if(response !== "null") 
                                                {
                                                    num_of_resp++;
                                                }
                                            });
                
                                            $(responseSection).append("<div><p class='lead text-left'>"+surveyArray.title+" : <small>"+surveyArray.label+"</small><small style='font-size: 60%;' class='form-text text-muted'> Total Respondents : "+num_of_resp+"</small></p>");  
                
                                            $.each(responsesArray, function(index, response) {
                                                var date = response.substring(0, 4) + "-" + response.substring(4, 6) + "-" + response.substring(6, 8);

                                                $(responseSection).append("<div class='resText text-left'>"+date+"</div>");      
                                            });
                
                                            $( "#surveyResponses" ).append(responseSection);
                                        } else {
                                            var responseSection = document.createElement("div");
                                            responseSection.className = "responses text-center";

                                            var num_of_resp = 0;
                                            $.each(responsesArray, function(index, response) {
                                                if(response !== "null") 
                                                {
                                                    num_of_resp++;
                                                }
                                            });
                
                                            $(responseSection).append("<div><p class='lead text-left'>"+surveyArray.title+" : <small>"+surveyArray.label+"</small><small style='font-size: 60%;' class='form-text text-muted'> Total Respondents : "+num_of_resp+"</small></p>");  
                               
                                            $.each(responsesArray, function(index, response) {
                                                $(responseSection).append("<div class='resText text-left'>"+response+"</div>");      
                                            });
                
                                            $( "#surveyResponses" ).append(responseSection);
                                        }
                                    }
                                });

                                document.getElementById("error_message").style.display= 'none';
                                document.getElementById("surveyResponses").style.display= 'block';

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
				setTimeout(getResponses, 15000);
            }
        </script>
_END;
}

// finish off the HTML for this page:
require_once("footer.php");

?>