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
			'viewClass': mad.view.component.Tab,
			// enable or not the embedded menu
			'generateMenu': true
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
		 * After start
		 */
		'afterStart': function() {
			var self = this;
			// Instantiate the menu which will rule the tabs container
			if (this.options.generateMenu) {
				this.menu = new mad.controller.component.MenuController($('.js_tabs_nav', this.element));
				this.menu.start();
			}
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
			
			// get the component defined by the tabId
			var tab = this.getComponent(this.enabledTabId);

			// if the tab to enable is not already started => start it
			if(tab.state.is(null)){
				tab.start();
			}
			
			// add the selected class to the tab
			tab.view.addClass('selected');
			// add the selected class to the menu entry
			// @todo move that code into the view
			$('#js_tab_nav_' + tabId).find('a').addClass('selected');
		},

		/**
		 * Add a component to the container
		 * @param {string} Class The component class to use to instantiate the component
		 * @param {array} options The optional data to pass to the component constructor
		 */
		'addComponent': function (Class, options) {
			// default tab content css
			var defaultTabCss = ['tab-content'];
			// get the component if or create it
			options.id = typeof options.id != 'undefined' ? options.id: uuid();

			// insert the associated menu entry
			if (this.options.generateMenu) {
				var menuEntry = new mad.model.Action({
					'id': 'js_tab_nav_' + options.id,
					'label': options.label
				});
				this.menu.insertItem(menuEntry);
			}

			// Add the default css classes to the new tab
			if ($.isArray(options.cssClasses)) {
				$.merge(options.cssClasses, defaultTabCss);
			} else {
				options.cssClasses = defaultTabCss;
			}

			var component = mad.helper.ComponentHelper.create($('.js_tabs_content', this.element), 'last', Class, options);
			return this._super(component);
		}
	});

});