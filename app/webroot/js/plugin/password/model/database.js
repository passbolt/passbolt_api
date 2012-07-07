steal( 
    'jquery/model'
    )
.then(function(){
    $.Model('password.model.Database',
	/** @static */
	{
		/**
		 * Get all passbolt databases
		 */
		'getAll': function()
		{
			throw new Error('Not implemented yet')
		},
		
		/**
		 * Get a passbolt database
		 */
        'get' : function(params, success, error)
		{
            throw new Error('Not implemented yet')
        }
    },
	/** @prototype */
    {
    }
    );
})
