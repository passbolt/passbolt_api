import 'mad/model/model';
import 'mad/model/serializer/cake_serializer';

/**
 * @inherits {mad.Model}
 * @parent index
 *
 * The group model
 *
 * @constructor
 * Creates a group
 *
 * @param {array} data
 * @return {passbolt.model.Group}
 */
var Group = passbolt.model.Group = mad.Model.extend('passbolt.model.Group', /** @static */ {

	/**
	 * The rules to validate this model are available on the server.
	 */
	checkServerRules: true,

	/**
	 * Attributes.
	 */
	attributes: {
		id: 'string',
		name: 'string',
		created: 'string',
		modified: 'string',
		Modifier: 'passbolt.model.User.model',
		GroupUser: 'passbolt.model.GroupUser.models'
	},

	/**
	 * Find all Groups.
	 * @param params
	 * @param success
	 * @param error
	 * @returns {deferred|*|request|request|request|request}
	 */
	findAll: function (params, success, error) {
		return mad.net.Ajax.request({
			url: APP_URL + '/groups.json',
			type: 'GET',
			params: params,
			success: success,
			error: error
		});
	},

	/**
	 * Find one Group.
	 * @param params
	 * @param success
	 * @param error
	 * @returns {deferred|*|request|request|request|request}
	 */
	findOne: function (params, success, error) {
		return mad.net.Ajax.request({
			url: APP_URL + 'groups/{id}.json',
			type: 'GET',
			params: params,
			success: success,
			error: error
		});
	},

    /**
     * Destroy a group following the given parameter
     * @params {string} id the id of the instance to remove
     * @return {jQuery.Deferred)
	 */
    destroy : function (id, success, error) {
        var params = {id:id};
        return mad.net.Ajax.request({
            url: APP_URL + 'groups/{id}.json',
            type: 'DELETE',
            params: params,
            success: success,
            error: error
        });
    }

}, /** @prototype */ {

	/**
	 * Check if a user is a group manager of the group.
	 * @param user
	 * @returns {boolean}
	 */
	isGroupManager: function(user) {
		var isGroupManager = false;

		if(this.GroupUser != undefined) {
			this.GroupUser.forEach(function(groupUser) {
				if (groupUser.user_id == user.id && groupUser.is_admin == true) {
					isGroupManager = true;
				}
			});
		}

		return isGroupManager;
	},

	/**
	 * Check if a user can edit a group.
	 * @param user
	 * @returns {boolean}
	 */
	isAllowedToEdit: function(user) {
		var isGroupManager = this.isGroupManager(user),
			isAdmin = user.Role.name == 'admin';
		return isGroupManager || isAdmin;
	},


    /**
     * Attempt a dry run of delete.
     *
     * @param id
     * @param attrs
     * @param success
     * @param error
     * @returns {*|jQuery.deferred}
     */
    deleteDryRun : function(id, attrs, success, error) {
        var params = {id:id};
        return mad.net.Ajax.request({
            url: APP_URL + 'groups/{id}/dry-run.json',
            type: 'DELETE',
            params: params,
            success: success,
            error: error,
			silentNotify: true
        });
    }

});

export default Group;
