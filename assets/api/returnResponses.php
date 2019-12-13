<?php
//    Page Name - || returnResponses.php
//                --
// Page Purpose - || This returns the responses for a given survey, 
//                --
//        Notes - || wants:
//         		  || surveyId, username
//                --

// Create a empty return array to populate with all users responses
$surveyResponses = array();

// If the API call point has not specified a username value then return NULL
if (!isset($_POST['username']))
{
    // Set the error response code to 400 meaning 'bad request'
    header("Content-Type: application/json", NULL, 400);
    // Encode the current empty array and return it
    echo json_encode($surveyResponses);
    // Exit out of the API, to not run any other bits of code
    exit;
}
else 
{
    $surveyID = $_POST['surveyID'];
    $username = $_POST['username'];
    $userIsAdmin = false;

    //CHECK IF THE SURVEY CREATOR IS THE POSTED USERNAME

    // Get the database connection details
    require_once("../../credentials.php");

    // Create a new connection to database
    $connection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
    if (!$connection) 
    { 
        // Set the error response code to 500 meaning 'Interal Server Error'
        header("Content-Type: application/json", NULL, 500);
        // Encode the current empty array and return it
        echo json_encode($surveyResponses);
        // Exit out of the API, to not run any other bits of code:
        exit; 
    }

    $sql = "SELECT * FROM `users` WHERE `username` = '$username' AND `accountType` = 'admin'";
    $checkAccountType = $connection->query($sql);
    $checkAccountResult = mysqli_num_rows($checkAccountType);

    if (!empty($checkAccountResult))
    {
        $checkSurveyCreator = "SELECT `survey_creator` FROM surveys WHERE `survey_id` = '$surveyID'";
        $getResponses = "SELECT `survey_RESPONSE`, `survey_JSON` FROM surveys WHERE `survey_id` = '$surveyID'";
    }
    else
    {
        $checkSurveyCreator = "SELECT `survey_creator` FROM surveys WHERE `survey_id` = '$surveyID' AND `survey_creator` = '$username';";
        $getResponses = "SELECT `survey_RESPONSE`, `survey_JSON` FROM surveys WHERE `survey_id` = '$surveyID' AND `survey_creator` = '$username';";
    }

    $result = $connection->query($checkSurveyCreator);
    
    if ($result->num_rows > 0 ) 
    {
        //USER HAS CREATED THE SURVEY AND WANTS TO VIEW THE INFORMATION ////////////////////////////////

            $result = $connection->query($getResponses);

            while($row = $result->fetch_assoc()) 
            {
                //Insert return objects into the return array
                $surveyData = json_decode($row["survey_RESPONSE"]);
                $surveyQuestionTitles = json_decode($row["survey_JSON"]);
            }

            if (empty($surveyData) == 1)
            {
                // Set the error response code to 500 meaning 'Interal Server Error'
                header("Content-Type: application/json", NULL, 503);
                // Encode the current empty array and return it
                $surveyResponses[] = "No responses have been submitted at this time!";
                echo json_encode($surveyResponses);
                // Exit out of the API, to not run any other bits of code:
                exit; 
            }

            $numberOfResponses = intval(count($surveyData));
            $numberOfQuestions = count($surveyData[$numberOfResponses-1]);


            // This was returning warnings as the data is null, but that's what i'm checking for
            // I disable error reporting and then re-enable it afterwards.
            error_reporting(0);
            for($k = 0; $k < $numberOfQuestions; $k++)
            {
                $questionResponses = array();
                
                for($i = 0; $i < $numberOfResponses; $i++)
                {
                    if(is_null($surveyData[$i][$k]))
                    {
                        $questionResponses[] = "null";
                    }
                    else {
                        $questionResponses[] = $surveyData[$i][$k];    
                    }
                        
                }

                $surveyResponses[$surveyQuestionTitles[$k]->title] = $questionResponses;
            }
            error_reporting(1);

        //END USER HAS CREATED THE SURVEY AND WANTS TO VIEW THE INFORMATION END ////////////////////////////////
    } else {
        // Set the error response code to 503 meaning 'Service Unavailable'
        header("Content-Type: application/json", NULL, 503);
        // Encode the current empty array and return it
        $surveyResponses[] = "You did not Create this survey, Please login to the correct account!";
        echo json_encode($surveyResponses);
        // Exit out of the API, to not run any other bits of code:
        exit;  
    }
    $connection->close();


    // Set the response code to 200 meaning the operation was a success
    header("Content-Type: application/json", NULL, 200);

    // Return the array of all the usernames
    echo json_encode($surveyResponses);
}
?>