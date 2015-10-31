import "test/bootstrap";
import "mad/control/bus";

describe("mad.Bus", function(){

    it("should inherit can.Control & mad.Control", function(){
        var bus = new mad.Bus($('#test-html'));
        expect(bus).to.be.instanceOf(can.Control);
        expect(bus).to.be.instanceOf(mad.Control);
        bus.destroy();
    });

    it("trigger() & bind(): should help to broadcast & intercept message on the bus", function() {
        var caught = false;
        var bus = new mad.Bus($('#test-html'));
        bus.bind('event_name', function() {
            caught = true;
        });
        bus.trigger('event_name');
        expect(caught).to.be.true;
        bus.destroy();
    });
});
