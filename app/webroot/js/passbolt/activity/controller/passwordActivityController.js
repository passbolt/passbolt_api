steal( 
    'jquery/view/ejs',
    'lb/core/controller/componentController.js'
)
.then(
    'passbolt/activity/view/template/passwordActivity.ejs'
    , function($){
        
        /*
        * @class passbolt.passbolt.controller.PasswordActivityController
        * @parent index 
        * @constructor
        * Creates a new PasswordActivityController.
        * @return {passbolt.password.controller.PasswordActivityController}
        */
        lb.core.controller.ComponentController.extend('passbolt.activity.controller.PasswordActivityController', 
        
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
                console.log('PasswordActivityController : password selected '+passwordId);
            }
            
            , '{lb.eventBus} passbolt_password_selected': function(element, evt, data)
            {
                lb.ajaxWrapper.request({
                    'url':'http://getacountdown.local/ajax/action2'
                    , 'data': {
                        'key1': 'value1',
                        'key2': 'value2',
                        'key3': 'value3'
                    }
                    , 'success': function(srvData){
                        console.log("password activity details controller callback "+srvData);
                    }
                }, true);
                this.selectPassword(data.password_id);
            }
            
        });
        
    }
);
