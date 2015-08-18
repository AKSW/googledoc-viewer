function showform(formId,replyDivId,form){
    
    var formhtml = "<form>\n";
    for(var i = 0; i<form.length;i++){
        formhtml += "<label for=\""+form[i].id+"\">"+form[i].label+"</label>\n";
        formhtml += "<select name=\""+form[i].id+"\" id=\""+form[i].id+"\">\n";
        for(var j = 0; j < form[i].options.length; j++){
            formhtml += "<option value=\""+form[i].options[j].value+"\">"+form[i].options[j].label+"</option>\n";
        }
        formhtml += "</select>\n";
    }
    formhtml += "</form>\n";
    $('#'+formId).html(formhtml);
    var selectors = new Array();
    for(var i = 0; i<form.length;i++){
        selectors.push('#'+form[i].id);
    }
    $(selectors.join()).change(function (event){
        printList(replyDivId,evaluateForm(form));
    });
    printList(replyDivId,evaluateForm(form));
}
function printList(replyDivId,data){
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
                        output += "<a href="+value+">Link</a>";
                    }else{
                        output += value;
                    }
                    output += "</td>";
                });
                output += "</tr>\n";
            });
            output += "</table>\n";
        };
        $('#'+replyDivId).html(output);//print html
    });
}

function evaluateForm(form){
    //loading parameters into variables
    formdata = new Object();
    for(var i = 0; i<form.length;i++){
        formdata[form[i].id] = $('#'+form[i].id).val();
    }
    return formdata;
}
