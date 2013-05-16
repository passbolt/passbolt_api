steal(
	'can/util/fixture',
	'mad/model',
	'mad/test/data/model/country.js'
).then(function () {

	var PERSONS = '';
	// get all the persons
	var jsonFile = steal.idToUri('mad/test/data/fixtures/persons.json').toString();
	can.fixture("/persons", jsonFile);
	// get a target person
	can.fixture("/persons/{id}", function (req) {
		var returnValue = null;
		$.ajax({
			url: jsonFile,
			async: false
		}).then(function (data) {
			can.each(data.body, function (person, i) {
				if (person.Person.id == req.originParams.id) {
					returnValue = {
						header: data.header,
						body: person
					};
				}
			});
		});
		return returnValue;
	});

	/**
	 * Demonstration model based on common Person profile
	 */
	mad.model.Model.extend('demo.model.Person', {

/* ************************************************************** */
/* MODEL DEFINITION */
/* ************************************************************** */

		'validateRules': {
			'surname': ['alphanum', 'required'],
			'name': ['alphanum', 'required'],
			'freelancer': ['required'],
			'phone': ['required', 'num'],
			'email': ['required', 'email']
		},

		'attributes': {
			'surname': 'string',
			'name': 'string',
			'freelancer': 'boolean',
			'phone': 'string',
			'email': 'string',
			'children': 'demo.model.Person.models',
			'visited': 'demo.model.Country.models'
		},

/* ************************************************************** */
/* CRUD FUNCTION */
/* ************************************************************** */

		/**
		 * Find a person following the given parameter
		 * @param {array} params Optional parameters
		 * @return {jQuery.Deferred)
		 */
		'findOne': function (params, success, error) {
			return mad.net.Ajax.request({
				'type': 'GET',
				'url': '/persons/{id}',
				'params': params,
				'success': success,
				'error': error
			});
		},

		/**
		 * Find a bunch of persons following the given parameters
		 * @param {array} params Optional parameters
		 * @return {jQuery.Deferred)
		 */
		'findAll': function (params, success, error) {
			return mad.net.Ajax.request({
				'async': false,
				'type': 'GET',
				'url': '/persons',
				'params': params,
				'success': success,
				'error': error
			});
		}

	}, /** @prototype */ { });

});