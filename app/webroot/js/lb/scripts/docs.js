//js lb/scripts/doc.js

load('steal/rhino/rhino.js');
steal("documentjs").then(function(){
	DocumentJS('lb/lb.html', {
		markdown : ['lb']
	});
});