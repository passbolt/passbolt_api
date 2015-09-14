import 'mad/model/model';
import 'app/model/user';
import 'mad/model/serializer/cake_serializer';


/**
 * @inherits {mad.Model}
 * @parent index
 *
 * The gpgkey model
 *
 * @constructor
 * Creates a gpgkey
 * @param {array} data
 * @return {passbolt.model.Gpgkey}
 */
var Gpgkey = passbolt.model.Gpgkey = mad.Model.extend('passbolt.model.Gpgkey', /** @static */ {

	attributes: {
		id: 'string',
		user_id: 'string',
		key: 'string',
		bits: 'string',
		uid: 'string',
		key_id: 'string',
		fingerprint: 'string',
		type: 'string',
		expires: 'string',
		key_created: 'string',
		User : 'passbolt.model.User.model'
	},

	findAll: function (params, success, error) {
		// a filter is provided, format it as GET request parameter
		if(typeof params.filter != 'undefined') {
			var filer = params.filter;
			delete params.filter;
			// add the filter to the request param
			var formatedFilter = filer.toRequest();
			$.extend(params, formatedFilter);
		}

		return mad.net.Ajax.request({
			url: APP_URL + 'gpgkeys.json',
			type: 'GET',
			params: params,
			success: success,
			error: error
		});
	},

	findOne: function (params, success, error) {
		return mad.net.Ajax.request({
			url: APP_URL + 'gpgkeys/{id}.json',
			type: 'GET',
			params: params,
			success: success,
			error: error
		});
	}

}, /** @prototype */ {


});

export default Gpgkey;