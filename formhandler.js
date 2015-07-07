//wait for the document to be loaded
$(document).ready(function(){
  printList(evaluateForm());
  $('#submit').click(function (event){
    event.preventDefault();
    printList(evaluateForm());
    });
});

function printList(data){
    //request php answer to get parameters
    $.get('requestHandler.php', data, function(data){
    $('#reply').html(data);//print html answer
    });
};

function evaluateForm(){
    //loading parameters into variables
    return {
    type: $('#type').val(),
    status: $('#status').val(),
    urgency: $('#urgency').val()
    };
}
