steal(
	'can/construct'
).then(function () {

	/*
	 * @class mad.core.Singleton
	 * @inherits mad.core.Class
	 * @parent mad.core
	 * 
	 * This class is our implementation of the pattern singleton on top of the
	 * javascriptMVC framework. There are two ways to use the singleton class :
	 * <ul>
	 *	<li> 
	 *		Either by <b>extending</b> directly the class
	 *		@codestart
	mad.core.Singleton.extend('myNewSingletonClass',{},{});
	 *		@codeend
	 *	</li>
	 *	<li>
	 *		Or by <b>augmenting</b> an existing class with the Singleton class
	 *		@codestart
	MyExistingClass.augment('mad.core.Singleton');
	 *		@codeend
	 *	</li>
	 * </ul>
	 * 
	 * Once your class is a singleton, the only way to get the instance is to call
	 * the static <i>singleton</i> method which expects the same parameters than the
	 * constructor of the class.
	 * 
	 * @codestart
	MyClass.singleton([CLASS_PARAMS]);
	 * @codeend
	 */
	can.Construct('mad.core.Singleton', /** @static */ {
		/**
		 * Singleton instance
		 * @type {jQuery.Class}
		 */
		'singletonInstance': null,

		/**
		 * Get the singleton instance
		 * @return {jQuery.Class}
		 */
		'singleton': function () {
			var returnValue = null;

			if (this.singletonInstance != null) {
				returnValue = this.singletonInstance;
			} else {
				this.singletonInstance = 'CALL_FROM_SINGLETON';
				returnValue = this.newInstance.apply(this, arguments);
			}

			return returnValue;
		}
	}, /** @hide @prototype */ {

		// Constructor like
		'init': function () {
			if (this.getClass().fullName == 'mad.core.Singleton') {
				throw new mad.error.CallAbstractFunctionException();
			} else if (this.getClass().singletonInstance != 'CALL_FROM_SINGLETON') {
				throw new mad.error.CallPrivateFunctionException('The class is a singleton');
			}

			this.getClass().singletonInstance = this;
		}

	});

});