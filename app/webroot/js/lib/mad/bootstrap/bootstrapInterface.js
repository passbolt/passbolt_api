steal( 
    'jquery/class'
)
.then( function ($) {

	/*
	 * @class mad.bootstrap.BootstrapInterface
	 * The core Interface Bootstrap is a representation of a Bootstrap process
	 * @parent index
	 */
	$.Class('mad.bootstrap.BootstrapInterface',

	/*
	 * @prototype
	 */
	{

		/**
		 * Constructor of the Bootstrap Class
		 */
		'init': function (options) {
			throw new mad.error.CallInterfaceConstructor();
		}

		/**
		 * The bootstrap process execute this function when the process
		 * is finished
		 */
		,
		'ready': function () {
			throw new mad.error.CallInterfaceFunction();
		}

	});

});