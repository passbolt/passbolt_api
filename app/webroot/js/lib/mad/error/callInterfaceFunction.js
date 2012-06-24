steal('jquery/lang/string')
.then( 
    function($){
        $.String.getObject('mad.error', null, true);
        mad.error.CallInterfaceFunction = function(message) {
            this.name = "CallInterfaceFunction";
            this.message = (message || "This function is an interface function");
        }
        mad.error.CallInterfaceFunction.prototype = Error.prototype;
    }
);