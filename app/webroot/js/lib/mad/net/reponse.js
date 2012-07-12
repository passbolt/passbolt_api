steal( 
	'jquery/class'
	)
.then( 
	function($){
        
		/*
        * @class mad.net.Response
		* 
		* @inherits {mad.core.Class}
        * @parent index
		* 
        * The ajax response type
		* 
        * @constructor
        * Creates a new ajax wrapper
        * @return {mad.net.Response}
        */
		$.Class('mad.net.Response', 
                
			/** @static */
			{ },

			/** @prototype */
			{
				'data': null,
				'message':null,
				'object':null,
				'responseId':null,
				'status':null
			}
		);
        
	}
);
