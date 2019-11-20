<?php
require_once "header.php";
echo '<link rel="stylesheet" type="text/css" href="assets/style/submittedStyle.css">';


echo<<<_END
<div class="jumbotron" id="successSubmit">
  <h1 class="display-4">Submitted Successfully!</h1>
  <p class="lead">We received your data successfully! Please follow the link below to return to the Homepage!</p>
  <hr class="my-4">
  <p>Click on me to go back to the main menu.</p>
  <a class="btn btn-primary btn-lg" href="index.php" role="button">Home</a>
</div>
_END;

require_once "footer.php";
?>