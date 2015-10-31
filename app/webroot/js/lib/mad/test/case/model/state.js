import "test/bootstrap";

describe("mad.model.State", function(){

	it("should inherit can.Model & mad.Model", function(){
		var state = new mad.model.State();
		expect(state).to.be.instanceOf(can.Model);
		expect(state).to.be.instanceOf(mad.Model);
	});

	it("setState() should change the current state(s)", function(){
		var state = new mad.model.State();
		expect(state.previous.length).to.be.equal(0);
		expect(state.current.length).to.be.equal(0);

		// Set the state to A.
		state.setState('A');
		expect(state.previous.length).to.be.equal(0);
		expect(state.current.length).to.be.equal(1);
		expect(state.is('A')).to.be.true;

		// Set the state to B.
		state.setState('B');
		expect(state.previous.length).to.be.equal(1);
		expect(state.current.length).to.be.equal(1);
		expect(state.is('A')).to.be.false;
		expect(state.is('B')).to.be.true;
		expect(state.was('A')).to.be.true;

		// Flush the current states list.
		state.setState();
		expect(state.previous.length).to.be.equal(1);
		expect(state.current.length).to.be.equal(0);
		expect(state.is('B')).to.be.false;
		expect(state.was('B')).to.be.true;
	});

	it("add() & remove() states", function(){
		var state = new mad.model.State();

		// Set the state to A.
		state.addState('A');
		expect(state.previous.length).to.be.equal(0);
		expect(state.current.length).to.be.equal(1);
		expect(state.is('A')).to.be.true;

		// Set the state to B.
		state.addState('B');
		expect(state.previous.length).to.be.equal(1);
		expect(state.current.length).to.be.equal(2);
		expect(state.is('A')).to.be.true;
		expect(state.is('B')).to.be.true;
		expect(state.was('A')).to.be.true;

		// Remove the state A.
		state.removeState('A');
		expect(state.previous.length).to.be.equal(2);
		expect(state.current.length).to.be.equal(1);
		expect(state.was('A')).to.be.true;
		expect(state.was('B')).to.be.true;
		expect(state.is('A')).to.be.false;
		expect(state.is('B')).to.be.true;

		// Remove the state B.
		state.removeState('B');
		expect(state.previous.length).to.be.equal(1);
		expect(state.current.length).to.be.equal(0);
		expect(state.was('A')).to.be.false;
		expect(state.was('B')).to.be.true;
		expect(state.is('A')).to.be.false;
		expect(state.is('B')).to.be.false;
	});

});
