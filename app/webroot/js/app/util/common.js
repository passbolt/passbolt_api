import jsSHA from 'sha1';
import XRegExp from 'xregexp';
import 'mad/component/component';

/**
 * @inherits mad.Component
 *
 * Passbolt common helper.
 */
var Common = passbolt.Common = mad.Component.extend('passbolt.Common', /** @static */ {
	/**
	 * Generates a predictable uuid from a string.
	 * uuid is sha1 based.
	 * @param seed
	 * @returns {String}
	 */
	uuid: function(seed) {
		// Create SHA hash from seed.
		var shaObj = new jsSHA("SHA-1", "TEXT");
		shaObj.update(seed);
		var hashStr = shaObj.getHash("HEX").substring(0, 32);
		// Build a uuid based on the md5
		var search = XRegExp('^(?<first>.{8})(?<second>.{4})(?<third>.{1})(?<fourth>.{3})(?<fifth>.{1})(?<sixth>.{3})(?<seventh>.{12}$)');
		var replace = XRegExp('${first}-${second}-3${fourth}-a${sixth}-${seventh}');
		// Replace regexp by corresponding mask, and remove / character at each side of the result.
		var uuid = XRegExp.replace(hashStr, search, replace).replace(/\//g, '');
		return uuid;
	},

    /**
     * Convert a datetime string into a js Date object.
     * @param dateTime
     * @returns {Date}
     */
    datetimeToJSDate: function(dateTime) {
        var dateTime = dateTime.split(" ");
        var date = dateTime[0];
        var time = dateTime[1];
        var dateArr = date.split('-');
        var timeArr = time.split(':');
        var dateObj = new Date(dateArr[0], dateArr[1] - 1, dateArr[2], timeArr[0], timeArr[1], timeArr[2]);
        return dateObj;
    }
}, {

});

export default Common;