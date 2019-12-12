<?php

// execute the header script:
require_once("header.php");

echo '<link rel="stylesheet" type="text/css" href="assets/style/surveyStyle.css">';

// finish off the HTML for this page:
if (!isset($_SESSION['loggedIn']))
{
	// user isn't logged in, display a message saying they must be:
  echo "<div class='col-md-6 offset-md-3 text-center'><div class='alert alert-success' role='alert'>You are already logged in, please log out first.</div></div>";
} 
else 
{

$surveyID = "noneSet";

if(isset($_GET['surveyID']))
{
  $surveyID = $_GET['surveyID'];
  $username = $_SESSION['username'];
  echo<<<_END
  <script src="assets/javascript/questionCreation.js"></script>
  <script>
    $(document).ready(function(){
      $.post('assets/api/checkSurveyCreator.php', {surveyID: '$surveyID', username: '$username' })
        .done(function(data) {
        
          $.post('assets/api/returnSurveyData.php', {surveyID: '$surveyID' })
            .done(function(data) {
              document.getElementById('surveyTitle').value = data["survey_title"];
              document.getElementById('submitSurvey').innerHTML = "Update";
              document.getElementById('submitSurvey').className = "btn btn-outline-warning";
              $('#submitSurvey').data('process',"update");
              
              $.each(data.survey_JSON, function( key, value ) {
                console.log(value);
                if(value.inputType === "multipleChoice")
                {
                  $( "#currentQuestions" ).append(createMultipleChoiceQuestion(value.title,value.label,value.choice1,value.choice2));
                } else
                {
                  $( "#currentQuestions" ).append(createTextQuestion(value.title,value.label,value.inputType));
                }
                
              });
              
            })
            .fail(function(error) {
              console.log(error);
            });

        })
        .fail(function(error) {
            console.log(error);
        });
    });
  </script>
_END;
}

echo<<<_END
<div class='text-center'>
  <div class="container">

  <div class="offset-md-3 col-md-6">
    <form class="text-center">
      <h3>Enter a survey Title</h3>
      <input id="surveyTitle" class="form-control" min="1" maxlength="32" name="surveyTitle" type="text"></input>
    </form>
  </div>
  
  <br>

  <div class="alert alert-danger" id="errorMessage" style="display: none;">
    <strong>Error!</strong> Please make sure all data is correct and inputted!
  </div>

  <hr>

  <div class="row">
    <div class='text-center' id='questionControls'>
      <hr>
        <h6>Click to add new question</h6>
        <div class="container" style="">
          <button type="button" id='addTextQuestion' class='questionButton btn btn-outline-success'>Text Question</button>
          <button type="button" id='addMulQuestion' class='questionButton btn btn-outline-success'>Multiple choice Question</button>
        </div>
          
      <hr>
        <h6>Finished?</h6>
          <button type="button" class="btn btn-outline-primary" data-process="insert" id="submitSurvey">Submit</button>
      <hr>
    </div>

    <div class="offset-md-2 col-md-8" id="currentQuestions" style=" min-height: 300px;">

    </div>

  </div>

</div>
_END;

$username = $_SESSION['username'];
echo<<<_END
<script src="assets/javascript/questionCreation.js"></script>
<script>
  $(document).ready(function(){

    //$( "#currentQuestions" ).append(createTextQuestion("","",""));

    // If the user wants to delete a question this function is called and the questionDiv removes itself
    $(document).on('click', '.removeQuestion', function(){
        $(this).parent('form').remove();
    });
    
    // If the user would like a new question box this inserts the div using the function below.
    $(document).on('click', '#addTextQuestion', function(){
        $( "#currentQuestions" ).append(createTextQuestion("","",""));
    });

    $(document).on('click', '#addMulQuestion', function(){
      $( "#currentQuestions" ).append(createMultipleChoiceQuestion("","","",""));
    });

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
            
            var process = $(this).data('process');
            
        //Now we post this array to a API that looks to see if the data is valid
            $.post('assets/api/insertSurveyIntoDatabase.php', {survey_JSON: surveyData, username: '$username', action: process, surveyID : '$surveyID'})
            .done(function(data) {
              window.location.replace("surveys_manage.php");
            })
            .fail(function(error) {
              document.getElementById("errorMessage").style.display= 'block';
              document.getElementById("errorMessage").innerHTML = error.responseText;
            })        
    });

  });

</script>
_END;

}
require_once("footer.php");

?>