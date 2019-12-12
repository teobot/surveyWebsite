<?php
// execute the header script:
require_once("header.php");
echo '<link rel="stylesheet" type="text/css" href="assets/style/surveyView.css">';


if (!isset($_GET['surveyID']))
{
echo<<<_END
<div class="jumbotron jumbotron-fluid">
  <div class="container">
    <h1 class="display-4">Well, It seems you've visited a incorrect link!</h1>
    <p class="lead">We suggest double checking to see if the link is correct! :)</p>
  </div>
</div>
_END;

}
else 
{
$surveyID = $_GET['surveyID'];

echo<<<_END
    <div class="alert alert-danger" id="errorMessage" style="display: none;">
    </div>
_END;

echo '<div class="text-center">';

echo '<div><h1 id="surveyTitle">Loading...</h1></div>';
echo '<div><small id="surveyCreator">Loading...</small></div>';

echo '<div id="questionsContainer" class="col-md-8 offset-md-2" >Loading...</div>';

echo '</div>';

echo<<<_END
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script>
        $(document).ready(function() {	
            // start checking for updates:
            getSurveys();
            
            var numberOfQuestions = 0;
        });

        function getSurveys() {
            $.post('assets/api/returnSurveyData.php', {surveyID: '$surveyID' })
                .done(function(data) {

                    // remove the old table rows:
                    $('.questions').remove();

                    $('#questionsContainer').empty();
                    
                    $('#surveyTitle').html(data.survey_title);
                    $('#surveyCreator').html("Survey Created By: " + data.survey_creator + "<hr>");

                    numberOfQuestions = data.survey_JSON.length;

                    $.each(data.survey_JSON, function( key, value ) {
                        var questionMarkup = "";
                        questionMarkup += "<form class='card'>";
                        questionMarkup += "<div><p class='lead'>"+value.title+"</p></div>";
                        questionMarkup += "<div><small>"+value.label+"</small><hr></div>";

                        if (value.inputType === "multipleChoice") 
                        {             
                            questionMarkup += '<div class="btn-group-toggle" data-toggle="buttons">';
                            questionMarkup += '<label class="btn btn-outline-success btn-lg">';
                            questionMarkup += '<input name="'+value.title+'" type="radio" value="'+value.choice1+'" required>' + value.choice1;
                            questionMarkup += '</label>';
                            questionMarkup += '<small> or </small>'
                            questionMarkup += '<label class="btn btn-outline-success btn-lg">';
                            questionMarkup += '<input name="'+value.title+'" type="radio" value="'+value.choice2+'" required>' + value.choice2;
                            questionMarkup += '</label></div><br>';
                        } 
                        else 
                        {
                            questionMarkup += "<div class='input col-md-6 offset-md-3 text-center'><input class='form-control' maxlength='32' min='1' required name='"+value.inputType+"' type='"+value.inputType+"'></input></div><br>";                 
                        }
                        
                        questionMarkup += "</form><br>";
                        $('#questionsContainer').append(questionMarkup);
                    });

                      $('#questionsContainer').append("<div><button class='btn btn-lg btn-primary' id='submitSurvey' type='button'>Submit</button><div>");

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


        var allDataPresent = true;
        $.each(surveyData, function( index, value ) {
            if(value.length === 0) {
                allDataPresent = false;
            }
        });

        if(allDataPresent)
        {
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
        }
        else
        {
            document.getElementById("errorMessage").style.display= 'block';
            $('.errors').remove();
            $('#errorMessage').append("<div class='errors'><strong>Multiple Choice Not Selected!</strong> Make sure all questions are answered!</div>");            
        }

    });

  });
</script>
_END;
}

// finish off the HTML for this page:
require_once("footer.php");
?>