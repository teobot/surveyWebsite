# 6G5Z2107 - 2CWK50 - 2019/20
## Created by Theo Jed Barber Clapperton
### Student id: 18055445

## My Website
> Welcome! This is my Survey Creating website, Unlike other websites this one,

> Allows the user to create surveys with as MANY questions as they like,

> I do this by inserting the data from a survey into a JSON array of objects and insert that into a database column that has the data type "JSON" or "LongText" (Both are the same), 
> which doesn't need a maximum length.

> This allows me to insert an array of questions that I can iterate over displaying every question the users creates. I do similar with the responses, meaning the survey can have 1 response or 1000+ responses.

> 

## Setup
> Once the website is loaded on the index/landing page, use the create data button to insert the data,
> The bar at the Index page will warn you if it can't find the database, It will turn green when it can see the db.

## Documentation On Each Page
### account.php - [ ] Commented and Checked?
>
### admin_Account_Edit.php - [ ] Commented and Checked?
>
### Admin.php - [ ] Commented and Checked?
>
### competitors.php - [ ] Commented and Checked?
>
### create_data.php - [ ] Commented and Checked?
>
### credentials.php - [ ] Commented and Checked?
>
### footer.php - [ ] Commented and Checked?
>
### header.php - [ ] Commented and Checked?
>
### index.php - [ ] Commented and Checked?
>
### sign_in.php - [ ] Commented and Checked?
>
### sign_out.php - [ ] Commented and Checked?
>
### sign_up.php - [ ] Commented and Checked?
>
### submitted.php - [ ] Commented and Checked?
>
### survey_view.php - [ ] Commented and Checked?
>
### survey_Analysis.php - [ ] Commented and Checked?
>
### survey_Creator.php - [ ] Commented and Checked?
>
### surveys_Manage.php - [ ] Commented and Checked?
>
### validation_Checker.php - [ ] Commented and Checked?
>


You should include a README.txt file as part of your submission. This should include details on how to set
up your submission (e.g., “run create_data.php”, “navigate to login.php”, a username/password to log in
with, etc.) and documentation for how your site works. The documentation doesn’t need to be very long,
but it does need to clearly explain what your site can do, covering all its individual pages.

A useful tip is to break the documentation up into sections for each of the features in the mark scheme
(User Accounts, Design and Analysis, Survey Management, Survey Results) and briefly describe how each
feature works. Once you have completed the documentation for each feature you have addressed, you
should naturally find you have described all of the pages in your site.

### TO DO BY RELEASE
- [x] User can download survey responses
- [x] survey editor
- [x] Admin edit user information
- [x] Finished Google Forms
- - [x] Added Images
- - [x] Completed Text
- [x] Finished ZohoSurvey Forms
- - [x] Added Images
- - [x] Completed Text
- [x] Finished Survey Monkey Forms
- - [x] Added Images
- - [x] Completed Text
- [x] Finish a conclusion page
- [ ] Comment all code
- [x] Updated questions to not show responses
- [x] Admins can now create new accounts

### Bugs and issues
- SurveyCreator.php
- - [x] When removing all questions the footer gets pushed up
- [x] Go though and add client side validation to addedQuestions on survey creator
> User reponses are now inserted into the database
- [x] Users responses now need to be analyzed
- [x] Inserted new questions need new data types
- [x] make sure the data is inserted into the responses correctly, removed inserting as a object
- SurveyAnalysis.php
- [x] the function to update causes the numOfResponses to append not replace, fixed
- SurveyView.php
- [x] When pressing enter in a input box it throws a error

### Features done
- [x] Added new validation to the sign_up page, so that data is both client side and server validated
- [x] Database script is written so that It will create the more complex database 
- [x] Sign_up page now allows user to give more details than before
- [x] Added reset feature for database if it becomes damaged
- [x] Commented loads of the signUp.php page
- [x] Date of birth needs validation, I've already done this somewhere, just need to find it
- [x] Show the new database information in account.php page
- [x] Lots more work on the competitors page
- [x] fixed the navbar admin tool link, and made it fullscreen with the body been in a container
- [x] Tweaked the look of the ReadMe.md page
- [x] Show the new database information in account.php page
- [x] Lots more work on the competitors page
- [x] fixed the navbar admin tool link, and made it fullscreen with the body been in a container
- [x] Created a RESTful API that returns all the usernames if the user is logged in
- [x] The admin page now displays all the current users usernames in real time
- [x] added another user into the database creation
- [x] Phone Number is now server validated but is in format 111222333, its 9 digits long
- Update section 5 on the google forms compet page
- - [x] Go through and fix the formatting.
- [x] Created a new API that returns a list of surveys of a user
- [x] Survey management page now returns a constantly updating list of surveys using a API
- [x] Create data now inserts a test form for the bonfire account user
- [x] Created a survey view form that gets the survey id and displays the information
- [x] When selecting number type you cannot submit
- [x] Questions with the same title cause a error - surveyAnalysis.php
- [x] Finish the updateUserAccountType.php API
- [x] Need to change the admin tools page, to look at the account type and not the username!
- [x] User can submit a survey with no questions and no title.
- [x] Check all data inserted in the same sizes
- - [x] username 16
- - [x] password 16
- - [x] firstname 32
- - [x] surname 64
- - [x] email 64
- - [x] dob
- - [x] telephone number 11
- [x] Check all surveys titles are the same max size - 32
- [x] check all surveys questions, labels and choice inputs have a max size - 32
- [x] add client side validation to these pages
- - [x] signin.php
- - [x] suvreyCreator.php
- - [x] accountPage.php
- - [x] signUp.php
- - [x] surveyView.php
- [x] on the suvrey page when pressing enter it crashes.
