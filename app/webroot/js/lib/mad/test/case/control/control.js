import "test/bootstrap";

describe("mad.Control", function(){

	it("should inherit can.Control & mad.Control", function(){
		var control = new mad.Control($('#test-html'));
		expect(control).to.be.instanceOf(can.Control);
		expect(control).to.be.instanceOf(mad.Control);
		control.destroy();
	});

	it("should be referenced on instantiation and unreferenced on destroy", function() {
		var control = new mad.Control($('#test-html'));
		assert.isDefined(mad._controls['test-html']['mad.Control']);
		var searchedControl = mad.getControl('test-html');
        expect(searchedControl).to.not.be.undefined;
		control.destroy();
		expect(mad._controls['test-html']['mad.Control']).to.be.undefined;
		var searchedControl = mad.getControl('test-html');
		assert.isUndefined(searchedControl);
	});
});
