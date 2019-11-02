<?php

// Things to notice:
// You need to add your Analysis and Design element of the coursework to this script
// There are lots of web-based survey tools out there already.
// It’s a great idea to create trial accounts so that you can research these systems. 
// This will help you to shape your own designs and functionality. 
// Your analysis of competitor sites should follow an approach that you can decide for yourself. 
// Examining each site and evaluating it against a common set of criteria will make it easier for you to draw comparisons between them. 
// You should use client-side code (i.e., HTML5/JavaScript/jQuery) to help you organize and present your information and analysis 
// For example, using tables, bullet point lists, images, hyperlinking to relevant materials, etc.

// execute the header script:
require_once "header.php";

$loadCompetitor = "googleForms";
$googleFormClass = "nav-link";
$checkBox = "nav-link";
$surveyMonkey = "nav-link";

// checks the session variable named 'loggedIn'
// take note that of the '!' (NOT operator) that precedes the 'isset' function
if (!isset($_SESSION['loggedIn']))
{
	// user isn't logged in, display a message saying they must be:
	echo "You must be logged in to view this page.<br>";
}

// the user must be signed-in, show them suitable page content
else
{

if ( (empty($_GET["competitors"])) || (($_GET['competitors'] == "googleForms")) ) 
{
	$loadCompetitor = "googleForms.php";
	$googleFormClass = "nav-link active";
} 
elseif (($_GET['competitors'] == "checkBox"))
{
	$loadCompetitor = "checkBox.php";
	$checkBox = "nav-link active";
}
elseif (($_GET['competitors'] == "surveyMonkey"))
{
	$loadCompetitor = "surveyMonkey.php";
	$surveyMonkey = "nav-link active";
}
echo <<<_END
	<div class="container">
		<div class="card text-center">
			<div class="card-header">
			<ul class="nav nav-tabs card-header-tabs">
				<li class="nav-item">
					<a class="{$googleFormClass}" href="competitors.php?competitors=googleForms">Google Forms</a>
				</li>
				<li class="nav-item">
					<a class="{$checkBox}" href="competitors.php?competitors=checkBox">Check Box</a>
				</li>
				<li class="nav-item">
					<a class="{$surveyMonkey}" href="competitors.php?competitors=surveyMonkey">Survey Monkey</a>
				</li>
			</ul>
			</div>
_END;

require_once "assets/competitors/{$loadCompetitor}";

echo "</div></div>";
}

// finish off the HTML for this page:
require_once "footer.php";
?>