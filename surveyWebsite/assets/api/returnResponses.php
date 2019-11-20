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

    //CHECK IF THE SURVEY CREATOR IS THE POSTED USERNAME

    // Get the database connection details
    include_once "../../credentials.php";

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

    $checkSurveyCreator = "SELECT `survey_creator` FROM surveys WHERE `survey_id` = '$surveyID' AND `survey_creator` = '$username';";

    $result = $connection->query($checkSurveyCreator);
    
    if ($result->num_rows > 0) 
    {
        //USER HAS CREATED THE SURVEY AND WANTS TO VIEW THE INFORMATION ////////////////////////////////

            $getResponses = "SELECT `survey_RESPONSE`, `survey_JSON` FROM surveys WHERE `survey_id` = '$surveyID' AND `survey_creator` = '$username';";

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

            $numberOfResponses = count($surveyData);
            $numberOfQuestions = count($surveyData[0]);

            for($k = 0; $k < $numberOfQuestions; $k++)
            {
                $questionResponses = array();
                
                for($i = 0; $i < $numberOfResponses; $i++)
                {
                    $questionResponses[] = $surveyData[$i][$k];          
                }

                $surveyResponses[$surveyQuestionTitles[$k]->title] = $questionResponses;
            }

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