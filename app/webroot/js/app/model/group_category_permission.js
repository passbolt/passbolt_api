import 'mad/model/model';
import 'app/model/category';
import 'app/model/permission_type';
import 'mad/model/serializer/cake_serializer';


/**
 * @inherits {mad.model.Model}
 * @parent index
 *
 * Group category permission model
 *
 * @constructor
 * Creates a resource
 * @param {array} data
 * @return {passbolt.model.GroupCategoryPermission}
 */
var GroupCategoryPermission = passbolt.model.GroupCategoryPermission = mad.model.Model.extend('passbolt.model.GroupCategoryPermission', /** @static */ {
	attributes: {
		'permission_id': 'string',
		'permission_type': 'number'
	}
}, {});

export default GroupCategoryPermission;