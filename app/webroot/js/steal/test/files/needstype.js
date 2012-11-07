steal.type("needs js", function(options, success, error){
	var parts = options.text.split(" ")
	options.text = parts[0]+"='"+parts[1]+"'";
	success();
});