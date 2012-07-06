steal( 
    'jquery/model'
    )
.then(function(){
    $.Model('password.model.Category',{
		attributes : {
			children: 'password.model.Category.models'
		},
        'get' : function(params, success, error){
//			params['id'] = typeof(params['id'])!='undefined'?params['id']:'';
            var url = 'categories/get/{id}';
            url = $.String.sub(url, params, true);
            return mad.net.Ajax.singleton().request({
                url:        url,
                type:       'get',
                dataType:   'json category.model',
                data:       null,
                success:    success,
                error:      error
            });
//            return $.get(url,
//                {}, 
//                success,
//                'jsonp password.model.Category.models');
        }
        
    //  create:  'POST /todos.json',
    //  update:  'PUT /todos/{id}.json',
    //  destroy: 'DELETE /todos/{id}.json' 
    },
    {
        test: function(){
            alert (this.name);
        }
    }
    );
})
