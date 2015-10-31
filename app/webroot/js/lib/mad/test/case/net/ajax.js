import "test/bootstrap";
import 'mad/net/ajax';
import 'can/util/fixture/fixture';

describe("mad.net.Ajax", function() {

    can.fixture({
        type: 'POST',
        url: '/ajax/request'
    }, function (original, settings, headers) {
        return {
            'header': {
                'id': uuid(),
                'status': mad.net.Response.STATUS_SUCCESS,
                'title': 'Ajax Unit Test fixture title',
                'message': 'Ajax Unit Test fixture message',
                'controller': 'controllerName',
                'action': 'actionName'
            },
            'body': 'RESULT REQUEST 1'
        };
    });

    can.fixture({
        type: 'POST',
        url: '/ajax/server_error'
    }, function (original, settings, headers) {
        return {
            'header': {
                'id': uuid(),
                'status': mad.net.Response.STATUS_ERROR,
                'title': 'Ajax Unit Test fixture title',
                'message': 'Ajax Unit Test fixture message',
                'controller': 'controllerName',
                'action': 'actionName'
            },
            'body': 'RESULT REQUEST 1'
        };
    });

    it("A successful ajax query should return a success status", function(done) {
        mad.net.Ajax.request({
            'type': 'POST',
            'url': '/ajax/request',
            'async': true,
            'dataType': 'json'
        }).then(function (data, response, request) {
            expect(response.header.status).to.contain('success');
            done();
        });
    });

    it("An ajax query to an unreachable url should return an error", function(done) {
        mad.net.Ajax.request({
            'type': 'POST',
            'url': '/ajax/not_reachable',
            'async': false,
            'dataType': 'json'
        }).then(function (data, response, request) {
            expect(false).to.be.ok;
            done();
        }).fail(function(jqXHR, status, response, request) {
            var unreachableResponse = mad.net.Response.getResponse('unreachable');
            expect(true).to.be.ok;
            expect(response).to.be.instanceOf(mad.net.Response);
            expect(response.getStatus()).to.be.equal(mad.net.Response.STATUS_ERROR);
            expect(response.getTitle()).to.be.equal(unreachableResponse.getTitle());
            expect(response.getAction()).to.be.equal(unreachableResponse.getAction());
            expect(response.getController()).to.be.equal(unreachableResponse.getController());
            expect(response.getData()).to.be.equal(unreachableResponse.getData());
            done();
        });
    });

    it("An ajax query to a url returning an error should return an error status", function(done) {
        mad.net.Ajax.request({
            'type': 'POST',
            'url': '/ajax/server_error',
            'async': false,
            'dataType': 'json'
        }).then(function (data, response, request) {
            expect(false).to.be.ok;
            done();
        }).fail(function(jqXHR, status, response, request) {
            expect(true).to.be.ok;
            expect(response).to.be.instanceOf(mad.net.Response);
            expect(response.getStatus()).to.be.equal(mad.net.Response.STATUS_ERROR);
            done();
        });
    });
});