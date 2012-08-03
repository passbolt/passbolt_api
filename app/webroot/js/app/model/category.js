steal( 
    'jquery/model'
    )
.then(function(){
    $.Model('passbolt.model.Category',
	/** @static */
	{
		attributes : {
			children: 'passbolt.model.Category.models'
		},
        'get' : function(params, success, error){
			params['children'] = typeof(params['children'])!='undefined'?params['children']:false;
            var url = APP_URL+'/categories/get/{id}/{children}';
            url = $.String.sub(url, params, true);
			
            return mad.net.Ajax.singleton().request({
                url:        url,
                type:       'get',
                dataType:   'passbolt.model.Category.models',
                data:       null,
                success:    success,
                error:      error
            });
        }
        
    //  create:  'POST /todos.json',
    //  update:  'PUT /todos/{id}.json',
    //  destroy: 'DELETE /todos/{id}.json' 
    
	},
    /** @prototype */
	{
        test: function(){
            alert (this.name);
        }
    });
})
