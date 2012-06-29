steal( 
    'jquery/class'
    , 'jquery/view/ejs'
)
.then( 
//    MAD_ROOT+'/view/template/component/decorator/box.ejs',
    function($){
        
        $.String.getObject('mad.helper.component', window, true);
        mad.helper.component.BoxDecorator = {
            'render': function(){
                var html = null;
                // Impossible to get the return value of the decorated function
                // the only way to get it is to pass through an internal variable
                // I would like to understand ...
                this._super({'display':false});
                html = this.renderedView;
                // Render the decorator template
                var template = '//'+MAD_ROOT+'/view/template/component/decorator/box.ejs';
                this.element.html($.View(template));
                // Add the component to the decorator
                // It is crap, but there is trouble with embedded view & the other way to pass the html of the component as a view data is also buggy (the html is not interpreted by the browser)
                this.element.find('.lb-decorator-box-content').html(html);
            }
        };
        
    }
);
