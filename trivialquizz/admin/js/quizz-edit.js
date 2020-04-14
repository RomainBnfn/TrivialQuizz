//return entre 1 et nbQuestions
function getPos(id)
{
  for(var i = 0; i < tableauQuestionOrder.length ; i++){
    if(tableauQuestionOrder[i]==id)
      return i+1;
  };
  return null;
}

// pos entre 1 et nbQuestions
function decreasePosition(pos)
{
  var nbQuestions = tableauQuestionOrder.length;
  var id = 0;
  for(var i = pos-1; i< nbQuestions-1; i++){
    id = tableauQuestionOrder[i+1];
    tableauQuestionOrder[i] = id;
    editFleches(i+1, id);
    editBadge(i+1, id);
  }
  tableauQuestionOrder.pop();
  if(id == 0) id = tableauQuestionOrder[nbQuestions-2];
  editFleches(nbQuestions-1, id);
}

function deleteQuestion(id)
{
  $(() =>{
    fetch("ajax/question-delete.php?id="+id)
    .then((response)=>{
      response.text()
      .then((resp)=>{
        if(resp == "ok"){
          $("#containerQuestionN"+id).remove();
          decreasePosition(getPos(id));
        }
      });
    })

  });
}

//pos entre 1 et nbQuestions
function editFleches(pos, id){
  var flecheUp = document.getElementById("questionUpN"+id),
      flecheDown = document.getElementById("questionDownN"+id),
      nbQuestions = tableauQuestionOrder.length;

  flecheUp.onclick = () => {
    moveQuestion(-1, id, pos);
    return false
  };
  flecheDown.onclick = () => {
    moveQuestion(1, id, pos);
    return false
  };
  flecheUp.style.display = "block";
  flecheDown.style.display = "block";
  if(pos == 1)
  {
    flecheUp.style.display = "none";
  }
  if (pos == nbQuestions)
  {
    flecheDown.style.display = "none";
  }
}

//pos entre 1 et nbQuestions
function editBadge(pos, id){
  $(()=>{
    $("#badgeQuestionN"+id).text(pos);
  })
}

function editPositionDisplay(pos, id){
  $(()=>{
    $("#containerQuestionN"+id).css("order", pos);
  })
}

function moveQuestion(direction, idQuestion, posQuestion){

  var nbQuestions = tableauQuestionOrder.length;
  if(posQuestion+direction<=0 || posQuestion+direction>nbQuestions){
    return;
  }
  fetch("ajax/question-move-order.php?idQuizz="+idQuizz+"&idQuestion="+idQuestion+"&oldPos="+posQuestion+"&direction="+direction)
  .then((response)=>{
    response.text()
    .then((resp) =>{
      if (resp == "ok"){
        $(()=>{
          var idCible = tableauQuestionOrder[posQuestion+direction-1];
          //Tableau edit
          tableauQuestionOrder[posQuestion+direction-1] = idQuestion;
          tableauQuestionOrder[posQuestion-1] = idCible;
          //badgeQuestion edit
          editBadge(posQuestion+direction, idQuestion);
          editBadge(posQuestion, idCible);

          //order edit
          editPositionDisplay(posQuestion+direction, idQuestion);
          editPositionDisplay(posQuestion, idCible);

          //fleches edit
          editFleches(posQuestion+direction, idQuestion);
          editFleches(posQuestion, idCible);
        });
      }
    });
  });
}

function saveQuestionReponse(id)
{
  var form = new FormData(document.getElementById("editQuestionN"+id));
  fetch("ajax/question-save-edit.php", {
    method: "POST",
    body: form
  })
  .then((response) => {
    response.text()
    .then((resp) => {
      if(resp=="ok"){
        var btnCtn = document.getElementById("editQuestion_BtnCtnN"+id),
            btn = document.getElementById("editQuestion_BtnN"+id);
        btn.className = "btn btn-outline-primary float-right";
        btnCtn.className = 'fas fa-check';
        setTimeout(() => {
          btn.className = "btn btn-success float-right";
          btnCtn.className = 'far fa-edit';
        }, 3000);
      }
    })
  });
}

$(document).ready(function(){

  $("#editGeneral").submit((e) => {

    e.preventDefault();

    var form = new FormData(document.getElementById("editGeneral"));
    fetch("ajax/quizz-save-edit.php", {
      method: "POST",
      body: form
    })
    .then((response) => {
      response.text()
      .then((resp) => {
        if(resp=="ok"){
          $("#infoGeneral_Button").css("visibility", "visible");
          setTimeout(() => {
            $("#infoGeneral_Button").css("visibility", "collapse");
          }, 3000);
        }
      })
    });
  });

  $("#boutonSuppression").click( () => {
    $(this).text("Suppression...");
    fetch("ajax/quizz-delete.php?id="+idQuizz)
    .then(()=>{
      document.location.href="quizz.php";
    });
  });

  $("#editGeneral_Nom").keyup(() => {
    $("#titreGeneral").text("Edition de Quizz : "+ $("#editGeneral_Nom").val());
    if( listeNoms.includes( $("#editGeneral_Nom").val() ))
    {
      $("#errorGeneral_Nom").css("visibility", "visible");
    } else {
      $("#errorGeneral_Nom").css("visibility", "collapse");
    }
  });

});
