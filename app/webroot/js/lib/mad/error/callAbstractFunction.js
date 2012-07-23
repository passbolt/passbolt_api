steal('jquery/lang/string')
.then( 
    function($){
        $.String.getObject('mad.error', null, true);
        mad.error.CallAbstractFunction = function(message) {
            this.name = "CallAbstractFunction";
            this.message = (message || "This function is abstract");
        }
        mad.error.CallAbstractFunction.prototype = new Error();
    }
);