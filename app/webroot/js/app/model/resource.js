steal(
    'jquery/model'
)
.then(function(){
	/*
	* @class passbolt.model.Resource
	* @inherits {$.Model}
	* @parent index
	* 
	* The resource model
	* 
	* @constructor
	* Creates a resource
	* @param {array} options
	* @return {passbolt.model.Resource}
	*/
    $.Model('passbolt.model.Resource',
	/** @static */
	{
		attributes : {
			'id':				'string',
			'name':				'string',
			'username':			'string',
			'expiry_date':		'string',
			'uri':				'string',
			'description':		'string',
			'deleted':			'string',
			'created':			'string',
			'modified':			'string'
		},
		
		/**
		 * Get resources for a given category
		 */
        'getCategoryResources' : function(params, success, error){
            var url = APP_URL+'/resources/getCategoryResources/{category_id}';
            url = $.String.sub(url, params, true);
            return mad.net.Ajax.singleton().request({
                url:        url,
                type:       'get',
                dataType:   'passbolt.model.Resource.models',
                data:       {id:params['id']},
                success:    success,
                error:      error
            });
        }
    },
	/** @prototype */
    {  });
})
