import 'mad/model/model';
import 'app/model/tag';
import 'mad/model/serializer/cake_serializer';


/**
 * @inherits {mad.Model}
 * @parent index
 *
 * The itemTag model
 *
 * @constructor
 * Creates an ItemTag
 * @param {array} data
 * @return {passbolt.model.ItemTag}
 */
var ItemTag = passbolt.model.ItemTag = mad.Model.extend('passbolt.model.ItemTag', /** @static */ {

	validateRules: {
		foreign_model: ['text'],
		foreign_id: ['uid'],
		tag_id: ['uid'],
		tag_list: ['tag_list']
	},

	attributes: {
		id: 'string',
		tag_id: 'string',
		foreign_model: 'string',
		foreign_id: 'string',
		created: 'string',
		modified: 'string',
		created_by: 'string',
		modified_by: 'string',
		Tag: 'passbolt.model.Tag.model'
	},

	create: function (attrs, success, error) {
		var self = this;
		var params = mad.model.serializer.CakeSerializer.to(attrs, this);
		return mad.net.Ajax.request({
			url: APP_URL + 'itemTags/' + attrs.foreign_model + '/' + attrs.foreign_id,
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

	/**
	 * Create/Edit ItemTags in bulk
	 * @param attrs
	 * 	- for this action, a new param tag_list can be passed. It is a list of tags separated by commas.
	 * @param success
	 * @param error
	 * @returns {jQuery.Deferred}
	 */
	createBulk: function (attrs, success, error) {
		var self = this;
		var params = mad.model.serializer.CakeSerializer.to(attrs, this);
		return mad.net.Ajax.request({
			url: APP_URL + 'itemTags/updateBulk/' + attrs.foreign_model + '/' + attrs.foreign_id,
			type: 'POST',
			params: params,
			success: success,
			error: error
		}).pipe(function (data, textStatus, jqXHR) {
				var def = $.Deferred();
				def.resolveWith(this, [mad.model.serializer.CakeSerializer.from(data, self)]);
				return def;
			});
	},

	destroy: function (id, success, error) {
		var params = {id: id};
		return mad.net.Ajax.request({
			url: APP_URL + '/itemTags/{id}',
			type: 'DELETE',
			params: params,
			success: success,
			error: error
		});
	},

	findAll: function (params, success, error) {
		return mad.net.Ajax.request({
			url: APP_URL + 'itemTags/{foreignModel}/{foreignId}',
			type: 'GET',
			params: params,
			success: success,
			error: error
		});
	}
}, /** @prototype */ {

});

export default ItemTag;