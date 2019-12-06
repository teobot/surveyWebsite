  // This returns the formatted div for a new question
  function createTextQuestion(questionTitle, questionDesc, dataType) {
    var questionDiv = '';
    questionDiv += '<form class="card" style="width: 100%;">';
    questionDiv += '<div class="card-body"><div class="row">';
    questionDiv += '<div class="col"><h6>Question Title:</h6> <input class="form-control" maxlength="32" min="1" type="text" required name="questionTitle" value="'+questionTitle+'"></input> <small class="form-text text-muted" >e.g. Enter you favorite animal?</small> </div>';
    questionDiv += '<div class="col"><h6>Question Description:</h6> <input value="'+questionDesc+'" class="form-control" name="questionLabel"></input maxlength="32" min="1" type="text" required><small class="form-text text-muted" >e.g. Please enter your first favorite animal!</small> </div>';
    questionDiv += '</div><hr><div class="row">';
    questionDiv += '<div class="col"><h6>Select a dataType:</h6><select class="custom-select" name="questionDataType"><option value="text">text</option><option value="email">email</option><option value="password">password</option><option value="number">number</option><option value="date">date</option>';
    if (dataType != "") {
      questionDiv += '<option value="'+dataType+'" selected>'+dataType+'</option>';
    }
    questionDiv += '</select></div>';
    questionDiv += '</div> </div> <button type="button" class="removeQuestion">Delete Question</button>';
    questionDiv += '</form>';
    return questionDiv;
  }

  // This returns the formatted div for a new question
  function createMultipleChoiceQuestion(questionTitle, questionDesc, questionChoice1, questionChoice2) {
    var questionDiv = '';
    questionDiv += '<form class="card" style="width: 100%;">';
    questionDiv += '<div class="card-body"><div class="row">';
    questionDiv += '<div class="col"><h6>Question Title:</h6> <input value="'+questionTitle+'" class="form-control" maxlength="32" min="1" type="text" required name="questionTitle"></input> <small class="form-text text-muted" >e.g. Enter you favorite animal?</small> </div>';
    questionDiv += '<div class="col"><h6>Question Description:</h6> <input value="'+questionDesc+'" class="form-control" maxlength="32" min="1" type="text" name="questionLabel" required></input><small class="form-text text-muted" >e.g. Please enter your first favorite animal!</small> </div>';
    questionDiv += '</div><hr><div name="multipleChoice" class="row">';
    questionDiv += '<input type="hidden" name="questionDataType" value="multipleChoice"/>';
    questionDiv += '<div class="col"><h6>Choice 1:</h6><input value="'+questionChoice1+'" class="form-control" maxlength="32" min="1" type="text" name="choice1data" required></input></div>';
    questionDiv += '<div class="col"><h6>Choice 2:</h6><input value="'+questionChoice2+'" class="form-control" maxlength="32" min="1" type="text" name="choice2data" required></input></div>';
    questionDiv += '</div> </div> <button type="button" class="removeQuestion">Delete Question</button>';
    questionDiv += '</form>';
    return questionDiv;
  }