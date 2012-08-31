 /*
  * @page mad.controller.component Components
  * @tag mad.controller.component
	* @parent index
  * 
	*	<p>
	*		Component controller is our representation of graphical controllers. It is linked and it is controlling a view
	*		which is by default the class [mad.view.View|mad.view.View].
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
	*		<h3>Example : Component controller with a default View</h3>
	*		@demo /js/mad/demo/controller/component.html
	*	</p>
	*	
	*	<p>
	*		<h3>Example : Component controller with a custom View</h3>
	*		@demo /js/mad/demo/controller/component.html
	*	</p>
	*	
	*	You will find in this package all the components we developped for our framework :
	*	<ul>
	*		<li>[mad.controller.component.ButtonController|Button]</li>
	*		<li>[mad.controller.component.ContainerController|Container]</li>
	*		<li>[mad.controller.component.GridController|Grid]</li>
	*		<li>[mad.controller.component.InputController|Input]</li>
	*		<li>[mad.controller.component.ListController|List]</li>
	*		<li>[mad.controller.component.PopupController|Popup]</li>
	*		<li>[mad.controller.component.TabController|Tab]</li>
	*		<li>[mad.controller.component.TreeController|Tree]</li>
	*		<li>[mad.controller.component.WorkspaceController|Workspace]</li>
	*	</ul>
	*	
  */

steal(
    MAD_ROOT+'/controller/controller.js',
    MAD_ROOT+'/helper/controllerHelper.js',
    MAD_ROOT+'/view/view.js'
)
.then( function ($) {

	/*
	 * @class mad.controller.ComponentController
	 * @inherits mad.controller.Controller
	 * @parent mad.controller.component
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
	mad.controller.Controller.extend('mad.controller.ComponentController',
	/** @static */
	{
		'defaults': {
			'label': 'ComponentController', // Label of the component
			'icon': null,					// @todo
			'templateUri': null,			// the template which will used by the view to render the component
			'templateBased': true,			// based on template, by default true
			'viewClass': mad.view.View,		// associated view will be an instance of this viewClass
			'state': 'ready',		// the state to put the component when the rendering is finished
			'cssClasses': ['js_component']
		},
		/**
		 * The component will listen to the following array of events.
		 * The class has to implement a function for each event. Prefere 
		 * the lowercase/underscore writting to be able to see easily what
		 * kind of function it is.
		 * @type array
		 */
		'listensTo': []

	}
	/** @prototype */
	, {
		/**
		 * The component state.
		 * @type {mad.model.ComponentState}
		 */
		'state': null,

		/** Data to pass to the view
		 * @type {array}
		 * @private
		 * @hide */
		'viewData': [],

		/** The rendered view, if not displayed it will be stored in it
		 * @type {string}
		 * @private
		 * @hide */
		'renderedView': '',

		/** The associated view class, by default {mad.view.View}
		 * @type {mad.view.View}
		 * @private
		 * @hide */
		'viewClass': null,

		// Class Constructor
		'init': function (el, options) {
			var self = this;
			this._super(el, options);

			// initialize the view
			this.view = new this.options.viewClass(this, {
				'templateUri': this.options.templateUri,
				'cssClasses': this.options.cssClasses,
				'templateBased': this.options.templateBased
			});

			// bind state changes
			this.state = new mad.model.ComponentState();
			this.state.bind('label', function (event, newStateName) {
				self.goNextState(newStateName);
			});

			// If the component is not template based, switch to its default state
			// after instanciation.
			if(!this.options.templateBased){
				this.setState(this.options.state);
			}
			// Pass common Component Controller data to the view
			else {
				this.setViewData('controller', this);
				this.setViewData('icon', this.options.icon);
				this.setViewData('label', this.options.label);
			}
		},

		// destructor like
		'destroy': function () {
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
				var previousStateListener = this['state' + $.String.capitalize(previousState)];
				if (previousStateListener) {
					previousStateListener.call(this, false);
				}
				debugMsg += ' from '+previousState;
			}
			debugMsg += ' to '+newState+' state';
			steal.dev.log(debugMsg);
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
		
		'refresh': function(){
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
			var returnValue = false,
				options = options || {},
				display = options.display || true;

			returnValue = this.view.render(options);
			
			// set the state of the component with the given default state
			this.setState(this.options.state);
			
			return returnValue === true ? this : returnValue;
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
				this.element.hide()
			} else {
				this.element.show()
			}
		}

	});

});