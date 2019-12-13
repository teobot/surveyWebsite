<?php
//    Page Name - || returnSurveyData.php
//                --
// Page Purpose - || This returns the question and survey data from the given surveyID
//                --
//        Notes - || wants:
//         		  || surveyID
//                --

// Create a empty return array to populate with all users usernames
$surveyData = array();

// If the API call point has not specified a username value then return NULL
if (!isset($_POST['surveyID']))
{
    // Set the error response code to 400 meaning 'bad request'
    header("Content-Type: application/json", NULL, 400);
    // Encode the current empty array and return it
    echo json_encode($surveyData);
    // Exit out of the API, to not run any other bits of code
    exit;
}
// The API call has specified the user is a admin, we now need to return the data
else 
{

    // Get the database connection details
    require_once("../../credentials.php");

    // Create a new connection to database
    $userConnection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

    // Check if the connection worked otherwise return a error
    if (!$userConnection) 
    { 
        // Set the error response code to 500 meaning 'Interal Server Error'
        header("Content-Type: application/json", NULL, 500);
        // Encode the current empty array and return it
        echo json_encode($surveyData);
        // Exit out of the API, to not run any other bits of code:
        exit; 
    }

    // Create the query to get all the surveyIDs from the user table
    $surveyID = $_POST['surveyID'];
    $surveyQuery = "SELECT survey_id, survey_title, survey_creator, survey_JSON FROM surveys WHERE survey_id = '$surveyID'";

    // Send the query off to the mysql database using the connection details
    $resultQuery = mysqli_query($userConnection, $surveyQuery);

    // Get the total number of rows from the results
    $resultQueryRows = mysqli_num_rows($resultQuery);

    // If nothing is returned, return error code 400 otherwise insert the db.data into the return.data
    if (!empty($resultQueryRows)) 
    {
        // For each of the usernames push them into the return array
        while($row = $resultQuery->fetch_assoc()) 
        {
            //Insert return objects into the return array
            $surveyData['survey_id'] = $row["survey_id"];
            $surveyData['survey_title'] = $row["survey_title"];
            $surveyData['survey_creator'] = $row["survey_creator"];
            $surveyData['survey_JSON'] = json_decode($row["survey_JSON"]);
        }
    }  
    else 
    {
        // If nothing returned, set the error response code to 400 meaning 'bad request'
        header("Content-Type: application/json", NULL, 400);
        // Encode the empty array and return
        $userConnection->close();
        echo "survey does not exist!";
        exit;          
    }

    // close the connection when finished getting data
    $userConnection->close();

    // Set the response code to 200 meaning the operation was a success
    header("Content-Type: application/json", NULL, 200);

    // Return the array of all the usernames
    echo json_encode($surveyData);
}
?>