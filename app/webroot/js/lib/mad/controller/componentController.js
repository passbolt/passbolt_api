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
			'tag': 'div'
		}

	}, /** @prototype */ {

		/**
		 * The component state.
		 * @type {mad.model.State}
		 */
		'state': null,

		/**
		 * Data to pass to the view
		 * @type {array}
		 * @hide
		 */
		'viewData': [],

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

			// Initialize the associated state instance. By default position it to
			this.state = new mad.model.State();
			// Observe any change on the state's label attribute
			this.state.bind('label', function (event, newStateName) {
				self.goNextState(newStateName);
			});

			this._super(el, options);

			// reference the controller to the application
			mad.app.referenceComponent(this);
		},

		// destructor like
		'destroy': function () {
			// unreference the component to the app
			mad.app.unreferenceComponent(this);
			// Unobserve any change on the state's label attribute
			this.state.unbind('label');
			this._super();
		},

		/**
		 * Listen to any state change and dispatch to the dedicated state 
		 * listener.
		 * 
		 * A state listener is represented as a function in your controller.
		 * This function should respect the following writing : state[Statename].
		 * 
		 * The listener will get in parameter a boolean "go" which indicate to the function
		 * if the controller is entering or leaving the state.
		 * 
		 * Of course with the inheritance concept you can call the parent listener state
		 * if this one is declared with the function "_super"
		 * 
		 * @param {mad.model.ComponentState} State The component state class
		 * @param {event} event The jQuery event
		 * @param {string} stateName The new state name
		 */
		'goNextState': function (newState) {
			var previousState = this.state.attr('previous');

			if (previousState) {
				// remove the previous state class
				this.view.removeClass('js_state_' + previousState);
				// leave the previous state
				var previousStateListener = this['state' + $.String.capitalize(previousState)];
				if (previousStateListener) {
					previousStateListener.call(this, false);
				}
			}

			// add the new state class
			this.view.addClass('js_state_' + newState);
			// enter in the new state
			var newStateListener = this['state' + $.String.capitalize(newState)];
			if (newStateListener) {
				newStateListener.call(this, true);
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
		 * Set the current element state. The component is listening on state.label
		 * attribute changes.
		 * @see {mad.controller.ComponentController.prototype.gotNextState}
		 * @param {string} name the state name to switch on
		 * @return {void}
		 */
		'setState': function (stateName) {
			this.state.setState(stateName);
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
				this.viewData[name] = value;
			}
			return this;
		},

		/**
		 * Refresh the view
		 * @return {void}
		 */
		'refresh': function () {
			this.element.empty();
			if(this.options.templateBased) {
				this.beforeRender();
				var render = this.view.render();
				render = this.afterRender(render);
				this.view.insertInDom(render);
			}
		},

		/**
		 * Start the component
		 * @return {void}
		 */
		'start': function() {
			this.initView();
			// if the component is template based, render it
			if(this.options.templateBased) {
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

		'initView': function() {
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
		 * Called right after the start function
		 * @return {void}
		 */
		'afterStart': function() { },

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
		'beforeRender': function() { },

		/**
		 * Execute this function after render the component
		 * @return {void}
		 */
		'afterRender': function(render) { 
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
			return;
			options = options || {}
			var returnValue = false;
			returnValue = this.view.render(options);

			return options.display ? this : returnValue;
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
			this.view.loading(go);
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