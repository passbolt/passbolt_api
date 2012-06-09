steal( 
    'lb/core/error/error.js'
)
.then( 
    function($){
        lb.core.error.TemplateMissing = function(message) {
            this.name = "TemplateMissing";
            this.message = (message || "Template missing");
        }
        lb.core.error.TemplateMissing.prototype = Error.prototype;
    }
);