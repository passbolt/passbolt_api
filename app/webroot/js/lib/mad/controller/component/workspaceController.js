steal(
	'mad/controller/component/containerController.js',
	'mad/view/template/component/workspace.ejs'
).then(function () {

	/*
	 * @class mad.controller.component.WorkspaceController
	 * @inherits mad.controller.ComponentController
	 * @parent mad.controller.component
	 *
	 * Our implementation of a workspace controller. The component
	 * is by definition an organized container which will carry other
	 * components
	 *
	 * @constructor
	 * Create a workspace controller
	 * @param {array} options Optional parameters
	 * @return {mad.controller.component.WorkspaceController}
	 */
	mad.controller.component.ContainerController.extend('mad.controller.component.WorkspaceController', /** @static */ {
		'defaults': {
			'label': 'WorkspaceController',
			'templateUri': 'mad/view/template/component/workspace.ejs'
		}
	}, /** @prototype */ {});

});