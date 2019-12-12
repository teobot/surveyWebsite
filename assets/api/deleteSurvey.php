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

// If the API call point has not specified a username value then return NULL
if (!isset($_POST['username']))
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
    $deleteSurveyConnection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

    // Check if the connection worked otherwise return a error
    if (!$deleteSurveyConnection) 
    { 
        // Set the error response code to 500 meaning 'Interal Server Error'
        header("Content-Type: application/json", NULL, 500);
        // Encode the current empty array and return it
        echo json_encode($allUsers);
        // Exit out of the API, to not run any other bits of code:
        exit; 
    }

    $username = $_POST["username"];
    $surveyId = $_POST["surveyid"];

    $userCreator = false;
    $userAdmin = false;

    //CHECK IF THE USER IS THE SURVEY CREATOR
        $sql = "SELECT `survey_creator` FROM `surveys` WHERE `survey_id` = $surveyId AND `survey_creator` = '$username'";
        $checkSurveyCreator = mysqli_query($deleteSurveyConnection, $sql);

        if (!empty($checkSurveyCreator))
        {
            $userCreator = true;
        }
    //----------------------------

    //CHECK IF THE USER IS ADMIN
        $sql = "SELECT `accountType` FROM `users` WHERE `username` = '$username'";
        $checkIfAdminResult = mysqli_query($deleteSurveyConnection, $sql);

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
    if ($userCreator || $userAdmin) {
        // sql to delete a record
        $sql = "DELETE FROM `surveys` WHERE `survey_id` = $surveyId;";

        if ($deleteSurveyConnection->query($sql) === TRUE) {
            header("Content-Type: application/json", NULL, 200);
            echo "success";
            $deleteSurveyConnection->close();
            exit; 
        } else {
            header("Content-Type: application/json", NULL, 400);
            echo "failed";
            $deleteSurveyConnection->close();
            exit;
        }
    } else {
        // Set the error response code to 500 meaning 'Interal Server Error'
        header("Content-Type: application/json", NULL, 500);
        // Encode the current empty array and return it
        echo "failed";
        $deleteSurveyConnection->close();
        // Exit out of the API, to not run any other bits of code:
        exit; 
    }
}
?>