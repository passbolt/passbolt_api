steal( 
	'jquery/dom/fixture',
    MAD_ROOT+'/controller/controller.js'
)
.then( 
    function($){
        
		// @dev BEGIN
		// create fixtures data for developpement
		var resourcesFixturedData = {};
		$.fixture({
			type: 'GET',  
			url: APP_URL+'/resources/getCategoryResources/{category_id}'
		},
		function(original, settings, headers){
			var content = resourcesFixturedData[original.data.category_id];
			var returnValue = {'id':uuid(),'status':'success','controller':uuid(),'action':uuid(),'title':uuid(),'message':uuid(),'content':content};
			return returnValue;
		});
		// @dev END
		
        /*
         * @class passbolt.controller.ResourceController
		 * @inherits mad.controller.Controller
         * @parent index
		 * 
         * @constructor
         * 
         * @return {passbolt.controller.ResourceController}
        */
        mad.controller.Controller.extend('passbolt.controller.ResourceController', 
		/** @static */
        { 
			'create': function()
			{
				steal.dev.log('add new password');
			}
			, 'getCategoryResources': function(options, callback)
			{
				passbolt.model.Resource.getCategoryResources({'category_id':options.category_id}, function(resources){
					callback(resources);
				});
			}
			, 'update': function()
			{
				steal.dev.log('update password');
			}
			, 'delete': function()
			{
				steal.dev.log('delete password');
			}
			
			// @dev BEGIN
			, 'createFixturedData': function(category){
				var catId = category.Category.id;
				resourcesFixturedData[catId] = [];

				for(var i = 0; i<10; i++){
					resourcesFixturedData[catId].push({
						'Resource':{
							'id':uuid(),
							'category_id':catId,
							'title':'password '+i+' in '+catId
						}
					});
				}

				for(var i in category.children){
					passbolt.controller.ResourceController.createFixturedData(category.children[i]);
				}
			}
			// @dev END
		}
		/** @prototype */
		,{
            'defaults' : {  }
        }
		);
	}	
);