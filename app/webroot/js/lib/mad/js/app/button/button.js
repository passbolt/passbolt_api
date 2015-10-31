import $ from "jquery";
import mad from "mad/mad";
import Button from "mad/component/button";

var button = new mad.component.Button($('#button'), {
    value: 'The value of the simple button',
    events: {
        'click': function (el, ev, value) {
            alert('click on simple button value : ' + value);
        }
    }
});
