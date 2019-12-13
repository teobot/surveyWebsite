<?php
//    Page Name - || validation_checker.php
//                --
// Page Purpose - || This is the validation functions to check through inputted data
//                --
//        Notes - || This page is where all the functions to validate data are stored.
//                --

require_once("credentials.php");

// This decides what validation the string should be checked against
function selectValidation($stringToCheck, $datatype) {
	if ($datatype == "text")
	{
		return validateString($stringToCheck, 0, 64, $stringToCheck);
	}

	elseif ($datatype == "number")
	{
		return validateInt($stringToCheck, 1, 9999999);	
	}

	elseif ($datatype == "date")
	{
		return validateDate($stringToCheck);	
	}

	elseif ($datatype == "email")
	{
		return validateEmail($stringToCheck, 0, 64, $stringToCheck);	
	}
		
	return "";
}
// function to sanitize (clean) user data:
function sanitise($str, $connection)
{
	if (get_magic_quotes_gpc())
	{
		// just in case server is running an old version of PHP with "magic quotes" running:
		$str = stripslashes($str);
	}

	// escape any dangerous characters, e.g. quotes:
	$str = mysqli_real_escape_string($connection, $str);
	// ensure any html code is safe by converting reserved characters to entities:
	$str = htmlentities($str);
	// return the cleaned string:
	return $str;
}

// function to both remove certain characters and sanitize
function sanitiseStrip($str, $connection)
{
	$str = preg_replace("/[^a-zA-Z0-9!?#@.,\s]/", "", $str);
	$str = sanitise($str, $connection);
	// return the cleaned string:
	return $str;
}

// function to check if a email is valid
function validateEmail($field, $minlength, $maxlength, $name) {
    if (strlen($field)<$minlength) 
    {
		// wasn't a valid length, return a help message:		
		return "<div class='alert alert-danger' role='alert'>{$name} must have a minimum length of: {$minlength}</div>";
    }

	elseif (strlen($field)>$maxlength) 
    { 
		// wasn't a valid length, return a help message:
		return "<div class='alert alert-danger' role='alert'>{$name} must have a maximum length of: {$maxlength}</div>";
	}
	
	elseif (!filter_var($field, FILTER_VALIDATE_EMAIL)) 
	{
		return '<div class="alert alert-danger" role="alert">Invalid Email detected</div>';
	}

	// data was valid, return an empty string:
    return ""; 
}

// This checks if the user name is both valid and not already taken
function validateUsername($field, $minlength, $maxlength, $connection, $name) {

	if ( validateString($field, $minlength, $maxlength, $name) == "" ) 
	{
		if ($field === "everyone")
		{
			return "<div class='alert alert-danger' role='alert'>Username not available!</div>";
		}
		$query = "SELECT * FROM users WHERE username = '$field' ";
		$result = $connection->query($query);

		if ($result->num_rows > 0) 
		{
			return "<div class='alert alert-danger' role='alert'>Username already taken sorry!</div>";
		} 
		else 
		{
			return "";
		}

	} else {
		return "";
	}

}

// Validate a phone number
function validateTelephoneNumber($field, $length) 
{
	// Check if the length of the 'number' is valid
	if (strlen($field)<$length)
	{
		return "<div class='alert alert-danger' role='alert'>Phone Number to short!</div>";
	} 
	elseif (strlen($field)>$length)
	{
		return "<div class='alert alert-danger' role='alert'>Phone Number to long!</div>";
	} 
	// The length is valid now to check if its actually a number
	else 
	{
		// Check that the number is a actual int/float/decimal
		if (is_numeric($field)) 
		{
			// is_numeric checks if the 'number' is a int/float/decimal
			// This can allow '0.0111222', '.00111222' or even '-11222333' as its technically a 'number'
			// But a phone number cannot contain anything but digits so
			// preg_match checks the number against a pattern and in this case the pattern i've select is
			// '\D' which checks for 'Any non-digit' and if it finds anything that not a 'digit'
			// then the number is a invalid telephone number. The forward slash's are for php
			// to differentiate the pattern from its container.
			if ( preg_match("/\D/", $field) )
			{		
				return "<div class='alert alert-danger' role='alert'>Phone Number must be a number only!</div>";
			} 
			else 
			{
				// The value has passed all tests and can now be entered into the database
				return "";
			}
		} 
		else 
		{
			return "<div class='alert alert-danger' role='alert'>Phone Number is not numeric!</div>";
		}
	}
}

// if the data is valid return an empty string, if the data is invalid return a help message
function validateString($field, $minlength, $maxlength, $name) 
{
    if (strlen($field)<$minlength) 
    {
		// wasn't a valid length, return a help message:		
		return "<div class='alert alert-danger' role='alert'>{$name} must have a minimum length of: {$minlength}</div>";
    }

	elseif (strlen($field)>$maxlength) 
    { 
		// wasn't a valid length, return a help message:
		return "<div class='alert alert-danger' role='alert'>{$name} must have a maximum length of: {$maxlength}</div>";
    }

	// data was valid, return an empty string:
    return ""; 
}


// if the data is valid return an empty string, if the data is invalid return a help message
function validateInt($field, $min, $max) 
{ 
    
	if ( filter_var($int, FILTER_VALIDATE_INT, array("options" => array("min_range"=>$min, "max_range"=>$max))) ) 
    { 
		// data was valid, return an empty string:
		return ""; 
	}
	else 
	{
		// wasn't a valid integer, return a help message:
		return "<div class='alert alert-danger' role='alert'>Not a valid number (must be whole and in the range: " . $min . " to " . $max . ")</div>";
	}

}

// all other validation functions should follow the same rule:
// if the data is valid return an empty string, if the data is invalid return a help message
// ...
function validateDate($date)
{
    $timestamp = strtotime($date);
    
    $day = date('d', $timestamp);
	$month = date('j', $timestamp);
	$year = date('Y', $timestamp);
    
	if (checkdate($month,$day,$year)) {
		return "";
	} else {
		return "<div class='alert alert-danger' role='alert'>Incorrect date entered!</div>";
	}
}

?>
