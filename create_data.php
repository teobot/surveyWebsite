<?php
//    Page Name - || create_data.php
//                --
// Page Purpose - || This is the page that creates the database and insert the data
//                --
//        Notes - || For each section I run the query and output the success
//         		  ||
//                --

//Insert the header
require_once("header.php");

//Create a table
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

// read in the details of our MySQL server:
require_once("credentials.php");

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
			password VARCHAR(40), 
			firstname VARCHAR(32), 
			surname VARCHAR(64), 
			email VARCHAR(64),
			dob DATE,
			telephoneNumber CHAR(11),
			accountType VARCHAR(12) NOT NULL DEFAULT 'default',
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
	$passwords[] = 'b7a875fc1ea228b9061041b7cec4bd3c52ab3ce3'; 
	$firstnames[] = 'Barry'; 
	$surnames[] = 'Misstop'; 
	$emails[] = 'barry@m-domain.com'; 
	$dobs[] = '1998-08-09'; 
	$telephoneNumbers[] = '07111222333';
	$accountType[] = "default";

$usernames[] = 'mandyb'; 
	$passwords[] = '6367c48dd193d56ea7b0baad25b19455e529f5ee'; 
	$firstnames[] = 'Mandy'; 
	$surnames[] = 'Bless'; 
	$emails[] = 'webmaster@mandy-g.co.uk'; 
	$dobs[] = '1999-07-08'; 
	$telephoneNumbers[] = '07444555666';
	$accountType[] = "default";

$usernames[] = 'timmy'; 
	$passwords[] = '6012303c7834d8c34d4183e8bec279e25fa2a421'; 
	$firstnames[] = 'Timmy'; 
	$surnames[] = 'Stoke'; 
	$emails[] = 'timmy@lassie.com'; 
	$dobs[] = '1991-02-05'; 
	$telephoneNumbers[] = '07777888999';
	$accountType[] = "default";

$usernames[] = 'admin'; 
	$passwords[] = 'e5e9fa1ba31ecd1ae84f75caaa474f3a663f05f4'; 
	$firstnames[] = 'Theo'; 
	$surnames[] = 'Clapperton'; 
	$emails[] = 'totallyRealEmail@real.com'; 
	$dobs[] = '1998-02-06'; 
	$telephoneNumbers[] = '07123123123';
	$accountType[] = "admin";

$usernames[] = 'bonfire'; 
	$passwords[] = 'ace40b39d09f50e370c71abb292183bbf73ddceb'; 
	$firstnames[] = 'Joe'; 
	$surnames[] = 'Dover'; 
	$emails[] = 'johnMarsden@red.com'; 
	$dobs[] = '1995-01-05'; 
	$telephoneNumbers[] = '07555666333';
	$accountType[] = "default";
	
// loop through the arrays above and add rows to the table:
for ($i=0; $i<count($usernames); $i++)
{
	// create the SQL query to be executed
    $sql = "INSERT INTO users (username, password, firstname, surname, email, dob, telephoneNumber, accountType) VALUES ('$usernames[$i]', '$passwords[$i]', '$firstnames[$i]', '$surnames[$i]', '$emails[$i]','$dobs[$i]', '$telephoneNumbers[$i]', '$accountType[$i]')";
	
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
				survey_creator varchar(16),
				survey_title varchar(32),
				survey_JSON longtext,
				survey_RESPONSE longtext,
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

	//Create my question objects to be inserted
	$allUsersSurveys[] = (object) array(
		'title' => 'Fullname?',
		'label' => 'Please enter your full name!',
		'inputType' => 'text',
	);

	$allUsersSurveys[] = (object) array(
		'title' => 'Personal Email?',
		'label' => 'Please enter your email address!',
		'inputType' => 'email',
	);

	$allUsersSurveys[] = (object) array(
		'title' => 'Age?',
		'label' => 'Please enter your personal age!',
		'inputType' => 'number',
	);

	$allUsersSurveys[] = (object) array(
		'title' => 'Birthday Date?',
		'label' => 'Please enter your birthday!',
		'inputType' => 'date',
	);

	$allUsersSurveys[] = (object) array(
		'title' => 'Fanta or Coke?',
		'label' => 'Pick a drink!',
		'inputType' => 'multipleChoice',
		'choice1' => 'Fanta',
		'choice2' => 'Coke'
	);

	$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
	// Check connection
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	
	// Encode the question data nad insert that and the responses into the database
	$json = json_encode($allUsersSurveys);
	$responsestring = '[["Theo Flappery","theoflappery@gmail.com","21","19980206","Coke"],["Flow Dry","flowdry@gmail.com","23","19991114","Fanta"],["Sillie Floucher","silfloucher@gmail.com","20","19990114","Coke"],["Jerky Tastier","jerkytastier@gmail.com","27","19900505","Coke"],["Richard Folerton","richfolderton@gmail.com","40","19710125","Coke"]]';
	$creator = "everyone";
	$sql = "INSERT INTO `surveys` (`survey_id`, `survey_creator`, `survey_title`, `survey_JSON`, `survey_RESPONSE`) VALUES (NULL, '$creator', 'First Survey', '$json', '$responsestring')";
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
	
	$conn->close();

// we're finished, close the connection:
echo <<<_END
		</tbody>
	</table>
	<a class="text-center" href="index.php?"><h1>Click here to return to menu</h1></a>
_END;

mysqli_close($connection);
?>