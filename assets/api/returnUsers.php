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
$allUsers = array();

// If the API call point has not specified a username value then return NULL
if (!isset($_POST['username']))
{
    // Set the error response code to 400 meaning 'bad request'
    header("Content-Type: application/json", NULL, 400);
    // Encode the current empty array and return it
    echo json_encode($allUsers);
    // Exit out of the API, to not run any other bits of code
    exit;
}
// The API call has specified the user is a admin, we now need to return the data
else 
{
    // Get the database connection details
    include_once "../../credentials.php";

    // Create a new connection to database
    $adminConnection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

    // Check if the connection worked otherwise return a error
    if (!$adminConnection) 
    { 
        // Set the error response code to 500 meaning 'Interal Server Error'
        header("Content-Type: application/json", NULL, 500);
        // Encode the current empty array and return it
        echo json_encode($allUsers);
        // Exit out of the API, to not run any other bits of code:
        exit; 
    }

    $username = $_POST['username'];
    
    //IF THE USER IS NOT A ADMIN THEN EXIT
    $sql = "SELECT * FROM `users` WHERE `username` = '$username' AND `accountType` = 'admin'";
    $checkAccountType = mysqli_query($adminConnection, $sql);
    $checkAccountResult = mysqli_num_rows($checkAccountType);

    if (empty($checkAccountResult))
    {
       // Set the error response code to 500 meaning 'Interal Server Error'
       header("Content-Type: application/json", NULL, 501);
       // Encode the current empty array and return it
       echo json_encode($allUsers);
       // Exit out of the API, to not run any other bits of code:
       exit;        
    }


    // Create the query to get all the usernames from the user table
    $userQuery = "SELECT username FROM `users` WHERE `username` != '$username'";

    // Send the query off to the mysql database using the connection details
    $resultQuery = mysqli_query($adminConnection, $userQuery);

    // Get the total number of rows from the results
    $resultQueryRows = mysqli_num_rows($resultQuery);

    $adminConnection->close();

    // If nothing is returned, return error code 400 otherwise insert the db.data into the return.data
    if ($resultQueryRows > 0) 
    {
        // For each of the usernames push them into the return array
        for ($i=0; $i<$resultQueryRows; $i++) 
        {
            array_push($allUsers, mysqli_fetch_assoc($resultQuery) );
        }
        // If nothing returned, set the error response code to 400 meaning 'bad request'
        header("Content-Type: application/json", NULL, 201);     
        echo json_encode($allUsers);
        exit;
    }
    elseif (empty($resultQueryRows))
    {
        // If nothing returned, set the error response code to 400 meaning 'bad request'
        header("Content-Type: application/json", NULL, 400);        
        // Return the array of all the usernames
        echo json_encode($return);
        exit;
    }
    else 
    {
        // If nothing returned, set the error response code to 400 meaning 'bad request'
        header("Content-Type: application/json", NULL, 500);
        // Encode the empty array and return
        echo json_encode($return);
        exit;   
    }
    

}
?>