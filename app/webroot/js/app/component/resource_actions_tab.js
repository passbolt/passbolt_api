import 'mad/component/tab';
import 'app/component/permissions';

/**
 * @inherits mad.component.Tab
 * @parent index
 *
 * @constructor
 * Creates new ResourceActionsTab
 *
 * @param {HTMLElement} element the element this instance operates on.
 * @param {Object} [options] option values for the controller.  These get added to
 * this.options and merged with defaults static variable
 * @return {passbolt.component.ResourceActionsTab}
 */
var ResourceActionsTab = passbolt.component.ResourceActionsTab = mad.component.Tab.extend('passbolt.component.ResourceActionsTab', /** @static */ {

	defaults: {
		label: null,
		resource: null,
		cssClasses: ['tabs'],
		// @todo The system is trying to get the view class of the ResourceActionsTabController.
		// @todo But we want this component to be based on the parent viewClass & templateUri.
		// @todo It's maybe a todo if we want to automatize this case.
		viewClass: mad.view.component.Tab,
		templateUri: 'mad/view/template/component/tab/tab.ejs'
	}

}, /** @prototype */ {

	/**
	 * Something has changed on a tab controlled by this component.
	 * @private
	 */
	_hasChanged: false,

	// after start
	afterStart: function() {
		this._super();
		var self = this;

		// Add the edition form controller to the tab
		var editFormCtl = this.addComponent(passbolt.form.resource.Create, {
			id: 'js_rs_edit',
			label: __('Edit'),
			action: 'edit',
			data: this.options.resource,
			callbacks: {
				submit: function (data) {
					// save the resource's changes
					self.options.resource.attr(data['passbolt.model.Resource'])
						.save();
					// Close the dialog which contains this component.
					self.closest(mad.component.Dialog)
						.remove();
				}
			}
		});
		editFormCtl.start();
		editFormCtl.load(this.options.resource);

		// Add the permission controller to the tab, if the user is allowed to share.
		var permCtl = this.addComponent(passbolt.component.Permissions, {
			id: 'js_rs_permission',
			label: 'Share',
			resource: this.options.resources,
			cssClasses: ['share-tab'],
            acoInstance: this.options.resource
		});
		permCtl.start();
	},

	/**
	 * Enable a tab
	 * @param {string} tabId id of the tab to enable
	 * @param {boolean} force Should the action be forced, or a confirmation is required
	 */
	enableTab: function (tabId, force) {
		var force = force || false,
			self = this;

		// If a change occurred on the current tab, and a confirmation is required.
		if (this._hasChanged && force === false) {
			new mad.component.Confirm(null, {
				label: __('Do you really want to leave ?'),
				content: __('If you continue you\'ll lose your changes'),
				action: function() {
					self.enableTab(tabId, true);
				}
			}).start();
			return;
		}

		// The tab to enable.
		var enabledTabCtl = this.getComponent(tabId)

		// The dialog should have a relevant title.
		var label = enabledTabCtl.options.label + '<span class="dialog-header-subtitle">' + this.options.resource.name + '</span>';
		this.closest(mad.component.Dialog)
			.setTitle(label);

		this._hasChanged = false;
		this._super(tabId);
	},

	/* ************************************************************** */
	/* LISTEN TO THE COMPONENT EVENTS */
	/* ************************************************************** */

	/**
	 * Listen to any changed event which occurred on the form elements contained by
	 * the form controller.
	 *
	 * When a change occurred, if the user wants to change the current tab, ensure
	 * he is notified regarding the changes he's going to lose.
	 *
	 * Ensure the component controlled by this component trigger an event "changed" while
	 * their content is updated.
	 *
	 * @param {HTMLElement} el The element the event occurred on
	 * @param {HTMLEvent} ev The event that occurred
	 * @param {mixed} data The new data
	 */
	' changed': function (el, ev, data) {
		this._hasChanged = true;
	},

	/**
	 * Listen to any saved event which occurred on the children components.
	 *
	 * When a save occurred, it's is not necessary anymore to display a feedback regarding
	 * the changes that can be lost.
	 *
	 * @param {HTMLElement} el The element the event occurred on
	 * @param {HTMLEvent} ev The event that occurred
	 */
	' saved': function (el, ev) {
		this._hasChanged = false;
	}

});

export default ResourceActionsTab;