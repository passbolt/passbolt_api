steal( 
    MAD_ROOT
)
.then(
    'plugin/password/view/template/component/accessRight.ejs'
    , function($){
        
        /*
        * @class passbolt.password.controller.component.AccessRightController
        * @parent index 
        * @constructor
        * Creates a new AccessRightController.
        * @return {passbolt.password.controller.component.AccessRightController}
        */
        mad.controller.ComponentController.extend('passbolt.password.controller.component.AccessRightController', 
        
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
