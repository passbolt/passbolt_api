steal( 
    MAD_ROOT+'/controller/appController.js',
    'app/controller/categoryController.js',
    'app/controller/resourceController.js',
    'app/controller/passwordWorkspaceController.js',
    'app/controller/component/menuController.js',
    'app/controller/component/notificationController.js',
	
	// @dev till database is not implemented, for the test the appcontroller will release an event to select a database
    'app/model/database.js',
    'app/model/category.js',
    'app/model/resource.js',
	
    'app/view/app.js'
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
				var menuCtl = new passbolt.controller.component.MenuController($('#js-menu-controller'));
                menuCtl.render();
				
				// Add a notification controller
				var notifCtl = passbolt.controller.component.NotificationController.singleton($('#js-notif-controller'));
//                notifContainer.render();
				
                // Add a workspaces container tabs element to the app 
                var workspaces = new mad.controller.component.TabController($('#js-workspaces-controller'));
                workspaces.render();
                
                // Add the password workspace component to the workspaces container
				// @todo addComponent is our factory, maybe more proper to do
                workspaces.addComponent(passbolt.controller.PasswordWorkspaceController, {
                    'id':'passbolt-passwordWorkspace-controller',
                    'label':'password'
                });
            },
			
			/**
			 * Called when the passbolt application is ready
			 * @return {void}
			 */
			'ready': function()
			{
				this._super();
				
				// @dev BEGIN
				
				var database = new passbolt.model.Database({id: '500c0ead-7c68-4fbd-b226-7d9fcbdd56cb'});
				// Create local resources for fixtures
				var categories = passbolt.model.Category.get({id:database.id, children:true}, function(categories){
					passbolt.controller.ResourceController.createFixturedData(categories[0]);
				});
				
				// simulate database selected
				mad.eventBus.trigger('passbolt_database_selected', [database]);
				return;
				// test the exception catcher
				throw new mad.error.Error('Simulated exception to demonstrate the error handler system, and the notification system');
				
				// @dev END
			}
            
        });
    }
);
