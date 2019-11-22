<?php

// execute the header script:
require_once "header.php";

echo '<link rel="stylesheet" type="text/css" href="assets/style/surveyStyle.css">';

// finish off the HTML for this page:
if (!isset($_SESSION['loggedIn']))
{
	// user isn't logged in, display a message saying they must be:
  echo "<div class='col-md-6 offset-md-3 text-center'><div class='alert alert-success' role='alert'>You are already logged in, please log out first.</div></div>";
} else 
{

echo<<<_END

_END;

echo "<div class='text-center'>";

echo<<<_END
<div class="container">

  <div class="offset-md-3 col-md-6"
    <form class="text-center">
      <h3>Enter a survey Title</h3>
      <input class="form-control" min="1" maxlength="32" name="surveyTitle" type="text"></input>
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
          <button type="button" class="btn btn-outline-primary" id="submitSurvey">Submit</button>
      <hr>
    </div>

    <div class="offset-md-2 col-md-8" id="currentQuestions" style=" min-height: 300px;">

    </div>

  </div>

</div>
_END;

$username = $_SESSION['username'];
echo<<<_END
<script>
  $(document).ready(function(){

    $( "#currentQuestions" ).append(createTextQuestion());

    // If the user wants to delete a question this function is called and the questionDiv removes itself
    $(document).on('click', '.removeQuestion', function(){
        $(this).parent('form').remove();
    });
    
    // If the user would like a new question box this inserts the div using the function below.
    $(document).on('click', '#addTextQuestion', function(){
        $( "#currentQuestions" ).append(createTextQuestion());
    });

    $(document).on('click', '#addMulQuestion', function(){
      $( "#currentQuestions" ).append(createMultipleChoiceQuestion());
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

            console.log(surveyData);

            
        //Now we post this array to a API that looks to see if the data is valid
            $.post('assets/api/insertSurveyIntoDatabase.php', {survey_JSON: surveyData, username: '$username'})
            .done(function(data) {
              window.location.replace("surveys_manage.php");
            })
            .fail(function(error) {
              document.getElementById("errorMessage").style.display= 'block';
              document.getElementById("errorMessage").innerHTML = error.responseText;
            })
            
            
    });

  });


  // This returns the formatted div for a new question
  function createTextQuestion() {
    var questionDiv = '';
    questionDiv += '<form class="card" style="width: 100%;">';
    questionDiv += '<div class="card-body"><div class="row">';
    questionDiv += '<div class="col"><h6>Question Title:</h6> <input class="form-control" maxlength="32" min="1" type="text" required name="questionTitle"></input> <small class="form-text text-muted" >e.g. Enter you favorite animal?</small> </div>';
    questionDiv += '<div class="col"><h6>Question Description:</h6> <input class="form-control" name="questionLabel"></input maxlength="32" min="1" type="text" required><small class="form-text text-muted" >e.g. Please enter your first favorite animal!</small> </div>';
    questionDiv += '</div><hr><div class="row">';
    questionDiv += '<div class="col"><h6>Select a dataType:</h6><select class="custom-select" name="questionDataType"><option value="text"selected>text</option><option value="email">email</option><option value="password">password</option><option value="number">number</option><option value="tel">tel</option></select></div>';
    questionDiv += '</div> </div> <button type="button" class="removeQuestion">Delete Question</button>';
    questionDiv += '</form>';
    return questionDiv;
  }

  // This returns the formatted div for a new question
  function createMultipleChoiceQuestion() {
    var questionDiv = '';
    questionDiv += '<form class="card" style="width: 100%;">';
    questionDiv += '<div class="card-body"><div class="row">';
    questionDiv += '<div class="col"><h6>Question Title:</h6> <input class="form-control" maxlength="32" min="1" type="text" required name="questionTitle"></input> <small class="form-text text-muted" >e.g. Enter you favorite animal?</small> </div>';
    questionDiv += '<div class="col"><h6>Question Description:</h6> <input maxlength="32" min="1" type="text" required name="questionLabel"></input><small class="form-text text-muted" >e.g. Please enter your first favorite animal!</small> </div>';
    questionDiv += '</div><hr><div name="multipleChoice" class="row">';
    questionDiv += '<input type="hidden" name="questionDataType" value="multipleChoice"/>';
    questionDiv += '<div class="col"><h6>Choice 1:</h6><input class="form-control" maxlength="32" min="1" type="text" required name="choice1data"></input></div>';
    questionDiv += '<div class="col"><h6>Choice 2:</h6><input class="form-control" maxlength="32" min="1" type="text" required name="choice2data"></input></div>';
    questionDiv += '</div> </div> <button type="button" class="removeQuestion">Delete Question</button>';
    questionDiv += '</form>';
    return questionDiv;
  }
</script>
_END;

}
require_once "footer.php";

?>