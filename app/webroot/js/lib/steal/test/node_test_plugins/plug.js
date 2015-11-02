import {prefix} from 'plugin-dep';

export function translate(load) {
	
	return "define(function(){"+
		"return function(){"+
			"return '"+prefix()+"-"+load.source+"'"+
		"}"+
	"})";
	
};
