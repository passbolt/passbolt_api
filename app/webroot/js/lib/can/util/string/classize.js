steal('./string', function(){
	
/**
 * Like [can.camelize|camelize], but the first part is also capitalized
 * @param {String} s
 * @return {String} the classized string
 */
can.classize =  function( s , join) {
	// this can be moved out ..
	// used for getter setter
	var parts = s.split(can.undHash),
		i = 0;
	for (; i < parts.length; i++ ) {
		parts[i] = can.capitalize(parts[i]);
	}

	return parts.join(join || '');
}
	
	
})
