import 'mad/control/control';
import 'mad/view/view';
import 'mad/model/state';
import 'mad/view/template/component/default.ejs!';

// Initialize the component namespaces.
mad.component = mad.component || {};
mad.view.component = mad.view.component || {};

/**
 * @parent Mad.core_api
 * @inherits mad.Control
 *
 * The component controller is our representation of a component.
 * @todo complete the documentation
 */
var Component = mad.Component = mad.Control.extend('mad.Component', /* @static */{

	defaults: {
		// The component's icon.
		icon: null,
		// The component's label.
		label: 'ComponentController',
		// The classes to add to the HTML Element the component is created on.
		cssClasses: ['js_component'],
		// The initial state the component will be initialized on (after start).
		state: 'ready',
		// The default HTML Element the component will be wrapped by. Used especially when
		// creating a component with the component helpers.
		tag: 'div',
		// Is the component based on a template. By default yes.
		templateBased: true,
		// Override the default template to use any other existing one.
		templateUri: null,
		// The component's view controller used to drive the component's view.
		viewClass: mad.View,
		// The data used by the view
		viewData: {},
		// Should the component notify others while it's loading.
		silentLoading: true
	}

}, /** @prototype */ {

	/**
	 * Constructor.
	 *
	 * @signature `new mad.Component( element, options )`
	 * @param {HTMLElement|can.NodeList|CSSSelectorString} el The element the control will be created on
	 * @param {Object} options Option values merged with the class defaults and set as this.options.
	 * @todo check if the icon option is still in use. Button use it in mad, but it is maybe the only one.
	 * @return {mad.Component} A new instance of the constructor function extending mad.Component.
	 *
	 * @body
	 * ## Options
	 *
	 * See the parent class to see the inherited options.
	 *
	 * ### icon {string}
	 * The icon to attribute to the component.
	 *
	 * ### cssClasses {array}
	 * The classes to add to the HTML Element the component is created on.
	 *
	 * ### label {string}
	 * The label to attribute to the component.
	 *
	 * ### loading {boolean}
	 * Auto loading prompt.
	 *
	 * ### state {string}
	 * The initial state the component will be initialized on (after start).
	 *
	 * ### tag {string}
	 * The default HTML Element the component will be wrapped by. Used especially when
	 * creating a component with the component helpers.
	 *
	 * ### templateBased {boolean}
	 * Is the component template based? By default yes.
	 *
	 * Some component doesn't need to render any html, the only HTML Element they are created on
	 * is enough. Complex components that are used most of the time need a template to express
	 * themselves.
	 *
	 * ### templateUri {string}
	 * The component's template used to render the component.
	 *
	 * By default (if templateBased is set at true) the system will look for a template
	 * based on the Component Class name in the template folder. It can be overridden with this
	 * options to use any other existing templates.
	 *
	 * ### viewClass {mad.View}
	 * The component's view controller used to drive the component's view.
	 *
	 * By default the system will try to define the View's controller based on the Component Class
	 * name. By instance if the Component is called MyComponent, the system will try to find a view
	 * class called MyComponent in the view folder.
	 *
	 * If no class has been defined, or figured out, the Component controller will use the default
	 * mad.View Control as View's controller.
	 */
	init: function (el, options) {
		var self = this;
		this._super(el, options);

		// If the view class parameter has been overridden. Check that it inherits mad.View
		if (!this.options.viewClass instanceof mad.View) {
			throw new mad.error.WrongParameter('options.viewClass', 'mad.View');
		}

		// Initialize the associated state instance. By default use the state variable defined in
		// the component's options.
		this.state = new mad.model.State();

		// Add the optional css classes to the HTMLElement.
		for (var i in options.cssClasses) {
			if(!this.element.hasClass(this.options.cssClasses[i])) {
				this.element.addClass(this.options.cssClasses[i]);
			}
		}
	},

	/**
	 * Override parent::destroy().
	 */
	destroy: function () {
		// If the component is destroyed whereas he is loading.
		// Complete the loading.
		if (this.state.is('loading')) {
			mad.bus.trigger('passbolt_component_loading_complete', [this]);
		}

		// Unbind the state's label attribute observer.
		this.state.unbind('label');

		// Remove all the current states classes from the HTMLElement.
		var currentStates = this.state.current.attr();
		for (var i in currentStates) {
			this.element.removeClass(currentStates[i]);
		}

		// Remove the optional css classes from the HTMLElement.
		for (var i in this.options.cssClasses) {
			this.element.removeClass(this.options.cssClasses[i]);
		}

		// Destroy the view.
		if (this.view) {
			this.view.destroy();
		}

		this._super();
	},

	/**
	 * Listen to any state changes and dispatch to the dedicated state listener (if defined).
	 *
	 * @body
	 * The state listener is defined by a class function of the controller. The function has
	 * to be called as following : state[STATE_NAME].
	 *
	 * The listener gets in parameter a go parameter which indicates to the function  if the
	 * controller is entering or leaving the state.
	 *
	 * ```
	 * // By instance if you try to catch changes on the state ready
	 * function stateReady(go) {
	 *   // Entering the state.
	 *   if (go) { ... }
	 *   // Leaving the state.
	 *   else { ... }
	 * }
	 *
	 */
	_goNextStates: function () {
		// List of states the component is leaving.
		var leaving = [],
			// List of states the component is entering on.
			entering = [],
			// List of current states.
			previous = this.state.previous.attr(),
			// List of previous states.
			current = this.state.current.attr(),
			// List of changes the component is staying on.
			staying = mad.array.intersect(previous, current);

		// Check which states the component is leaving.
		leaving = previous.filter(function(item) {
			return staying.indexOf(item) == -1;
		});

		// Check which states the component is entering on.
		entering = current.filter(function(item) {
			return staying.indexOf(item) == -1;
		});

		// Treat the states the component is going to leave.
		for (var i in leaving) {
			// Eemove the previous state class.
			this.element.removeClass(leaving[i]);

			// Execute the function 'stateStateName' if it exists, passing a boolean set a false
			// to the function to notify it that the component is leaving the state.
			var previousStateListener = this['state' + can.capitalize(leaving[i])];
			if (previousStateListener) {
				previousStateListener.call(this, false);
			}
		}

		// Treat the states the component is going to enter on.
		for (var i in entering) {
			// Add the new state class.
			this.element.addClass(entering[i]);
			// Execute the function 'stateStateName' if it exists, passing a boolean set a true
			// to the function to notify it that the component is entering on the state.
			var newStateListener = this['state' + can.capitalize(entering[i])];
			if (newStateListener) {
				newStateListener.call(this, true);
			}
		}
	},

	/**
	 * Set the template that will be used to render the component.
	 * The template can also be set by defining the templateUri option.
	 *
	 * @param {string} templateUri The template uri
	 */
	setTemplateUri: function (templateUri) {
		this.view.setTemplateUri(templateUri);
	},

	/**
	 * Switch the component' state.
	 * For more information about how a component deals with states, see _goNextStates() and mad.model.State.
	 *
	 * @param {string|array} statesName the new state name or an array of states name
	 */
	setState: function (statesName) {
		this.state.setState(statesName);
        this._goNextStates();
        return this;
	},

	/**
	 * Pass a variable to the component's View & template.
	 *
	 * @param {string|array} name The variable name or an array of variables.
	 * @param {mixed} value (optional) The variable value if a variable name as been given.
	 */
	setViewData: function (name, value) {
		if (typeof name == 'object') {
			var data = name;
			for (var i in data) {
				this.setViewData(i, data[i]);
			}
		} else {
			this.options.viewData[name] = value;
		}
		return this;
	},

	/**
	 * Get a variable value that has been passed to the component's View & template.
	 *
	 * @param {mixed} name (optional) The variable name. If null, returns all the variables passed
	 * to the component's View & template.
	 * @return {mixed}
	 */
	getViewData: function (name) {
		if (typeof name == 'undefined') {
			return this.options.viewData;
		}
		return this.options.viewData[name];
	},

	/**
	 * Refresh the component.
	 * @todo Still in use? Code similar to start ?
	 */
	refresh: function () {
		// If the element is null don't refresh it and release a warning.
		// It could happened when components embed other components.
		if (this.element == null) {
			console.warn('Try to refresh a component which doesn\'t have a DOM element.')
			return;
		}
		this.element.empty();
		if (this.options.templateBased) {
			this.beforeRender();
			var render = this.view.render();
			render = this.afterRender(render);
			this.view.insertInDom(render);
		}
		this.afterStart();
		// Switch the element in its default state
		this.setState(this.options.state);

		return this;
	},

	/**
	 * Override parent::start().
	 */
	start: function () {
		// Shift the component into its loading state.
		this.setState('loading');

		// Call the before start hook.
		this.beforeStart();

		// Start by initializing the component's view.
		this.initView();

		// If the component is template based, render it.
		if (this.options.templateBased) {
			this.beforeRender();
			var render = this.view.render();
			render = this.afterRender(render);
			this.view.insertInDom(render);
		}

		// Call the after start hook.
		this.afterStart();

		// Switch the element to its default start state.
		this.setState(this.options.state);

		return this;
	},

	/**
	 * Initialize the component's View.
	 */
	initView: function () {
		// Instantiate the component's View.
		this.view = new this.options.viewClass(this.element, {
			templateUri: this.options.templateUri,
			cssClasses: this.options.cssClasses,
			templateBased: this.options.templateBased,
			controller: this
		});

		// Set the common view data
		this.setViewData('controller', this);
		this.setViewData('icon', this.options.icon);
		this.setViewData('label', this.options.label);
		this.setViewData('view', this.view);
	},

	/**
	 * Called right before the component is started.
	 * Override this function if you want add a specific behavior.
	 */
	beforeStart: function () {
	},

	/**
	 * Called right after the component has been started.
	 * Override this function if you want add a specific behavior.
	 */
	afterStart: function () {
	},

	/**
	 * Called right before the component is rendered.
	 * Override this function if you want add a specific behavior.
	 *
	 * This hook is generally used to
	 * * Pass data to the component's View & template.
	 */
	beforeRender: function () {
	},

	/**
	 * Called right after the component has been rendered.
	 * Override this function if you want add a specific behavior.
	 */
	afterRender: function (render) {
		return render;
	},

	/**
	 * Render the component
	 *
	 * @param {array} options Associative array of options
	 * @param {boolean} options.display Display the rendered component. If true
	 * the rendered component will be push in the DOM else the rendered component
	 * will be stored in the instance's variable renderedView
	 * @return {mixed} Return true if the method does not encountered troubles else
	 * return false. If the option display is set to false, return the rendered view
	 * @deprecated {0.0.2} Is it deprecated or not ? Is this function called directly ?
	 */
	render: function (options) {
		this.start();
	},

	/**
	 * Search the closest parent component controller.
	 *
	 * @param {mad.Control} Control The component controller to look for.
	 */
	closest: function (Control) {
		var classCssSelector = '.' + Control._fullName,
			data = this.element.closest(classCssSelector).data();
		// @todo #BUG #JMVC $(ELEMENT).data(ControllerName) doesn't work.
		for (var i in data.controls) {
			if (data.controls[i].getClass().fullName == Control.fullName) {
				return data.controls[i];
			}
		}
		;
		return null;
	},

	/* ************************************************************** */
	/* LISTEN TO ANY STATES CHANGES */
	/* ************************************************************** */

	/**
	 * Listen to any changes relative to the state Loading
	 * Override this function if you want add a specific behavior.
	 *
	 * @param {boolean} go Entering or leaving the state
	 */
	stateLoading: function (go) {
		// If the view has already been instantiated, notify it that the component is now loading.
		if (this.view) {
			this.view.loading(go);
		}

		// Entering the loading state.
		if (go) {
			// Broadcast an event on the application event bus to notify all other components.
			// @todo The event name shouldn't start by passbolt
			if (mad.bus) {
				mad.bus.trigger('passbolt_component_loading_start', [this]);
			}
		}
		// Leaving the loading state.
		else {
			// Broadcast an event on the application event bus to notify all other components.
			// @todo The event name shouldn't start by passbolt
			if (mad.bus) {
				mad.bus.trigger('passbolt_component_loading_complete', [this]);
			}
		}
	},

	/**
	 * Listen to any changes relative to the state Ready.
	 * Override this function if you want add a specific behavior.
	 *
	 * @param {boolean} go Entering or leaving the state
	 */
	stateReady: function (go) {
	},

	/**
	 * Listen to any changes relative to the state Disabled.
	 * Override this function if you want add a specific behavior.
	 *
	 * @param {boolean} go Entering or leaving the state
	 */
	stateDisabled: function (go) {
	},

	/**
	 * Listen to any changes relative to the state Hidden.
	 * Override this function if you want add a specific behavior.
	 *
	 * @param {boolean} go Entering or leaving the state
	 */
	stateHidden: function (go) {
		if (go) {
			this.view.hide()
		} else {
			this.view.show()
		}
	}

});

export default Component;
