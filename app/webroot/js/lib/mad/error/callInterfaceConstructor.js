steal('jquery/lang/string')
.then( 
    function($){
        $.String.getObject('mad.error', null, true);
        mad.error.CallInterfaceConstructor = function(message) {
            this.name = "CallInterfaceConstructor";
            this.message = (message || "This constructor is an interface constructor");
        }
        mad.error.CallInterfaceConstructor.prototype = Error.prototype;
    }
);