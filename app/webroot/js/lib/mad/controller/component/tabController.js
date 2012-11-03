steal(
	'mad/controller/component/containerController.js',
	'mad/view/component/tab.js',
	'mad/view/template/component/tab.ejs'
).then(function () {

	/*
	 * @class mad.controller.component.TabController
	 * @inherits mad.controller.ComponentController
	 * @see mad.view.component.Tab
	 * @parent mad.component
	 * 
	 * @constructor
	 * Creates a new TabController
	 * 
	 * @param {HTMLElement} element the element this instance operates on.
	 * @param {Object} [options] option values for the controller.  These get added to
	 * this.options and merged with defaults static variable 
	 * @return {mad.controller.component.TabController}
	 */
	mad.controller.component.ContainerController.extend('mad.controller.component.TabController', /** @static */ {

		'defaults': {
			'label': 'Tab Controller',
			'viewClass': mad.view.component.Tab
		}

	}, /** @prototype */ {

		/**
		 * The current enabled tab id
		 * @type {string}
		 */
		'crtEnabledTabId': null,

		/**
		 * Enable a tab
		 * @param {string} tabId id of the tab to enable
		 * @return {void}
		 */
		'enableTab': function (tabId) {
			if (this.crtEnabledTabId) {
				this.getComponent(this.crtEnabledTabId).setState('hidden');
			}
			this.crtEnabledTabId = tabId;
			this.getComponent(this.crtEnabledTabId).setState('ready');
		},

		/**
		 * Add a component to the container
		 * @param {string} ComponentClass The component class to use to instantiate the component
		 * @param {array} componentOptions The optional data to pass to the component constructor
		 * @param {string} area The area to add the component. Default : mad-container-main
		 */
		'addComponent': function (ComponentClass, componentOptions, area) {
			var component = null,
				$component = null;

			$component = this.view.addComponent(componentOptions);
			component = new ComponentClass($component, componentOptions);
			this.components[componentOptions.id] = component;
			component.setState('hidden');

			return component;
		}
	});

});