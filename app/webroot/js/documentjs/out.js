steal("./json",function(toJSON){
	return function(data, how, Char, filename) {
		try {
			var converted =  toJSON(data, how);
			return (Char|| "C")+"(" + converted + ")"
		} catch(e) {
			print(e + ' (' + filename + ')')
		}
		
	}
})
