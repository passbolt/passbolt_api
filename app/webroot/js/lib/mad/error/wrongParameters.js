steal('jquery/lang/string')
.then( 
    function($){
        $.String.getObject('mad.error', null, true);
        mad.error.WrongParameters = function(message) {
            this.name = "WrongParameters";
            this.message = (message || "Wrong parameters");
        }
        mad.error.WrongParameters.prototype = new Error();
    }
);