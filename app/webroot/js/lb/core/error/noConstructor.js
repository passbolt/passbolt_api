steal( 
    'lb/core/error/error.js'
)
.then( 
    function($){
        lb.core.error. NoConstructor = function(message) {
            this.name = "NoConstructor";
            this.message = (message || "This class has no constructor");
        }
        lb.core.error.NoConstructor.prototype = Error.prototype;
    }
);