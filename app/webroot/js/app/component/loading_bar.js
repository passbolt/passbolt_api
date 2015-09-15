import 'mad/component/component';
import 'app/view/component/loading_bar';

/**
 * @inherits mad.Component
 * @parent index
 * @see {mad.view.View}
 *
 * @constructor
 * The Loading Bar class Component will be used to display to users
 * feedbacks about loading processus.
 *
 * @param {HTMLElement} element the element this instance operates on.
 * @param {Object} [options] option values for the controller.  These get added to
 * this.options and merged with defaults static variable
 * @return {passbolt.component.LoadingBar}
 */
var LoadingBar = passbolt.component.LoadingBar = mad.Component.extend('passbolt.component.LoadingBar', /** @static */ {

	defaults: {
		label: 'Loading Bar Component',
		viewClass: passbolt.view.component.LoadingBar,
		templateBased: false,
		currentProcs: 0,
		previousProcs: 0,
		maxProcs: 0,
		loadingPercent: 0,
		postponedUpdate: false
	}

}, /** @prototype */ {

	/**
	 * Start a loading.
	 */
	loading_start: function (callback) {
		this.view.update(20, true, function() {
			if (callback) {
				callback();
			}
		});
	},

	/**
	 * Complete a loading.
	 */
	loading_complete: function (callback) {
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
	stateLoading: function (go) {},

	/**
	 * Refresh the loading bar
	 */
	update: function(postponedUpdate) {
		var self = this;

		// If we are in a postponed update.
		// Release the lock and allow other requests to be postponed.
		if (typeof postponedUpdate != 'undefined' && postponedUpdate) {
			this.options.postponedUpdate = false;
		}

		// If the loading bar is currently updating.
		if (this.state.is('updating')) {
			// Postpone an update, unless one is already scheduled.
			if (!this.options.postponedUpdate) {
				this.options.postponedUpdate = true;
				setTimeout(function() {
					self.update(true);
				}, 100);
			}
			return;
		} else {
			// Lock the component.
			this.state.addState('updating');
		}

		// Make a temporary working copy of the class' variables.
		// Measurement are based on these variables, and they can change asynchronously.
		var currentProcs = this.options.currentProcs;
		// If we have more processus in the queue than during the previous execution.
		if (this.options.maxProcs < currentProcs) {
			this.options.maxProcs = currentProcs;
		}
		// The variation of processus compare to the latest execution of the function.
		var diffProcs = currentProcs - this.options.previousProcs;

		// As much as processus than during the previous execution.
		// In asynchronous context it can happened.
		if(!diffProcs) {
			this.state.removeState('updating');
		}
		else if(!currentProcs) {
			// All processus have been completed.
			// Even if the bar is not in "progressing" state, complete it.
			this.state.addState('completing');
			this.loading_complete(function() {
				// Broadcast an event on the app event bus to notify all other components about the
				// the completion of the currents processus.
				mad.bus.trigger('passbolt_application_loading_completed', [this]);
				// Mark the loading bar component as ready.
				self.state.setState('ready');
			});
		} else {
			// Update the loading bar depending on the latest changes.
			// If there was no other processus currently loading.
			if (!this.state.is('progressing')) {
				// Broadcast an event on the app event bus to notify all other components that some
				// processus are currently in action.
				mad.bus.trigger('passbolt_application_loading', [this]);
				this.state.addState('progressing');
			}

			// A new processus will fill the loading bar at 50%.
			// The other 50% will be filled while the processus will be completed.
			var procSpace = ( 100 / this.options.maxProcs ) * 1/2;
			var spaceLeft = ( this.options.maxProcs - ( this.options.maxProcs - this.options.currentProcs ) ) * procSpace;
			this.view.update(100 - spaceLeft, true, function() {
				self.state.removeState('updating');
			});
		}

		// The processus which are still active.
		this.options.previousProcs = currentProcs;
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
		// @todo fixed in future canJs.
		if (!this.element) return;

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
		// @todo fixed in future canJs.
		if (!this.element) return;

		this.options.currentProcs--;
		this.update();
	},

	/**
	 * Listen when an ajax request is starting.
	 * @param {HTMLElement} el The element the event occured on
	 * @param {HTMLEvent} ev The event which occured
	 */
	'{mad.bus} passbolt_ajax_request_start': function (el, ev, request) {
		// @todo fixed in future canJs.
		if (!this.element) return;

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
		// @todo fixed in future canJs.
		if (!this.element) return;

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
		// @todo fixed in future canJs.
		if (!this.element) return;

		this.options.currentProcs++;
		this.update();
	},

	/**
	 * Listen the event passbolt_loading_completed and display a feedback to the user
	 * @param {HTMLElement} el The element the event occured on
	 * @param {HTMLEvent} ev The event which occured
	 */
	'{mad.bus} passbolt_loading_complete': function (el, ev) {
		// @todo fixed in future canJs.
		if (!this.element) return;

		this.options.currentProcs--;
		this.update();
	}
});

export default LoadingBar;
