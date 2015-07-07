/**
 * Our array utilities.
 * @type {{}}
 */
mad.array = {};

/**
 * Return the intersection of two arrays given in parameter
 *
 * @param arr1
 * @param arr2
 * @returns {*}
 */
mad.array.intersect = function(arr1, arr2) {
	return arr1.filter(function(n) {
		return arr2.indexOf(n) > -1;
	});
};
