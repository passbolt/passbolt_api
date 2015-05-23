steal(
	'mad/controller/componentController.js',
	'mad/devel/components/componentsBrowserController.js',
	'mad/view/template/devel/develSidebar.ejs'
).then(function () {
	/*
	 * @class mad.devel.DevelSidebarController
	 * @inherits {mad.controller.ComponentController}
	 * @parent index
	 *
	 * Our devel sidebar controller.
	 *
	 * @constructor
	 * Creates a new Devel Sidebar Controller
	 *
	 * @param {HTMLElement} element the element this instance operates on.
	 * @param {Object} [options] option values for the controller.  These get added to
	 * this.options and merged with defaults static variable
	 * @return {mad.devel.DevelSidebarController}
	 */
	mad.controller.ComponentController.extend('mad.devel.DevelSidebarController', /** @static */ {

		'defaults': {
			'templateUri': 'mad/view/template/devel/develSidebar.ejs'
		}

	}, /** @prototype */ {

		// Constructor like
		'afterStart': function (el, options) {
			var self = this;

			this.options.toggleButton = new mad.controller.component.ButtonController($('#js-devel-sidebar-toggle-button'), {
				'state': 'closed'
			});
			this.options.toggleButton.start();

			var menuItems = [
				new mad.model.Action({
					'id': uuid(),
					'label': __('Components browser'),
					'cssClasses': [],
					'action': function () {
						self.showDevelComponentsGrid();
					}
				}),
				new mad.model.Action({
					'id': uuid(),
					'label': __('SQL trace'),
					'cssClasses': [],
					'action': function () {
						self.showDevelSqlTrace();
					}
				}),
				new mad.model.Action({
					'id': uuid(),
					'label': __('Plugin configuration'),
					'cssClasses': [],
					'action': function () {
						mad.bus.trigger("passbolt.config.debug", passbolt.model.User.getCurrent().id);
					}
				})
			];
			this.options.menu = new mad.controller.component.MenuController($('#js-devel-sidebar-menu'))
				.start()
				.load(menuItems);

			// Sql trace component.
			// Trick to hide the cakephp sqldump.
			this.options.sqlTrace = new mad.controller.ComponentController($('#js-devel-sql-trace'), {
				'state': 'hidden',
				'templateBased': false
			});
			this.options.sqlTrace.start();

			this.on();
		},

		/**
		 * Manage the devel sidebar button click.
		 * @param el
		 * @param ev
		 */
		'{toggleButton} click': function(el, ev) {
			if (this.options.toggleButton.state.is('opened')) {
				this.options.toggleButton.setState('closed');
				$('.devel-sidebar', this.element).hide();
			}
			else if (this.options.toggleButton.state.is('closed')) {
				this.options.toggleButton.setState('opened');
				$('.devel-sidebar', this.element).show();
			}
		},

		/**
		 * Display the devel components grid.
		 */
		'showDevelComponentsGrid': function() {
			var components = mad.app._components;

			// Instanciate the dialog component.
			var dialog = new mad.controller.component.DialogController(null, {
				label: __('Application components'),
				cssClasses: ['dialog-wrapper-devel-components-browser'].concat(mad.controller.component.DialogController.defaults.cssClasses)
			}).start();

			// Instanciate the components browser.
			var de = dialog.add(mad.devel.ComponentsBrowserController)
				.start()
				.load(components);
		},

		/**
		 * Display the devel sql trace.
		 */
		'showDevelSqlTrace': function() {
			var dialog = new mad.controller.component.DialogController(null, {
				label: __('Sql trace'),
				cssClasses: ['dialog-wrapper-devel-sql-trace'].concat(mad.controller.component.DialogController.defaults.cssClasses)
			}).start();
			$('.dialog-content', dialog.element).html(this.options.sqlTrace.element.html());
		}

	});

});