import 'mad/model/model';
import 'mad/model/serializer/cake_serializer';


/**
 * @inherits {mad.Model}
 * @parent index
 *
 * The comment model
 *
 * @constructor
 * Creates a comment
 * @param {array} data
 * @return {passbolt.model.Comment}
 */
var Comment = passbolt.model.Comment = mad.Model.extend('passbolt.model.Comment', /** @static */ {

    checkServerRules: true,

	attributes: {
		id: 'string',
		parent_id: 'string',
		foreign_model: 'string',
		foreign_id: 'string',
		content: 'string',
		created: 'string',
		modified: 'string',
		Creator: 'passbolt.model.User.model',
		Modifier: 'passbolt.model.User.model'
	},

	create: function (attrs, success, error) {
		var self = this;
		var params = mad.model.serializer.CakeSerializer.to(attrs, this);
		return mad.net.Ajax.request({
			url: APP_URL + 'comments/' + attrs.foreign_model + '/' + attrs.foreign_id + '.json',
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

	destroy: function (id, success, error) {
		var params = {id: id};
		return mad.net.Ajax.request({
			url: APP_URL + 'comments/{id}.json',
			type: 'DELETE',
			params: params,
			success: success,
			error: error
		});
	},

	findAll: function (params, success, error) {
		return mad.net.Ajax.request({
			url: APP_URL + 'comments/{foreignModel}/{foreignId}.json',
			type: 'GET',
			params: params,
			success: success,
			error: error
		});
	},

	findOne: function (params, success, error) {
		return mad.net.Ajax.request({
			url: APP_URL + 'comments/{foreignModel}/{foreignId}.json',
			type: 'GET',
			params: params,
			success: success,
			error: error
		});
	}
}, /** @prototype */ {});

export default Comment;