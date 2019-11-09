<?php
require_once "header.php";

echo <<<_END
	<table class="table table-hover">
	<thead>
		<tr>
		<th scope="col">Action</th>
		<th>Name</th>
		<th scope="col">Complete?</th>
		</tr>
	</thead>
	<tbody>
_END;

// Things to notice:
// This file is the first one we will run when we mark your submission
// Its job is to: 
// Create your database (currently called "skeleton", see credentials.php)... 
// Create all the tables you will need inside your database (currently it makes a simple "users" table, you will probably need more and will want to expand fields in the users table to meet the assignment specification)... 
// Create suitable test data for each of those tables 
// NOTE: this last one is VERY IMPORTANT - you need to include test data that enables the markers to test all of your site's functionality

// read in the details of our MySQL server:
require_once "credentials.php";

// We'll use the procedural (rather than object oriented) mysqli calls

// connect to the host:
$connection = mysqli_connect($dbhost, $dbuser, $dbpass);

// exit the script with a useful message if there was an error:
if (!$connection)
{
	die("Connection failed: " . $mysqli_connect_error);
}
  
// build a statement to create a new database:
$sql = "CREATE DATABASE IF NOT EXISTS " . $dbname;

// no data returned, we just test for true(success)/false(failure):
if (mysqli_query($connection, $sql)) 
{
	echo <<<_END
		<tr>
			<td scope="row">Create database</td>
			<td>{$dbname}</td> 
      		<td class="p-3 mb-2 bg-success text-white">Success</td>
		</tr>
_END;
} 
else
{
	die("Error creating database: " . mysqli_error($connection));
}

// connect to our database:
mysqli_select_db($connection, $dbname);

//SET SOME DATABASE VARIABLES //////////////////////////
$sql = 'SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO"';
if (mysqli_query($connection, $sql)) 
{
	echo <<<_END
		<tr>
			<td scope="row">Set database attributes</td>
			<td>variables</td>
			<td class="p-3 mb-2 bg-success text-white">Success</td>
		</tr>
_END;
}

else 
{
	die("Error creating table: " . mysqli_error($connection));
}	

$sql = "SET AUTOCOMMIT = 0";
if (mysqli_query($connection, $sql)) 
{
	echo <<<_END
		<tr>
			<td scope="row">Set database attributes</td>
			<td>variables</td>
			<td class="p-3 mb-2 bg-success text-white">Success</td>
		</tr>
_END;
}

else 
{
	die("Error creating table: " . mysqli_error($connection));
}
///////////////////////////// END OF SETTING DATABASE VARIABLES

///////////////////////////////////////////
////////////// USERS TABLE //////////////
///////////////////////////////////////////

// if there's an old version of our table, then drop it:
$sql = "DROP TABLE IF EXISTS users";

// no data returned, we just test for true(success)/false(failure):
if (mysqli_query($connection, $sql)) 
{
	echo <<<_END
		<tr>
			<td scope="row">Drop existing table</td>
			<td>users</td>
			<td class="p-3 mb-2 bg-success text-white">Success</td>
		</tr>
_END;
}

else 
{	
	die("Error checking for existing table: " . mysqli_error($connection));
}

// make our table:
// notice that the username field is a PRIMARY KEY and so must be unique in each record
$sql = "
	CREATE TABLE users 
		(
			username VARCHAR(16), 
			password VARCHAR(16), 
			firstname VARCHAR(32), 
			surname VARCHAR(64), 
			email VARCHAR(64),
			dob DATE,
			telephoneNumber CHAR(11),
			PRIMARY KEY(username)
		)
	";

// no data returned, we just test for true(success)/false(failure):
if (mysqli_query($connection, $sql)) 
{
	echo <<<_END
		<tr>
			<td scope="row">Create Table</td>
			<td>users</td>
			<td class="p-3 mb-2 bg-success text-white">Success</td>
		</tr>
_END;
}

else 
{
	die("Error creating table: " . mysqli_error($connection));
}

// put some data in our table:
// create an array variable for each field in the DB that we want to populate
$usernames[] = 'barrym'; 
	$passwords[] = 'letmein'; 
	$firstnames[] = 'Barry'; 
	$surnames[] = 'Misstop'; 
	$emails[] = 'barry@m-domain.com'; 
	$dobs[] = '1998-08-09'; 
	$telephoneNumbers[] = '07111222333';

