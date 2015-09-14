import 'mad/model/model';
import 'mad/model/serializer/cake_serializer';

/**
 * @inherits {mad.model.Model}
 * @parent index
 *
 * The Tag model
 *
 * @constructor
 * Creates an ItemTag
 * @param {array} data
 * @return {passbolt.model.Tag}
 */
var Tag = passbolt.model.Tag = mad.model.Model.extend('passbolt.model.Tag', /** @static */ {

	validateRules: {
		name: ['text']
	},

	attributes: {
		id: 'string',
		name: 'string',
		created: 'string',
		modified: 'string',
		created_by:'string',
		modified_by: 'string'
	},

	create: function (attrs, success, error) {
		var self = this;
		var params = mad.model.serializer.CakeSerializer.to(attrs, this);
		return mad.net.Ajax.request({
			url: APP_URL + 'tags',
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
			url: APP_URL + '/tags/{id}',
			type: 'DELETE',
			params: params,
			success: success,
			error: error
		});
	},

	findAll: function (params, success, error) {
		return mad.net.Ajax.request({
			url: APP_URL + 'tags',
			type: 'GET',
			params: params,
			success: success,
			error: error
		});
	}

}, /** @prototype */ {

});

export default Tag;