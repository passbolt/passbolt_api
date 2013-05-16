steal(
	'mad/controller/component/compositeController.js',
	'mad/view/component/tab.js',
	'mad/view/template/component/tab.ejs'
).then(function () {

	/*
	 * @class mad.controller.component.TabController
	 * @inherits mad.controller.CompositeController
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
	mad.controller.component.CompositeController.extend('mad.controller.component.TabController', /** @static */ {

		'defaults': {
			'label': 'Tab Controller',
			'viewClass': mad.view.component.Tab
		}

	}, /** @prototype */ {

		// constructor like
		'init': function(el, opts) {
			/**
			 * The current enabled tab id
			 * @type {string}
			 */
			this.enabledId = null;
			
			this._super(el, opts);
		},

		/**
		 * Enable a tab
		 * @param {string} tabId id of the tab to enable
		 * @return {void}
		 */
		'enableTab': function (tabId) {
			// if a previous tab is enabled, disable it
			if (this.enabledTabId) {
				this.getComponent(this.enabledTabId).setState('hidden');
			}
			
			this.enabledTabId = tabId;
			var tab = this.getComponent(this.enabledTabId);
			// if the tab to enable is not already rendered, render it
			if(tab.state.is(null)){
				tab.start();
			}
			tab.setState('ready');
		},

		/**
		 * Add a component to the container
		 * @param {string} Class The component class to use to instantiate the component
		 * @param {array} options The optional data to pass to the component constructor
		 * @param {string} area The area to add the component. Default : mad-container-main
		 */
		'addComponent': function (Class, options, area) {
			// insert the element which will carries the component in the DOM
			var $component = this.view.add(Class, options);
			// instantiate the component
			var component = new Class($component, options);
			// add the component to the internal list
			this._components[options.id] = component;
			return component;
		}
	});

});