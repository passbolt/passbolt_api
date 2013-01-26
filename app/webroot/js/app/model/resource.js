steal(
	'jquery/model',
	'app/model/category.js',
	'mad/model/serializer/cakeSerializer.js'
).then(function () {

	/*
	 * @class passbolt.model.Resource
	 * @inherits {mad.model.Model}
	 * @parent index
	 * 
	 * The resource model
	 * 
	 * @constructor
	 * Creates a resource
	 * @param {array} data 
	 * @return {passbolt.model.Resource}
	 */
	mad.model.Model('passbolt.model.Resource', /** @static */ {

		'validateRules': {
			'name': ['alphanum', 'required'],
			'username': ['alphanum', 'required', 'nospace'],
//			'uri': ['required', 'uri'],
			'description': ['text']
		},

		attributes: {
			'id': 'string',
			'name': 'string',
			'username': 'string',
			'uri': 'string',
			'modified': 'date',
			'created': 'date',
			'description': 'string',
			'Category': 'passbolt.model.Category.models'
		},

		'create' : function (attrs, success, error) {
			var self = this;
			var params = mad.model.serializer.CakeSerializer.to(attrs, this);
			return mad.net.Ajax.request({
				url: APP_URL + '/resources',
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

		'destroy' : function (id, success, error) {
			var params = {id:id};
			return mad.net.Ajax.request({
				url: APP_URL + '/resources/{id}',
				type: 'DELETE',
				params: params,
				success: success,
				error: error
			});
		},

		'findAll': function (params, success, error) {
			return mad.net.Ajax.request({
				url: APP_URL + '/resources',
				type: 'GET',
				params: params,
				success: success,
				error: error
			});
		},

		'findOne': function (params, success, error) {
			return mad.net.Ajax.request({
				url: APP_URL + '/resources/{id}',
				type: 'GET',
				params: params,
				success: success,
				error: error
			});
		},

		'update' : function(id, attrs, success, error) {
			var self = this;
			// remove not desired attributes
			delete attrs.created;
			delete attrs.modified;
			// format data as expected by cakePHP
			var params = mad.model.serializer.CakeSerializer.to(attrs, this);
			// add the root of the params, it will be used in the url template
			params.id = id;
			return mad.net.Ajax.request({
				url: APP_URL + '/resources/{id}',
				type: 'PUT',
				params: params,
				success: success,
				error: error
			}).pipe(function (data, textStatus, jqXHR) {
				// pipe the result to convert cakephp response format into can format
				var def = $.Deferred();
				def.resolveWith(this, [mad.model.serializer.CakeSerializer.from(data, self)]);
				return def;
			});
		}

	}, /** @prototype */ {
		
		/**
		 * Override the constructor function
		 * Listen change on Category, and update the model when a category has been destroyed
		 */
		'init': function () {
			var self = this;
			// Listen when a category is destroyed
			passbolt.model.Category.bind('destroyed', function(ev, category) {
				var destroyedCategories = mad.model.Model.nestedToList(category, 'children', 'id');
				var toUpdate = false;
				can.each(self.Category, function(resourceCategory, i) {
					if(destroyedCategories.indexOf(resourceCategory.id) != -1) {
						self.Category.splice(i, 1);
						toUpdate = true;
					}
				});
				if (toUpdate) {
					can.trigger(passbolt.model.Resource,'updated', self);
				}
			});
		}
		
		'destroy': function () {
			
		}
	});
});
