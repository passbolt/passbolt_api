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
//                this._super({'display':false});
//                html = this.renderedView;

                // Render the decorator template
                var template = '//'+MAD_ROOT+'/view/template/component/decorator/box.ejs';
                // Wrap the existing component with the decorator box
                this.element.wrap('<div id="mad-helper-component-box_decorator" />');
                // The box is now the parent of the element
                var $box = this.element.parent();
                // Detache the existing content, to re-add it later
                var existingContent = $box.children().detach();
                // Render the box template
                $box.html($.View(template));
                // Add the element to the decorator. at the defined place
                $box.find('.mad-helper-component-box_decorator-content').append(existingContent);
                // Render the component
                this._super();
            },
            'boxElement': function()
            {
                return this.element.parents('#mad-helper-component-box_decorator');
            }
        };
        
    }
);
