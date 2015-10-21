steal('./each.js', function (can) {
	can.makeArray = function (arr) {
		var ret = [];
		can.each(arr, function (a, i) {
			ret[i] = a;
		});
		return ret;
	};
	return can;
});