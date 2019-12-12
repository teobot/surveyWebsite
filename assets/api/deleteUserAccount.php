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
// If the posted username is a not a admin then return NULL
elseif ($_POST['username'] != "admin")
{
    // Set the error response code to 403 meaning 'forbidden' as the user is not a admin
    header("Content-Type: application/json", NULL, 403);
    // Encode the current empty array and return it
    echo "failed";
    // Exit out of the API, to not run any other bits of code
    exit; 
}
// The API call has specified the user is a admin, we now need to return the data
else 
{
    // Get the database connection details
    require_once("../../credentials.php");

    // Create a new connection to database
    $deleteUserConnection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

    // Check if the connection worked otherwise return a error
    if (!$deleteUserConnection) 
    { 
        // Set the error response code to 500 meaning 'Interal Server Error'
        header("Content-Type: application/json", NULL, 500);
        // Encode the current empty array and return it
        echo "failed";
        // Exit out of the API, to not run any other bits of code:
        exit; 
    }

    $username = $_POST['username'];
    $usernameToDelete = $_POST['toDeleteUsername'];

    // sql to delete a record
    $sql = "DELETE FROM `users` WHERE `username` = '$usernameToDelete'";

    if ($deleteUserConnection->query($sql) === TRUE) {
        echo "success";
    } else {
        header("Content-Type: application/json", NULL, 400);
        echo "failed";
        exit;
    }

    // close the connection when finished getting data
    $deleteUserConnection->close();

    // Set the response code to 200 meaning the operation was a success
    header("Content-Type: application/json", NULL, 200);
 
}
?>