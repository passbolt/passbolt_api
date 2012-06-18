steal( 
    'jquery/class'
)
.then( 
    function($){
        $.String.getObject('lb.core.helper', window, true);
        lb.core.helper.LoadingDecorator = {
            'loading': function(enable){
                if(enable){
                    this.element.html('loading bordel');
                }
            },
            
            'render': function(){
                this.loading(true);
                this._super();
                this.loading(false);
            }
        };
    }
);
