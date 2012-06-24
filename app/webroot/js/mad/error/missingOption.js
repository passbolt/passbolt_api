steal('jquery/lang/string')
.then( 
    function($){
        $.String.getObject('mad.error', null, true);
        mad.error.MissingOption = function(optionName, className, message) {
            this.name = "MissingOption";
            this.message = (message || "The option ("+optionName+") is missing when the class ("+className+") is instantiated");
        }
        mad.error.MissingOption.prototype = Error.prototype;
    }
);