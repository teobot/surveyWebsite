<?php

// execute the header script:
require_once "header.php";

// finish off the HTML for this page:
if (!isset($_SESSION['loggedIn']))
{
	// user isn't logged in, display a message saying they must be:
	echo "You must be logged in to view this page.<br>";
} else 
{

echo<<<_END

_END;

echo "<div class='text-center'>";

echo<<<_END
  <div class="container">

    <form><h3>Enter a survey Title</h3><input min="1" max="32" name="surveyTitle" type="text"></input></form>

    <hr>

    <div class="row">

      <div class='text-center' id='questionControls'>
        <hr>
          <h6>Click to add new question</h6>
            <button id='addQuestion' >Add Question</button>
        <hr>
          <h6>Finished?</h6>
            <button id="submitSurvey">Submit</button>
        <hr>
      </div>

      <div class="col-md-8 offset-md-2" id="currentQuestions">

          <form class="card" style="width: 100%;">
            <div class="card-body">
              <div class="row">
                <div class="col">
                  <h6>Enter a question title:</h6>
                    <input name="questionTitle"></input>
                  <small class="form-text text-muted" >e.g. Enter you favorite animal?</small>
                </div>
                <div class="col">
                  <h6>Enter a small label:</h6>
                    <input name="questionLabel"></input>
                  <small class="form-text text-muted" >Just like me :)</small>
                </div>
              </div>
              <hr>
              <div class="row">
                <div class="col">
                  <h6>Enter a minimum value:</h6>
                    <input name="questionMin"></input>
                  <small class="form-text text-muted" >e.g. 5</small>
                </div>
                <div class="col">
                  <h6>Enter a maximum value:</h6>
                    <input name="questionMax"></input>
                  <small class="form-text text-muted" >e.g. 32</small>
                </div> 
              </div>
              <hr>
              <div class="row">
                <div class="col">
                  <h6>Select a dataType:</h6>
                    <select class="custom-select" name="questionDataType">
                      <option value="text"selected>text</option>
                      <option value="email">email</option>
                      <option value="password">password</option>
                    </select>
                </div>
                <div class="col">
                  <h6>Required Question?</h6>
                  <div class="form-check">
                    <div class="col">
                      <small>True</small>
                      <input type="radio" name="questionRequired" value="true">
                    </div>
                    <div class="col">
                      <small>False</small>
                      <input type="radio" name="questionRequired" value="false" checked="checked">
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <button class="removeQuestion">Delete Question</button>
          </form>

      </div>
    </div>
  </div>
</div>
_END;

$username = $_SESSION['username'];
echo<<<_END
<script>
  $(document).ready(function(){

    // If the user wants to delete a question this function is called and the questionDiv removes itself
  $(document).on('click', '.removeQuestion', function(){
        $(this).parent('div').remove();
    });
    
    // If the user would like a new question box this inserts the div using the function below.
  $(document).on('click', '#addQuestion', function(){
        $( "#currentQuestions" ).append(createQuestion());
    });

    // This returns the formatted div for a new question
    function createQuestion() {
        return '<form class="card" style="width: 100%;"> <div class="card-body"> <div class="row"> <div class="col"> <h6>Enter a question title:</h6> <input name="questionTitle"></input> <small class="form-text text-muted" >e.g. Enter you favorite animal?</small> </div> <div class="col"> <h6>Enter a small label:</h6> <input name="questionLabel"></input> <small class="form-text text-muted" >Just like me :)</small> </div> </div> <hr> <div class="row"> <div class="col"> <h6>Enter a minimum value:</h6> <input name="questionMin"></input> <small class="form-text text-muted" >e.g. 5</small> </div> <div class="col"> <h6>Enter a maximum value:</h6> <input name="questionMax"></input> <small class="form-text text-muted" >e.g. 32</small> </div> </div> <hr> <div class="row"> <div class="col"> <h6>Select a dataType:</h6> <select class="custom-select" name="questionDataType"> <option value="text"selected>text</option> <option value="email">email</option> <option value="password">password</option> </select> </div> <div class="col"> <h6>Required Question?</h6> <div class="form-check"> <div class="col"> <small>True</small> <input type="radio" name="questionRequired" value="true" aria-label="..."> </div> <div class="col"> <small>False</small> <input type="radio" name="questionRequired" value="false" aria-label="..." value="false" checked="checked"> </div> </div> </div> </div> </div> <button class="removeQuestion">Delete Question</button> </form>';
    }

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
              console.log(data);
            });

    });

  });
</script>
_END;

}
require_once "footer.php";

?>