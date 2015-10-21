steal('can/view/node_lists', 'can/view/elements.js', 'steal-qunit', function (nodeLists, elements) {

	module('can/view/live/node_lists');

	test('unregisters child nodeLists', function () {
		expect(3);
		// two spans that might have been created by #each
		var spansFrag = can.buildFragment("<span>1</span><span>2</span>");
		var spansList = can.makeArray(spansFrag.childNodes);
		
		nodeLists.register(spansList, function(){
			ok(true,"unregistered spansList");
		});
		

		// A label that might have been created by #foo
		var labelFrag = can.buildFragment("<label>l</label>");
		var labelList = can.makeArray(labelFrag.childNodes);
			
		nodeLists.register( labelList, function(){
			ok(true,"unregistered labelList");
		});
		
		// the html inside #if}
		var ifPreHookupFrag = can.frag(["~","","-",""]),
			ifChildNodes = ifPreHookupFrag.childNodes,
			ifEls = can.makeArray(ifChildNodes);
		
		// 
		elements.replace([ifChildNodes[1]], spansFrag);
		
		// 4 because 2 elements are inserted
		elements.replace([ifChildNodes[4]], labelFrag);
		
		var ifList = can.makeArray(ifPreHookupFrag.childNodes);
		
		nodeLists.register(ifList, function(){
			ok(true,"unregistered ifList");
		});
		
		deepEqual(ifList,[
			ifEls[0],
			spansList,
			ifEls[2],
			labelList
		]);
		
		
		nodeLists.update(ifList, [document.createTextNode("empty")]);
		
	});
});
