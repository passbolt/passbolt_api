@page mad.net Net
@tag mad.net
@parent index

The mad.net package is our layer placed upon the jQuery ajax tool. This layer has been developped 
to hook easily communications between the client application and the server controllers.
It uses the same <a href="http://api.jquery.com/jQuery.ajax/">jquery ajax setting options</a> to 
perform its task and add some additional features :

<ul>
<li>
	to make all communications between the client application and the server controllers coherent 
	by using a unique model of answer. This model can be overrided regarding the server answer
	model. The default response model is represented by the class <a href="#!mad.net.Response">mad.net.Response</a>.
	Check <a href="https://docs.google.com/a/passbolt.com/document/d/1aC86MkBpgnJpG30gc_NB_pZKGWLIg4apratgx8BJPoE/edit#heading=h.gc21wf52wh2t">
	our coding guide lines</a> to get more information.
	<br/><br/>
</li>

<li>
	to allow you to define the expected server result datatype following the setting option.
	xml, html, script, json, jsonp or a <i>javascript mvc model reference</i>. This last option allow you 
	to automatically map the server result to a ready to use object model by the client application.
	<br/><br/>
</li>

<li>
	(<b>DEPRECATED</b>) to aggregate the requests to decrease the server loading.
	<br/><br/>
</li>
</ul>