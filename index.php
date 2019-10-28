<?php
// Things to notice:
// This is an empty page where you can provide a simple overview and description of your site
// Consider it the 'welcome' page for your survey web site

// execute the header script:
require_once "header.php";
require_once "credentials.php";

echo "This is the skeleton code for 2CWK50. See the assignment specification for details of how you need to extend it.<br>You may wish to include a short description of your survey site and how to use the main features it has here.<br><br>";

// connect directly to our database (notice 4th argument) we need the connection for sanitisation:
    $connection = mysqli_connect($dbhost, $dbuser, $dbpass);

    if (!$connection) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $query = "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '$dbname'";

    // this query can return data ($result is an identifier):
    $result = mysqli_query($connection, $query);

    $n = mysqli_num_rows($result);

    if ($n > 0) {
        echo <<<_END
        <div class="alert alert-success" role="alert">
            Database and data exist, Welcome! You can reset <a href="create_data.php">Here</a>
        </div>    
_END;
    } else {
        echo <<<_END
        <div class="alert alert-danger" role="alert">
            Database doesn't exist! Please click <a href="create_data.php">Here</a> to create the database!
        </div>    
_END;
    }

    mysqli_close($connection);

    echo "<br>";

require_once "footer.php";

?>