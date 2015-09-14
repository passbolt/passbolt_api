import 'mad/model/model';
import 'app/model/category';
import 'app/model/permission_type';
import 'mad/model/serializer/cake_serializer';


/**
 * @class passbolt.model.UserCategoryPermission
 * @inherits {mad.model.Model}
 * @parent index
 *
 * User category permission model
 *
 * @constructor
 * Creates a resource
 * @param {array} data
 * @return {passbolt.model.UserCategoryPermission}
 */
var UserCategoryPermission = passbolt.model.UserCategoryPermission = mad.model.Model.extend('passbolt.model.UserCategoryPermission', /** @static */ {
	attributes: {
		'permission_id': 'string',
		'permission_type': 'number'
	}
}, {});

export default UserCategoryPermission;