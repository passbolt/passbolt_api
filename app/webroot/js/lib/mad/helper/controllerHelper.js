steal( 
    'jquery/class'
)
.then( 
    function($){
        
        /**
         * The controller class helper offers to the developper tools arround controllers
         */
        $.Class('mad.helper.controllerHelper', {
            
            /**
             * Get controller path
             * @deprecated
             */
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
            
            /**
             * Get view path of a controller.
             * <br/>
             * This function can determine view path for controllers from :
             * <br/>
             * <ul>
             *   <li>the mad squirrel library itself</li>
             *   <li>the application</li>
             *   <li>the plugins of the application</li>
             * </ul>
             * <br/><br/>
             * For controllers from the mad squirrel library (mad.controller.component.ContainerController):
             * <br/>
             * MAD_ROOT/view/template/component/containerController.ejs
             * <br/><br/>
             * For controllers from the application (passbolt.controller.PasswordWorkspaceController):
             * <br/>
             * passbolt/view/template/passwordWorkspaceController.ejs
             * <br/><br/>
             * For controllers from the plugins of the application (passbolt.activity.controller.activityWorkspaceController):
             * <br/>
             * passbolt/plugin/activity/view/template/activityWorkspaceController.ejs
             * 
             * @param {jQuery.Controller} clazz Controller to determine the view path
             * @return {string}
             */
            'getViewPath': function(clazz)
            {
                var returnValue = '';
                
                var clazzName = clazz.fullName;
                var split = clazzName.split('.');
                
                // extract the controller name, and treat it to find its view name
                var controllerName = split.pop();
                var viewName = $.String.camelize(controllerName.substr(0, controllerName.indexOf('Controller')));
                
                // extract namespace
                if(split[0]=='mad'){
                    returnValue = MAD_ROOT;
                    split = split.splice(1);
                }
                else if(split[0] == mad.getGlobal('APP_NAMESPACE_ID')){
                    //we are in a plugin
                    if(split[1]!='controller'){
                        returnValue = 'plugin/'+split[1];
                        split = split.splice(2);
                    }
                    //else we are in the application
                    else {
                        returnValue = 'app';
                        split = split.splice(1);
                    }
                }
                
                //the next in the split has to be the controller, else there is an error in the controller name
                if(split[0] != 'controller'){
                    throw new Error('Controller name mal formed');
                }
                split = split.splice(1);
                
                // target the view folder
                returnValue += '/view/template/';
                if(split.length)
                    returnValue += split.join('/') + '/';
                
                // add the view name (et voila batard)
                returnValue += viewName + '.ejs';
                
                return '//'+returnValue;
            }
            
        }
        , {
            /** 
             * There is no constructor
             * @throw {mad.error.NoConstructor} 
             */
            'init' : function()
            {
                throw new mad.NoConstructor();
            }
        });
    
    }
);
