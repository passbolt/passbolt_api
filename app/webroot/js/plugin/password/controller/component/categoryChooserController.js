steal( 
    MAD_ROOT
)
.then(
//    'plugin/password/view/template/component/categoryChooser.ejs',
    function($){
        
        /*
        * @class passbolt.passbolt.controller.CategoryChooserController
		* @inherits mad.controller.component.TreeController
        * @parent index 
        * @constructor
        * Creates a new CategoryChooserController.
        * @return {passbolt.password.controller.CategoryChooserController}
        */
//        mad.controller.ComponentController.extend('passbolt.password.controller.component.CategoryChooserController', 
        mad.controller.component.TreeController.extend('passbolt.password.controller.component.CategoryChooserController', 
        
        /** @static */
        {},
        
        /** @prototype */
        {
            
            'init' : function(el, options)
            {
				options.map = {
					'attr.id':{
						'key':	'id',
						'func':	function(value, map){
							return value;
						}
					},
//					'type':		'icon',
					'data':		'name',
					'children': {
						'key':	'children',
						'func':	mapObjects
					}
				};
                this._super(el, options);
            },
            
//            'selectCategory': function(categoryId)
//            {
//                passbolt.eventBus.trigger('passbolt_category_selected', {'category_id':categoryId})
//            },
//            
//            'li click': function(element, evt, data)
//            {
//                this.selectCategory(element.html());
//            },
			
			/**
			 * Observe when a new database is selected
			 */
			'{mad.eventBus} passbolt_database_selected': function(ui, event, database)
			{
				var self = this;
				//load categories function of the selected database
				password.model.Category.get({id:database.id, children:true}, function(category){
					// load the tree with the categories
					self.load(category);
				});
			}
            
        });
        
    }
);
