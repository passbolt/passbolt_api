import mad from 'mad/util/util';

/**
 * Our array utilities.
 */
mad.array = mad.array || {};

/**
 * Return the intersection of two arrays given in parameter
 *
 * @param arr1
 * @param arr2
 * @return {[]} The intersection of the two arrays.
 */
mad.array.intersect = function(arr1, arr2) {
	return arr1.filter(function(n) {
		return arr2.indexOf(n) > -1;
	});
};

export default mad.array;
