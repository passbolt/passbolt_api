//js passbolt/scripts/doc.js

load('steal/rhino/rhino.js');
steal("documentjs").then(function(){
	DocumentJS('passbolt/passbolt.html', {
		markdown : ['passbolt']
	});
});