var config = require('./config.js');
var labels = require('./labels.js');
var lib = require('./formhandler.js');

lib.showform(config.phpOrigin,config.formId,config.replyId,labels.labels,config.selector);
