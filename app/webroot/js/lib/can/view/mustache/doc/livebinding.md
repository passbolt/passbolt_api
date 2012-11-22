@page Binding
@parent can.Mustache 5

# Live binding

Live binding is templates that update themselves as the data 
used in the magic tags change.

It's very common as the page is interacted with that the underlying 
data represented in the page changes.  Typically, you have callbacks 
in your AJAX methods or events and then update the content of your 
controls manually.

In this example, we have a simple user welcome screen.

	<h1>Welcome {{user}}!</h1>
	<p>
		{{#if messages}}
			You have {{messages}} new messages.
		{{else}}
			You no messages.
		{{/messages}}
	</p>

	var data = new can.Observe({
		user: 'Tina Fey',
		messages: 0
	});

	var template = can.view("#template", data);

The template evaluates the `messages` variable as if
it were a regular object we created.  Since we have 
no message it will render:

	<h1>Welcome Tina Fey!</h1>
	<p>You no messages.</p>

Now say we have a request that updates
the `messages` attribute to have `5` messages.  We 
call the `.attr` method on the `can.Observe` to update
the attribute to the new value.

	data.attr('message', 5)

After `can.Observe` recieves this update, it will
update the paragraph tag to reflect the new value.

	<p>You have 5 new message.</p>

For more information visit the [can.Observe].