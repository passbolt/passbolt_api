steal( 
    'jquery/controller'
    , 'lb/core/controller/controller.js'
    , 'lb/core/model/pageDispatcher.js'
)
.then( 
    function($){
        
        lb.core.controller.Controller.extend('lb.user.controller.UsersController', {
            'getDispatcher': function()
            {
                return lb.core.model.PageDispatcher;
            }
        }
        , {    
            'init' : function()
            {
                console.log("users controller initialized");
            }
            
            , 'destroy': function(){
                console.log("user controller destroyed");
                this._super();
            }
            
            , 'index' : function()
            {
                console.log('exec index action of the users controller');
            }
            
            , 'index2' : function()
            {
                console.log('exec index 2 action of the users controller');
            }
        });
        
    }
);