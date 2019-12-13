<?php 
//    Page Name - || insertNewAccount.php
//                --
// Page Purpose - || This checks if the user is a admin, then checks if the new account data is valid before inserting
//                --
//        Notes - || wants:
//         		  || username, a array of new account information
//                --

// Set starting boolean variables
$isDataValid = false;
$isDataLongEnough = false;
$isUserAdmin = false;

$response = array();

if ( !isset($_POST['username']) && !isset($_POST['accountInfoArray']) )
{
    // Set the error response code to 400 meaning 'bad request'
    header("Content-Type: application/json", NULL, 400);
    // Encode the current empty array and return it
    echo "<div class='alert alert-danger' role='alert'>Incorrect Data Passed To API!</div>";	
    // Exit out of the API, to not run any other bits of code
    exit;
} 
else
{
    require_once('../../validation_Checker.php');
    require_once('../../credentials.php');
    
    $username = $_POST['username'];
    $accountData = $_POST['accountInfoArray'];
    $accountData = json_encode($accountData);
    $accountData = json_decode($accountData);

    //Make the db connection----------------------
    $conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
    if ($conn->connect_error) 
    {
        // Set the error response code to 400 meaning 'bad request'
        header("Content-Type: application/json", NULL, 503);
        // Encode the current empty array and return it
        $response[] = "<div class='alert alert-danger' role='alert'>DB Connection Failed!</div>";	
        // Exit out of the API, to not run any other bits of code
        echo $response;
        exit;
    }
    //----------------------Make the db connection


    //check if user is admin------------------------
    $sql = "SELECT * FROM `users` WHERE `username` = '$username' AND `accountType` = 'admin'";
    $checkAccountType = mysqli_query($conn, $sql);
	$checkAccountResult = mysqli_num_rows($checkAccountType);

    if (!empty($checkAccountResult))
	{
		$isUserAdmin = true;
	}
    //------------------------check if user is admin


    //check if the given data is valid-----------------
    if(count($accountData) === 8) {
        $isDataLongEnough = true;
    } else {
        // Set the error response code to 400 meaning 'bad request'
        header("Content-Type: application/json", NULL, 503);
        // Encode the current empty array and return it
        $response[] = "<div class='alert alert-danger' role='alert'>Incomplete Data Given!</div>";	
        // Exit out of the API, to not run any other bits of code
        echo $response;
        exit;
    }

    try {
        // Sanitize all the data
        $username_val = sanitiseStrip($accountData[0]->value, $conn);
        $password_val = sanitiseStrip($accountData[1]->value, $conn);
        $firstname_val = sanitiseStrip($accountData[2]->value, $conn);
        $surname_val = sanitiseStrip($accountData[3]->value, $conn);
        $dob_val = sanitiseStrip($accountData[4]->value, $conn);
        $telephoneNumber_val = sanitiseStrip($accountData[5]->value, $conn);
        $email_val = sanitiseStrip($accountData[6]->value, $conn);
        $accountType = sanitiseStrip($accountData[7]->value, $conn);

        $username_err = validateUsername($username_val, 1, 16, $conn, "Username");
        $password_err = validateString($password_val, 1, 16, "Password");
        $firstname_err = validateString($firstname_val, 1, 32, "Firstname");
        $surname_err = validateString($surname_val, 1, 64, "Surname");
        $dob_err = validateDate($dob_val);
        $telephoneNumber_err = validateTelephoneNumber($telephoneNumber_val, 9);
        $email_err = validateEmail($email_val, 1, 64, "Email");
        // ... 

        // check for errors
        $errors = $username_err . $password_err . $email_err . $firstname_err . $surname_err . $dob_err . $telephoneNumber_err;
	
        if ($errors == "")
        {
            $isDataValid = true;
        }
        else {
            // Set the error response code to 400 meaning 'bad request'
            header("Content-Type: application/json", NULL, 503);
            // Encode the current empty array and return it
            echo $errors;
            // Exit out of the API, to not run any other bits of code
            exit;        
        }
    } catch (Exception $e) {
        $response[] = "<div class='alert alert-danger' role='alert'>Loading Data Failed!</div>";
        echo $response;
        header("Content-Type: application/json", NULL, 500);
        $conn->close();
        exit;
    }
    //-----------------check if the given data is valid

    //if user is admin and data is valid enter in db--------------------

    if($isDataLongEnough && $isUserAdmin && $isDataValid)
    {
        // Create the query string to insert the user data into the users table:
		$query = "INSERT INTO users (username, password, firstname, surname, email, dob, telephoneNumber, accountType) VALUES ('$username_val', '$password_val', '$firstname_val', '$surname_val', '$email_val', '$dob_val', '07$telephoneNumber_val', '$accountType');";

		// Get the result by passing the query to the database for execution:
		$result = mysqli_query($conn, $query);
		
		// no data returned, we just test for true(success)/false(failure):
		if ($result) 
		{
            // show a successful signup message:
            header("Content-Type: application/json", NULL, 200);
            $response[] = "<div class='alert alert-success' role='alert'>Account was successfully inserted!</div>";	
            echo json_encode($response);
            $conn->close();
            exit;		
		} 
		else 
		{
            $response[] = "<div class='alert alert-danger' role='alert'>Inserting Data Failed!</div>";
            echo $response;
            header("Content-Type: application/json", NULL, 500);
            $conn->close();
            exit;
		}
    }
    //--------------------if user is admin and data is valid enter in db

    
}
?>