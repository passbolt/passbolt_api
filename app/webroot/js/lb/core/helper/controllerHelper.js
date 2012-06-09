steal( 
    'jquery/class'
)
.then( 
    function($){
        
        $.Class('lb.core.helper.controllerHelper', {
            
            'getControllerPath': function(clazz)
            {
                var returnValue = '';
                
                var clazzName = clazz.fullName;
                var split = clazzName.split('.');
                var controllerName = split.pop();
                returnValue = split.join('/');
                returnValue += '/' + controllerName.substr(0,1).toLowerCase() + controllerName.slice(1) + '.js';
                
                return returnValue;
            },
            
            'getViewPath': function(clazz)
            {
                var returnValue = '';
                
                var clazzName = clazz.fullName;
                var split = clazzName.split('.');
                var controllerName = split.pop();
                split[split.indexOf('controller')] = 'view/template'
                returnValue = split.join('/');
                var viewName = controllerName.substr(0, controllerName.indexOf('Controller'));
                returnValue += '/' + viewName.substr(0,1).toLowerCase() + viewName.slice(1) + '.ejs';
                
                return returnValue;
            }
            
        }
        , {
            /**
             * There is no constructor
             * @throw {lb.core.NoConstructor}
             */
            'init' : function(){
                throw new lb.core.NoConstructor();
            }
        });
    
    }
);
