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
	 * Instanciate a Resource Controller
	 * 
	 * @param {HTMLElement} element the element this instance operates on.
	 * @param {Object} [options] option values for the controller.  These get added to
	 * this.options and merged with defaults static variable 
	 * @return {passbolt.controller.ResourceController}
	 */
	mad.controller.Controller.extend('passbolt.controller.ResourceController', /** @static */ {

		'add': function (resource) {
			passbolt.model.Resource.add(resource['passbolt.model.Resource'], function (request, response, resource) {
				mad.eventBus.trigger('resource_created', resource);
				mad.eventBus.trigger('passbolt_notify', {'title': response.header.message});
			});
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

		'delete': function (resourceId) {
			passbolt.model.Resource['delete']({
				'id': resourceId
			}, function (request, response, resource) {
				mad.eventBus.trigger('resource_deleted', resourceId);
				mad.eventBus.trigger('passbolt_notify', {'title': response.header.message});
			});
		}

	}, /** @prototype */ { });
});