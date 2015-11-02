export function translate(load) {
	
	return "define(function(){"+
		"return '"+load.source+"';"+
	"})";
	
};