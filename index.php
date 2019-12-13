<?php
//    Page Name - || index.php
//                --
// Page Purpose - || This is the landing page,
//                --
//        Notes - || The user can reset the data from here
//         		  ||
//                --

// execute the header script and import the database connection details
require_once("header.php");
require_once("credentials.php");

// Insert a introduction to the website
echo<<<_END
<div class="jumbotron jumbotron-fluid">
  <div class="container">
    <h1 class="display-4">Welcome!</h1>
    <p class="lead">
        This is Theo's survey website coursework, <br><br>

        Its made primarily from PHP, <br>
        It also uses HTML, CSS, Javascript, Bootstrap and JQuery.
    </p>
  </div>
</div>
_END;
// ...

    // Create a new database connection
    $connection = mysqli_connect($dbhost, $dbuser, $dbpass);

    // If the connection fails then tell the user
    if (!$connection) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Create a query that checks if the database table exists in xampp
    $query = "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '$dbname'";

    // Execute the query to the database
    $result = mysqli_query($connection, $query);

    // return the number of rows back
    $n = mysqli_num_rows($result);
    
    // close the connection
    mysqli_close($connection);

    // If the result confirms the database name exists then display a success message
    if ($n > 0) 
    {
        echo <<<_END
        <div class="alert alert-success" role="alert">
            Database and data exist, Welcome! You can reset <a href="create_data.php">Here</a>
        </div>    
_END;
    }
    // The database table does not exist in the database
    else 
    {
        echo <<<_END
        <div class="alert alert-danger" role="alert">
            Database doesn't exist! Please click <a href="create_data.php">Here</a> to create the database!
        </div>    
_END;
    }

// Insert the footer
require_once("footer.php");

?>