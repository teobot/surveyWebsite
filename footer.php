<?php
//    Page Name - || footer.php
//                --
// Page Purpose - || This is the footer of the html page
//                --
//        Notes - ||
//                --

// Create variables for my id and name
$student_name = "Theo Jed Barber Clapperton";
$student_id = "18055445";

// Insert the end of the webpage into the website
echo <<<_END
    <br>
		&copy;6G5Z2107 - Created By: $student_name - StudentNumber: $student_id - 2019/20
    	
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>	
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
			</div>
		</body>
    </html>
_END;
?>