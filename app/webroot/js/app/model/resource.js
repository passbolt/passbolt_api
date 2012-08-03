steal(
    'jquery/model'
)
.then(function(){
	
    $.Model('passbolt.model.Resource',{
		attributes : {},
		
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
    {
        test: function(){
            alert (this.name);
        }
    }
    );
})
