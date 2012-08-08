steal( 
    MAD_ROOT+'/controller/component/gridController.js',
    'app/controller/component/copyLoginButtonController.js',
    'app/controller/component/copySecretButtonController.js'
)
.then(
    function($){
        
        /*
        * @class passbolt.controller.component.PasswordBrowserController
        * @inherits {mad.controller.component.GridController}
        * @parent index 
		* 
		* Our password grid controller
		* 
        * @constructor
        * Creates a new PasswordBrowserController.
        * @return {passbolt.controller.component.PasswordBrowserController}
        */
        mad.controller.component.GridController.extend('passbolt.controller.component.PasswordBrowserController', 
        /** @static */
        {
            'eventToAjaxTransaction': ['passbolt_password_selected'],
			'listensTo':['item_selected', 'item_hovered'],
			'defaults': { }
        },
        /** @prototype */
        {
			/**
			 * The current selected resource id
			 * @type {string}
			 */
			'crtSelectedResourceId': null,
			
			// Constructor like
            'init' : function(el, options)
            {
				// The map to use to make jstree working with our category model
				options.map = new mad.object.Map({
					'id': 'Resource.id',
					'title': 'Resource.title',
					'login': 'Resource.login',
					'url': 'Resource.url',
					'copyLogin': 'Resource.id',
					'copySecret': 'Resource.id'
				});
				
				// the column names
				options.columnNames = ['Row', 'Title', 'Login', 'Url', '', ''];
				
				// the column model
				options.columnModel = [ 
					{name:'row', index:'row', width:100, 'valueAdapter':function(value, item, columnModel, rowNum){ return rowNum; }},
					{name:'title', index:'title', width:100},
					{name:'login', index:'login', width:100},
					{name:'url', index:'url', width:100},
					{name:'copyLogin', index:'copyLogin', width:100,  
						'cellAdapter':function(cellElement, cellValue){
							mad.helper.ComponentHelper.create(
								cellElement, 'inside_replace',
								passbolt.controller.component.CopyLoginButtonController, {
									'cssClasses': ['js_copy_login_button'],
									'readyState':'hidden',
									'events':function(elt, data){
										mad.eventBus.trigger('copy_login_clipboard', data);
									},
									'value':cellValue
								}
							);
						}
					},
					{name:'copySecret', index:'copySecret', width:100,  
						'cellAdapter':function(cellElement, cellValue){
							mad.helper.ComponentHelper.create(
								cellElement, 'inside_replace',
								passbolt.controller.component.CopySecretButtonController, {
									'cssClasses': ['js_copy_secret_button'],
									'readyState':'hidden',
									'events':function(elt, data){
										mad.eventBus.trigger('copy_secret_clipboard', data);
									},
									'value':cellValue
								}
							);
						}
					}
				];
				
                this._super(el, options);
            }
			
			/**
			 * Load the browsers with the given resources
			 * @param {app.model.Resource[]} resources The resources to display
			 * @return {void}
			 */
            , 'load': function(resources)
            {
				this._super(resources);
            },
			
			/* ************************************************************** */
			/* LISTEN TO THE VIEW EVENTS */
			/* ************************************************************** */
			
			/**
			 * Observe when the mouse leave the component
			 * @param {jQuery} element The source element
			 * @param {Event} event The jQuery event
			 * @return {void}
			 */
			'mouseleave': function(element, evt)
            {
				if(this.crtFocusedResourceId){
					mad.eventBus.trigger('resource_unfocused', {'id':this.crtFocusedResourceId});
					this.crtFocusedResourceId = null;
				}
            },
			
			/**
			 * Observe when a resource is hovered
			 * @param {jQuery} element The source element
			 * @param {Event} event The jQuery event
			 * @param {string} data The hovered resource id
			 * @return {void}
			 */
			'item_hovered': function(element, evt, itemId)
            {
				// Display button such as copy to clipboard
				if(this.crtFocusedResourceId){
					mad.eventBus.trigger('resource_unfocused', {'id':this.crtFocusedResourceId});
				}
				
				this.crtFocusedResourceId = itemId;
				mad.eventBus.trigger('resource_focused', {'id':this.crtFocusedResourceId});
            },
			
			/**
			 * Observe when an resource is selected
			 * @param {jQuery} element The source element
			 * @param {Event} event The jQuery event
			 * @param {string} data The selected resource id
			 * @return {void}
			 */
			'item_selected': function(element, evt, itemId)
            {
				// if the resource selected is the same than the previous one unselect
				if(itemId == this.crtSelectedResourceId){
					this.crtSelectedResourceId = null;
					mad.eventBus.trigger('resource_unselected', {'id':itemId});
					this.state.setState('ready');
				}
				else{
					this.crtSelectedResourceId = itemId;
					mad.eventBus.trigger('resource_selected', {'id':itemId});
					this.setState('resourceSelected')
				}
            },
			
			/* ************************************************************** */
			/* LISTEN TO THE APP EVENTS */
			/* ************************************************************** */
			
            /**
			 * Observe when category is selected
			 * @param {jQuery} element The source element
			 * @param {Event} event The jQuery event
			 * @param {string} category The selected Category
			 * @return {void}
			 */
            '{mad.eventBus} category_selected': function(element, evt, category)
            {
				var self = this;
								
				// if a resource was selected, inform the system that the resource is no more selected
				if(this.state.is('resource_selected')){
					mad.eventBus.trigger('resource_unselected', {'id':this.crtSelectedResourceId});
				}
				
				// change the state of the component to loading & and load the new resources
				this.setState('loading');
				passbolt.model.Resource.getCategoryResources({'category_id':category.id}, function(resources){
					self.load(resources);
					// change the state to ready
					self.setState('ready');
				});
            },
			
			/**
			 * Observe when the user want to copy the password to the clipboard
			 * @param {jQuery} element The source element
			 * @param {Event} event The jQuery event
			 * @param {string} resourceId The resource id
			 * @return {void}
			 */
			'{mad.eventBus} copy_login_clipboard': function(element, evt, resourceId)
            {
				alert('copy the password to clipboard '+resourceId);
            },			
			
			/* ************************************************************** */
			/* LISTEN TO THE STATE CHANGES */
			/* ************************************************************** */
			
			/**
			 * Listen to the change relative to the state Ready.
			 * The ready state is fired automatically after the Component is rendered
			 * @param {boolean} go Enter or leave the state
			 * @return {void}
			 */
			'listenReady': function(go){
				if(go) { }
				else { }
			},
			
			/**
			 * Listen to the change relative to the state ResourceSelected
			 * @param {boolean} go Enter or leave the state
			 * @return {void}
			 */
			'listenResourceSelected': function(go){
				if(go) {
					this.hideColumn('copyLogin');
					this.hideColumn('copySecret');
				}
				else {
					this.showColumn('copyLogin');
					this.showColumn('copySecret');
				}
			}
			
        });
        
    }
);
