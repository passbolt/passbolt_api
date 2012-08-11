steal( 
    MAD_ROOT+'/bootstrap/extensionBootstrap.js'
    , 'plugin/activity/controller/activityWorkspaceController.js'
    , 'plugin/activity/controller/component/passwordActivityController.js'
)
.then( 
    function($){
        
        /*
        * @class passbolt.activity.bootstrap.Bootstrap
        * Bootstrap of the passbolt plugin activity
        * @parent index
        * @constructor
        * Init the plugin activity bootstrap
        */
        mad.bootstrap.BootstrapInterface.extend('passbolt.activity.bootstrap.Bootstrap', {},
        {
            'init': function()
            {
                var wksContainer = passbolt.app.getComponent('js-workspaces-controller');
				var passwordWk = passbolt.app.getComponent('password-passwordWorkspace-controller');
				var passwordWkSecondSide = passwordWk.getChildController('.workspace-sidebar-second');
                
                // Add the Password Information component
                var passwordActivityComponent = passwordWkSecondSide.addComponent(passbolt.activity.controller.PasswordActivityController, {
                    'id':'passbolt_activity_password_activity_controller'
                });
				passwordActivityComponent
					.decorate('mad.helper.component.BoxDecorator')
					.render();
					
                // Add the Activity workspace component to the application
                var activityWorkspaceController = wksContainer.addComponent(passbolt.activity.controller.ActivityWorkspaceController, {
                    'id':'activity-activityWorkspace-controller',
                    'label':'activity'
                });
            }
        });
    }
);
