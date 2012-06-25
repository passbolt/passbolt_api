steal( 
    'lb/core/controller/tabController.js'
    , 'lb/core/controller/appController.js'
    , 'passbolt/password/controller/passwordWorkspaceController.js'
)
.then( 
    function($){
        
        mad.controller.AppController.extend('passbolt.password.controller.AppController', {    
            
            // constructor of the Class
            'init': function(el, options)
            {
                this._super(el, options);
                
                // Add a workspaces container tabs element to the app 
                var $workspacesContainer = $('<div id="app_workspaces_container"></div>').appendTo(this.element);
                var workspacesContainer = new lb.core.controller.TabController($workspacesContainer);
                workspacesContainer.render();
                
                // Add the password workspace component to the workspaces container
                workspacesContainer.addComponent(passbolt.password.controller.PasswordWorkspaceController, {
                    'id':'password-passwordWorkspace-controller',
                    'label':'Password'
                });
            }
            
        });
    }
);
