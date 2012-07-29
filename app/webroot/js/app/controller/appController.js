steal( 
    MAD_ROOT+'/controller/appController.js',
    'app/controller/component/notificationController.js',
    'plugin/password/controller/passwordWorkspaceController.js',
	
	// @dev till database is not implemented, for the test the appcontroller will release an event to select a database
    'plugin/password/model/database.js'
)
.then( 
    function($){
        
        mad.controller.AppController.extend('passbolt.controller.AppController', {
            
            // constructor of the Class
            'init': function(el, options)
            {
                this._super(el, options);
                this.render();
				
				        // Add a notification controller
				        var notifContainer = passbolt.controller.component.NotificationController.singleton($('#js-notif-controller'));
                //notifContainer.render();
				
                // Add a workspaces container tabs element to the app 
                var workspacesContainer = new mad.controller.component.TabController($('#js-wk-controller'));
                workspacesContainer.render();
                
                // Add the password workspace component to the workspaces container
				        // @todo addComponent is our factory, maybe more proper to do
                workspacesContainer.addComponent(passbolt.password.controller.PasswordWorkspaceController, {
                    'id':'password-passwordWorkspace-controller',
                    'label':'Password Workspace Controller'
                });
            }
			
			    /**
			     * Called when the passbolt application is ready
			     * @return {void}
			     */
			    , 'ready': function()
			    {
				    this._super();
				    // @dev Used for the developpement
				    var database = new password.model.Database({id: '500c0ead-7c68-4fbd-b226-7d9fcbdd56cb'});
				    mad.eventBus.trigger('passbolt_database_selected', [database]);
				    // test the exception catcher
				    throw new mad.error.Error('my Error message');
			    }
                
        });
    }
);
