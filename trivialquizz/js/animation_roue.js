var themeFocused = -1;
$(document).ready(function(){
  $(".bt-theme").mouseenter(function(){
    var themeNumber = getThemeNumber($(this));
    focusTheme(themeNumber);
    displayDescTheme(descTheme[themeNumber])
  });
  $(".bt-theme").mouseleave(function(){
    var themeNumber = getThemeNumber($(this));
    unfocusTheme(themeNumber);
    undisplayDescTheme();
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
    text.style.fontSize = "15px";
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
    text.style.fontSize = "19.5px";
  }
}

function displayDescTheme(description){
  var paragraphe = document.getElementById("th-desc");
  paragraphe.textContent = description;
  paragraphe.style.height = "50px";
}

function undisplayDescTheme(){
var paragraphe = document.getElementById("th-desc");
paragraphe.textContent = "";
paragraphe.style.height = "0px";
}
