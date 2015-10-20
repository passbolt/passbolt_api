import mad from 'mad/mad';

mad.setGlobal('passbolt', {});

// Define the component namespace.
if (passbolt.component == undefined) {
    passbolt.component = {};
}

// Define the model namespace.
if (passbolt.model == undefined) {
    passbolt.model = {};
}

// Define the view namespace.
if (passbolt.view == undefined) {
    passbolt.view = {};
}

// Define the view component namespace.
if (passbolt.view.component == undefined) {
	passbolt.view.component = {};
}

// Define the view component namespace.
if (passbolt.form == undefined) {
	passbolt.form = {};
}

// Define the component namespace.
if (passbolt.form == undefined) {
	passbolt.form = {};
}

export default passbolt;
