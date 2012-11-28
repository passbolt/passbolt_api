steal(
	'mad/controller',
	'mad/event/eventable.js'
).then(function () {

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
			/**
			 * The associated template uri. If null, the templateUri will be defined on the Class name
			 * @type {string}
			 * @hide
			 */
			'templateUri': null,
			/**
			 * The view is based on a template
			 * @type {string}
			 * @hide
			 */
			'templateBased': true,
			/**
			 * The DOM element associated to this view controller
			 * @type {HTMLElement}
			 * @hide
			 */
			'element': null
		},

		/**
		 * Render a template. Prefer use this function instean of can.View.render or
		 * jQuery.View cause they are not working with steal mapping of JMVC 3.3. We
		 * do
		 * @param {string} uri Template uri to render
		 * @param {array} data The data to pass to the renderer
		 * @return {string}
		 */
		'render': function (uri, data) {
			data = data || {};
			uri = steal.idToUri(uri).toString();
			return can.view.render(uri, data);
		}

	}, /** @prototype */ {

		/**
		 * The component controller which use this view
		 * @type {mad.controller.ComponentController}
		 * @hide
		 */
		'controller': null,

		// Constructor like 
		'init': function (element, options) {
			this._super(element, options);
			this.controller = options.controller;

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
			if(this.getTemplateUri() != null) {
				returnValue = this.getTemplateUri();
			}
			// define the template functions of the class name
			else {
				returnValue = mad.helper.ControllerHelper.getViewPath(this.controller.getClass());
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
			options = options || {};
			var returnValue = null;

			// if the view does is not template based leave
			if(!this.options.templateBased) {
				return true;
			}
			var display = options.display || true;

			// render the view
			var render = mad.view.View.render(this.getTemplate(), this.controller.viewData);

			// display the rendered view
			if (display) {
				this.element.html(render);
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
		 * Get the view's template uri
		 * @return {string}
		 */
		'getTemplateUri': function () {
			return this.options.templateUri;
		},

		/**
		 * Set the view's template uri
		 * @param {string} templateUri The template uri
		 * @return {void}
		 */
		'setTemplateUri': function (templateUri) {
			this.options.templateUri = templateUri;
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