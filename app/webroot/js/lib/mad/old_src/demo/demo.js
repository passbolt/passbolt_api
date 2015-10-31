$.ajax({
	url: jsonFile,
	async: false,
	dataType: 'json',
	success: function (data) {
		$.extend(true, mad.config, data);
	}
});