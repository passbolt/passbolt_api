steal( 
    MAD_ROOT+'/controller/component/gridController.js'
)
.then(
    function($){
        
        /*
        * @class passbolt.controller.component.PasswordBrowserController
        * @parent index 
		* 
        * @constructor
        * Creates a new PasswordBrowserController.
        * @return {passbolt.controller.component.PasswordBrowserController}
        */
        mad.controller.component.GridController.extend('passbolt.controller.component.PasswordBrowserController', 
        
        /** @static */
        {
            'eventToAjaxTransaction': ['passbolt_password_selected'],
			'listensTo':['item_selected']
        },
        
        /** @prototype */
        {
				
			// Constructor like
            'init' : function(el, options)
            {
				// The map to use to make jstree working with our category model
				options.map = new mad.object.Map({
					'id': 'Resource.id',
					'category_id': 'Resource.category_id',
					'title': 'Resource.title'
				});
				// the column names
				options.columnNames = ['Resource Id', 'Category Id', 'Title'];
				// the column model
				options.columnModel = [ 
					{name:'id', index:'id', width:100},
					{name:'category_id', index:'category_id', width:100},
					{name:'title', index:'title', width:100}
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
			 * Observe when category is selected
			 * @param {jQuery} element The source element
			 * @param {Event} event The jQuery event
			 * @param {string} data The selected item id
			 * @return {void}
			 */
			'item_selected': function(element, evt, itemId)
            {
				console.log('a resource has been selected '+itemId);
                mad.eventBus.trigger('resource_selected', {'id':itemId})
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
				passbolt.controller.ResourceController.getCategoryResources({'category_id':category.id}, function(resources){
					self.load(resources);
				});
            }
			
        });
        
    }
);
