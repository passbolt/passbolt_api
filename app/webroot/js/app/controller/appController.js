steal( 
    MAD_ROOT,
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
                
                // Add a workspaces container tabs element to the app 
                var $workspacesContainer = $('<div id="app_workspaces_container"></div>').appendTo(this.element);
                var workspacesContainer = new mad.controller.component.TabController($workspacesContainer);
                workspacesContainer.render();
                
                // Add the password workspace component to the workspaces container
                workspacesContainer.addComponent(passbolt.password.controller.PasswordWorkspaceController, {
                    'id':'password-passwordWorkspace-controller',
                    'label':'Password'
                });
            }
			
			/**
			 * Called when the passbolt application is ready
			 * @return {void}
			 */
			, 'ready': function()
			{
				this._super();
				// @todo Used for the developpement
				var database = new password.model.Database({id: '4ff6eb28-e200-4e3f-8251-0a9acbdd56cb'});
				mad.eventBus.trigger('passbolt_database_selected', [database]);
			}
            
        });
    }
);
