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
			// Generate a menu automatically.
			'autoMenu': true
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

			// Set the view vars.
			this.setViewData('autoMenu', this.options.autoMenu);
		},

		/**
		 * After start
		 */
		'afterStart': function() {
			// Instantiate the menu which will rule the tabs container
			if (this.options.autoMenu) {
				this.options.menu = new mad.controller.component.MenuController($('.js_tabs_nav', this.element));
				this.options.menu.start();
			}

			this.on();
		},

		/**
		 * A tab has been selected
		 * @return {void}
		 */
		'{menu} item_selected': function (el, ev, item) {
			// If the tab controller generate is own menu to drive itself
			if(this.options.autoMenu) {
				var tabId = item.id.replace('js_tab_nav_', '');
				this.enableTab(tabId);
			}
		},

		/**
		 * Enable a tab
		 * @param {string} tabId id of the tab to enable
		 * @return {void}
		 */
		'enableTab': function (tabId) {
			// if a previous tab is enabled
			// -> unselect it
			if (this.enabledTabId) {
				this.getComponent(this.enabledTabId).setState('hidden');
				this.view.unselectTab(this.enabledTabId);
			}

			// get the component defined by the tabId
			this.enabledTabId = tabId;
			var tab = this.getComponent(this.enabledTabId);

			// if the tab to select is not already started
			// -> start it
			if(tab.state.is(null)){
				tab.start();
			}
			// if the tab is hidden
			// -> display it
			else if(tab.state.is('hidden')) {
				tab.setState('ready');
			}

			this.view.selectTab(this.enabledTabId);
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
			if (typeof options.id != 'undefined') {
				options.id = options.id;
			} else {
				options.id = uuid();
			}

			// insert the associated menu entry
			if (this.options.autoMenu) {
				var menuEntry = new mad.model.Action({
					'id': 'js_tab_nav_' + options.id,
					'label': options.label
				});
				this.options.menu.insertItem(menuEntry);
			}

			// Add the default css classes to the new tab
			if ($.isArray(options.cssClasses)) {
				$.merge(options.cssClasses, defaultTabCss);
			} else {
				options.cssClasses = defaultTabCss;
			}

			var component = mad.helper.ComponentHelper.create(
				$('.js_tabs_content', this.element),
				'last',
				Class,
				options
			);
			return this._super(component);
		}

	});

});
