import "test/bootstrap";
import "test/fixture/users";
import "test/helper/model";
import "mad/model/serializer/cake_serializer";

describe("mad.model.serializer.CakeSerializer", function () {

    // Test CakeSerializer : From.
    it('test CakeSerializer : From', function (done) {
        mad.net.Ajax.request({
            'type': 'GET',
            'url': '/testusers',
            'async': true,
            'dataType': 'json'
        }).then(function (data, response, request) {
            // Query is ok.
            expect(true).to.be.ok;

            // Test result.
            var user = data[0];
            var Class = mad.test.model.UserTestModel;
            var s = mad.model.serializer.CakeSerializer.from(user, Class);

            for (var attrName in Class.attributes) {
                if (!Class.isModelAttribute(attrName)) {
                    expect(user.UserTestModel[attrName]).to.be.equal(s[attrName]);
                }
                else {
                    var subMdlAttr = Class.getAttribute(attrName);
                    if (subMdlAttr.multiple) {
                        can.each(user[attrName], function (item, i) {
                            for (var subMdlAttrName in subMdlAttr.modelReference.attributes) {
                                expect(user[attrName][i][subMdlAttrName]).to.be.equal(s[attrName][i][subMdlAttrName]);
                            }
                        });

                    } else {
                        for (var subMdlAttrName in subMdlAttr.modelReference.attributes) {
                            expect(user[attrName][i][subMdlAttrName]).to.be.equal(s[attrName][i][subMdlAttrName]);
                        }
                    }
                }
            }
            done();

        }).fail(function(jqXHR, status, response, request) {
            expect(false).to.be.ok;
            done();
        });
    });

    // Test CakeSerializer : To.
    it('test CakeSerializer : To', function (done) {
        mad.net.Ajax.request({
            'type': 'GET',
            'url': '/testusers',
            'async': true,
            'dataType': 'json'
        }).then(function (data, response, request) {
            var user = data[0];
            var Class = mad.test.model.UserTestModel;
            var s = mad.model.serializer.CakeSerializer.to(user, Class);

            expect(true).to.be.ok;

            for (var attrName in Class.attributes) {
                if (!Class.isModelAttribute(attrName)) {
                    expect(user[attrName]).to.be.equal(s['UserTestModel'][attrName]);
                } else {
                    var subMdlAttr = Class.getAttribute(attrName);
                    if (subMdlAttr.multiple) {
                        can.each(user[attrName], function (item, i) {
                            for (var subMdlAttrName in subMdlAttr.modelReference.attributes) {
                                expect(user[attrName][i][subMdlAttrName]).to.be.equal(s[attrName][i][subMdlAttrName]);
                            }
                        });

                    } else {
                        for (var subMdlAttrName in subMdlAttr.modelReference.attributes) {
                            expect(user[attrName][i][subMdlAttrName], s[attrName][i][subMdlAttrName]);
                        }
                    }
                }
            }
            done();

        }).fail(function(jqXHR, status, response, request) {
            expect(false).to.be.ok;
            done();
        });
    });
});