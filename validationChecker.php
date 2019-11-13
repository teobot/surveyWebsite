<?php
require_once "credentials.php";
// Things to notice:
// This script holds the sanitisation function that we pass all our user data to
// This script holds the validation functions that double-check our user data is valid
// You can add new PHP functions to validate different kinds of user data (e.g., emails, dates) by following the same convention:
// if the data is valid return an empty string, if the data is invalid return a help message
// You are encouraged to create/add your own PHP functions here to make frequently used code easier to handle

// function to sanitise (clean) user data:
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

// function to sanitise (clean) user data:
function sanitiseStrip($str, $connection)
{
	$str = sanitise($str, $connection);
	$str = preg_replace("/[^a-zA-Z0-9\s]/", "", $str);
	// return the cleaned string:
	return $str;
}

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

function validateUsername($field, $minlength, $maxlength, $connection, $name) {

	if ( validateString($field, $minlength, $maxlength, $name) == "" ) 
	{
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
		return "Phone Number to short";
	} 
	elseif (strlen($field)>$length)
	{
		return "Phone Number to long";
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
				return "Phone Number must be a number only";
			} 
			else 
			{
				// The value has passed all tests and can now be entered into the database
				return "";
			}
		} 
		else 
		{
			return "Phone Number is not numeric";
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
	// see PHP manual for more info on the options: http://php.net/manual/en/function.filter-var.php
	$options = array("options" => array("min_range"=>$min,"max_range"=>$max));
    
	if (!filter_var($field, FILTER_VALIDATE_INT, $options)) 
    { 
		// wasn't a valid integer, return a help message:
		return "<div class='alert alert-danger' role='alert'>Not a valid number (must be whole and in the range: " . $min . " to " . $max . ")</div>";
    }
	// data was valid, return an empty string:
    return ""; 
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
