import $ from "jquery";
import can from "can/can";
import 'can/construct/super/super';
import mad from "mad/mad";
import Tree from "mad/component/tab";
import FreeComposite from "mad/component/component";
import Button from "mad/component/button";

// Instantiate the main tabs controller
var tabs = new mad.component.Tab('#tab', {
    autoMenu: true,
    menu: new can.Control('body')
});
tabs.start();

// Tab 1.
var tab1 = tabs.addComponent(mad.Component, {
    id: 'free-composite-1',
    label: 'tab1',
    templateBased: false
});

// Tab2.
var tab2 = tabs.addComponent(mad.Component, {
    id: 'free-composite-2',
    label: 'tab2',
    templateBased: false
});
tabs.enableTab('free-composite-2');
tabs.enableTab('free-composite-1');

// Add text inside the tabs.
$('<p class="txt1">this is the content of tab 1</p>').appendTo('#free-composite-1 .component');
$('<p class="txt2">this is the content of tab 2</p>').appendTo('#free-composite-2 .component');

