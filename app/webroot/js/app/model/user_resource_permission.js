import 'mad/model/model';
import 'app/model/permission_type';
import 'mad/model/serializer/cake_serializer';

/**
 * @inherits {mad.Model}
 * @parent index
 *
 * User resource permission model
 *
 * @constructor
 * Creates a resource
 * @param {array} data
 * @return {passbolt.model.UserResourcePermission}
 */
var UserResourcePermission = passbolt.model.UserResourcePermission = mad.Model.extend('passbolt.model.UserResourcePermission', /** @static */ {
	attributes: {
		'permission_id': 'string',
		'permission_type': 'number'
	}
}, {});
export default UserResourcePermission;