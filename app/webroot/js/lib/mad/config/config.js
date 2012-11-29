steal(
	'jquery/class'
).then(function () {

	// create the config namespace
	can.getObject('mad.config', null, true);

	/*
	 * @class mad.config.Config
	 */
	$.Class('mad.Config', /** @static */ {

		/**
		 * Load a config file
		 * @param {string} url Url of the config file to load
		 * @return {void}
		 */
		'load': function (url) {
			var jsonFile = steal.idToUri(url).toString();
			$.ajax({
				url: jsonFile,
				async: false,
				dataType: 'json',
				success: function (data) {
					$.extend(true, mad.config, data);
				}
			});
		},

		/**
		 * Read a config
		 * @param {string} name The name of the config
		 * @return {mixed}
		 */
		'read': function (name) {
			return can.getObject(name, mad.config);
		},

		/**
		 * Write a config
		 * @param {string} name The name of the config
		 * @param {mixed} value The value of the config
		 * @return {void}
		 */
		'write': function (name, value) {
			can.getObject(name, mad.config, true, value);
		}
	}, /** @prototype */ {
	});

});