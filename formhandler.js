function showform(pathToPhpHandler,formId,replyDivId,selector){
    var form = generateForm(phpOrigin,selector);
    form.done(function(form){
        //actual form html generation
        var output = "<form>\n<table>\n";
        for(var i = 0; i<form.length;i++){
            if(form[i].options.length == 1){
                //hide one-option selectors
                output += "<input type=\"hidden\" id=\""+form[i].id+
                "\"value=\""+form[i].options[0].value+"\">\n";
            }else{
                output += "<tr><td>\n"+form[i].label+"</td>\n";
                output += "<td><select name=\""+form[i].id+"\" id=\""+form[i].id+"\">\n";
                for(var j = 0; j < form[i].options.length; j++){
                    output += "<option value=\""+form[i].options[j].value+"\">"+form[i].options[j].label+"</option>\n";
                }       
                output += "</select></td></tr>\n";
            }
        }
        output += "</table>\n</form>\n";
        //html output
        $('#'+formId).html(output);
        var selectors = new Array();
        for(var i = 0; i<form.length;i++){
            //select ids of dropdown fields
            selectors.push('#'+form[i].id);
        }
        //binding change event
        $(selectors.join()).change(function (event){
            printList(pathToPhpHandler,replyDivId,evaluateForm(form),selector);
        });

        //print first reply
        printList(pathToPhpHandler,replyDivId,evaluateForm(form),selector);
    });
}

function evaluateForm(form){
    //loading form parameters into variables
    formdata = new Object();
    for(var i = 0; i<form.length;i++){
        formdata[form[i].id] = $('#'+form[i].id).val();
    }
    return formdata;
}
function generateForm(pathToPhpHandler,selector){
    var form = new Array();
    var p = $.Deferred(); //for asynchron calculation
    var requestQuery = pathToPhpHandler;
    if(selector){
        requestQuery += "?action=getMissingTags";
        var selectorKey;
        var selectorValue;
        //add all selector elements to requestQuery
        $.each(selector,function(key,value){
            requestQuery += "&"+key+"="+value;
            selectorKey = key;
            selectorValue = value;
        });
    }else{
            requestQuery += "?action=getTags";
    }
    $.getJSON(requestQuery, function(jsonTagList){
        var form = new Array();
        $.each(jsonTagList,function(jsonId,jsonTag){
            var formTag = {};
            formTag['label']=jsonId;
            formTag['id']=jsonId;
            if(jsonId == selectorKey){
                var tagOptions = new Array({value:selectorValue});
            }else{
                var tagOptions = new Array({value:'all', label:'all'});
                $.each(jsonTag,function(jsonTagOptionId,jsonTagOption){
                        tagOptions.push({value:jsonTagOption, label:jsonTagOption});
                });
            }
            formTag['options']=tagOptions;
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
function printList(pathToPhpHandler,replyDivId,data,selector){
    if(!selector){
        var selector = 'no selector';
    }
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
                    if(!value || selector[key.toLowerCase()] != undefined){
                        return;
                    }
                    output += "<th>"+key+"</th>";
                    });
                    keysPrinted = true;
                    output += "</tr>\n<tr>";
                }
                $.each(documentInstance,function(key,value){
                    if(!value || selector[key.toLowerCase()] != undefined){
                        return;
                    }
                    output += "<td>";
                    if(key == "Download"){
                        output += "<a href="+value+">PDF</a>";
                    }else if(key == "Supervisor"){
                        if(value.constructor === Array){
                            for(var i=0; i<value.length;i++){
                                output += "<a href=http://aksw.org/"+value[i]+">"+value[i]+"</a></br>";
                            }
                        }else if(value == "n.a."){
                            output += value;
                        }else{

                            output += "<a href=http://aksw.org/"+value+">"+value+"</a>";
                        }
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
