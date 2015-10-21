<?php
if (Configure::read('HtmlPurifier.standalone') != true) {
	require_once(CakePlugin::path('HtmlPurifier') . 'Vendor' . DS . 'HtmlPurifier' . DS . 'library' . DS . 'HTMLPurifier.auto.php');
} else {
	require_once(CakePlugin::path('HtmlPurifier') . 'Vendor' . DS . 'htmlpurifier-4.4.0-standalone' . DS . 'HTMLPurifier.standalone.php');
}
App::uses('Purifier', 'HtmlPurifier.Lib');