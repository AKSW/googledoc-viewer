//TODO Filter by Supervisor bugged
//TODO invisible html formular option
//TODO wenn nur zwei optionen, dann unsichtbar im html
//TODO Beschreibung abschicken
function showform(pathToPhpHandler,formId,replyDivId,form){
    if(!form){
      var form = generateForm(phpOrigin);
      form.done(function(form){
        showform(pathToPhpHandler,formId,replyId,form);
      });
    }else{
        var output = "<form>\n";
        for(var i = 0; i<form.length;i++){
            if(form[i].options.length == 1){
                output += "<input type=\"hidden\" id=\""+form[i].id+
                "\"value=\""+form[i].options[0].value+"\">\n";
            }else{
                output += "<label for=\""+form[i].id+"\">"+form[i].label+"</label>\n";
                output += "<select name=\""+form[i].id+"\" id=\""+form[i].id+"\">\n";
                for(var j = 0; j < form[i].options.length; j++){
                    output += "<option value=\""+form[i].options[j].value+"\">"+form[i].options[j].label+"</option>\n";
                }       
                output += "</select>\n";
            }
        }
        output += "</form>\n";
        $('#'+formId).html(output);
        var selectors = new Array();
        for(var i = 0; i<form.length;i++){
            //select ids of dropdown fields
            selectors.push('#'+form[i].id);
        }
        //bind change event
        $(selectors.join()).change(function (event){
            printList(pathToPhpHandler,replyDivId,evaluateForm(form));
        });
        //print first reply
        printList(pathToPhpHandler,replyDivId,evaluateForm(form));
    }
}

function evaluateForm(form){
    //loading form parameters into variables
    formdata = new Object();
    for(var i = 0; i<form.length;i++){
        formdata[form[i].id] = $('#'+form[i].id).val();
    }

    return formdata;
}
function generateForm(pathToPhpHandler){
    var form = new Array();
    var p = $.Deferred(); //for asynchron calculation
    $.getJSON(pathToPhpHandler+"?action=getTags", function(jsonTagList){
        var form = new Array();
        $.each(jsonTagList,function(jsonId,jsonTag){
            var formTag = {};
            formTag['label']=jsonId;
            formTag['id']=jsonId;
            $.each(jsonTag,function(jsonTagOptionId,jsonTagOption){
                var tagOptions = new Array({value:'all', label:'all'});
                tagOptions.push({value:jsonTagOption, label:jsonTagOption});
                formTag['options']=tagOptions;
            });
            form.push(formTag);
        });
        //console.log(JSON.stringify(form,null,2));
        p.resolve(form); 
    });
    return p.promise();
}


/**
 * generate reply html tabular, print to replyDiv
 */
function printList(pathToPhpHandler,replyDivId,data){
    //request php json response
    $.getJSON(pathToPhpHandler, data, function(responseList){
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
                        output += "<a href="+value+">PDF</a>";
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
