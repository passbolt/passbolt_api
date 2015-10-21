import 'mad/control/control';
import 'mad/helper/control';

// Initialize the view namespaces.
mad.view = mad.view || {};

/**
 * @parent Mad.core_api
 * @inherits mad.Control
 *
 * The view controller that will be used to drive component's view.
 * @todo complete the documentation
 * @todo a control variable should be available to the devs.
 */
var View = mad.View = mad.Control.extend('mad.View', /** @static */{

	defaults: {
		/**
		 * The associated template uri. If null, the templateUri will be defined based on the
		 * Class name
		 */
		templateUri: null,
		/**
		 * The view is based on a template
		 */
		templateBased: true,
		/**
		 * The DOM element associated to this view controller
		 */
		element: null
	},

	/**
	 * Render a template. Prefer use this function instead of can.View.render or
	 * jQuery.View cause they are not working with steal mapping of JMVC 3.3. We
	 * do
	 * @param {string} uri Template uri to render
	 * @param {array} data The data to pass to the renderer
	 * @return {string}
	 */
	render: function (uri, data) {
		return can.view.render(uri, data);
	}

}, /** @prototype */  {

	/**
	 * Return the controller the view is associated with
	 * @return {mad.controller.ComponentController}
	 */
	getController: function () {
		return this.options.controller;
	},

	/**
	 * Add	the given class
	 * @param {string} className The class to add
	 * @return {void}
	 */
	addClass: function (className) {
		this.element.addClass(className);
	},

	/**
	 * Get the component's template. If the options.template has been defined
	 * use this one. Else build the template uri functions of the component name.
	 * @return {String} The component template uri
	 */
	getTemplate: function () {
		var tpl = '';

		// The template uri has been defined in the options
		if(this.getTemplateUri() != null) {
			tpl = this.getTemplateUri();
		}
		// Build the view path regarding the Controller class.
		else {
			tpl = mad.helper.Control.getViewPath(this.getController().getClass());
		}

		return tpl;
	},

	/**
	 * Hide the element
	 * @return {void}
	 */
	hide: function () {
		this.element.hide();
	},

	/**
	 * The component is loading
	 * @param {boolean} loading Display or not the loading
	 * @return {void}
	 */
	loading: function (loading) {
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
	removeClass: function (className) {
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
	position: function(options) {
		mad.helper.Html.position(this.element, options);
	},

	/**
	 * The render method renders the view based on its template.
	 *
	 * @return {string} The rendered view
	 */
	render: function () {
		return mad.View.render(this.getTemplate(), this.getController().getViewData());
	},

	/**
	 * Insert the given string in the dom
	 * @param {string} html The html to insert in the DOM element the view is build upon
	 * @return {void}
	 */
	insertInDom: function(html) {
		this.element.html(html);
	},

	/**
	 * Get the view's template uri
	 * @return {string}
	 */
	getTemplateUri: function () {
		return this.options.templateUri;
	},

	/**
	 * Set the view's template uri
	 * @param {string} templateUri The template uri
	 * @return {void}
	 */
	setTemplateUri: function (templateUri) {
		this.options.templateUri = templateUri;
	},

	/**
	 * Show the element
	 * @return {void}
	 */
	show: function () {
		this.element.show();
	}
});

export default View;