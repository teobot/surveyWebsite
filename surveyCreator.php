<?php

// execute the header script:
require_once "header.php";

echo "<script src='assets/javascript/surveyCreator.js'></script>";
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
        <div class="addedQuestion">
          <div class="card" style="width: 100%;">
            <div class="card-body">
              <div class="row">
                <div class="col">
                  <h6>Enter a question title:</h6>
                    <input id="titleInput"></input>
                  <small class="form-text text-muted" >e.g. Enter you favorite animal?</small>
                </div>
                <div class="col">
                  <h6>Enter a small label:</h6>
                    <input id="labelInput"></input>
                  <small class="form-text text-muted" >Just like me :)</small>
                </div>
              </div>
              <hr>
              <div class="row">
                <div class="col">
                  <h6>Enter a minimum value:</h6>
                    <input id="minInput"></input>
                  <small class="form-text text-muted" >e.g. 5</small>
                </div>
                <div class="col">
                  <h6>Enter a maximum value:</h6>
                    <input id="maxInput"></input>
                  <small class="form-text text-muted" >e.g. 32</small>
                </div> 
              </div>
              <hr>
              <div class="row">
                <div class="col">
                  <h6>Select a dataType:</h6>
                    <select class="custom-select" id="datatypeInput">
                      <option selected>Choose...</option>
                      <option value="1">text</option>
                      <option value="2">email</option>
                      <option value="3">password</option>
                    </select>
                </div>
                <div class="col">
                  <h6>Required Question?</h6>
                  <div class="form-check">
                    <input class="form-check-input position-static" type="checkbox" id="requiredInput" value="required" aria-label="...">
                  </div>
                </div>
              </div>
            </div>
            <button class="removeQuestion">Delete Question</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
_END;

}
require_once "footer.php";

?>