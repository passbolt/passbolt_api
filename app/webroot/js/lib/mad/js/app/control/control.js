import $ from "jquery";
import mad from "mad/mad";

var OpenTheDoor = mad.Control.extend({
    'click': function () {
        $('#bar').toggle('display');
    }
});
var control = new OpenTheDoor($('#foo'));
