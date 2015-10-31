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
		 * Render a template. Prefer use this function instead of can.View.render or
		 * jQuery.View cause they are not working with steal mapping of JMVC 3.3. We
		 * do
		 * @param {string} uri Template uri to render
		 * @param {array} data The data to pass to the renderer
		 * @return {string}
		 */
		'render': function (uri, data) {
			data = data || {};

			// Because of the stealconfig mapping, the id system used to cache
			// the template is buggy. The system is unable to find its templates
			// if that ones have been mapped. Temporary dirty code to avoid
			// useless calls to the server
			if (uri.substring(0, 3) == 'mad') {
				uri = 'lib/' + uri;
			}

			// Check if the template has well been referenced by the dev.
			// Included in the referenced file by steal will avoid a useless server call.
			if (typeof can.view.cached[can.view.toId(uri)] == 'undefined') {
				console.warn('[PERF] the template ' + uri + ' is not referenced correctly, what will imply a useless server call on prod.');
				uri = steal.idToUri(uri).toString();
			}

			return can.view.render(uri, data);
		}

	}, /** @prototype */ {

		// Constructor like 
		'init': function (element, options) {
			this._super(element, options);

			// add the classes to the top element
			// @todo Hmmmmmmm. Ou pas ici
			for (var i in options.cssClasses) {
				if(!this.element.hasClass(options.cssClasses[i])) {
					this.element.addClass(options.cssClasses[i]);
				}
			}
		},

		/**
		 * Return the controller the view is associated with
		 * @return {mad.controller.ComponentController}
		 */
		'getController': function () {
			return this.options.controller;
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
		'getTemplate': function () {
			
			var returnValue = '';

			// the template uri defined
			if(this.getTemplateUri() != null) {
				returnValue = this.getTemplateUri();
			}
			// define the template functions of the class name
			else {
				returnValue = mad.helper.ControllerHelper.getViewPath(this.getController().getClass());
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
		 * Position an element in absolute
		 * @param {array} options Array of options
		 * @param {array} options.coordinates (optional) Position the element functions of the given coordinates
		 * @param {integer} options.coordinates.x Position the element functions of the given x coordinates
		 * @param {integer} options.coordinates.y Position the element functions of the given y coordinates
		 * @param {array} options.reference (optional) Position the element functions of a reference element
		 * @param {HTMLElement} options.reference.element The reference element
		 * @param {array} options.reference.my As per Jquery position plugin, the target corner of my element ("top left" by instance)
		 * @param {array} option.reference.at As per Jquery position plugin, the target corner of the reference element ("bottom left" by instance)
		 * @return {void}
		 */
		'position': function(options) {
			mad.helper.HtmlHelper.position(this.element, options);
		},

		/**
		 * The render method renders the view based on its template.
		 * @see {getTemplate}
		 * @return {string} The rendered view
		 */
		'render': function () {
			return mad.view.View.render(this.getTemplate(), this.getController().getViewData());
		},

		/**
		 * Insert the given string in the dom
		 * @param {string} html The html to insert in the DOM element the view is build upon
		 * @return {void}
		 */
		'insertInDom': function(html) {
			this.element.html(html);
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