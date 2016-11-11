function showform(pathToPhpHandler,formId,replyDivId,labels,selector){
    var promise = jQuery.Deferred();
    var form = generateForm(pathToPhpHandler,selector);
    form.done(function(form){
        //actual form html generation
        var output = "<form>\n<table>\n";
        for(var i = 0; i<form.length;i++){
            if(form[i].options.length == 1){
                //hide one-option selectors
                output += "<input type=\"hidden\" id=\""+form[i].id+
                "\"value=\""+form[i].options[0].value+"\">\n";
            }else{
                output += "<tr><td>\n"+findLabel(form[i].label,labels)+"</td>\n";
                output += "<td><select name=\""+findLabel(form[i].id,labels)+"\" id=\""+form[i].id+"\">\n";
                for(var j = 0; j < form[i].options.length; j++){
                    output += "<option value=\""+form[i].options[j].value+"\">"
                           +  findLabel(form[i].options[j].label,labels)+"</option>\n";
                }
                output += "</select></td></tr>\n";
            }
        }
        output += "</table>\n</form>\n";
        //html output
        jQuery('#'+formId).html(output);
        var selectors = new Array();
        for(var i = 0; i<form.length;i++){
            //select ids of dropdown fields
            selectors.push('#'+form[i].id);
        }
        //binding change event
        jQuery(selectors.join()).change(function (event){
            printTable(pathToPhpHandler,replyDivId,evaluateForm(form),labels,selector);
        });

        //print first reply
        printTable(pathToPhpHandler,replyDivId,evaluateForm(form),labels,selector, promise);
    });

    return promise.promise();
}

function evaluateForm(form){
    //loading form parameters into variables
    formdata = new Object();
    for(var i = 0; i<form.length;i++){
        formdata[form[i].id] = jQuery('#'+form[i].id).val();
    }
    return formdata;
}
function generateForm(pathToPhpHandler,selector){
    var form = new Array();
    var p = jQuery.Deferred(); //for asynchron calculation
    var requestQuery = pathToPhpHandler;
    if(selector){
        var selectorKeys = new Array();
        var selectorValues = new Array();
        requestQuery += "?action=getMissingTags";
        //add all selector elements to requestQuery
        jQuery.each(selector,function(key,value){
            selectorKeys.push(key);
            if(jQuery.isArray(value)){
                var valueslength = value.length;
                var serializedArray = key+":"+valueslength+"{";
                selectorValues[key] = new Array();
                for(var i=0;i<valueslength;i++){
                    serializedArray += "i:"+i+";s:"+value[i].length+":\""+value[i]+"\";";
                    selectorValues[key].push(value[i]);
                }
                serializedArray += "}";
                requestQuery += "&"+key+"="+serializedArray;
            }else{
                requestQuery += "&"+key+"="+value;
                selectorValues[key] = value;
            }
        });
    }else{
            requestQuery += "?action=getTags";
    }
    jQuery.getJSON(requestQuery, function(jsonTagList){

        var form = new Array();
        jQuery.each(jsonTagList,function(jsonId,jsonTag){
            var formTag = {};
            formTag['label']=jsonId;
            formTag['id']=jsonId;

            if(jQuery.inArray(jsonId,selectorKeys)>-1){
                var tagOptions = new Array();
                if(jQuery.isArray(selectorValues[jsonId])){
                    jQuery.each(selectorValues[jsonId],function(key,selectorTagOption){
                            tagOptions.push({value:selectorTagOption, label:selectorTagOption});
                    });
                }else{
                    tagOptions.push({value:selectorValues[jsonId], label:selectorValues[jsonId]});
                }
            }else{
                var tagOptions = new Array({value:'all', label:'all'});
                if(!jQuery.isArray(jsonTag)){
                    jsonTag = [jsonTag];
                }
                jQuery.each(jsonTag,function(jsonTagOptionId,jsonTagOption){
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
function printTable(pathToPhpHandler,replyDivId,data,labels,selector,promise){
    if(!selector){
        var selector = 'no selector';
    }
    //request php json response
    jQuery.getJSON(pathToPhpHandler, data, function(responseList){
        if(responseList.length == 0){
            var output = "<p>"+findLabel('Sorry, no topic matched your criteria.',labels)+"</p>";
            }else{
            //start generating output table
            var output = "<table id=\"deliverable_table_list\" class=\"display\" cellspacing=\"0\">\n";
            var keysPrinted = false;
            jQuery.each(responseList,function(id, documentInstance){
                //add keys as table head
                if(!keysPrinted){
                    output += "<thead>\n<tr>\n";
                    jQuery.each(documentInstance,function(key,value){
                    if(!value){
                        return;
                    }
                    output += "<th>"+findLabel(key,labels)+"</th>";
                    });
                    keysPrinted = true;
                    output += "</tr>\n</thead>\n<tbody>\n";
                }
                output += "<tr>\n";
                jQuery.each(documentInstance,function(key,value){
                    output += "<td>\n";
                    if(key == "download"){
                        output += "<a href="+value+">PDF</a>";
                    }else if(key == "webView"){
                        output += "<a href="+value+" target=\"_blank\">View</a>";
                    }else if(key == "supervisor"){
                        if(value.constructor === Array){
                            for(var i=0; i<value.length;i++){
                                output += "<a href=http://aksw.org/"+value[i]+">"+findLabel(value[i],labels)+"</a></br>";
                            }
                        }else if(value == "n.a."){
                            output += findLabel(value,labels);
                        }else{

                            output += "<a href=http://aksw.org/"+value+">"+findLabel(value,labels)+"</a>";
                        }
                    }else{
                        output += findLabel(value,labels);
                    }
                    output += "</td>\n";
                });
                output += "</tr>\n";
            });
            output += "</tbody>\n</table>\n";
        };
        jQuery('#'+replyDivId).html(output);//print html
        jQuery('#deliverable_table_list').DataTable();
        promise.resolve(replyDivId);
    });
}
function findLabel(needle,haystack){
    if(haystack[needle]){
        return haystack[needle];
    }else{
        return needle;
    }
}
