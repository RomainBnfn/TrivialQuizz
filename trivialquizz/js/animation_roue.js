var themeFocused = -1;
var isTouched = false;
$(document).ready(function(){
  $(".bt-theme").on('mouseenter',function(){
    var themeNumber = getThemeNumber($(this));
    focusTheme(themeNumber);
    displayDescTheme(descTheme[themeNumber]);
    unDisplayButtonTheme()
  });
  $(".bt-theme").on('mouseleave',function(){
    var themeNumber = getThemeNumber($(this));
    unfocusTheme(themeNumber);
    undisplayDescTheme();
  });
  $(".bt-theme").on('touchend',function(){
    var themeNumber = getThemeNumber($(this));
    if(themeNumber==themeFocused){
      unfocusTheme(themeNumber);
      undisplayDescTheme();
      themeFocused=-1;
    }else{
      if(themeFocused!=-1){
        unfocusTheme(themeFocused)
      }
      focusTheme(themeNumber);
      displayDescTheme(descTheme[themeNumber]);
      displayButtonTheme(themeNumber);
      themeFocused=themeNumber;
    }
    isTouched = true;
    console.log("touch");
  });
  $(".bt-theme").on('click',function(){
    var themeNumber = getThemeNumber($(this));
    if(!isTouched){
      //document.location.href="quizz-choice.php?theme="+numberIdThemeRelation[themeNumber];
    }else{
    }
    isTouched = false;
  });
});

function getThemeNumber(pathObj){
  return pathObj[0].getAttribute("class")[14];
}

function unfocusTheme(themeNumber){
  var path = document.getElementsByClassName("theme"+themeNumber);
  var text = document.getElementById("th-text"+themeNumber);
  if(themeNumber != -1){
    for (var i=0;i<path.length;i=i+1)
    {
      path[i].setAttribute("d",pathUnfocus[themeNumber]);
    }
    text.setAttribute("x",coordTextUnfocus[themeNumber][0]);
    text.setAttribute("y",coordTextUnfocus[themeNumber][1]);
    text.style.fontSize = fontSizeTextUnfocus+"px";
  }
}

function focusTheme(themeNumber){
  var path = document.getElementsByClassName("theme"+themeNumber);
  var text = document.getElementById("th-text"+themeNumber);
  if(themeNumber != -1){
    for (var i=0;i<path.length;i=i+1)
    {
      path[i].setAttribute("d",pathFocus[themeNumber]);
    }
    text.setAttribute("x",coordTextFocus[themeNumber][0]);
    text.setAttribute("y",coordTextFocus[themeNumber][1]);
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

function displayButtonTheme(themeNumber){
  console.log(themeNumber);
  $("#btn-quizz-smartphone").css("display","block");
  $("#btn-quizz-smartphone").css("background-color",colorTheme[themeNumber]);
  $('#btn-quizz-smartphone').parent().attr('href','quizz-choice.php?theme='+numberIdThemeRelation[themeNumber]);
  $('#btn-quizz-smartphone')[0].textContent = "Voir les quizzes: "+nomTheme[themeNumber];
}

function unDisplayButtonTheme(themeNumber){
  $("#btn-quizz-smartphone").css("display","none");
}
