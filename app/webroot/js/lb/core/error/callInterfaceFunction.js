steal( 
    'lb/core/error/error.js'
)
.then( 
    function($){
        lb.core.error.CallInterfaceFunction = function(message) {
            this.name = "CallInterfaceFunction";
            this.message = (message || "Do not call interface function");
        }
        lb.core.error.CallInterfaceFunction.prototype = Error.prototype;
    }
);