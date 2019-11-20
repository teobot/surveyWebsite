<?php
// execute the header script:
require_once "header.php";

$surveyID = $_GET['surveyID'];

echo<<<_END
    <div class="alert alert-danger" id="errorMessage" style="display: none;">
    </div>
_END;

echo '<div class="text-center">';

echo '<div><h1 id="surveyTitle">Loading...</h1></div>';

echo '<div id="questionsContainer" class="col-md-8 offset-md-2" >Loading...</div>';

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

                    // remove the old table rows:
                    $('.questions').remove();

                    $('#questionsContainer').empty();
                    
                    $('#surveyTitle').html(data.survey_title);

                    $.each(data.survey_JSON, function( key, value ) {
                        $('#questionsContainer').append("<form class='card'> <div class='h6'>"+ value.title +"</div> <div><small>"+value.label+"</small></div> <div class='input'><input name='"+value.inputType+"' type='"+value.inputType+"'></input></div> </form><br>");
                      });

                      $('#questionsContainer').append("<div><button id='submitSurvey' type='button'>Submit</button><div>");

                })
                .fail(function(jqXHR) {
                    // debug message to help during development:
                    console.log('request returned failure, HTTP status code ' + jqXHR.status);
                })   
        }

  $(document).ready(function(){

    // If the user clicks submit this function handles getting all the data back
    $(document).on('click', '#submitSurvey', function(){

        // Create a new array full of our created questions.
            var surveyData = [];

        // Each question is actually a form and by using this function below it finds all the forms,
        // And then using .serializeArray() it gets all the values out and places them in a 
        // Indexed javascript array ready to be sent to the submission page to be encode to a JSON array and inserted into the db
            $("form").each(function(){
                surveyData.push($( this ).serializeArray());
            });

        //Now we post this array to a API that looks to see if the data is valid
            $.post('assets/api/insertResponse.php', {survey_RESPONSE: surveyData, surveyID: $surveyID})
            .done(function(data) {
                document.getElementById("errorMessage").style.display= 'none';
                window.location.replace("submitted.php");
            })
            .fail(function(error) {
              document.getElementById("errorMessage").style.display= 'block';
              $('.errors').remove();
              $.each(error.responseJSON, function( key, reason ) {
                    $('#errorMessage').append("<div class='errors'><hr>"+reason+"</div>");
                });
            })        
    });

  });
</script>
_END;

// finish off the HTML for this page:
require_once "footer.php";
?>