steal(
	'mad/controller/controller.js',
	'mad/helper/controllerHelper.js'
).then(function () {

	/*
	 * @class mad.controller.ComponentController
	 * @inherits mad.controller.Controller
	 * @parent mad.controller.component
	 * @see mad.model.ComponentState
	 *
	 * The class Component controller is our representation of widget component.
	 *
	 * @constructor
	 * Creates a new Component Controller
	 * @param {array} options Optional parameters
	 * @param {string} options.label Label of the component
	 * @param {boolean} options.loading Auto loading prompt
	 * @param {Class} options.viewClass Defined the associated view class. If it is not defined, the system will try to figure out
	 * the view class from the class name. If no view class found, it will use the default mad.view.View
	 * the template.
	 * @return {mad.controller.ComponentController}
	 */
	mad.controller.Controller.extend('mad.controller.ComponentController', /** @static */ {

		'defaults': {
			// the default component label
			'label': 'ComponentController',
			// the default component icon (@deprecated or not ?)
			'icon': null,
			// the template which will used by the view to render the component
			'templateUri': null,
			// based on template, by default true
			'templateBased': true,
			// associated view will be an instance of this viewClass
			'viewClass': mad.view.View,
			// the initial state of the component
			'state': 'ready',
			// the default tags to associate to the top component DOM HTMLElement
			'cssClasses': ['js_component'],
			// the default top component DOM HTMLElement
			'tag': 'div',
			// the data used by the view
			'viewData': {}
		}

	}, /** @prototype */ {

		/**
		 * The component state.
		 * @type {mad.model.State}
		 */
		'state': null,

		// Override the init and render functions to be sure the component is fully
		// initialized before calling the ready state
		'setup': function () {
			var orgInit = this.init,
				orgRender = this.render;

			// override the init function
			this.init = function () {
				orgInit.apply(this, arguments);
				// If the component is not based on a template, switch to its init state
				// define by the optional parameter state
				if (!this.options.templateBased) {
					// this.setState(this.options.state);
				}
			};

			// override the render function
			this.render = function () {
				orgRender.apply(this, arguments);
				// If the component is  based on a template, switch to its init state
				// define by the optional parameter state
				if (this.options.templateBased) {
					// this.setState(this.options.state);
				}
			};

			return this._super.apply(this, arguments);
		},

		// Constructor like
		'init': function (el, options) {
			var self = this;

			// Check that the view class parameters is well a mad.view.View instance
			if (!this.options.viewClass instanceof mad.view.View) {
				throw new mad.error.WrongParametersException('options.viewClass', 'mad.view.View');
			}

			// Initialize the associated state instance. Byt default use the stateName defined in
			// the options.state
			this.state = new mad.model.State();
			// Observe any change on the state's current attribute
			this.state.current.bind('change', function (ev, row, eventName, statesName) {
				if (eventName == 'add') {
					self.goNextStates();
				}
			});

			this._super(el, options);

			// reference the controller to the application
			mad.app.referenceComponent(this);
		},

		// destructor like
		'destroy': function () {
			// If the component was in loading state, resolve the loading.
			if (this.state.is('loading')) {
				mad.bus.trigger('passbolt_component_loading_complete', [this]);
			}
			// unreference the component to the app
			mad.app.unreferenceComponent(this);
			// Unobserve any change on the state's label attribute
			this.state.unbind('label');
			this._super();
		},

		/**
		 * Listen to any state change and dispatch to the dedicated states listener.
		 *
		 * A state listener is represented as a function in your controller.
		 * This function should respect the following writing : state[Statename].
		 *
		 * The listener will get in parameter a boolean "go" which indicate to the function
		 * if the controller is entering or leaving the state.
		 *
		 * Of course with the inheritance concept you can call the parent listener state
		 * if this one is declared with the function "_super"
		 */
		'goNextStates': function () {
			// List of states the component is leaving.
			var leaving = [],
				// List of states the component is entering on.
				entering = [],
				// List of current states.
				previous = this.state.previous.attr(),
				// List of previous states.
				current = this.state.current.attr(),
				// List of changes the component is statying on.
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
				var previousStateListener = this['state' + $.String.capitalize(leaving[i])];
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
				var newStateListener = this['state' + $.String.capitalize(entering[i])];
				if (newStateListener) {
					newStateListener.call(this, true);
				}
			}
		},

		/**
		 * Set the view template uri
		 * @param {string} templateUri The template uri
		 * @return {void}
		 */
		'setTemplateUri': function (templateUri) {
			this.view.setTemplateUri(templateUri);
		},

		/**
		 * Set the component current state(s)
		 * @see {mad.controller.ComponentController.prototype.gotNextStates}
		 * @param {string|array} statesName the new state name or an array of states name
		 * @return {void}
		 */
		'setState': function (statesName) {
			this.state.setState(statesName);
		},

		/**
		 * The set method allows developper to set data to the view
		 * @param {mixed} name the variable's name or the array of data to add to the view
		 * @param {mixed} value the variable's value or null if the name is an array
		 * @return {void}
		 */
		'setViewData': function (name, value) {
			if (typeof name == 'object') {
				var viewDataZ = name;
				for (var i in viewDataZ) {
					this.setViewData(i, viewDataZ[i]);
				}
			} else {
				this.options.viewData[name] = value;
			}
			return this;
		},

		/**
		 * The get method allows developper to get view data
		 * @param {mixed} name the variable's name or the array of data to add to the view.
		 *  If empty return all.
		 * @return {mixed}
		 */
		'getViewData': function (name) {
			if (typeof name == 'undefined') {
				return this.options.viewData;
			}
			return this.options.viewData[name];
		},

		/**
		 * Refresh the view
		 * @return {void}
		 */
		'refresh': function () {
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
		 * Start the component
		 * @return {mad.controller.ComponentController}
		 */
		'start': function () {
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
		 * Initialize the coponent view.
		 */
		'initView': function () {
			// Init the associated view
			this.view = new this.options.viewClass(this.element, {
				'templateUri': this.options.templateUri,
				'cssClasses': this.options.cssClasses,
				'templateBased': this.options.templateBased,
				'controller': this
			});

			// Set the common view data
			this.setViewData('controller', this);
			this.setViewData('icon', this.options.icon);
			this.setViewData('label', this.options.label);
			this.setViewData('view', this.view);
		},

		/**
		 * Called right before the component is started.
		 *
		 * @return {void}
		 */
		'beforeStart': function () {
		},

		/**
		 * Called right after the component has been started.
		 *
		 * @return {void}
		 */
		'afterStart': function () {
		},

		/**
		 * Execute this function before render each component.
		 *
		 * By default the beforeRender function is :
		 *
		 * * instantiating the associated view ;
		 * * setting the common view data : controller (to be able to access to the associated controller), icon, label ...
		 *
		 * @return {void}
		 */
		'beforeRender': function () {
		},

		/**
		 * Execute this function after render the component
		 * @return {void}
		 */
		'afterRender': function (render) {
			return render;
		},

		/**
		 * Render the component
		 * @see {mad.view.View}
		 * @param {array} options Associative array of options
		 * @param {boolean} options.display Display the rendered component. If true
		 * the rendered component will be push in the DOM else the rendered component
		 * will be stored in the instance's variable renderedView
		 * @return {mixed} Return true if the method does not encountered troubles else
		 * return false. If the option display is set to false, return the rendered view
		 * @deprecated
		 */
		'render': function (options) {
			this.start();
		},

		/**
		 * Search the closest parent component controller.
		 * @param {class} clazz The component controller to look for.
		 */
		'closest': function (clazz) {
			var classCssSelector = '.' + clazz._fullName,
				data = this.element.closest(classCssSelector).data();
			// @todo #BUG #JMVC $(ELEMENT).data(ControllerName) doesn't work.
			for (var i in data.controls) {
				if (data.controls[i].getClass().fullName == clazz.fullName) {
					return data.controls[i];
				}
			}
			;
			return null;
		},

		/* ************************************************************** */
		/* LISTEN TO THE STATE CHANGES */
		/* ************************************************************** */

		/**
		 * Listen to the change relative to the state Loading
		 * @param {boolean} go Enter or leave the state
		 * @return {void}
		 */
		'stateLoading': function (go) {
			// If the view has already been instanciated.
			// Notify it that the component is now loading.
			if (this.view) {
				this.view.loading(go);
			}
			// While entering the loading state.
			// Broadcast an event on the application event bus to notify all other components.
			if (go) {
				mad.bus.trigger('passbolt_component_loading_start', [this]);
			}
			// While leaving the loading state.
			// Broadcast an event on the application event bus to notify all other components.
			else {
				mad.bus.trigger('passbolt_component_loading_complete', [this]);
			}
		},

		/**
		 * Listen to the change relative to the state Ready.
		 * This is the default state of the Component
		 * @param {boolean} go Enter or leave the state
		 * @return {void}
		 */
		'stateReady': function (go) {
			// override this function to implement your own behavior
		},

		/**
		 * Listen to the change relative to the state Disabled.
		 * @param {boolean} go Enter or leave the state
		 * @return {void}
		 */
		'stateDisabled': function (go) {
			// override this function to implement your own behavior
		},

		/**
		 * Listen to the change relative to the state Hidden.
		 * @param {boolean} go Enter or leave the state
		 * @return {void}
		 */
		'stateHidden': function (go) {
			if (go) {
				this.view.hide()
			} else {
				this.view.show()
			}
		}

	});

});