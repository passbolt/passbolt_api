steal(
	'mad/controller/componentController.js',
	'mad/devel/components/develSidebarController.js'
).then(function () {

	/*
	 * @class mad.devel.Devel
	 * @inherits mad.controller.Controller
	 * @parent mad.core
	 * 
	 * The class Devel enhances the application by offering devel tools
	 * to the developers.
	 * 
	 * @constructor
	 * Creates a devel controller
	 * @return {mad.devel.Devel}
	 */
	mad.controller.Controller.extend('mad.devel.Devel', /** @prototype */ {

		// Constructor like.
		'init': function (el, opts) {
			this._super(el, opts)

			var develMenu = mad.helper.ComponentHelper.create(
				$(el),
				'first',
				mad.devel.DevelSidebarController, {
					'id': 'js-devel-sidebar-ctl'
				}
			);
			develMenu.start();
		}
	});
});
