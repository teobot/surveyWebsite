# 6G5Z2107 - 2CWK50 - 2019/20
## Created by Theo Jed Barber Clapperton
### Student id: 18055445

## My Website
> Welcome! This is my Survey Creating website, Unlike other websites this one,

> Allows the user to create surveys with as MANY questions as they like,

> I do this by inserting the data from a survey into a JSON array of objects and insert that into a database column that has the data type "JSON" or "LongText" (Both are the same), 
> which doesn't need a maximum length.

> This allows me to insert an array of questions that I can iterate over displaying every question the users creates. I do similar with the responses, meaning the survey can have 1 response or 1000+ responses.

> In this project I've pushed over 200 commits, And added over 12,523 Lines and removed 6,908 (according to github insights). I plan to get around the 85+ Marks. Enjoy!

## Setup
> Once the website is loaded on the index/landing page, use the create data button to insert the data,
> The bar at the Index page will warn you if it can't find the database, It will turn green when it can see the db.
> On inserting the data everything should be green, for success.

> To login into a admin account use:
> admin
> secret
> To login to a default account use:
> bonfire
> getout1

> A pre made survey has been created for you, It has the creator "everyone" as every single user can see it, I've also placed 5 responses inside.

## Documentation On Each Page
### account.php
> The account.php page is where the user can edit their account information, and update that to the database, simply edit the data and click "Update"
### admin_Account_Edit.php
> This is just like the account.php page but for the admin, it checks if the user accessing is a admin and if so using the GET request allows the admin to edit the account information.
### Admin.php
> Here is the admin tools for admins only,
> The admin can create new users and set the account Type and details,
> Search by username, This finds the user by the complete username so the admin can change the account type, edit their data and delete the account,
> It also displays all the current user accounts in the system, giving the admin the ability to change the account type, edit the account info and delete the account.
### competitors.php
> This is also known as "design and analysis", here each competitor is reviewed using a set criteria
> Here I review Google Forms, Zoho and Survey Monkey as well as a conclusion.
> The actual files are stored in the assets/competitors folder, This is for managing the long amount of HTML, The competitor.php page displays these files.
### create_data.php
> This page is where I create my database, tables, users and surveys/responses, This should be ran at the start as you have errors unless you do.
### credentials.php
> This is where I store my database credentials, Here I store the db location as well as username and password, This is used for creating database connections.
### footer.php
> This runs at the bottom of the page, and displays my Name and user ID, this also ends the container div, that allows my site to be responsive.
### header.php
> This is the header and runs at the top of the screen, this also includes my navbar which updates if the user is a admin. I start my container div here to allow the site to be responsive. The session is also started here.
### index.php
> This is the landing page, This is where the user is taken for the first time on the site, From this page the user can reset the db data, as well as sign_in/sign_up.
### sign_in.php
> From here the user can enter their account information to log into the website, They need to enter their username and password to get to the main website.
### sign_out.php
> This allows the user to log out of the current account, It removes the session that allows them to stay logged in, and destroys it, Afterwards gives a logged out message.
### sign_up.php
> From here the user can create a new account for the website,
> It works by displaying a form, Once the form is inputted with information it submits the data back to the webpage, 
> The data is server validated, and the data is sanitized, The username is also checked as each username has to be unique,
> If there are any errors with the submitted information it returns a error message for the user to read and make the correct changes,
> Once the data is correct it's then submitted into the database and the user can then go to the sign_in screen to get into the website.
### submitted.php
> This is a simple webpage that tells the user that the submitted form was successful, This is used to provide instant feedback to the user.
### survey_view.php
> This is the view of the survey, The page takes a GET survey Id and displays all the questions in a form,
> From here the user can submit the survey, all the data is posted to a API (insertResponse.php) that checks if the data is valid, sanitized
> and ready for the database, if so then it inserts the information and returns a successful header and message. The user is then taken to
> the submitted.php page.
### survey_Analysis.php
> This is the page where the survey responses are formatted and displayed to the creator,
> The page shows the number of total respondents, the survey title, survey creator, and a download the data to CSV option.
> The page dynamically updates every 15 seconds along side the "Updating In" timer that shows the user the time un till the new data gets displayed.
> Each question has a specific way of display the data, the multiple choice is displayed in a simple bar chart, the text based questions are in a table,
> The number data shows a graph showing the lowest, highest, range, total and average. This is super useful for quickly finding out group information,
> Each question also displays the title, the description and the total number number of responses per question.
### survey_Creator.php
> The survey creator is for creating surveys,
> The user must give the survey a title and at least one question with all the information entered,
> To add a new question the user can simply choose the question base from the buttons on the left, They can add as many questions as they want.
> With a text based question the user gives the question a title and label, and can select from 5 different data types, text,email,password,number,date.
> With a multiple choice based question the user must provide a title, label and then 2 choices.
> Once finished the user clicks submit and can now view the analysis and survey.
> The survey Creator page also doubles as a surveyEditor, If the page receives a surveyID then it checks if the user logged in is either the creator or admin,
> If so it then pre-loads all existing questions into the creator, then the user can edit all the information, and update the survey.
### surveys_Manage.php
> Survey Manage displays all the available surveys to the user,
> It gives the user the feature to create a new survey and Search by survey title,
> Every survey available to the user displays here, They can then view the ID, creator, Title, as well as, Edit/View/Analyse the survey,
> They can view the total number of responses and delete the survey.
> default users can only view their own survey and surveys for "everyone", while admins can view everyone's surveys.
### validation_Checker.php
> This is a compilation of custom made functions that check the inputted data for errors and invalid information,
> This page is used by all forms to make sure all data is correct.

## RestFul APIS
### checkSurveyCreator.php
> This checks if the inputted creator is allowed to manipulate the inputted survey.
### deleteSurvey.php
> This takes a username and surveyID, and checks if the user is allowed to delete the survey before deleting it.
### deleteUserAccount.php
> This takes 2 usernames, 1 target username and 1 is the person deleting, if the user has admin rights then it deletes the target account.
### insertNewAccount.php
> This takes username and a array of user account information, if the account information is valid and the user is a admin it inserts the account into the db.
### insertResponse.php
> This takes the response data from a survey, and the survey ID, 
> If the response data is valid and matches to the question numbers then it will insert the response to the survey.
### insertSurveyIntoDatabase.php
> This takes a username, survey question array, a action (update/insert) and a survey_id/
> If the user is creating a new survey the API checks the question data and sanitizes it or errors,
> It will then insert the survey into the database, If the action variables is Update,
> It will check for errors and invalid data, but update the current surveys question array using the username and survey_id.
### returnsResponse.php
> This takes a username and surveyID, if the user is a admin or creator of a survey it will return all the responses of that survey.
### returnSurveyData.php
> This takes a surveyID and returns the questions and survey details for the survey_view.php.
### returnUsers.php
> This takes a username, if the user is a admin then it will return all the users.
### returnUsersSurveys.php
> This takes a username, if the username is a admin then it returns all the surveys, otherwise just the surveys the user created or surveys for everyone.
### updateUserAccountType.php
> This takes a target username, a username and a accountType,
> If the user is a admin and the accountType is either admin or default then change the accountType of the target User.
