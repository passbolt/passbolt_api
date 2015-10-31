import "mad/net/ajax";
import "can/util/fixture/fixture";

var store = [
    {
        'UserTestModel': {
            'id': '50cdea9c-aa88-46cb-a09b-2f4fd7a10fce',
            'username': 'betty@passbolt.com',
            'email': 'betty@passbolt.com',
            'role_id': '0208f57a-c5cd-11e1-a0c5-080027796c4c',
            'active': 1
        }
    },
    {
        'UserTestModel': {
            'id': '50cdea9c-7e80-4eb6-b4cc-2f4fd7a10fce',
            'username': 'carol@passbolt.com',
            'email': 'carol@passbolt.com',
            'role_id': '0208f57a-c5cd-11e1-a0c5-080027796c4c',
            'active': 1
        }
    },
    {
        'UserTestModel': {
            'id': 'bbd56042-c5cd-11e1-a0c5-080027796c4e',
            'username': 'edith@passbolt.com',
            'email': 'edith@passbolt.com',
            'role_id': '0208f57a-c5cd-11e1-a0c5-080027796c4c',
            'active': 1
        }
    }
];

// Fixture for UserTestModel findAll.
can.fixture({
    type: 'GET',
    url: '/testusers'
}, function (original, settings, headers) {
    return {
        'header': {
            'id': uuid(),
            'status': mad.net.Response.STATUS_SUCCESS,
            'title': 'success',
            'message': '',
            'controller': 'Users',
            'action': 'index'
        },
        'body': store
    };
});

// Fixture for UserTestModel findAll with a change on Carol.
can.fixture({
    type: 'GET',
    url: '/testuserscarolupdated'
}, function (original, settings, headers) {
    var storeCopy = $.extend(true, [], store),
        instance = mad.Model.searchOne(storeCopy, 'UserTestModel.username', 'carol@passbolt.com');

    instance.UserTestModel.email = 'carol_updated_email@passbolt.com';
    return {
        'header': {
            'id': uuid(),
            'status': mad.net.Response.STATUS_SUCCESS,
            'title': 'success',
            'message': '',
            'controller': 'Users',
            'action': 'index'
        },
        'body': storeCopy
    };
});

// Fixture for UserTestModel findOne.
can.fixture({
    type: 'GET',
    url: '/testusers/{id}'
}, function (original, settings, headers) {
    var data = mad.Model.searchOne(store, 'UserTestModel.id', original.params.id);
    return {
        'header': {
            'id': uuid(),
            'status': mad.net.Response.STATUS_SUCCESS,
            'title': 'success',
            'message': '',
            'controller': 'Users',
            'action': 'view'
        },
        'body': data
    };
});

// Fixture for UserTestModel updated.
can.fixture({
    type: 'PUT',
    url: '/testusers/{id}'
}, function (original, settings, headers) {
    var instance = mad.Model.searchOne(store, 'UserTestModel.id', original.params.id),
        instanceCopy = $.extend(true, [], instance);

    for (var param in original.params) {
        if (instanceCopy[param] && instanceCopy[param] != original.params[param]) {
            instanceCopy[param] = original.params[param];
        }
    }
    return {
        'header': {
            'id': uuid(),
            'status': mad.net.Response.STATUS_SUCCESS,
            'title': 'success',
            'message': '',
            'controller': 'Users',
            'action': 'view'
        },
        'body': instanceCopy
    };
});

// Fixture for UserTestModel findOne.
can.fixture({
    type: 'GET',
    url: '/testusersupdated/{id}'
}, function (original, settings, headers) {
    var instance = mad.Model.searchOne(store, 'UserTestModel.id', original.params.id),
        instanceCopy = $.extend(true, [], instance);
    instanceCopy.UserTestModel.email = 'carol_updated_email@passbolt.com';
    return {
        'header': {
            'id': uuid(),
            'status': mad.net.Response.STATUS_SUCCESS,
            'title': 'success',
            'message': '',
            'controller': 'Users',
            'action': 'view'
        },
        'body': instanceCopy
    };
});

// Fixture for UserTestModel findCustom.
can.fixture({
    type: 'GET',
    url: '/testusers/custom/0'
}, function (original, settings, headers) {
    var instance = mad.Model.searchOne(store, 'UserTestModel.username', 'carol@passbolt.com'),
        instanceCopy = $.extend(true, [], instance);
    instanceCopy.UserTestModel.email = 'carol_updated_email@passbolt.com';
    return {
        'header': {
            'id': uuid(),
            'status': mad.net.Response.STATUS_SUCCESS,
            'title': 'success',
            'message': '',
            'controller': 'Users',
            'action': 'view'
        },
        'body': instanceCopy
    };
});
