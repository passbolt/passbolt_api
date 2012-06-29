steal( 
    MAD_ROOT+'/controller/componentController.js'
)
.then(
//    'plugin/activity/view/template/passwordActivity.ejs',
    function($){
        
        /*
        * @class passbolt.passbolt.controller.PasswordActivityController
        * @parent index 
        * @constructor
        * Creates a new PasswordActivityController.
        * @return {passbolt.password.controller.PasswordActivityController}
        */
        mad.controller.ComponentController.extend('passbolt.activity.controller.PasswordActivityController', 
        
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
            
            , '{mad.eventBus} passbolt_password_selected': function(element, evt, data)
            {
                mad.net.Ajax.singleton().request({
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
