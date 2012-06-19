steal( 
    'jquery/class'
    , 'jquery/view/ejs'
)
.then( 
    'lb/core/view/template/decorator/box.ejs',
    function($){
        
        $.String.getObject('lb.core.helper', window, true);
        lb.core.helper.BoxDecorator = {
            'render': function(){
                var html = null;
                // Impossible to get the return value of the decorated function
                // the only way to get it is to pass through an internal variable
                // I would like to understand ...
                this._super({'display':false});
                html = this.renderedView;
                // Render the decorator template    
                this.element.html($.View('/js/lb/core/view/template/decorator/box.ejs'));
                // Add the component to the decorator
                // It is crap, but there is trouble with embedded view & the other way to pass the html of the component as a view data is also buggy (the html is not interpreted by the browser)
                this.element.find('.lb-decorator-box-content').html(html);
            }
        };
        
    }
);