$usernames[] = 'mandyb'; 
	$passwords[] = 'abc123'; 
	$firstnames[] = 'Mandy'; 
	$surnames[] = 'Bless'; 
	$emails[] = 'webmaster@mandy-g.co.uk'; 
	$dobs[] = '1999-07-08'; 
	$telephoneNumbers[] = '07444555666';

$usernames[] = 'timmy'; 
	$passwords[] = 'secret95'; 
	$firstnames[] = 'Timmy'; 
	$surnames[] = 'Stoke'; 
	$emails[] = 'timmy@lassie.com'; 
	$dobs[] = '1991-02-05'; 
	$telephoneNumbers[] = '07777888999';

$usernames[] = 'admin'; 
	$passwords[] = 'secret'; 
	$firstnames[] = 'Theo'; 
	$surnames[] = 'Clapperton'; 
	$emails[] = 'totallyRealEmail@real.com'; 
	$dobs[] = '1998-02-06'; 
	$telephoneNumbers[] = '07123123123';

$usernames[] = 'bonfire'; 
	$passwords[] = 'getout1'; 
	$firstnames[] = 'Joe'; 
	$surnames[] = 'Dover'; 
	$emails[] = 'johnMarsden@red.com'; 
	$dobs[] = '1995-01-05'; 
	$telephoneNumbers[] = '07555666333';
	
// loop through the arrays above and add rows to the table:
for ($i=0; $i<count($usernames); $i++)
{
	// create the SQL query to be executed
    $sql = "INSERT INTO users (username, password, firstname, surname, email, dob, telephoneNumber) VALUES ('$usernames[$i]', '$passwords[$i]', '$firstnames[$i]', '$surnames[$i]', '$emails[$i]','$dobs[$i]', '$telephoneNumbers[$i]')";
	
	// run the above query '$sql' on our DB
    // no data returned, we just test for true(success)/false(failure):
	if (mysqli_query($connection, $sql)) 
	{
	echo <<<_END
		<tr>
			<td scope="row">Insert Row</td>
			<td>{$usernames[$i]}</td>
			<td class="p-3 mb-2 bg-success text-white">Success</td>
		</tr>
_END;
	}

	else 
	{
		die("Error inserting row: " . mysqli_error($connection));
	}
}

///////////////////////////////////////////
////////////// SURVEY TABLE //////////////
///////////////////////////////////////////

// if there's an old version of our table, then drop it:
	$sql = "DROP TABLE IF EXISTS surveys";

	// no data returned, we just test for true(success)/false(failure):
	if (mysqli_query($connection, $sql)) 
	{
		echo <<<_END
			<tr>
				<td scope="row">Drop existing table</td>
				<td>surveys</td>
				<td class="p-3 mb-2 bg-success text-white">Success</td>
			</tr>
	_END;
	}
	
	else 
	{	
		die("Error checking for existing table: " . mysqli_error($connection));
	}
	
	// make our table:
	// notice that the username field is a PRIMARY KEY and so must be unique in each record
	$sql = "
	CREATE TABLE surveys
			(
				survey_id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
				survey_title varchar(32),
				survey_JSON longtext,
				PRIMARY KEY(survey_id)
			)	 		 
		";
	
	// no data returned, we just test for true(success)/false(failure):
	if (mysqli_query($connection, $sql)) 
	{
		echo <<<_END
			<tr>
				<td scope="row">Create Table</td>
				<td>surveys</td>
				<td class="p-3 mb-2 bg-success text-white">Success</td>
			</tr>
	_END;
	}
	
	else 
	{
		die("Error creating table: " . mysqli_error($connection));
	}

// we're finished, close the connection:
echo <<<_END
		</tbody>
	</table>
	<a class="text-center" href="index.php?"><h1>Click here to return to menu</h1></a>
_END;

mysqli_close($connection);

/*
{
  "surveydata": [
    {
      "question": {
        "title": "favourite colour?",
        "label": "enter your fav colour",
        "inputType": "text",
        "min": 3,
        "max": 10,
        "required": true
      }
    },
    {
      "question": {
        "title": "favourite date?",
        "label": "enter you favourite date",
        "inputType": "date",
        "min": "1930-01-01T00:00:00.000Z",
        "max": "2019-01-01T00:00:00.000Z",
        "required": false
      }
    },
    {
      "question": {
        "title": "Email address",
        "label": "please enter your email address",
        "inputType": "email",
        "min": 7,
        "max": 32,
        "required": false
      }
    }
  ]
}
*/
?>