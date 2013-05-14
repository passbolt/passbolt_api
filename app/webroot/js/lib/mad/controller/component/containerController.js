steal(
	'mad/controller/componentController.js'
).then(function () {

	/*
	 * @class mad.controller.component.ContainerController
	 * @inherits mad.controller.ComponentController
	 * @parent mad.controller.component
	 * 
	 * Our implementation of the UI container component
	 * 
	 * @constructor
	 * Create a container controller
	 * 
	 * @param {HTMLElement} element the element this instance operates on.
	 * @param {Object} [options] option values for the controller.  These get added to
	 * this.options and merged with defaults static variable 
	 * @return {mad.controller.component.ContainerController}
	 */
	mad.controller.ComponentController.extend('mad.controller.component.ContainerController', /** @static */ {

		'defaults': {
			'label': 'Container Component Controller'
		}

	}, /** @prototype */ {

		/**
		 * Container's components
		 * @type {Array}
		 * @todo
		 */
		'components': [],
		
		/**
		 * Get a component
		 * @param {string} id The component id to get
		 * @return mad.controller.ComponentController
		 */
		'getComponent': function (id) {
			return this.components[id];
		},

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
			returnValue = mad.helper.ComponentHelper.create($area, 'inside_replace', ComponentClass, componentOptions);
			this.components[returnValue.getId()] = returnValue;
			return returnValue;
		}

	});

});