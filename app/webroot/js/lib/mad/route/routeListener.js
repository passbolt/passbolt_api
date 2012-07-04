steal( 
    MAD_ROOT+'/core/singleton.js'
)
.then( 
    function($){
        
        /*
        * @class mad.route.RouteListener
        * The route listener will be one of the stone reference of the application.
        * It will be the guarantor of the route change
        * 
        * @parent index
        * @constructor
        * Creates a new route listener
        * @return {mad.route.RouteListener}
        */
        mad.core.Singleton('mad.route.RouteListener', 
                
        /** @static */
        
        {},
        
        /** @prototype */
        {
            'init': function()
            {
                // load the routes to listen
                $.route(":extension/:controller/:action/:p1/:p2/:p3/:p4/:p5");
                $.route(":extension/:controller/:action/:p1/:p2/:p3/:p4");
                $.route(":extension/:controller/:action/:p1/:p2/:p3");
                $.route(":extension/:controller/:action/:p1/:p2");
                $.route(":extension/:controller/:action/:p1");
                $.route(":extension/:controller/:action");
                $.route(":extension/:controller");        
                $.route(":extension");
                $.route("");
                $.route.ready();
                
                // listen the special haschange event, disptatch when a new route is comming
                // @note : Using the $.route.bind('change', function(){ ... }) is maybe the proper method, but it seems impossible to listen the whole change (extension+controler+action)
                $(window).bind('hashchange', function(){
                    if(mad.eventBus) mad.eventBus.trigger(mad.APP_NS_ID+'_route_change', {});
                });
            }
        });
    }
);