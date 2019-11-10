<?php
// execute the header script:
require_once "header.php";

$surveyID = $_GET['surveyID'];

echo '<div class="text-center">';

echo '<div><h1 id="surveyTitle">Loading...</h1></div>';

echo '<div id="questionsContainer">Loading...</div>';

echo '</div>';

echo<<<_END
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script>
        $(document).ready(function() {	
            // start checking for updates:
            getSurveys();	
        });

        function getSurveys() {
            $.post('assets/api/returnSurveyData.php', {surveyID: '$surveyID' })
                .done(function(data) {

                    console.log(data);

                    // remove the old table rows:
                    $('.questions').remove();

                    $('#questionsContainer').empty();
                    
                    $('#surveyTitle').html(data.survey_title);

                    $.each(data.survey_JSON, function( key, value ) {
                        $('#questionsContainer').append("<div class='questions'> <div class='h6'>"+ value.title +"</div> <div><small>"+value.label+"</small></div> <div class='input'><input></input></div> </div><br>");
                      });

                      $('#questionsContainer').append("<div><button>Submit</button><div>");

                })
                .fail(function(jqXHR) {
                    // debug message to help during development:
                    console.log('request returned failure, HTTP status code ' + jqXHR.status);
                })
                    
                // call this function again after a brief pause:    
        }
    </script>
_END;

// finish off the HTML for this page:
require_once "footer.php";
?>