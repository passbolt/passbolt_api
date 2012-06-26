steal( 
    MAD_ROOT
    , 'plugin/password/controller/passwordWorkspaceController.js'
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
            
        });
    }
);
