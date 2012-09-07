steal(
	'jquery/dom/fixture',
	MAD_ROOT + '/controller/controller.js'
).then(function ($) {

	/*
	 * @class passbolt.controller.ResourceController
	 * @inherits mad.controller.Controller
	 * @parent index
	 * 
	 * @constructor
	 * 
	 * @return {passbolt.controller.ResourceController}
	 */
	mad.controller.Controller.extend('passbolt.controller.ResourceController', /** @static */{

		'create': function () {
			steal.dev.log('add new resource');
		},

		'getCategoryResources': function (options, callback) {
			passbolt.model.Resource.getCategoryResources({
				'category_id': options.category_id
			}, function (resources) {
				callback(resources);
			});
		},

		'update': function () {
			steal.dev.log('update password');
		},

		'delete': function () {
			steal.dev.log('delete password');
		}
		
	}, /** @prototype */ {

		'defaults': {
			
		}

	});
});