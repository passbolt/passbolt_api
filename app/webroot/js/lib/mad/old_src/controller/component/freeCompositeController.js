steal(
	'mad/controller/component/compositeController.js',
	'mad/view/template/component/workspace.ejs'
).then(function () {

	/*
	 * @class mad.controller.component.WorkspaceController
	 * @inherits mad.controller.CompositeController
	 * @parent mad.controller.component
	 *
	 * Our implementation of a workspace controller. The component
	 * is by definition an organized container which will carry other
	 * components
	 *
	 * @constructor
	 * Create a workspace controller
	 * @param {array} options Optional parameters
	 * @return {mad.controller.component.FreeCompositeController}
	 */
	mad.controller.component.CompositeController.extend('mad.controller.component.FreeCompositeController', /** @static */ {
		'defaults': {
			'label': 'WorkspaceController'
		}
	}, /** @prototype */ {
		
		/**
		 * Add a component to the container
		 * @param {String} ComponentClass The component class to use to instantiate the component
		 * @param {Array} componentOptions The optional data to pass to the component constructor
		 * @param {String} area The area to add the component. Default : mad-container-main
		 * @todo Implement this function with the view system
		 */
		'addComponent': function (ComponentClass, componentOptions, area) {
			area = area || 'mad-container-main';
			var returnValue = null;
			var $area = $('.' + area, this.element);
			var component = mad.helper.ComponentHelper.create($area, 'inside_replace', ComponentClass, componentOptions);
			return this._super(component);
		}
		
	});

});