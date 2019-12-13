<?php
//    Page Name - || checkSurveyCreator.php
//                --
// Page Purpose - || This checks the given user is able to manipulate the give survey
//                --
//        Notes - || Wants:
//         		  || username, surveyID
//                --

// Create a empty return array to populate with all users usernames
$error_message = array();

// If the API call point has not specified a username value then return NULL
if (!isset($_POST['username']) && !isset($_POST['surveyID']))
{
    // Set the error response code to 400 meaning 'bad request'
    header("Content-Type: application/json", NULL, 400);
    // Encode the current empty array and return it
    echo "failed";
    // Exit out of the API, to not run any other bits of code
    exit;
}
else 
{
    // Get the database connection details
    require_once("../../credentials.php");

    // Create a new connection to database
    $CheckerConnection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

    // Check if the connection worked otherwise return a error
    if (!$CheckerConnection) 
    { 
        // Set the error response code to 500 meaning 'Interal Server Error'
        header("Content-Type: application/json", NULL, 500);
        // Encode the current empty array and return it
        echo json_encode($allUsers);
        // Exit out of the API, to not run any other bits of code:
        exit; 
    }

    $username = $_POST["username"];
    $surveyId = $_POST["surveyID"];

    $userCreator = false;
    $userAdmin = false;
    $surveyForEveryone = false;

    //CHECK IF THE USER IS THE SURVEY CREATOR
        $sql = "SELECT `survey_creator` FROM `surveys` WHERE `survey_id` = $surveyId AND `survey_creator` IN ('$username', 'everyone')";
        $checkSurveyCreator = mysqli_query($CheckerConnection, $sql);

        if (!empty($checkSurveyCreator))
        {
            $userCreator = true;
        }
    //----------------------------

    //CHECK IF THE USER IS ADMIN
        $sql = "SELECT `accountType` FROM `users` WHERE `username` = '$username'";
        $checkIfAdminResult = mysqli_query($CheckerConnection, $sql);

        if (!empty($checkIfAdminResult))
        {      
            $checkAccount = $checkIfAdminResult->fetch_assoc();

            if ($checkAccount['accountType'] == "admin")
            {
                $userAdmin = true;
            }
        }
    //---------------------------------------
    
    //DELETE THE SURVEY
    if ($userCreator || $userAdmin || $surveyForEveryone) {
        // Set the error response code to 500 meaning 'Interal Server Error'
        header("Content-Type: application/json", NULL, 201);
        // Encode the current empty array and return it
        $error_message[] = "user can edit survey!";
        echo json_encode($error_message);
        $CheckerConnection->close();
        // Exit out of the API, to not run any other bits of code:
        exit; 
    } else {
        // Set the error response code to 500 meaning 'Interal Server Error'
        header("Content-Type: application/json", NULL, 500);
        // Encode the current empty array and return it
        echo "failed";
        $CheckerConnection->close();
        // Exit out of the API, to not run any other bits of code:
        exit; 
    }
}
?>