<?php

$allUsersSurveys[] = (object) array('survey_id' => $row["survey_id"],'survey_title' => $row["survey_title"],'survey_JSON' => json_decode($row["survey_JSON"]) );

array_push($allUsersSurveys, mysqli_fetch_assoc($resultQuery) );

    // Create connection
    $conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }


    $cars = array("Volvo", "BMW", "Toyota");
    $json = json_encode($cars);
    $creator = "bonfire";
    $sql = "INSERT INTO `surveys` (`survey_id`, `survey_creator`, `survey_title`, `survey_JSON`) VALUES (NULL, '$creator', 'Test From', '$json' )";

    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();

/*
{"surveydata": [{"question": {"title": "favourite colour?","label": "enter your fav colour","inputType": "text","min": 3,"max": 10,"required": true}},{"question": {"title": "favourite date?","label": "enter you favourite date","inputType": "date","min": "1930-01-01T00:00:00.000Z","max": "2019-01-01T00:00:00.000Z","required": false}},{"question": {"title": "Email address","label": "please enter your email address","inputType": "email","min": 7,"max": 32,"required": false}}]}
*/

?>