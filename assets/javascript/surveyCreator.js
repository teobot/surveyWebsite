$(document).ready(function(){

	$(document).on('click', '.removeQuestion', function(){
        $(this).parent('div').remove();
    });
    
	$(document).on('click', '#addQuestion', function(){
        $( "#currentQuestions" ).append(createQuestion());
    });
    

    function createQuestion() {

        //Create question container
        var questionContainer = document.createElement("div");
        //Add the className for styling
        questionContainer.className = "addedQuestion";

        //Create new title for question container
        var questionTitle = document.createElement("h6");
        //Add the title text
        questionTitle.innerHTML = "New Question";
        //Insert the title into the container
        questionContainer.append(questionTitle);

        //Create a button to delete the added question
        var deleteButton = document.createElement("button");
        //Add the remove class for removing the button
        deleteButton.className = "removeQuestion";
        //Change the button text
        deleteButton.innerHTML = "delete question";
        //Insert the button to the container
        questionContainer.append(deleteButton);

        //Return the created question
        return questionContainer;
    }

});