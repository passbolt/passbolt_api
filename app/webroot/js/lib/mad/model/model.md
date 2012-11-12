@page mad.model.Model Model
@tag mad.model.Model
@parent mad.core

The super class mad.model.Model is based on the JMVC model class. We override the 
JMVC model class for several reasons

<ul>

	<li>
		Support of our server replies format :
@codestart
{
	header: // Additional data
	body: // data
}
@codeend
	</li>

</ul>