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
if (!isset($_POST['survey_RESPONSE']))
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
    require_once('../../validation_Checker.php');
    require_once('../../credentials.php');


    // Create connection
    $responseConn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
        // Check connection
        if ($responseConn->connect_error) 
        {
            // Set the error response code to 400 meaning 'bad request'
            header("Content-Type: application/json", NULL, 503);
            // Encode the current empty array and return it
            echo json_encode($allUsersSurveys);
            // Exit out of the API, to not run any other bits of code
            exit;
        }

    // Get the users response
    $json = $_POST['survey_RESPONSE'];
    $json = json_encode($json);
    $json = json_decode($json);

    $userResponse = array();

    for($i = 0; $i < count($json);$i++)
    {
        $responseINI = sanitiseStrip($json[$i][0]->value, $responseConn);

        $responseDataType = $json[$i][0]->name;

        if (empty($responseINI)) 
        {
            $allUsersSurveys[] = "<strong>Some fields are still empty!</strong>, Please enter into all fields!";
            header("Content-Type: application/json", NULL, 500);
            echo json_encode($allUsersSurveys);
            exit;
        } 
        else 
        {
            $validationError = selectValidation($responseINI, $responseDataType);

             if ($validationError = " ")
             {
                array_push($userResponse, $responseINI);
             }
             else
             {
                $allUsersSurveys[] = "<strong>You've entered a incorrect Data Type!</strong> Please make sure all your answers are in the correct data Type!";
                $allUsersSurveys[] = $validationError;
                header("Content-Type: application/json", NULL, 406);
                echo json_encode($allUsersSurveys);
                exit;
             }
        }    
    }

    $survey_id = $_POST["surveyID"];

    //GET THE DATABASE ARRAY AND INSERT THE USER INPUT AS A OBJECT ARRAY INSIDE THEN UPDATE THE RECORD
    $surveyQuery = "SELECT survey_RESPONSE FROM surveys WHERE survey_id = '$survey_id'";

    $resultQuery = mysqli_query($responseConn, $surveyQuery);

    $resultQueryRows = mysqli_num_rows($resultQuery);

    if ($resultQueryRows > 0) 
    {
        while($row = $resultQuery->fetch_assoc()) {
            $returnedJSON = $row['survey_RESPONSE'];
            $returnedJSON = json_decode($returnedJSON, true);
        }

    }

    $returnedJSON[] = $userResponse;


    //UPDATE THE RECORD WITH THE NEW RESPONSE
    //Survey Questions
    
    $returnedJSON = json_encode($returnedJSON);
    $sql = "UPDATE `surveys` SET `survey_RESPONSE`='$returnedJSON' WHERE `survey_id`='$survey_id'";
	
    if ($responseConn->query($sql) === TRUE) {
        echo "Record updated successfully";
    } else {
        echo "Error updating record: " . $responseConn->error;
    }

    mysqli_close($responseConn); 

}
?>


