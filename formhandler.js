$(document).ready(function(){
  $('#submit').click(function(e){
    e.preventDefault();
    var getParams = {
        type: $('#type').val(),
        status: $('#status').val(),
        urgency: $('#urgency').val()
    };
    $.get('requestHandler.php', getParams, function(data){
      $('#reply').html(data);
    });
  });
});
