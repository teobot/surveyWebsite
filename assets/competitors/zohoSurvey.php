<?php
//    Page Name - || zohoSurvey.php
//                --
// Page Purpose - || This displays the competition research about the zoho survey Form webpage.
//                --
//        Notes - ||
//         		  ||
//                --
//SECTION 1 - [] TextDone - [] Images Done
//SECTION 2 - [] TextDone - [] Images Done
//SECTION 3 - [] TextDone - [] Images Done
//SECTION 4 - [] TextDone - [] Images Done
//SECTION 5 - [] TextDone - [] Images Done
?>
<h2 class="text-center">
	Zoho Survey
</h2>

<?php // 1. Layout/presentation of surveys section Card ?>
<div class="container" style="background-color: paleturquoise; border-left: 6px solid mediumturquoise;">
    <br>
    <div class="row">
        <div class="col">
            <h4>1. Layout/presentation of surveys</h4>
            <p class="text-center">
                The layout of the form is clean, with each section question and title being in a "card" section, the background color and theme
                can be changed. The whole page is responsive and adjustable on all devices with little effort.
            </p>  
        </div>
    </div>
    <div class="row">
        <div class="text-center">
                <img src="assets/img/googleFormsLayoutPres.PNG" class="rounded img-fluid" alt="Responsive image">
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col">
            <ul class="list-group">
                <li class="list-group-item"><h5 class="mb-1">Positives</h5></li>
                <li class="list-group-item">Required questions have a asterisk, this makes it really easy to view which questions are required.</li>
                <li class="list-group-item">The card or sectioned display, having questions and titles separated makes them a lot easier to read.</li>
                <li class="list-group-item">The cards have a theme, having a matching coloured tab across the top of the section title makes it more stylish.</li>
                <li class="list-group-item">The background color can be set, which I think is very stylish.</li>
            </ul>
        </div>
        <div class="col">
            <ul class="list-group">
                <li class="list-group-item"><h5 class="mb-1">Negatives</h5></li>
                <li class="list-group-item">There are terms and conditions at the bottom of the page, Even though I think these are needed they could be made simpler.</li>
                <li class="list-group-item">I don't like how the submit button is on the left, I would move it to the right instead.</li>
            </ul>
        </div>
    </div>
    <hr>
</div>
<?php // END Layout/presentation of surveys section Card END ?>


<?php // 2. Ease of Use of surveys section Card ?>
<div class="container" style="background-color: moccasin; border-left: 6px solid goldenrod;">
    <br>
    <div class="row">
        <div class="col">
            <h4>2. Ease of Use</h4>
            <p class="text-center">
                Creating the forms is really easy, Here you can see that by clicking the "+" button adds a new 
                question and from there you can change the question type and title. You can also embed youtube videos and images 
                and change the font size and type. You can also set the question to required meaning that the user won't be able 
                to submit the form without filling it out. You can also create different sections with separate titles and question types. 
            </p>  
        </div>
    </div>
    <div class="row">
        <div class="text-center">
                <img src="assets/img/googleFormsLayout.PNG" class="rounded img-fluid" alt="Responsive image">
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col">
            <ul class="list-group">
                <li class="list-group-item"><h5 class="mb-1">Positives</h5></li>
                <li class="list-group-item">Really easy to add a new questions.</li>
                <li class="list-group-item">Can easily add multiple question types.</li>
                <li class="list-group-item">Embed images and youtube videos.</li>
                <li class="list-group-item">Able to create complete sections that have their own title and questions.</li> 
                <li class="list-group-item">Can easily make a question as required.</li>  
            </ul>
        </div>
        <div class="col">
            <ul class="list-group">
                <li class="list-group-item"><h5 class="mb-1">Negatives</h5></li>
                <li class="list-group-item">You can not drag a question on top of the first question, You have to drag all questions down until the question gets pushed to top.</li>         
            </ul>
        </div>
    </div>
    <hr>
</div>
<?php // END Ease of Use of surveys section Card END ?>

<?php // 3. User account set-up/login process section Card ?>
<div class="container" style="background-color: paleturquoise; border-left: 6px solid mediumturquoise;">
    <br>
    <div class="row">
        <div class="col">
            <h4>3. User account set-up/login process</h4>
            <p class="text-center">
		To create a Google Form, The user must first create a google account, this is very easy and the user can use 
		their own email account. They would need to give there first name, Lastname, current email 
		address/create a new one, password and on some accounts need a phone number to be linked. <br> 
		To submit a response to the form a user doesn't have to a have an account unless the form 
		creator selects "Can only submit 1 response" in which the user is asked to log in before submitting.
            </p>  
        </div>
    </div>
    <div class="row">
        <div class="text-center">
                <img src="assets/img/googleFormsAcc.PNG" class="rounded img-fluid" alt="Responsive image">
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col">
            <ul class="list-group">
                <li class="list-group-item"><h5 class="mb-1">Positives</h5></li>
                <li class="list-group-item">The user can limit the number of responses to avoid spamming.</li>
                <li class="list-group-item">User doesn't have to have a account to submit a response.</li>
            </ul>
        </div>
        <div class="col">
            <ul class="list-group">
                <li class="list-group-item"><h5 class="mb-1">Negatives</h5></li>
                <li class="list-group-item">There aren't any methods in place to reduce each user submission apart from signing in.</li>
                <li class="list-group-item">If the user has to make a account to submit the form they will most likely leave.</li>         
            </ul>
        </div>
    </div>
    <hr>
