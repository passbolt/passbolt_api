steal(
    MAD_ROOT+'/controller/componentController.js'
)
.then( function ($) {

	/*
	 * @class mad.controller.component.ContainerController
	 * @inherits mad.controller.ComponentController
	 * @parent mad.controller.component
	 * 
	 * Our implementation of the UI container component
	 * 
	 * @constructor
	 * Create a container controller
	 * @return {mad.controller.component.ContainerController}
	 */
	mad.controller.ComponentController.extend('mad.controller.component.ContainerController',
	/** @static */
	{
		'defaults': {
			'label': 'Container Component Controller'
		}
	},
	/** @prototype */
	{
		/**
		 * Container's components
		 * @type {Array}
		 * @todo
		 */
		'components': [],

		// Constructor like
		'init': function (el, options) {
			this._super(el, options);
		},

		/**
		 * Add a component to the container
		 * @param {String} componentClass The component class to use to instantiate the component
		 * @param {Array} componentOptions The optional data to pass to the component constructor
		 * @param {String} area The area to add the component. Default : mad-container-main
		 * @todo Implement this function with the view system
		 */
		'addComponent': function (componentClass, componentOptions, area) {
			var returnValue = null;

			var area = typeof area != 'undefined' ? area : 'mad-container-main';
			var $area = this.element.find('.' + area);

			var $component = $('<div id="' + componentOptions.id + '"/>').appendTo($area);
			// if the component is a singleton
			// @todo do not forget to check about the instanceof
			if (typeof componentClass.singleton != 'undefined') {
				returnValue = componentClass.singleton($component, componentOptions);
			} else {
				returnValue = new componentClass($component, componentOptions);
			}

			// reference the component
			//                this.referenceComponent({
			//                    'id':           componentOptions.id,
			//                    'component':    component,
			//                    'area':         area
			//                });
			//                //use a model maybe
			//                this.components.push({
			//                    'id':           componentOptions.id,
			//                    'component':    component,
			//                    'area':         area
			//                });
			return returnValue;
		}

	});

});