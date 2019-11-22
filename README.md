# 6G5Z2107 - 2CWK50 - 2019/20
## Created by Theo Jed Barber Clapperton
### Student id: 18055445

### Where I left it
> i've created the analysis part of the website, all the survey data is returned with all the responses,
I created the different sections of relaying the information back but need to get the number one going.
> changed some styling
> user can now edit all their information on the accounts page


### Things to think about after reading through specification
- [x] Sign Up using  a unique username, I think I may have added a user ID (maybe remove)
- [ ] Check over server side validation it should be able to do
- - [ ] check if a firstname has been supplied and its not too long
- - [ ] check a valid surname and not too long
- - [ ] email, supplied, length, not to long for db, if its valid
- - [ ] dob, supplied, actual date
- - [ ] telenum, supplied, valid, length
- [ ] Admin page
- - [ ] see all users, and view that users account information, update account info, create a user, del a user
- [ ] maybe move the competitors to the same competitiors page

# URGENT FIXES NEEDED
## FIXES


## DONE
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


### Bugs and issues
- SurveyCreator.php
- - [ ] When removing all questions the footer gets pushed up
- [x] Go though and add client side validation to addedQuestions on survey creator
> User reponses are now inserted into the database
- [x] Users responses now need to be analyzed
- [x] Inserted new questions need new data types
- [x] make sure the data is inserted into the responses correctly, removed inserting as a object
- SurveyAnalysis.php
- [x] the function to update causes the numOfResponses to append not replace, fixed
- SurveyView.php
- [x] When pressing enter in a input box it throws a error

### Features to add / Things to do
- [ ] Make sure that all the images on the competitors pages are correct to the section
- [ ] Similar validation on the sign in page, sign up page is mostly done
- Complete the competition evaluation for:
    - - [x] Google Forms, 
    - - [ ] CheckBox,
    - - [ ] Survey Monkey
- [x] Go back to the analysis page and do something with the different input types

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

SETUP:
...


DOCUMENTATION:
...
