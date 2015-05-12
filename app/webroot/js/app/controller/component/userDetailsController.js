steal(
	'mad/view/component/tree.js',
	'app/view/component/userDetails.js',
	'app/controller/component/sidebarSectionController.js',
	'app/controller/component/sidebarSections/sidebarSectionDescriptionController.js',
	'app/view/template/component/userDetails.ejs'
).then(function () {

		/*
		 * @class passbolt.controller.UserDetailsController
		 * @inherits mad.controller.component.ComponentController
		 * @parent index
		 *
		 * @constructor
		 * Creates a new User details controller
		 *
		 * @param {HTMLElement} element the element this instance operates on.
		 * @param {Object} [options] option values for the controller.  These get added to
		 * this.options and merged with defaults static variable
		 * @return {passbolt.controller.UserDetailsController}
		 */
		mad.controller.ComponentController.extend('passbolt.controller.component.UserDetailsController', /** @static */ {

			'defaults': {
				'label': 'User Details Controller',
				'viewClass': passbolt.view.component.UserDetails,
				// the resource to bind the component on
				'resource': null,
				// the selected resources, you can pass an existing list as parameter of the constructor to share the same list
				'selectedUsers': new can.Model.List()
			}

		}, /** @prototype */ {

			/**
			 * before start hook.
			 * @return {void}
			 */
			'beforeRender': function () {
				this._super();
				// pass the new resource to the view
				this.setViewData('user', this.options.user);
			},

			/**
			 * Load details of a resource
			 * @param {passbolt.model.User} user The user to load
			 * @return {void}
			 */
			'load': function (user) {
				// push the new resource in the options to be able to listen the resource
				// change in the function name
				this.options.user = user;
				// If the component has not been already started
				if (this.state.is(null)) {
					this.start();
				} else {
					// refresh the component -> afterStart
					this.refresh();
				}
				// Some options changed, make the controller able to listen changes on this new options
				this.on();
			},

			/**
			 * Check if the component is disabled or it is planned to disable it right after
			 * its start
			 * @return {boolean}
			 */
			'isDisabled': function() {
				// if the component is disabled
				if(this.state.is('disabled') ||
					// OR the component is not started AND it will be disabled right after its start
					(this.state.is(null) && 
						(this.options.state == 'disabled' || 
							($.isArray(this.options.state) && this.options.state.indexOf('disabled') != -1)
						)
					)
				) {
					return true;
				}
				return false; 
			},

			/* ************************************************************** */
			/* LISTEN TO THE STATE CHANGES */
			/* ************************************************************** */

			/**
			 * Listen to the change relative to the state Ready
			 * @param {boolean} go Enter or leave the state
			 * @return {void}
			 */
			'stateReady': function(go) {
				if(go) {
					// because by default the component is hidden (see associated ejs)
					this.view.show();
				}
				this._super(go);
			},

			/* ************************************************************** */
			/* LISTEN TO THE VIEW EVENTS */
			/* ************************************************************** */

			/**
			 * Listen when a user clicks on copy public key.
			 * @return {void}
			 */
			' request_copy_publickey': function(el, ev) {
				// Get secret out of Resource object.
				var gpgKey = this.options.selectedUsers[0].Gpgkey.key;
				// Build data.
				var data = {
					name : 'Public key',
					data : gpgKey
				};
				// Request decryption. (delegated to plugin).
				mad.bus.trigger('passbolt.clipboard', data);
			},

			/* ************************************************************** */
			/* LISTEN TO THE MODEL EVENTS */
			/* ************************************************************** */

			/**
			 * Observe when a user is updated
			 * @param {passbolt.model.User} user The updated user
			 * @return {void}
			 */
			'{user} updated': function (user) {
				// The reference of the resource does not change, refresh the component
				if(!this.isDisabled()) {
					this.refresh();
				}
			},

			/* ************************************************************** */
			/* LISTEN TO THE APP EVENTS */
			/* ************************************************************** */

			/**
			 * Observe when the user desire to hide the sidebar
			 * @param {passbolt.model.User} user The updated user
			 * @return {void}
			 */
			'{mad.bus} workspace_showSidebar': function(el, ev, show) {
				// @todo fixed in future canJs.
				if (!this.element) return;

				if (show) {
					if(this.state.is(null)) {
						this.options.state = 'ready';
					} else {
						this.setState('ready');
						// and if one resource has been selected
						if (this.options.selectedUsers.length == 1) {
							this.refresh();
						}
					}
				} else {
					if(this.state.is(null)) {
						this.options.state = ['hidden', 'disabled'];
					} else {
						this.setState(['hidden', 'disabled']);
					}
				}
			},

			/**
			 * Observe when a resource is selected
			 * @param {HTMLElement} el The element the event occured on
			 * @param {HTMLEvent} ev The event which occured
			 * @param {passbolt.model.User} user The selected user
			 * @return {void}
			 */
			'{selectedUsers} add': function (el, ev, user) {
				// if more than one resource selected, or no resource selected
				if (this.options.selectedUsers.length == 0 || this.options.selectedUsers.length > 1) {
					this.options.user = null;
					if(!this.isDisabled()) {
						this.setState('hidden');
					}

				// else if only 1 resource selected show the details
				} else {
					// load the only one resource
					this.options.user = this.options.selectedUsers[0];
					if(!this.isDisabled()) {
						this.load(this.options.user);
						this.setState('ready');
					}
				}
			},

			/**
			 * Observe when a user is unselected
			 * @param {HTMLElement} el The element the event occured on
			 * @param {HTMLEvent} ev The event which occured
			 * @param {passbolt.model.User} user The unselected user
			 * @return {void}
			 */
			'{selectedUsers} remove': function (el, ev, user) {
				// if more than one user selected, or no user selected
				if (this.options.selectedUsers.length == 0 || this.options.selectedUsers.length > 1) {
					this.options.user = null;
					// if the component is not disabled, hide it
					// and the component has already been started
					if(!this.isDisabled() && !this.state.is(null)) {
						this.setState('hidden');
					}

					// else if only 1 user selected show the details
				} else {
					// load the only one user
					this.options.user = this.options.selectedUsers[0];
					// if the component is not disabled, load the user and display the component
					if(!this.isDisabled()) {
						this.load(this.options.user);
						this.setState('ready');
					}
				}
			}
		});
	});
