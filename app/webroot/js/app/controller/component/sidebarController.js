steal(
	'mad/view/component/tree.js',
	'app/controller/component/commentsController.js',
	'app/controller/component/sidebarSectionController.js'
).then(function () {

		/*
		 * @class passbolt.controller.SidebarController
		 * @inherits mad.controller.component.ComponentController
		 * @parent index
		 *
		 * @constructor
		 * Creates a new Sidebar Controller
		 *
		 * @param {HTMLElement} element the element this instance operates on.
		 * @param {Object} [options] option values for the controller.  These get added to
		 * this.options and merged with defaults static variable
		 * @return {passbolt.controller.SidebarController}
		 */
		mad.controller.ComponentController.extend('passbolt.controller.component.SidebarController', /** @static */ {

			'defaults': {
				'label': 'Sidebar Controller',
				'viewClass': passbolt.view.component.ResourceDetails,
				// the resource to bind the component on
				'resource': null
			}

		}, /** @prototype */ {

			/**
			 * before start hook.
			 * @return {void}
			 */
			'beforeRender': function () {
				this._super();
			},

			/**
			 * Called right after the start function
			 * @return {void}
			 * @see {mad.controller.ComponentController}
			 */
			'afterStart': function () {

			}


			/* ************************************************************** */
			/* LISTEN TO THE MODEL EVENTS */
			/* ************************************************************** */


		});

	});