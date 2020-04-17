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
  editBadge(nbQuestions-1, id);
}

function deleteQuestion(id)
{
  $(() =>{
    fetch("ajax/question-delete.php?id="+id)
    .then((response)=>{
      response.text()
      .then((resp)=>{
        if(resp == "ok"){
          displayAfterDeleteQuestion(id);
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

          //fleches edit;
          editFleches(posQuestion+direction, idQuestion);
          editFleches(posQuestion, idCible);
        });
      }
    });
  });
}

function switchType(target, id){
  if(target.prop('checked')) {//  QCM --> Libre

    for (var i = 0; i <= 4; i++) {
      var val = $("#input_repQCMN"+i).val(); //On save la value actuelle dans le HTML
      $("#input_repQCMN"+i).attr("value", val);
    }

    window["html_repQCM"+id] = $("#reponseQCMN"+id).html();

    $("#reponseQCMN"+id).html("");
    $("#reponseLibreN"+id).html(window["html_repLibre"+id]);
  }
  else{ // Libre --> QCM

    var val = $("#input_repLibreN"+id).val(); //On save la value actuelle dans le HTML
    $("#input_repLibreN"+id).attr("value", val);

    window["html_repLibre"+id] = $("#reponseLibreN"+id).html();

    $("#reponseLibreN"+id).html("");
    $("#reponseQCMN"+id).html(window["html_repQCM"+id]);
  }
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

function temps_changed(){
  var malus = $("#editGeneral_Malus").val();
  var temps = $("#editGeneral_Temps").val();
  if( isNaN(malus) || isNaN(temps))
    return;

  var tempFinal = parseInt(temps * (1 - malus * 4 / 100) );

  $("#tempsDifficile").html(""+tempFinal+ "s");
  $("#amountMalus").html("-"+malus+ "%");
  $("#tempsFacile").html(""+ temps + "s");
}

function displayAfterDeleteQuestion(id){
  $("#containerQuestionN"+id).remove();
  decreasePosition(getPos(id));
}

$(document).ready(function(){
  $(".btn-delier-question").click((e)=>{
    var idQuestion = e.target.dataset.idquestion;
    var idQuizz = e.target.dataset.idquizz;
    fetch("ajax/question-delier.php?idQuizz="+idQuizz+"&idQuest="+idQuestion)
    .then((response)=>{
      response.text()
      .then((resp)=>{
        if(resp=="ok"){
          displayAfterDeleteQuestion(idQuestion);
        }
      })
    })
  });

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
        console.log(resp);
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
