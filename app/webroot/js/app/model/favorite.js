import 'mad/model/model';
import 'app/model/secret';
import 'mad/model/serializer/cake_serializer';

/**
 * @inherits {mad.Model}
 * @parent index
 *
 * The favorite model
 *
 * @constructor
 * Creates a favorite
 * @param {array} data
 * @return {passbolt.model.Favorite}
 */
var Favorite = passbolt.model.Favorite = mad.Model.extend('passbolt.model.Favorite', /** @static */ {

	validateRules: {
		user_id: [],
		foreign_model: [],
		foreign_id: []
	},

	attributes: {
		id: 'string',
		user_id: 'string',
		foreign_model: 'string',
		foreign_id: 'string'
	},

	create : function (attrs, success, error) {
		var params = mad.model.serializer.CakeSerializer.to(attrs, this);
		return mad.net.Ajax.request({
			url: APP_URL + '/favorites/' + attrs['foreign_model'].toLowerCase() + '/' + attrs['foreign_id'],
			type: 'POST',
			params: params,
			success: success,
			error: error
		}).pipe(function (data, textStatus, jqXHR) {
			// pipe the result to convert cakephp response format into can format
			// else the new attribute are not well placed
			var def = $.Deferred();
			def.resolveWith(this, [mad.model.serializer.CakeSerializer.from(data, self)]);
			return def;
		});
	},

	destroy : function (id, success, error) {
		var params = {id:id};
		return mad.net.Ajax.request({
			url: APP_URL + '/favorites/' + id,
			type: 'DELETE',
			params: params,
			success: success,
			error: error
		});
	}

}, /** @prototype */ {

});

export default Favorite;
