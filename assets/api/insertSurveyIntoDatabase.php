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

    $json = $_POST['survey_JSON'];
    
    //CANT EXPLAIN THIS ASK FOR HELP!
    $json = json_encode( $json );
    $json = json_decode( $json );

    $questionCount = count($json);

    for ($x = 1; $x < $questionCount; $x++) {
        $allUsersSurveys[] = (object) array(
            'title' => $json[$x][0]->value,
            'label' => $json[$x][1]->value,
            'inputType' => $json[$x][2]->value,
            'min' => $json[$x][3]->value,
            'max' => $json[$x][4]->value,
            'required' => $json[$x][5]->value,
        );
    }

    //Survey Title
    $survey_title = $json[0][0]->value;

    //var_dump($allUsersSurveys);
    echo json_encode($allUsersSurveys);

    exit;


    /*
    $insertJSON = json_encode($allUsersSurveys);
	$creator = $_GET['username'];
	$sql = "INSERT INTO `surveys` (`survey_id`, `survey_creator`, `survey_title`, `survey_JSON`) VALUES (NULL, '$creator', 'First Test Form', '$insertJSON' )";
	
    if ($conn->query($sql) === TRUE) {
		echo <<<_END
			<tr>
				<td scope="row">insert data</td>
				<td>inserting survey</td>
				<td class="p-3 mb-2 bg-success text-white">Success</td>
			</tr>
_END;
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
*/








/*
    // Get the database connection details
    include_once "../../credentials.php";

    // Create a new connection to database
    $userConnection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

    // Check if the connection worked otherwise return a error
    if (!$userConnection) 
    { 
        // Set the error response code to 500 meaning 'Interal Server Error'
        header("Content-Type: application/json", NULL, 500);
        // Encode the current empty array and return it
        echo json_encode($allUsersSurveys);
        // Exit out of the API, to not run any other bits of code:
        exit; 
    }

    // Create the query to get all the usernames from the user table
    $username = $_POST['username'];
    $userQuery = "SELECT * FROM surveys WHERE survey_creator = '$username'";

    // Send the query off to the mysql database using the connection details
    $resultQuery = mysqli_query($userConnection, $userQuery);

    // Get the total number of rows from the results
    $resultQueryRows = mysqli_num_rows($resultQuery);

    // If nothing is returned, return error code 400 otherwise insert the db.data into the return.data
    if ($resultQueryRows > 0) 
    {
        // For each of the usernames push them into the return array
        while($row = $resultQuery->fetch_assoc()) {
            //CREATE ARRAY
            $allUsersSurveys[] = (object) array('survey_id' => $row["survey_id"],'survey_title' => $row["survey_title"]);
        }
    } 
    
    else 
    {
        // If nothing returned, set the error response code to 400 meaning 'bad request'
        header("Content-Type: application/json", NULL, 400);
        // Encode the empty array and return
        echo json_encode($return);           
    }

    // close the connection when finished getting data
    $userConnection->close();

    // Set the response code to 200 meaning the operation was a success
    header("Content-Type: application/json", NULL, 200);

    // Return the array of all the usernames
    echo json_encode($allUsersSurveys);

*/

// http://localhost/surveyWebsite/assets/api/insertSurveyIntoDatabase.php?survey_JSON=[[{%22name%22:%22surveyTitle%22,%22value%22:%22%22}],[{%22name%22:%22questionTitle%22,%22value%22:%22%22},{%22name%22:%22questionLabel%22,%22value%22:%22%22},{%22name%22:%22questionMin%22,%22value%22:%22%22},{%22name%22:%22questionMax%22,%22value%22:%22%22},{%22name%22:%22questionDataType%22,%22value%22:%22text%22},{%22name%22:%22questionRequired%22,%22value%22:%22false%22}]]&username=bonfire
// [[{"name":"surveyTitle","value":"My First survey"}],[{"name":"questionTitle","value":"fav color"},{"name":"questionLabel","value":"lable for fav color"},{"name":"questionMin","value":"1"},{"name":"questionMax","value":"5"},{"name":"questionDataType","value":"text"},{"name":"questionRequired","value":"false"}]]
}
?>


