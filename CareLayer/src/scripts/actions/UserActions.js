var Reflux = require('reflux');

var UserActions = Reflux.createActions(
[
    'retrieveSession',
    'login',
    'logout'
]);

module.exports = UserActions;