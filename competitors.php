<?php
//    Page Name - || competitors.php
//                --
// Page Purpose - || This is where the user can view my research on the competition
//                --
//        Notes - ||
//         		  ||
//      

// execute the header script:
require_once("header.php");

// For display the active webspage
$loadCompetitor = "googleForms";
$googleFormClass = "nav-link";
$zoho = "nav-link";
$surveyMonkey = "nav-link";
$conclusion = "nav-link";

// Check if the user is logged in
if (!isset($_SESSION['loggedIn']))
{
	// user isn't logged in, display a message saying they must be:
	echo "<div class='col-md-6 offset-md-3 text-center'><div class='alert alert-success' role='alert'>You are already logged in, please log out first.</div></div>";
}

// the user must be signed-in, show them suitable page content
else
{
	//Check which webpage the user wants to view and then set the active tab to that page and load it in the container
	if ( (empty($_GET["competitors"])) || (($_GET['competitors'] == "googleForms")) ) 
	{
		$loadCompetitor = "googleForms.php";
		$googleFormClass = "nav-link active";
	} 
	elseif (($_GET['competitors'] == "zoho"))
	{
		$loadCompetitor = "zohoSurvey.php";
		$zoho = "nav-link active";
	}
	elseif (($_GET['competitors'] == "surveyMonkey"))
	{
		$loadCompetitor = "surveyMonkey.php";
		$surveyMonkey = "nav-link active";
	}
	elseif (($_GET['competitors'] == "conclusion"))
	{
		$loadCompetitor = "conclusion.php";
		$conclusion = "nav-link active";
	}
	//Load the webpage with the competition webpage inside
echo <<<_END
	<div class="container">
		<div class="card text-center">
			<div class="card-header">
			<ul class="nav nav-tabs card-header-tabs">
				<li class="nav-item">
					<a class="{$googleFormClass}" href="competitors.php?competitors=googleForms">Google Forms</a>
				</li>
				<li class="nav-item">
					<a class="{$zoho}" href="competitors.php?competitors=zoho">Zoho Survey</a>
				</li>
				<li class="nav-item">
					<a class="{$surveyMonkey}" href="competitors.php?competitors=surveyMonkey">Survey Monkey</a>
				</li>
				<li class="nav-item">
					<a class="{$conclusion}" href="competitors.php?competitors=conclusion">Conclusion</a>
				</li>
			</ul>
			</div>
_END;

require_once("assets/competitors/{$loadCompetitor}");

echo "</div></div>";
}

// finish off the HTML for this page:
require_once("footer.php");
?>
