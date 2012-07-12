//js passbolt/scripts/doc.js

//load('steal/rhino/rhino.js');
steal("documentjs").then(function(){
	DocumentJS('lib/mad/mad.html', {
		markdown : ['passbolt']
	});
});