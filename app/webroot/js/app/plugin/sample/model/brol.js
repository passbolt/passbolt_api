steal(
    'jquery/model'
)
.then(function(){
	
    $.Model('passbolt.plugin.sample.model.Brol',{
		attributes : {
			'id':		'string',
			'label':	'string'
		}
    },
    { 
		'init': function(){
//			this.validate("label", function(){
//				if(this.label == null){
//					return "label cannot be empty";
//				}
//				if(this.label.length > 3){
//					return "label requires at least 3 chars";
//				}
//			});
		}
	}
    );
})
