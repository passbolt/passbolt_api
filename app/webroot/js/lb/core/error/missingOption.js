steal( 
    'lb/core/error/error.js'
)
.then( 
    function($){
        lb.core.error. MissingOption = function(optionName, className, message) {
            this.name = "MissingOption";
            this.message = (message || "The option ("+optionName+") is missing when the class ("+className+") is instantiated");
        }
        lb.core.error.MissingOption.prototype = Error.prototype;
    }
);