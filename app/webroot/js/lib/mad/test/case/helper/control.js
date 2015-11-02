import "test/bootstrap";

describe("mad.helper.Control", function(){

    // Initialize the helper namespace for tests.
    mad.test.helper = mad.test.helper || {};

	beforeEach(function(){
		mad.Config.write('app.namespace', 'testapp');
	});

	afterEach(function(){
		mad.Config.flush();
	});

    it("getViewPath() should build the template path based on mad's Controls full name", function(){
        mad.test.helper.Control1 = mad.Control.extend('mad.test.helper.Control1', {}, {});
        var path = mad.helper.Control.getViewPath(mad.test.helper.Control1);
        expect(path).to.be.equal('mad/view/template/test/helper/control1.ejs');
    });

    it("getViewPath() should build the template path based on application's Controls full name", function(){
        mad.test.helper.Control1 = mad.Control.extend('testapp.control.Control1', {}, {});
        var path = mad.helper.Control.getViewPath(mad.test.helper.Control1);
        expect(path).to.be.equal('testapp/view/template/control/control1.ejs');
    });

    it("getViewPath() should build the template path based on out of context Controls name", function(){
        mad.test.helper.Control1 = mad.Control.extend('out_of_context.control.Control1', {}, {});
        var path = mad.helper.Control.getViewPath(mad.test.helper.Control1);
        expect(path).to.be.equal('testapp/view/template/out_of_context/control/control1.ejs');
    });

});