</div>
<?php // END User account set-up/login process section Card END ?>

<?php // 4. Question types section Card ?>
<div class="container" style="background-color: moccasin; border-left: 6px solid goldenrod;">    
    <br>
    <div class="row">
        <div class="col">
            <h4>4. Question types</h4>
            <p class="text-center">
                The user can select a list of 11 different question types:
                <br> • Short answer, • Paragraph, • Multiple choice, • Drop-down, • File upload,
                <br> • Linear scale, • Multiple-choice grid, • Tick box grid, • Date, • Time,
                <br>
                This covers all the users possible input, in terms of question types and content types.
            </p>  
        </div>
    </div>
    <div class="row">
        <div class="text-center">
                <img src="assets/img/googleFormsQuestionType.PNG" class="rounded img-fluid" alt="Responsive image">
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col">
            <ul class="list-group">
                <li class="list-group-item"><h5 class="mb-1">Positives</h5></li>
                <li class="list-group-item">Wide range of question types covering nearly everything someone would want.</li>  
            </ul>
        </div>
        <div class="col">
            <ul class="list-group">
                <li class="list-group-item"><h5 class="mb-1">Negatives</h5></li>
                <li class="list-group-item">Doesn't have a Number question type.</li>             
            </ul>
        </div>
    </div>
    <hr>
</div>
<?php // END Question types section Card END ?>

<?php // 5. Analysis tools section Card ?>
<div class="container" style="background-color: paleturquoise; border-left: 6px solid mediumturquoise;">    
    <br>
    <div class="row">
        <div class="col">
            <h4>5. Analysis tools</h4>
            <p class="text-center">
                Google forms has a response section where all the submitted form data gets analyzed and displayed. <br>
                
                The user can select between the "summary" section which collects all the data submitted and shows graphs based on the question type. <br>
                
                The other section the user can select is the "individual" section where the user can see all the individual forms that the users have submitted, 
                this would be good for applications where the user need to view each candidate e.g. a job application. 

                For a text/text area answer, Google has opted for a list of all the responses. 
                Multiple choice gets a pie chart, checkboxes get a line chart, and dropdown questions also get a pie chart. 
                Linear scales question types get a line chart, and so do multiple choice grid and checkbox questions. 
                Date questions get grouped by month and year, having the individual days within those months displayed next to it. 
                Time responses are similar to the date display method, with same hour answers getting grouped with the complete time displayed next to it.
            </p>  
        </div>
    </div>
    <div class="row">
        <div class="text-center">
                <img src="assets/img/googleFormsAnalysis.PNG" class="rounded img-fluid" alt="Responsive image">
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col">
            <ul class="list-group">
                <li class="list-group-item"><h5 class="mb-1">Positives</h5></li>
                <li class="list-group-item">Each question type has a purposely selected graph/method to accurately show the data.</li>
                <li class="list-group-item">The presented data is incredibly clean and efficient in showing the form data.</li>
                <li class="list-group-item">Date and time data are grouped to reduce wasted space/spam.</li>
                <li class="list-group-item">Text/text area data is placed into a table and coloured so its easy to read.</li>
		<li class="list-group-item">Each chart and data set can be copied and exported relatively easily.</li>
		<li class="list-group-item">The analyzed data colors match the theme of the form.</li>
		<li class="list-group-item">Above each represented question shows the number of submitted data for that question.</li> 
            </ul>
        </div>
        <div class="col">
            <ul class="list-group">
                <li class="list-group-item"><h5 class="mb-1">Negatives</h5></li>
                <li class="list-group-item">If the user doesn't agree with the presented form the user doesn't have a choice to change it apart from downloading the raw data and graphing it themselves.</li>
                <li class="list-group-item">The layout of the website means that with long forms it could take sometime to go through the data.</li> 
                <li class="list-group-item">The website doesn't show how many users missed or didn't fill in a question.</li>
            </ul>
        </div>
    </div>
    <hr>
</div>
<?php // END Analysis tools section Card END ?>
