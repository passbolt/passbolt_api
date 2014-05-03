steal(
	'mad/controller/componentController.js',
	'app/view/component/loadingBar.js'
).then(function () {

	/*
	 * @class passbolt.controller.component.LoadingBarController
	 * @inherits mad.controller.ComponentController
	 * @parent index
	 * @see {mad.view.View}
	 * 
	 * @constructor
	 * The Loading Bar class Controller will be used to display to users
	 * feedbacks about loading processus.
	 * 
	 * @param {HTMLElement} element the element this instance operates on.
	 * @param {Object} [options] option values for the controller.  These get added to
	 * this.options and merged with defaults static variable 
	 * @return {passbolt.controller.component.LoadingBarController}
	 */
	mad.controller.ComponentController.extend('passbolt.controller.component.LoadingBarController', /** @static */ {

		'defaults': {
			'label': 'Loading Bar Controller',
			'viewClass': passbolt.view.component.LoadingBar,
			'templateBased': false,
			'currentProcs': 0,
			'previousProcs': 0,
			'loadingPercent': 0,
			'postponedUpdate': false
		}

	}, /** @prototype */ {

		/**
		 * Start a loading.
		 */
		'loading_start': function (callback) {
			this.view.update(20, true, function() {
				if (callback) {
					callback();
				}
			});
		},

		/**
		 * Complete a loading.
		 */
		'loading_complete': function (callback) {
			var self = this;
			this.view.update(100, true, function () {
				self.view.update(0, false);
				if (callback) {
					callback();
				}
			});
		},

		/**
		 * Listen to the change relative to the state Loading
		 * @param {boolean} go Enter or leave the state
		 * @return {void}
		 */
		'stateLoading': function (go) {},

		/*
		 1p update
		 50 0L 1P
		 complete
		 100 1L 0P

		 2p update
		 50 0L 1P
		 75 1L 2P
		 complete
		 87.5 2L 1P
		 100 2L 0P

		 3p update
		 50 0L 1P
		 75 1L 2P
		 81.25 2L 3P
		 complete
		 87.5 3L 2P
		 93.75 3L 1P
		 100 3L 0P

		 RANDOM CASE
		 50 0L 1P
		 75 1L 2P
		 complete 1
		 87.5 2L 1P
		 load 1
		 91,66667 2L 2P
		 complete
		 95.833334 1L 1P
		 100 0 0

		 */

		/**
		 * Refresh the loading bar
		 */
		'update': function(postponedUpdate) {
			var self = this;
			postponedUpdate = typeof postponedUpdate != 'undefined' ? postponedUpdate : false;

			// If it's a delegated update.
			if (postponedUpdate) {
				this.options.postponedUpdate = false;
			}

			// If the loading bar is currently updating.
			if (this.state.is('updating')) {
				// If an update has not already been postponed, postpone one.
				if (!this.options.postponedUpdate) {
					this.options.postponedUpdate = true;
					setTimeout(function() {
						self.update(true);
					}, 100);
				}
				return;
			}

			// Lock the component.
			this.state.addState('updating');

			// If no current process are runing. The loading is complete.
			if(!this.options.currentProcs) {
				// If the loading bar is loading, complete it.
				if (this.state.is('loading')) {
					this.loading_complete(function() {
						// Reset class' variables.
						self.options.loadingPercent = 0;
						self.options.previousProcs = 0;
						// Release the component to its initial state.
						self.state.setState('ready');
					});
				}
			} else {
				if (!this.state.is('loading')) {
					this.state.addState('loading');
				}

				var diffProcs = this.options.currentProcs - this.options.previousProcs;
				// No more nor less processus.
				if (diffProcs == 0) {
					this.state.removeState('updating');
				} else if (diffProcs > 0) {
					// New processus are loading.
					this.options.loadingPercent = this.options.loadingPercent + ((100 - this.options.loadingPercent) / (diffProcs * 2));
					this.view.update(this.options.loadingPercent, true, function() {
						// release the lock on the component.
						self.state.removeState('updating');
					});
				} else {
					// Processus complete.
					this.options.loadingPercent = this.options.loadingPercent + ((100 - this.options.loadingPercent) / (Math.abs(diffProcs) * 2));
					this.view.update(this.options.loadingPercent, true, function() {
						self.state.removeState('updating');
					});
				}
				this.options.previousProcs = this.options.currentProcs;
			}
		},

		/* ************************************************************** */
		/* LISTEN TO THE APP EVENTS */
		/* ************************************************************** */

		/**
		 * Listen when a component is entering loading state.
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @param {mad.controller.CoponentController} component The target component
		 */
		'{mad.bus} passbolt_component_loading_start': function (el, ev, component) {
			this.options.currentProcs++;
			this.update();
		},

		/**
		 * Listen when a component is leaving loading state.
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @param {mad.controller.CoponentController} component The target component
		 */
		'{mad.bus} passbolt_component_loading_complete': function (el, ev, component) {
			this.options.currentProcs--;
			this.update();
		},

		/**
		 * Listen when an ajax request is starting.
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 */
		'{mad.bus} passbolt_ajax_request_start': function (el, ev, request) {
			if (!request.silentLoading) {
				this.options.currentProcs++;
				this.update();
			}
		},

		/**
		 * Listen when an ajax request is completed.
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 */
		'{mad.bus} passbolt_ajax_request_complete': function (el, ev, request) {
			if (!request.silentLoading) {
				this.options.currentProcs--;
				this.update();
			}
		},

		/**
		 * Listen the event passbolt_loading and display a feedback to the user
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 */
		'{mad.bus} passbolt_loading': function (el, ev) {
			this.options.currentProcs++;
			this.update();
		},

		/**
		 * Listen the event passbolt_loading_completed and display a feedback to the user
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 */
		'{mad.bus} passbolt_loading_complete': function (el, ev) {
			this.options.currentProcs--;
			this.update();
		}

	});
});
