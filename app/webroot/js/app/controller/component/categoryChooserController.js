steal( 
    MAD_ROOT,
    MAD_ROOT+'/view/component/tree.js'
)
.then(
    function($){
        
        /*
        * @class passbolt.controller.CategoryChooserController
		* @inherits mad.controller.component.TreeController
        * @parent index 
		* 
        * @constructor
        * Creates a new CategoryChooserController.
        * @return {passbolt.controller.CategoryChooserController}
        */
        mad.controller.component.TreeController.extend('passbolt.controller.component.CategoryChooserController', 
        
        /** @static */
        {
			'defaults':{
				'label' :			'Category Chooser'
			},
			'listensTo':['item_selected']
		},
        
        /** @prototype */
        {
            
            'init' : function(el, options)
            {
				// The map to use to make jstree working with our category model
				options.map = new mad.object.Map({
					'attr.id':{
						'key':	'Category.id',
						'func':	function(value, map){
							return value;
						}
					},
					'data':		'Category.name',
					'children': {
						'key':	'children',
						'func':	mad.object.Map.mapObjects
					}
				});
                this._super(el, options);
            },
            
			/* ************************************************************** */
			/* LISTEN TO THE VIEW EVENTS */
			/* ************************************************************** */
			
			/**
			 * An item has been selected
			 * @param {jQuery} element The source element
			 * @param {Event} event The jQuery event
			 * @param {string} itemId The item identifier
			 * @return {void}
			 */
            'item_selected': function(element, event, itemId)
            {
                passbolt.eventBus.trigger('category_selected', {'id':itemId});
            },
			
			/* ************************************************************** */
			/* LISTEN TO THE APP EVENTS */
			/* ************************************************************** */
			
			/**
			 * Observe when a new database is selected
			 * @param {jQuery} element The source element
			 * @param {Event} event The jQuery event
			 * @param {string} itemId The item identifier
			 * @return {void}
			 */
			'{mad.eventBus} passbolt_database_selected': function(ui, event, database)
			{
				var self = this;
				//load categories function of the selected database
				passbolt.controller.CategoryController.get({id:database.id, children:true}, function(category){
					// load the tree with the categories
					self.load(category);
				});
			}
            
        });
        
    }
);
