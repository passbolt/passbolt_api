steal(function(){
	steal.client = {};
	steal.client.dataQueue = [];
	steal.client.trigger = function(type, data){
		steal.client.dataQueue.push({
			type: type,
			data: data
		});
	};
})

// used for instrumentation

// in firefox, console window has chrome:// url
// accessing properties causes error
try{
	top.opener.steal = window.steal;
}catch(e){}
