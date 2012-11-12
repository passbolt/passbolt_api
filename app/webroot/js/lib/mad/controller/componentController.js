/*
 * @page mad.controller.component Components
 * @tag mad.controller.component
 * @parent index
 * @see mad.controller.ComponentController
 * @see mad.model.ComponentState
 *
 *	<p>
 *		Component controller is our representation of graphical controllers. It is linked to a view 
 *		that it is controlling. An other aspect of the component controller is that it is implementing
 *		a state system to make the development of the behaviors cleaner.
 *	</p>
 *
 *	<p>
 *		<h2>Linked view</h2>
 *		Each component controller can be rendered following these strategies :
 *		<ul>
 *			<li>Use the default View</li>
 *			<li>Use a custom view</li>
 *		</ul>
 *	</p>
 *	
 *	<p>
 *		<h3>The default View</h3>
 *		By default the Component controller uses the provided default view [mad.view.View|mad.view.View].
 *		The view is initialized in the component controller constructor and rendered in the 
 *		[mad.controller.ComponentController.prototype.render|render] function of this one.
 *		
 *		<br/><br/>
 *		
 *		The view is using the EmbedJS engine to render components. Our view classes are automatically binded
 *		to a template file based on the name of the component controller. By instance for the following
 *		<i>mad.controller.component.MyComponent</i> component controller class the associated template will be
 *		
 *		@codestart
 lib/mad/view/template/controller/component/myComponent.ejs
 *		@codeend
 *		
 *		You can override this template uri by setting the optional parameter <i>templateUri</i>.
 *		
 *		@codestart
	var myComponent = new mad.controller.component.MyComponent($('#myComponent'), {
		'templateUri': 'mad/view/template/controller/component/myCustomTemplateUri.ejs'
	});
 *		@codeend
 *	</p>
 *
 *	<p>
 *		<h3>The custom View</h3>
 *		The framework is flexible and allow you to customize the view to use by the component controller to render
 *		its view.
 *		
 *		@codestart
	var myComponent = new mad.controller.component.MyComponent($('#myComponent'), {
		'viewClass': mad.view.component.MyCustomViewComponent
	});
 *		@codeend
 *		
 *		The custom view class mad.view.component.MyCustomViewComponent has to inherit the 
 *		[mad.view.View|mad.view.View] class. You can implement in this class all the required view features.
 *	</p>
 *	
 *	<p>
 *		<h2>Component' states management</h2>
 *		Each component controller behavior can be isolated and packaged in a specific function to make the 
 *		code clear and reusable.
 *		<br/><br/>
 *		Each Component Controller instances embeds a [mad.model.ComponentState|mad.model.ComponentState] object
 *		to manage its state. The Component Controller instances are listening changes from the Component State
 *		model and they are updating their behavior following this process.
 *		<br/>
 *		<ol>
 *			
 *			<li>
 *				By default the Component Controller is entering in ready state when the Component is rendered or if
 *				it is not a renderable component (Button, Input ...) when the instanciation process is finished.<br/><br/>
 *			</li>
 *		
 *			<li>
 *				Change the Component Controller state
 *				@codestart
	myComponentController.setState('monkeyState');
 *				@codeend
 *			</li>
 *			
 *			<li>
 *				Implement the method which will carry the state
 *				@codestart
	'stateMonkeyState': function (go) {
		if (go) {
			// Code to fire when the Component 
			// Controller is entering into MonkeyState
		} else {
			// Code to fire when the Component 
			// Controller is leaving MonkeyState
		}
	}
 *				@codeend
 *			</li>
 *		</ol>
 *		
 *		By default all Component Controllers own the following state :
 *		
 *		<ul>
 *			<li>loading : a loading state which display a loading animation</li>
 *			<li>ready : the initial state</li>
 *			<li>hidden : hide the component controller</li>
 *		</ul>
 *	</p>
 *
 *	<p>
 *		<h2>Included framework Components Controllers</h2>
 *		<ul>
 *			<li>[mad.controller.component.ButtonController|Button]</li>
 *			<li>[mad.controller.component.ContainerController|Container]</li>
 *			<li>[mad.controller.component.GridController|Grid]</li>
 *			<li>[mad.controller.component.InputController|Input]</li>
 *			<li>[mad.controller.component.ListController|List]</li>
 *			<li>[mad.controller.component.PopupController|Popup]</li>
 *			<li>[mad.controller.component.TabController|Tab]</li>
 *			<li>[mad.controller.component.TreeController|Tree]</li>
 *			<li>[mad.controller.component.WorkspaceController|Workspace]</li>
 *		</ul>
 *	</p>
 *	
 *	<p>
 *		<h2>Example</h2>
 *		@demo ./mad/demo/controller/component.html
 *	</p>
 */

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
	 * The class Component controller is our representation of controllers which take
	 * care of UI Components.
	 * <br/>
	 * The class Component controller is associated to its own view which takes to
	 * display data to users.
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
			'label': 'ComponentController',
			// Label of the component
			'icon': null,
			// @todo
			'templateUri': null,
			// the template which will used by the view to render the component
			'templateBased': true,
			// based on template, by default true
			'viewClass': mad.view.View,
			// associated view will be an instance of this viewClass
			'state': 'ready',
			// the state to put the component when the rendering is finished
			'cssClasses': ['js_component'],
			// the associated tag
			'tag': 'div'
		}

	}, /** @prototype */ {

		/**
		 * The component state.
		 * @type {mad.model.ComponentState}
		 */
		'state': null,

		/** Data to pass to the view
		 * @type {array}
		 * @hide */
		'viewData': [],

		/** The rendered view, if not displayed it will be stored in it
		 * @type {string}
		 * @hide */
		'renderedView': '',

		/** The associated view class, by default {mad.view.View}
		 * @type {mad.view.View}
		 * @hide */
		'viewClass': null,

		// Constructor like
		'init': function (el, options) {
			var self = this;

			// initialize the view
			if (!this.options.viewClass instanceof mad.view.View) {
				// @todo throw the convenient Exception
				steal.dev.warn('not good viewClass');
			}

			// Initialize the associated state instance
			this.state = new mad.model.State();
			this.state.bind('label', function (event, newStateName) {
				self.goNextState(newStateName);
			});

			this._super(el, options);

			// reference the controller to the application
			this.getApp().referenceComponent(this);

			// Once the controller is fully released, initialize the component's associated view
			this.initView();
		},

		// destructor like
		'destroy': function () {
			// unreference the component to the app
			this.getApp().unreferenceComponent(this);
			this.state.unbind('label');
			this._super();
		},

		/**
		 * Listen to any state change and dispatch to the dedicated state 
		 * listener.
		 * <br/>
		 * A state listener is represented as a function in your controller.
		 * This function should respect the following writing : state[Statename].
		 * <br/>
		 * The listener will get in parameter a boolean "go" which indicate to the function
		 * if the controller is entering or leaving the state.
		 * <br/>
		 * Of course with the inheritance concept you can call the parent listener state
		 * if this one is declared with the function "_super"
		 * 
		 * @param {mad.model.ComponentState} State The component state class
		 * @param {event} event The jQuery event
		 * @param {string} stateName The new state name
		 */
		'goNextState': function (newState) {
			var previousState = this.state.attr('previous'),
				debugMsg = this.getId() + ' switching';

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
		 * Initialize the associated component's view
		 * <ul>
		 *	<li>Instanciate the view with the given options.viewClass (default : mad.view.View)</li>
		 *	<li>Set the common view data : controller (to be able to access to the associated controller), icon, label ...</li>
		 *	<li>Switch the component to the ready state if it is not based on a template, else the render function will do the transition</li>
		 * </ul>
		 * @return {void}
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

			// If the component is not based on a template, switch to its default state
			if (!this.options.templateBased) {
				this.setState(this.options.state);
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
		 * Set the current element state
		 * @param {string} name the state name to switch on
		 * @return {void}
		 */
		'setState': function (stateName) {
			this.state.setState(stateName);
		},

		/**
		 * The set method allows developper to set data to the view
		 * @param {string} name the variable's name or the array of data to add to the view
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
			this.render();
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
		 */
		'render': function (options) {
			options = options || {}
			var returnValue = false;
			returnValue = this.view.render(options);

			// set the state of the component with the given default state
			this.setState(this.options.state);

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