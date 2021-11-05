
  var isPswdValid = false;

  $(document).ready(function(){

    var pswds = $('input[type="password"]');
    pswds.on('input',function(){
    if(pswds[0].value == pswds[1].value && pswds[1].value != '') {
        $('#invalid-conf-feedback').css('display','none');
        $('#valid-conf-feedback').css('display','inline');
        isPswdValid = true;
      }else if(pswds[1].value != ''){
        $('#invalid-conf-feedback').css('display','inline');
        $('#valid-conf-feedback').css('display','none');
        isPswdValid = false;
      }else {
        $('#invalid-conf-feedback').css('display','none');
        $('#valid-conf-feedback').css('display','none');
        isPswdValid = false;
      }
    });
    $('#form').on('submit', function(event){
      if(!isPswdValid) {
        event.preventDefault();
        event.stopPropagation();
      }
    });
    $('input[name="pseudo"]').on('input',function(){
      $('.force-display').css('display','none');
    });
  });
