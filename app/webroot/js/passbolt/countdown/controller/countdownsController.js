steal( 
    'jquery/controller'
    , 'jquery/plugin/jquery.timepicker.js'
    , 'lb/core/controller/controller.js'
    , 'lb/core/model/pageDispatcher.js'
    , 'passbolt/countdown/controller/createCountdownComponent.js'
)
.then( 
    function($){
        
        lb.core.controller.Controller.extend('passbolt.countdown.controller.CountdownsController', {
//            'getDispatcher': function()
//            {
//                return lb.core.model.PageDispatcher;
//            }
        }
        , {    
            'init' : function()
            {
                this._super();
            }
            
            , 'destroy': function(){
                this._super();
            }
            
            , 'index' : function()
            {
                steal.dev.log('exec index 1 action of the countdowns controller');
                new passbolt.countdown.controller.CreateCountdownComponent($('#CountdownIndexForm'));
            }
            
            , 'index2' : function()
            {
                steal.dev.log('exec index 2 action of the countdowns controller');
            }
        });
        
    }
);