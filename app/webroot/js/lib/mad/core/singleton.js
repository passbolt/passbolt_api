steal( 
    'jquery/class'
)
.then( function ($) {

	/*
	 * @class mad.core.Singleton
	 * @inherits mad.core.Class
	 * @parent index
	 * 
	 * Implementation of the pattern singleton in javascript on top of the
	 * JMVC framework.
	 * <br/>
	 * There are two ways to use the singleton class :
	 * <ul><li> 
	 * Either by <b>extending</b> directly the class
	 * @codestart
	 * mad.core.Singleton.extend('myNewSingleton',{},{});
	 * @codeend
	 * </li><li>
	 * Or by <b>augmenting</b> an existing class with the Singleton class
	 * @codestart
	 * MyExistingClass.augment('mad.core.Singleton');
	 * @codeend
	 * </li></ul>
	 * 
	 * @constructor
	 * Instanciate the Singleton class.
	 * <br/> <b>private</b>
	 * @return {mad.core.Singleton}
	 */
	$.Class('mad.core.Singleton',

	/** @static */

	{
		/**
		 * Singleton instance
		 * @type {jQuery.Class}
		 */
		'instance': null,

		/**
		 * Get instance of the singleton
		 * @return {jQuery.Class}
		 */
		'singleton': function () {
			var returnValue = null;

			if (this.instance != null) {
				returnValue = this.instance;
			} else {
				this.instance = 'CALL_FROM_SINGLETON';
				returnValue = this.newInstance.apply(this, arguments);
			}

			return returnValue;
		}
	},

	/** @prototype */
	{

		/**
		 * Class Constructor
		 * @private
		 */
		'init': function () {
			if (this.Class.fullName == 'mad.core.Singleton') {
				throw new mad.error.CallAbstractFunction();
			} else if (this.Class.instance != 'CALL_FROM_SINGLETON') {
				throw new mad.error.CallPrivateFunction();
			}

			this.Class.instance = this;
		}

	});

});