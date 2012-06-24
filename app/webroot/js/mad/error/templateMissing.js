steal('jquery/lang/string')
.then( 
    function($){
        $.String.getObject('mad.error', null, true);
        mad.error.TemplateMissing = function(message) {
            this.name = "TemplateMissing";
            this.message = (message || "Template missing");
        }
        mad.error.TemplateMissing.prototype = Error.prototype;
    }
);