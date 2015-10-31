import mad from "mad/mad";
import Button from "mad/component/button";
import 'js/app/bootstrap/app.ejs!';

var DemoApp = mad.Component.extend('mad.DemoApp', {

    defaults: {
        templateUri: 'js/app/bootstrap/app.ejs',
    }

}, {
    afterStart: function() {
        var button = new mad.component.Button($('#foo'), {
            value: 'The value of the simple button',
            events: {
                'click': function (el, ev, value) {
                    $('#bar').toggle();
                }
            }
        });
    }
});

export default DemoApp;