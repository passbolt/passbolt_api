steal( 
    'jquery/controller'
    , 'lb/core/model/moduleControllerActionDispatcher.js'
)
.then( 
    function($){

        /*
        * @class lb.core.controller.Controller
        * The core class Controller is an extension of the JavascriptMVC controller. This
        * class provides to developpers specific common tools to create application's controllers.
        * @parent index
        * @constructor
        * Creates a new controller
        * @return {lb.core.controller.EventBusController}
        */
        $.Class('passbolt.password.controller.BoxDecorator',
        {
            'func1': function()
            {
                console.log('call func 1');
            }
            
            , 'render': function()
            {
                console.log('box decoration rendering');
                this._super();
            }
        });
    }
);