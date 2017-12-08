/*
 *  Author:         Bruno Lima
 *  Date Created:   June 15, 2015
 *  Description:    Application javascript entry point
 */

var React = require('react');
var UserActions = require('./actions/UserActions');
var UserStore = require('./stores/UserStore');
var Header = require('./components/Header');
var Footer = require('./components/Footer');
var Login = require('./components/Login');
var Router = require('./Router');

var login_callback = function()
{
    React.unmountComponentAtNode(document.getElementById('content'));
    React.render(<Header />, document.getElementById('header'));
    React.render(<Footer />, document.getElementById('footer'));
    Router.start();
}

var start_session_callback = function(result)
{
    if(result.hasOwnProperty('error') && result.error == 'Session Not Set')
    {
        // If no session was found, render the Login module
        window.location.href = '/#/';
        React.render(<Login onSuccess={login_callback} />, document.getElementById('content'));
    }
    else
    {
        // If a session was successfully retrieved, load the header and footer and start the router
        React.render(<Header />, document.getElementById('header'));
        React.render(<Footer />, document.getElementById('footer'));
        Router.start();
    }
}



// Attempt to retrieve a session if it exists and then call the above callback function
UserActions.retrieveSession(start_session_callback);