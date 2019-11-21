<?php
//    Page Name - || returnUsers.php
//                --
// Page Purpose - || When the admin goes to the admin tools page, a javascript function will request all the current users
//                || usernames from this API, if a username is specified and the username is the admin then it will
//         		  || connect to the database, retrieve all the usernames of the users and encode them in JSON format before 
//         		  || returning it back to the API call point. Otherwise, it will return nothing.
//                --
//        Notes - || This is a API to retrieve all the user usernames from the database
//         		  ||
//                --

// Create a empty return array to populate with all users usernames
$allUsersSurveys = array();

// If the API call point has not specified a username value then return NULL
if (!isset($_POST['username']))
{
    // Set the error response code to 400 meaning 'bad request'
    header("Content-Type: application/json", NULL, 400);
    // Encode the current empty array and return it
    echo json_encode($allUsersSurveys);
    // Exit out of the API, to not run any other bits of code
    exit;
}
// The API call has specified the user is a admin, we now need to return the data
else 
{
    require_once('../../validationChecker.php');
    require_once('../../credentials.php');

    // connect to the host:
    $connection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

    $json = $_POST['survey_JSON'];
    
    //CANT EXPLAIN THIS ASK FOR HELP!
    $json = json_encode( $json );
    $json = json_decode( $json );

    $questionCount = count($json);

    //CREATE A ARRAY OF ALL QUESTION TITLES
    $duplicateTitles = array();
    

    for ($x = 1; $x < $questionCount; $x++) {

        $questionTitle = sanitiseStrip($json[$x][0]->value, $connection);
        $questionLabel = sanitiseStrip($json[$x][1]->value, $connection);
        $questionType = sanitiseStrip($json[$x][2]->value, $connection);


        $errors = validateString($questionTitle, 1, 64, "title") . validateString($questionLabel, 1, 64, "label") . validateString($questionType, 1, 64, "questionType");

        if ($errors != "") 
        {
            // Set the error response code to 400 meaning 'bad request'
            header("Content-Type: application/json", NULL, 400);
            // Encode the current empty array and return it
            echo $errors;
            // Exit out of the API, to not run any other bits of code
            exit;                           
        }

        //THIS CHECKS FOR DUPLICATE QUESTION TITLES
        if (in_array($questionTitle, $duplicateTitles)) 
        {
            $questionTitle = $questionTitle . " : " . $x;
        } 
        else 
        {
            array_push($duplicateTitles, $questionTitle);
        }

        $allUsersSurveys[] = (object) array(
            'title' => $questionTitle,
            'label' => $questionLabel,
            'inputType' => $questionType
        );
    }

    //Survey Title
    $survey_title = sanitiseStrip($json[0][0]->value, $connection);
    //Survey Questions
    $insertJSON = json_encode($allUsersSurveys);
    //Survey Creator 
    $creator = $_POST['username'];

    // exit the script with a useful message if there was an error:
    if (!$connection)
    {
        die("Connection failed: " . $mysqli_connect_error);
    }

	$sql = "INSERT INTO `surveys` (`survey_id`, `survey_creator`, `survey_title`, `survey_JSON`, `survey_RESPONSE`) VALUES (NULL, '$creator', '$survey_title', '$insertJSON', '[]' )";
	
    if ($connection->query($sql) === TRUE) {
		echo "success";
    } else {
        echo "Error: " . $sql . "<br>" . $connection->error;
    }

    mysqli_close($connection);
}
?>


