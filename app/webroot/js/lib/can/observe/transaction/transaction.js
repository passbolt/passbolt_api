steal('can', function(can){
	
	var events = [],
		transactionCount = 0,
		originalBatchTrigger = can.batchTrigger,
		changedBatchTrigger = function(obj, ev){
			originalBatchTrigger.apply(this, arguments);
			if(ev === "change"){
				var args = can.makeArray(arguments);
				args[1] = "changed";
				originalBatchTrigger.apply(this, args);
			}
		},
		recordingBatchTrigger = function(obj, ev){
			originalBatchTrigger.apply(this, arguments);
			if(ev === "change"){
				var args = can.makeArray(arguments);
				args[1] = "changed";
				events.push( args );
			}
		};
	
	can.batchTrigger = changedBatchTrigger;
	
	can.transaction = function(){
		if( transactionCount === 0 ) {
			can.batchTrigger = recordingBatchTrigger;
		}
		
		
		transactionCount++;
		
		
		return function(){
			transactionCount--;
			if( transactionCount === 0 ) {
				var myEvents = events.slice(0)
				events = [];
				can.batchTrigger = changedBatchTrigger;
				can.each(myEvents, function(eventArgs){
					originalBatchTrigger.apply(can, eventArgs);
				});
			}
		}
	};
});
