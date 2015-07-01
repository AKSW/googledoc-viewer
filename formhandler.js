//wait for the document to be loaded
$(document).ready(function(){
  $('#submit').click(function(e){
    //preventing default reload of the website after submit click
    e.preventDefault();
    //loading parameters into variables
    var getParams = {
        type: $('#type').val(),
        status: $('#status').val(),
        urgency: $('#urgency').val()
    };
    //request php answer to get parameters
    $.get('requestHandler.php', getParams, function(data){
      $('#reply').html(data);//print html answer
    });
  });
});
