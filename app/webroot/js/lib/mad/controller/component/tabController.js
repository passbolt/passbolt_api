steal( 
    MAD_ROOT+'/controller/component/containerController.js',
	MAD_ROOT+'/view/component/tab.js',
	MAD_ROOT+'/view/template/component/tab.ejs')
	
.then( function ($) {

	/*
	 * @class mad.controller.component.TabController
	 * @inherits mad.controller.ComponentController
	 * @parent mad.component
	 * 
	 * @constructor
	 * Creates a new TabController
	 * @return {mad.controller.component.TabController}
	 */
	mad.controller.component.ContainerController.extend('mad.controller.component.TabController',
	/** @static */
	{
		'defaults': {
			'label': 'Tab Controller',
			'viewClass': mad.view.component.Tab
		}
	},
	/** @prototype */
	{
		/**
		 * The current enabled tab id
		 * @type {string}
		 */
		'crtEnabledTabId': null,
		
		/**
		 * Enable a tab
		 * @param {string} tabId id of the tab to enable
		 * @return {void}
		 */
		'enableTab': function (tabId) {
			if(this.crtEnabledTabId){
				this.components[this.crtEnabledTabId].setState('hidden');
			}
			this.crtEnabledTabId = tabId;
			this.components[tabId].setState('ready');
		},

		/**
		 * Add a component to the container
		 * @param {string} componentClass The component class to use to instantiate the component
		 * @param {array} componentOptions The optional data to pass to the component constructor
		 * @param {string} area The area to add the component. Default : mad-container-main
		 */
		'addComponent': function (componentClass, componentOptions, area) {
			var component = null,
				$component = null;

			$component = this.view.addComponent(componentOptions);
			component = new componentClass($component, componentOptions);
			this.components[componentOptions.id] = component;
			component.setState('hidden');

			return component;
		}
	});
	
});
