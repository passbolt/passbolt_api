steal( 
    'lb/core/error/error.js'
)
.then( 
    function($){
        lb.core.error.CallInterfaceConstructor = function(message) {
            this.name = "CallInterfaceConstructor";
            this.message = (message || "Do not call interface constructor");
        }
        lb.core.error.CallInterfaceConstructor.prototype = Error.prototype;
    }
);