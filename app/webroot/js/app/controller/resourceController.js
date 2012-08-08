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
			var returnValue = {
				 'header': new mad.net.Header({
					'id':			uuid(),
					'status':		mad.net.Header.STATUS_SUCCESS,
					'title':		'Resource fixture',
					'message':		'Resource fixture for the category '+original.data.category_id,
					'controller':	'resources',
					'action':		'getCategoryResources'
				}),
				'body':content
			};
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
				
				var titles=['facebook', 'jira', 'google'];
				var logins=['cedbul', 'rem&ms', 'kevbab', 'myrioum', 'Ismateo'];
				var urls=['facebook.com', 'passbolt.altasian.com', 'google.com']
				function getRandomInt(min, max)
				{
				return Math.floor(Math.random() * (max - min + 1)) + min;
				}
				
				for(var i = 0; i<10; i++){
					resourcesFixturedData[catId].push({
						'Resource':{
							'id':uuid(),
							'category_id':catId,
							'title':titles[getRandomInt(0,2)],
							'login':logins[getRandomInt(0,4)],
							'url':urls[getRandomInt(0,2)]
						}
					});
					
				}
//				console.dir

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