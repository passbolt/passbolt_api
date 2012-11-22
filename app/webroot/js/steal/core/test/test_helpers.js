var TH = {
	isArray : function (o) {
		return Object.prototype.toString.call(o) === '[object Array]';
	},
	isDeferred : function(obj){
		return TH.isArray(obj.doneFuncs) && TH.isArray(obj.failFuncs);
	}
}