steal(
	'jquery/class',
	MAD_ROOT + '/event/eventable.js'
).then(function ($) {

	/*
	 * @class mad.view.View
	 * @inherits jQuery.View
	 * @parent mad.core
	 * 
	 * The view class is our representation of the <b>V</b> of the MVC model. 
	 * 
	 * @constructor
	 * constructor oh yeah
	 * @return {mad.view.View}
	 */
	mad.controller.Controller('mad.view.View', /** @static */ {

		'defaults': {
			'templateUri': null,
			'element': null
		}

	}, /** @prototype */ {

		/**
		 * The component controller which use this view
		 * @type {mad.controller.ComponentController}
		 * @private
		 * @hide
		 */
		'controller': null,

		/**
		 * The associated template uri. If null, the templateUri will be defined on the Class name
		 * @type {string}
		 * @private
		 * @hide
		 */
		'templateUri': null,

		/**
		 * The view is based on a template
		 * @type {string}
		 * @private
		 * @hide
		 */
		'templateBased': null,

		// Constructor like 
		'init': function (element, options) {
			this._super(element, options);

			this.controller = options.controller;
			this.templateUri = options.templateUri;
			this.templateBased = options.templateBased;

			// add the classes to the top element
			// @todo Hmmmmmmm. Ou pas ici
			for (var i in options.cssClasses) {
				if(!this.element.hasClass(options.cssClasses[i])) {
					this.element.addClass(options.cssClasses[i]);
				}
			}
		},

		/**
		 * Add	the given class
		 * @param {string} className The class to add
		 * @return {void}
		 */
		'addClass': function (className) {
			this.element.addClass(className);
		},

		/**
		 * Get the component's template. If the options.template has been defined 
		 * use this one. Else build the template uri functions of the component name.
		 * @return {String} The component template uri
		 */
		'getTemplate': function (options) {
			var returnValue = '';

			// the template uri defined
			if(this.templateUri != null) {
				returnValue = this.templateUri;
			}
			// define the template functions of the class name
			else {
				returnValue = mad.helper.ControllerHelper.getViewPath(this.controller.Class);
			}

			return returnValue;
		},
		
		/**
		 * Hide the element
		 * @return {void}
		 */
		'hide': function () {
			this.element.hide();
		},

		/**
		 * The component is loading
		 * @param {boolean} loading Display or not the loading
		 * @return {void}
		 */
		'loading': function (loading) {
			if (loading) {
				this.element.prepend('<div class="js_loading" />');
			} else {
				$('.js_loading', this.element).remove();
			}
		},

		/**
		 * Remove	the given class
		 * @param {string} className The class to remove
		 * @return {void}
		 */
		'removeClass': function (className) {
			this.element.removeClass(className);
		},

		/**
		 * The render method renders the view based on its template.
		 * @see {getTemplate}
		 * @param {array} options Optional parameters	
		 * @param {boolean} options.display Display the rendered component. If true
		 * the rendered component will be push in the DOM else the rendered component
		 * will be stored in the instance's variable renderedView
		 * @return {mixed} Return true if the method does not encountered troubles else
		 * return false. If the option display is set to false, return the rendered view
		 */
		'render': function (options) {
			// if the view does is not template based leave
			if(!this.templateBased) {
				return true;
			}

			//				console.log('RENDER TEMPLATE '+this.getTemplate());
			var options = options || {};
			var display = options.display || true;

			// render the view
			var render = $.View(this.getTemplate(), this.controller.viewData);

			// display the rendered view
			if (display) {
				this.element.append(render);
				returnValue = true;
			}
			// return the rendered view
			else {
				this.renderedView = render;
				returnValue = render;
			}

			return returnValue;
		},

		/**
		 * Set the view's template uri
		 * @param {string} templateUri The template uri
		 * @return {void}
		 */
		'setTemplateUri': function (templateUri) {
			this.templateUri = templateUri;
		},
		
		/**
		 * Show the element
		 * @return {void}
		 */
		'show': function () {
			this.element.show();
		}
		
	});

//	mad.view.View.augment('mad.event.Eventable');
});