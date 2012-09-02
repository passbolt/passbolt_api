steal(
	MAD_ROOT + '/controller/appController.js', 
	'app/plugin/sample/controller/component/createBrolFormController.js', 
	'app/plugin/sample/model/brol.js', 
	'app/plugin/sample/controller/ajaxSampleWorkspaceController.js'
	
).then(function ($) {

//		brol = new passbolt.plugin.sample.model.Brol();
	mad.controller.AppController.extend('passbolt.sample.controller.AppController', {
		'defaults': {
			'brol': new passbolt.plugin.sample.model.Brol(),
			'model': passbolt.plugin.sample.model.Brol
		}
	}, {
		// constructor of the Class
		'init': function (el, options) {
			this._super(el, options);
			this.render();

			// Add a workspaces container tabs element to the app 
			var workspaces = new mad.controller.component.TabController($('#js_workspaces_container'));
			workspaces.render();

			// Add the password workspace component to the workspaces container
			// @todo addComponent is our factory, maybe more proper to do
			workspaces.addComponent(passbolt.sample.controller.AjaxSampleWorkspaceController, {
				'id': 'js_sample_ajax_controller',
				'label': 'Ajax'
			});
			workspaces.enableTab('js_sample_ajax_controller');
			return;

			//				this.options.brol.attr('name', 'update the attr name of the brol instance');
			//				
			//				return;
			/* *************************************************************
			 * SIMPLE BUTTON
			 ************************************************************ */
			// create a simple button
			var mySimpleButton = new mad.controller.component.ButtonController($('#js_simple_button', this.element), {
				'events': { // Mapping view event to app event
					'click': function (elt, value) {
						console.log('the simple button as been clicked', elt, value);
						//mad.eventBus.trigger('simple_button_click_event');
					}
				}
			});
			mySimpleButton.setValue('I am a simple button');
			mySimpleButton.render();

			/* *************************************************************
			 * OPTIONS CONTAINER HIDDEN BY DEFAULT
			 ************************************************************ */
			var myHiddenContainer = new mad.controller.component.ContainerController($('#js_hidden_options', this.element), {
				'templateUri': '//' + MAD_ROOT + '/view/template/component/container/vertical.ejs',
				'state': 'hidden'
			});
			myHiddenContainer.render();

			// add a component options 1 to display text info
			var myOptionComponent1 = myHiddenContainer.addComponent(mad.controller.ComponentController, {
				'id': 'js_options_1',
				'templateUri': '//app/plugin/sample/view/template/component/option.ejs'
			});
			myOptionComponent1.setViewData('value', 'Value of the option 1');
			myOptionComponent1.render();

			// add a component options 2 to display text info
			var myOptionComponent2 = myHiddenContainer.addComponent(mad.controller.ComponentController, {
				'id': 'js_options_2',
				'templateUri': '//app/plugin/sample/view/template/component/option.ejs'
			});
			myOptionComponent2.setViewData('value', 'Value of the option 2');
			myOptionComponent2.render();

			// create a button to display the optional box
			var myShowOptionsButton = new mad.controller.component.ButtonController($('#js_show_hidden_options_button', this.element), {
				'events': {
					'click': function (elt, value) {
						if(myHiddenContainer.state.is('hidden')) {
							//								mySimpleButton.set('label', 'Hide options').refresh();
							myHiddenContainer.setState('ready');
						} else {
							//								mySimpleButton.set('label', 'Show options').refresh();
							myHiddenContainer.setState('hidden');
						}
					}
				}
			});
			myShowOptionsButton.render();

			/* *************************************************************
			 * OPEN A COMPONENT IN A POPUP
			 ************************************************************ */

			// create a button to display the optional box
			var myShowPopupButton = new mad.controller.component.ButtonController($('#js_show_popup_button', this.element), {
				'events': {
					'click': function (elt, value) {
						var myPopupComponent = mad.controller.component.PopupController.get({
							'label': 'My demonstration popup'
						}).render().addComponent(mad.controller.ComponentController, {
							'id': 'js_options_3',
							'templateUri': '//app/plugin/sample/view/template/component/option.ejs'
						}, 'js_popup_content').setViewData('value', 'popup options value').render();
					}
				}
			});
			myShowPopupButton.render();

			/* *************************************************************
			 * OPEN A FORM CONTROLLER IN A POPUP
			 ************************************************************ */

			// create a button to display the optional box
			var myCreateBrolModelPopupButton = new mad.controller.component.ButtonController($('#js_create_model_popup_button', this.element), {
				'label': 'Show popup',
				'events': {
					'click': function (elt, value) {
						var myCreateModelPopupComponent = mad.controller.component.PopupController.get({
							'label': 'Create new instance of the model brol'
						}).render().addComponent(passbolt.sample.controller.component.CreateBrolFormController, {
							'id': 'js_create_brol_form'
						}, 'js_popup_content');
					}
				}
			});
			myCreateBrolModelPopupButton.render();

			return;

			// Add a notification controller
			var menuCtl = new passbolt.controller.component.MenuController($('#js_menu_controller'));
			menuCtl.render();

			// Add a notification controller
			var notifCtl = passbolt.controller.component.NotificationController.singleton($('#js_notif_controller'));
			//                notifContainer.render();

			// Add a workspaces container tabs element to the app 
			var workspaces = new mad.controller.component.TabController($('#js_workspaces_container'));
			workspaces.render();

			// Add the password workspace component to the workspaces container
			// @todo addComponent is our factory, maybe more proper to do
			workspaces.addComponent(passbolt.controller.PasswordWorkspaceController, {
				'id': 'js_passbolt_passwordWorkspace_controller',
				'label': 'password'
			});
		},

		/**
		 * Called when the passbolt application is ready
		 * @return {void}
		 */
		'ready': function () {
			this._super();

			return;

			// @dev BEGIN
			var database = new passbolt.model.Database({
				id: '501cfdec-b4dc-4aa3-91b3-0ea56511fe85'
			});
			// Create local resources for fixtures
			var categories = passbolt.model.Category.get({
				id: database.id,
				children: true
			}, function (categories) {
				passbolt.controller.ResourceController.createFixturedData(categories[0]);
			});

			// simulate database selected
			mad.eventBus.trigger('app_ready');
			return;
			// test the exception catcher
			throw new mad.error.Error('Simulated exception to demonstrate the error handler system, and the notification system');

			// @dev END
		}

	});
});