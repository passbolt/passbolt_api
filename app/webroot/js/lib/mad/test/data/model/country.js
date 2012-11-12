steal(
	'can/util/fixture',
	'mad/model'
).then(function () {

	var COUNTRIES = [];

	// get all the countries
	can.fixture("/countries", '//mad/test/data/fixtures/countries.json');
	// get a target country
	can.fixture("/countries/{id}", function (req) {
		var returnValue = null;
		$.ajax({
			url: '/js/mad/test/data/fixtures/countries.json',
			async: false
		}).then(function (data) {
			can.each(data.body, function (country, i) {
				if (country.Country.id == req.originParams.id) {
					returnValue = {
						header: data.header,
						body: country
					};
				}
			});
		});
		return returnValue;
	});

	/**
	 * Demonstration model based on common Country data
	 */
	mad.model.Model.extend('demo.model.Country', {

/* ************************************************************** */
/* MODEL DEFINITION */
/* ************************************************************** */

		'validateRules': {
			'label': ['alphanum', 'required']
		},

/* ************************************************************** */
/* CRUD FUNCTION */
/* ************************************************************** */

		/**
		 * Find a country following the given parameter
		 * @params {array} params Optional parameters
		 * @return {jQuery.Deferred)
		 */
		'findOne': function (params, success, error) {
			return mad.net.Ajax.request({
				'type': 'GET',
				'url': '/countries/{id}',
				'params': params,
				'success': success,
				'error': error
			});
		},

		/**
		 * Find a bunch of countries following the given parameters
		 * @params {array} params Optional parameters
		 * @return {jQuery.Deferred)
		 */
		'findAll': function (params, success, error) {
			return mad.net.Ajax.request({
				'async': false,
				'type': 'GET',
				'url': '/countries',
				'params': params,
				'success': success,
				'error': error
			});
		}

	}, /** @prototype */ { });

});