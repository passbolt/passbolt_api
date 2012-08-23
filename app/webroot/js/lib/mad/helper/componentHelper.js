steal( 
    'jquery/class'
)
.then( 
    function($){
        
        /**
         * The controller class helper offers to the developper tools arround controllers
         */
        $.Class('mad.helper.ComponentHelper', 
        
        /** @static */
        {
			'create': function(refElement, position, clazz, options) {
				var viewOptions = $.extend(true, {}, clazz.defaults, options),
					templateUri = null;
				
				// Render the component
				if (viewOptions.templateUri != null)
					templateUri = viewOptions.templateUri;
				else 
					templateUri = mad.helper.ControllerHelper.getViewPath(clazz);
				
				var componentRender = $.View(templateUri, viewOptions);
				
				// insert the component functions of the reference element and the given position
				switch(position){
					case 'inside_replace':
						refElement.empty();
						var $component = $(componentRender).prependTo(refElement);
						break;
						
					case 'first':
						var $component = $(componentRender).prependTo(refElement);
						break;

					case 'last':
						var $component = $(componentRender).appendTo(refElement);
						break;

					case 'before':
						var $component = $(componentRender).insertBefore(refElement);
						break;

					case 'after':
						var $component = $(componentRender).insertAfter(refElement);
						break;
				}
				
				// init the component
				return new clazz($component, options);
			}
		},
		
		/** @prototype */
        {  }
	)}
);
		