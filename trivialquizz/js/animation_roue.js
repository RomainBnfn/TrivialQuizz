// TODO: travaillé sur les id directement au lieu de passer par themeNumber
var themeFocused = -1;
var id;
var isTouched = false;
$(document).ready(function(){
  $(".bt-theme").on('mouseenter',function(){
    var idTheme = getThemeId($(this));
    focusTheme(idTheme);
    displayDescTheme(themes[idTheme].desc)
    unDisplayButtonTheme()
  });
  $(".bt-theme").on('mouseleave',function(){
    var idTheme = getThemeId($(this));
    unfocusTheme(idTheme);
    undisplayDescTheme();
  });
  $(".bt-theme").on('touchend',function(){
    var idTheme = getThemeId($(this));
    if(idTheme==themeFocused){
      unfocusTheme(idTheme);
      undisplayDescTheme();
      themeFocused=-1;
    }else{
      if(themeFocused!=-1){
        unfocusTheme(themeFocused)
      }
      focusTheme(idTheme);
      displayDescTheme(themes[idTheme].desc);
      displayButtonTheme(idTheme);
      themeFocused=idTheme;
    }
    isTouched = true;
  });
});

function clickMainTheme(idTheme){
  id = idTheme;
  if(!isTouched){
    document.location.href="quizz.php?theme="+idTheme;
  }else{
    $('#btn-quizz-smartphone').parent().attr('href','quizz.php?theme='+idTheme);
  }
  isTouched = false;
};

function getThemeId(pathObj){
  //return pathObj[0].getAttribute("class")[14];
  return pathObj[0].getAttribute("class").substring(14);
}

function unfocusTheme(getThemeId){
  var path = document.getElementsByClassName("theme"+getThemeId);
  var text = document.getElementById("th-text"+getThemeId);
  if(getThemeId != -1){
    for (var i=0;i<path.length;i=i+1)
    {
      path[i].setAttribute("d",pathUnfocus[getThemeId]);
    }
    text.setAttribute("x",coordTextUnfocus[getThemeId][0]);
    text.setAttribute("y",coordTextUnfocus[getThemeId][1]);
    text.style.fontSize = fontSizeTextUnfocus+"px";
  }
}

function focusTheme(getThemeId){
  var path = document.getElementsByClassName("theme"+getThemeId);
  var text = document.getElementById("th-text"+getThemeId);
  if(getThemeId != -1){
    for (var i=0;i<path.length;i=i+1)
    {
      path[i].setAttribute("d",pathFocus[getThemeId]);
    }
    text.setAttribute("x",coordTextFocus[getThemeId][0]);
    text.setAttribute("y",coordTextFocus[getThemeId][1]);
    text.style.fontSize = fontSizeTextFocus+"px";
  }
}

function displayDescTheme(description){
  var paragraphe = document.getElementById("th-desc");
  paragraphe.textContent = description;
}

function undisplayDescTheme(){
  var paragraphe = document.getElementById("th-desc");
  paragraphe.textContent = "";
}

function displayButtonTheme(idTheme){
  $("#btn-quizz-smartphone").css("display","block");
  $("#btn-quizz-smartphone").css("background-color",themes[idTheme].couleur);
  $('#btn-quizz-smartphone')[0].textContent = "Voir les quizzes: "+themes[idTheme].nom;
}

function unDisplayButtonTheme(idTheme){
  $("#btn-quizz-smartphone").css("display","none");
}
