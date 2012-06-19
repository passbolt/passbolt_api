steal( 
    'jquery/view/ejs',
    'lb/core/controller/componentController.js'
)
.then(
    'passbolt/password/view/template/accessRight.ejs'
    , function($){
        
        /*
        * @class passbolt.passbolt.controller.AccessRightController
        * @parent index 
        * @constructor
        * Creates a new AccessRightController.
        * @return {passbolt.password.controller.AccessRightController}
        */
        lb.core.controller.ComponentController.extend('passbolt.password.controller.AccessRightController', 
        
        /** @static */
        {},
        
        /** @prototype */
        {
            
            'init' : function(el, options)
            {
                this._super();
                this.render();
            }
            
            , 'selectPassword': function(passwordId)
            {
                console.log('AccessRightController : password selected '+passwordId);
            }
            
            , '{lb.eventBus} passbolt_password_selected': function(element, evt, data)
            {
                this.selectPassword(data.password_id);
            }
            
        });
        
    }
);
