import $ from "jquery";
import mad from "mad/mad";
import Textbox from "mad/form/element/textbox";

var textbox = new mad.form.Textbox($('#textbox'), { });
textbox.start();

$('#textbox').on('changed', function() {
    $('li.count .value').html(textbox.getValue().length)
});
