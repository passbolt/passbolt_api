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

export default passbolt;
