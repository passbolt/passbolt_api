# CakePHP Email Queue plugin #

This plugin provides an interface for creating emails on the fly and
store them in a queue to be processed later by an offline worker using a
cakephp shell command.

It also contains a handy shell for previewing queued emails, a very handy tool for modifying
email templates and watching the result.

## Requirements ##

* CakePHP 2.x

## Installation ##

The only installation method supported by this plugin is by using composer. Just add this to your composer.json configuration:

	{
	  "require" : {
		"lorenzo/cakephp-email-queue": "1.0"
	  }
	}


### Enable plugin

In 2.0 you need to enable the plugin your `app/Config/bootstrap.php` file:

    CakePlugin::load('EmailQueue');

If you are already using `CakePlugin::loadAll();`, then this is not necessary.

### Load required database table

In order to use this plugin, you need to create a database table.
Required SQL is located at

	# Config/Schema/email_queue.sql

Just load it into your database.

## Usage

Whenever you need to send an email, use the EmailQueue model to create
and queue a new one by storing the correct data:

	ClassRegistry::init('EmailQueue.EmailQueue')->enqueue($to, $data, $options);

`enqueue` method receives 3 arguments:

- First argument is a string or array of email addresses that will be treated as recipients.
- Second arguments is an array of view variables to be passed to the
  email template
- Third arguments is an array of options, possible options are
 * `subject` : Email's subject
 * `send_at` : date time sting representing the time this email should be sent at (in UTC)
 * `template` :  the name of the element to use as template for the email message
 * `layout` : the name of the layout to be used to wrap email message
 * `format` : Type of template to use (html, text or both)
 * `headers`: A key-value list of headers to send in the email
 * `config` : the name of the email config to be used for sending

### Previewing emails

It is possible to preview emails that are still in the queue, this is very handy during development to check if the rendered
email looks at it should; no need to queue the email again, just make the changes to the template and run the preview again:

	# Console/cake EmailQueue.preview

### Sending emails

Emails should be sent using bundled Sender command, use `-h` modifier to
read available options

	# Console/cake EmailQueue.sender -h

You can configure this command to be run under a cron or any other tool
you wish to use.
