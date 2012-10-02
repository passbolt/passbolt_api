steal(MAD_ROOT + '/controller/component/gridController.js', 
	'app/controller/component/copyLoginButtonController.js', 
	'app/controller/component/copySecretButtonController.js')
	
.then( function($) {

	/*
	 * @class passbolt.controller.component.PasswordBrowserController
	 * @inherits {mad.controller.component.GridController}
	 * @parent index 
	 * 
	 * Our password grid controller
	 * 
	 * @constructor
	 * Creates a new Password Browser Controller
	 * 
	 * @param {HTMLElement} element the element this instance operates on.
	 * @param {Object} [options] option values for the controller.  These get added to
	 * this.options and merged with defaults static variable 
	 * @return {passbolt.controller.component.PasswordBrowserController}
	 */
	mad.controller.component.GridController.extend('passbolt.controller.component.PasswordBrowserController',
	/** @static */
	{
		'listensTo': ['item_selected', 'item_hovered'],
		'defaults': {}
	},
	/** @prototype */
	{
		/**
		 * The current selected resource id
		 * @type {string}
		 */
		'crtSelectedResourceId': null,
		/**
		 * The current focused resource id
		 * @type {string}
		 */
		'crtFocusedResourceId': null,

		// Constructor like
		'init': function (el, options) {
			// The map to use to make jstree working with our category model
			options.map = new mad.object.Map({
				'id': 'Resource.id',
				'title': 'Resource.name',
				'login': 'Resource.username',
				'url': 'Resource.uri',
				'modified': 'Resource.modified',
				'copyLogin': 'Resource.id',
				'copySecret': 'Resource.id'
			});

			// the columns names
			options.columnNames = ['Row', 'Title', 'Login', 'Url', 'Modified.', '', ''];

			// the columns model
			options.columnModel = [{
				'name': 'row',
				'index': 'row',
				'width': 100,
				'valueAdapter': function (value, item, columnModel, rowNum) {
					return rowNum;
				}
			}, {
				'name': 'title',
				'index': 'title',
				'width': 100
			}, {
				'name': 'login',
				'index': 'login',
				'width': 100
			}, {
				'name': 'url',
				'index': 'url',
				'width': 100
			}, {
				'name': 'modified',
				'index': 'modified',
				'width': 100,
				'valueAdapter': function (value, item, columnModel, rowNum) {
					return moment(value).fromNow();
				}
			}, {
				'name': 'copyLogin',
				'index': 'copyLogin',
				'width': 100,
				'cellAdapter': function (cellElement, cellValue) {
					mad.helper.ComponentHelper.create(
						cellElement, 'inside_replace', passbolt.controller.component.CopyLoginButtonController, {
							'cssClasses': ['js_copy_login_button'],
							'state': 'hidden',
							'value': cellValue
						});
				}
			}, {
				'name': 'copySecret',
				'index': 'copySecret',
				'width': 100,
				'cellAdapter': function (cellElement, cellValue) {
					mad.helper.ComponentHelper.create(
						cellElement, 'inside_replace', passbolt.controller.component.CopySecretButtonController, {
							'cssClasses': ['js_copy_secret_button'],
							'state': 'hidden',
							'value': cellValue
						});
				}
			}];

			this._super(el, options);
		}

		/**
		 * Load the browsers with the given resources
		 * @param {app.model.Resource[]} resources The resources to display
		 * @return {void}
		 */
		,
		'load': function (resources) {
			this._super(resources);
		},

		/* ************************************************************** */
		/* LISTEN TO THE VIEW EVENTS */
		/* ************************************************************** */

		/**
		 * Observe when the mouse leave the component
		 * @param {jQuery} element The source element
		 * @param {Event} event The jQuery event
		 * @return {void}
		 */
		'tbody mouseleave': function (element, evt) {
			if (this.crtFocusedResourceId) {
				mad.eventBus.trigger('resource_unfocused', {
					'id': this.crtFocusedResourceId
				});
				this.crtFocusedResourceId = null;
			}
		},

		/**
		 * Observe when a resource is hovered
		 * @param {jQuery} element The source element
		 * @param {Event} event The jQuery event
		 * @param {string} data The hovered resource id
		 * @return {void}
		 */
		'item_hovered': function (element, evt, itemId) {
			// Display button such as copy to clipboard
			if (this.crtFocusedResourceId) {
				mad.eventBus.trigger('resource_unfocused', {
					'id': this.crtFocusedResourceId
				});
			}

			this.crtFocusedResourceId = itemId;
			mad.eventBus.trigger('resource_focused', {
				'id': this.crtFocusedResourceId
			});
		},

		/**
		 * Observe when an resource is selected
		 * @param {jQuery} element The source element
		 * @param {Event} event The jQuery event
		 * @param {string} data The selected resource id
		 * @return {void}
		 */
		'item_selected': function (element, evt, itemId) {
			// if the resource selected is the same than the previous one unselect
			if (itemId == this.crtSelectedResourceId) {
				this.crtSelectedResourceId = null;
				mad.eventBus.trigger('resource_unselected', {
					'id': itemId
				});
				this.state.setState('ready');
			} else {
				this.crtSelectedResourceId = itemId;
				mad.eventBus.trigger('resource_selected', {
					'id': itemId
				});
				this.setState('resourceSelected');
			}
		},

		/* ************************************************************** */
		/* LISTEN TO THE APP EVENTS */
		/* ************************************************************** */

		/**
		 * Observe when category is selected
		 * @param {jQuery} element The source element
		 * @param {Event} event The jQuery event
		 * @param {string} category The selected Category
		 * @return {void}
		 */
		'{passbolt.eventBus} category_selected': function (element, evt, category) {
			var self = this;

			this.crtCategoryId = category.id;

			// if a resource was selected, inform the system that the resource is no more selected
			if (this.state.is('resourceSelected')) {
				mad.eventBus.trigger('resource_unselected', {
					'id': this.crtSelectedResourceId
				});
			}

			// change the state of the component to loading 
			this.setState('loading');
			// load resources of the selected category
			passbolt.model.Resource.getByCategory({
				'category_id': category.id,
				'recursive': true
			}, function (request, response, resources) {
				// The callback is out of date, an other category has been selected
				if(self.crtCategoryId != request.data.category_id){
					steal.dev.log('(OutOfDate) Cancel passbolt.model.Resource.getByCategory request callback in passbolt.controller.component.PasswordBrowserController');
					return;
				}
				self.load(resources);
				// change the state to ready
				self.setState('ready');
			});
		},

		/**
		 * Observe when the user want to copy the login to the clipboard
		 * @param {jQuery} element The source element
		 * @param {Event} event The jQuery event
		 * @param {string} resourceId The resource id
		 * @return {void}
		 */
		'{passbolt.eventBus} copy_login_clipboard': function (element, evt, resourceId) {
			// let's the workspace manage the copy, usefull here to adapt the password browser
			steal.dev.log('the password browser listen to the event copy_login_clipboard');
		},

		/**
		 * Observe when the user want to copy the secret to the clipboard
		 * @param {jQuery} element The source element
		 * @param {Event} event The jQuery event
		 * @param {string} resourceId The resource id
		 * @return {void}
		 */
		'{passbolt.eventBus} copy_secret_clipboard': function (element, evt, resourceId) {
			// let's the workspace manage the copy, usefull here to adapt the password browser
			steal.dev.log('the password browser listen to the event copy_secret_clipboard');
		},

		/* ************************************************************** */
		/* LISTEN TO THE STATE CHANGES */
		/* ************************************************************** */

		/**
		 * Listen to the change relative to the state Ready.
		 * The ready state is fired automatically after the Component is rendered
		 * @param {boolean} go Enter or leave the state
		 * @return {void}
		 */
		'stateReady': function (go) {
			if (go) {
				this.crtSelectedResourceId = null;
				this.crtFocusedResourceId = null;
			} else {}
		},

		/**
		 * Listen to the change relative to the state ResourceSelected
		 * @param {boolean} go Enter or leave the state
		 * @return {void}
		 */
		'stateResourceSelected': function (go) {
			if (go) {
				console.log('yo');
				this.hideColumn('modified');
				this.hideColumn('copyLogin');
				this.hideColumn('copySecret');
			} else {
				this.showColumn('modified');
				this.showColumn('copyLogin');
				this.showColumn('copySecret');
			}
		}

	});

});