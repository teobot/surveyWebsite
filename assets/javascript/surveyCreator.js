$(document).ready(function(){

	$(document).on('click', '.removeQuestion', function(){
        $(this).parent('div').remove();
    });
    
	$(document).on('click', '#addQuestion', function(){
        $( "#currentQuestions" ).append(createQuestion());
    });

    $(document).on('click', '#submitSurvey', function(){
        //CREATE ARRAY FOR DATA
            var surveyData = [];
        //GET ALL ELEMENT SO YOU CAN EXTRACT DATA
            
    });

});


function createQuestion() {
    return '<div class="addedQuestion"><div class="card" style="width: 100%; margin-top: 5px;"><div class="card-body"><div class="row" ><div class="col"><h6>Enter a question title:</h6><input id="titleInput"></input><small class="form-text text-muted" >e.g. Enter you favorite animal?</small></div><div class="col"><h6>Enter a small label:</h6><input id="labelInput"></input><small class="form-text text-muted" >Just like me :)</small></div></div><hr><div class="row"><div class="col"><h6>Enter a minimum value:</h6><input id="minInput"></input><small class="form-text text-muted" >e.g. 5</small></div><div class="col"><h6>Enter a maximum value:</h6><input id="maxInput"></input><small class="form-text text-muted" >e.g. 32</small></div> </div><hr><div class="row"><div class="col"><h6>Select a dataType:</h6><select class="custom-select" id="datatypeInput"><option selected>Choose...</option><option value="1">text</option><option value="2">email</option><option value="3">password</option></select></div><div class="col"><h6>Required Question?</h6><div class="form-check"><input class="form-check-input position-static" type="checkbox" id="requiredInput" value="required" aria-label="..."></div></div> </div></div><button class="removeQuestion">Delete Question</button></div></div>';
}