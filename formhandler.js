//wait for the document to be loaded
$(document).ready(function(){

    printList(evaluateForm());
    $('#submit').click(function (event){
        event.preventDefault();
        printList(evaluateForm());
    });
});

function printList(data){
    //request php json response
    $.getJSON('requestHandler.php', data, function(responseList){
        if(responseList.length == 0){
            var output = "<p>Sorry, no topic matched your criteria.</p>";
            }else{
            //start generating output table
            var output = "<table>\n";
            var keysPrinted = false;
            $.each(responseList,function(id, documentInstance){
                output += "<tr>";
                //add keys as first table line
                if(!keysPrinted){
                    $.each(documentInstance,function(key,value){
                    output += "<th>"+key+"</th>";
                    });
                    keysPrinted = true;
                    output += "</tr>\n<tr>";
                }
                $.each(documentInstance,function(key,value){
                    if(!value){
                        return;
                    }
                    output += "<td>";
                    if(key == "Download"){
                        output += "<a href=\">"+value+"\">Link</a>";
                    }else{
                        output += value;
                    }
                    output += "</td>";
                });
                output += "</tr>\n";
            });
            output += "</table>\n";
        };
        $('#reply').html(output);//print html
    });
}

function evaluateForm(){
    //loading parameters into variables
    return {
    type: $('#type').val(),
    status: $('#status').val(),
    urgency: $('#urgency').val()
    };
}
