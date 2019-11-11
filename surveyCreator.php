<?php

// execute the header script:
require_once "header.php";

echo "<script src='assets/javascript/surveyCreator.js'></script>";
// finish off the HTML for this page:

echo "<div class='text-center'>";

echo<<<_END
<div class="container">
  <div class="row">
    <div id='currentQuestions' class="col-10">
    </div>
    <div class="col-2">
      <button id='addQuestion' >Add Question</button>
    </div>
  </div>
</div>
_END;

echo "</div>";
require_once "footer.php";

?>