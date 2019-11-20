<?php
$isUserAdmin = false;
$doesVariablesExist = false;
$isUserChangeExist = false;
$isAccountTypeValid = false;

//CHECK IF THE VARIABLES HAVE BEEN POSTED
if ( (isset($_POST['username'])) || (isset($_POST['usernameToChange'])) || (isset($_POST['accountType'])) )
{
    $username = $_POST['username'];
    $usernameToChange = $_POST['usernameToChange'];
    $accountType = $_POST['accountType'];

    $doesVariablesExist = true;
} 
else
{
    // Set the error response code to 500 meaning 'Interal Server Error'
    header("Content-Type: application/json", NULL, 500);
    // Encode the current empty array and return it
    echo "not all wanted variables exist!";
    // Exit out of the API, to not run any other bits of code:
    exit; 
}
//---------------------------------------

//CREATE DATABASE CONNECTION
// Get the database connection details
include_once "../../credentials.php";

// Create a new connection to database
$checkDetails = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

// Check if the connection worked otherwise return a error
if (!$checkDetails) 
{ 
    // Set the error response code to 500 meaning 'Interal Server Error'
    header("Content-Type: application/json", NULL, 500);
    // Encode the current empty array and return it
    echo "database connection has failed";
    // Exit out of the API, to not run any other bits of code:
    exit; 
}
//--------------------------

//CHECK IF THE USER IS ADMIN
$checkIfAdminSQL = "SELECT `accountType` FROM `users` WHERE `username` = '$username'";
$checkIfAdminResult = mysqli_query($checkDetails, $checkIfAdminSQL);

if (!empty($checkIfAdminResult))
{
    $userAccountType = $checkIfAdminResult->fetch_assoc();

    if ($userAccountType['accountType'] == "admin")
    {
        $isUserAdmin = true;
    }
    else
    {
        $checkDetails->close();
        // Set the error response code to 500 meaning 'Interal Server Error'
        header("Content-Type: application/json", NULL, 500);
        // Encode the current empty array and return it
        echo "this user is not a admin";
        // Exit out of the API, to not run any other bits of code:
        exit; 
    }

}
else
{
    $checkDetails->close();
    // Set the error response code to 500 meaning 'Interal Server Error'
    header("Content-Type: application/json", NULL, 500);
    // Encode the current empty array and return it
    echo "database connection has failed";
    // Exit out of the API, to not run any other bits of code:
    exit; 
}
//--------------------------

//CHECK IF TARGET USER IS REAL
$checkIfTargetExists = "SELECT EXISTS (SELECT `username` FROM users WHERE username='$username') as 'existResult' ";
$checkIfTargetExistsResult = mysqli_query($checkDetails, $checkIfTargetExists);

if (!empty($checkIfTargetExistsResult))
{
    $ifAccountExists = $checkIfTargetExistsResult->fetch_assoc();

    if ($ifAccountExists['existResult'] == 1)
    {
        $isUserChangeExist = true;
    }
    else
    {
        $checkDetails->close();
        // Set the error response code to 500 meaning 'Interal Server Error'
        header("Content-Type: application/json", NULL, 500);
        // Encode the current empty array and return it
        echo "this user does not exist";
        // Exit out of the API, to not run any other bits of code:
        exit; 
    }

}
else
{
    $checkDetails->close();
    // Set the error response code to 500 meaning 'Interal Server Error'
    header("Content-Type: application/json", NULL, 500);
    // Encode the current empty array and return it
    echo "database connection has failed";
    // Exit out of the API, to not run any other bits of code:
    exit; 
}

//---------------------------

//CHECK ACCOUNT TYPE IS VALID
    if ( $isAccountTypeValid = ("admin" || "default") )
    {
        $isAccountTypeValid = true;
    }
//------------------

$checkDetails->close();

//CHANGE THE ACCOUNT TYPE OF THE USER
if ($isUserAdmin && $doesVariablesExist && $isUserChangeExist && $isAccountTypeValid)
{
    $updateAccountType = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

    // Check if the connection worked otherwise return a error
    if (!$updateAccountType) 
    { 
        // Set the error response code to 500 meaning 'Interal Server Error'
        header("Content-Type: application/json", NULL, 500);
        // Encode the current empty array and return it
        echo "database connection has failed";
        // Exit out of the API, to not run any other bits of code:
        exit; 
    }

    //PASSED ALL TESTS
    $sql = "UPDATE `users` SET `accountType`= '$accountType' WHERE `username` = '$usernameToChange'";
    if ($updateAccountType->query($sql) === TRUE) {
        echo "Record updated successfully";
    } else {
        echo "Error updating record: " . $updateAccountType->error;
    }

    $updateAccountType->close();
}
else
{
    // Set the error response code to 500 meaning 'Interal Server Error'
    header("Content-Type: application/json", NULL, 500);
    // Encode the current empty array and return it
    echo "this user does not exist";
    // Exit out of the API, to not run any other bits of code:
    exit; 
}
//-----------------------------------

?>