/*
 *  Author:         Bruno Lima
 *  Date Created:   June 17, 2015
 *  Description:    Defines the UserStore class that stores the logged in user's information
 */

var React = require('react');
var Reflux = require('reflux');
var $ = require('jquery');

var UserActions = require('../actions/UserActions');


var UserStore = Reflux.createStore(
{
    listenables: [UserActions],
    data: 
    {
        invalid_password: 0,
        invalid_username: 0,
        user: {}
    },

    getInitialState: function()
    {
        return this.data;
    },
    
    retrieveSession: function(callback) 
    {
        $.ajax(
        {
            url: 'user',
            cache: false,
            context: this,
            method: 'POST',
            data: {type: 'retrieveSession'},
            success: function(d) 
            {
                
                var result = d;
                
                
                
                if(!result.hasOwnProperty('error') || result.error != 'Session Not Set')
                {
                    this.data.user = result.User;
                    this.trigger(this.data);
                }
                
                callback(result);
                
            }
        });
    },

    login: function(username, password, callback) 
    {
        $.ajax(
        {
            url: 'user',
            cache: false,
            context: this,
            method: 'POST',
            data: {type: 'login', username: username, password: password},
            success: function(data) 
            {
                
                var result = data;
                
                if(result.hasOwnProperty('error'))
                {
                    if(result.error == 'Invalid Username')
                    {
                        this.data.invalid_username = 1;
                    }
                    else
                    {
                        this.data.invalid_username = 0;
                    }
                    if(result.error == 'Invalid Password')
                    {
                        this.data.invalid_password = 1;
                    }
                    else
                    {
                        this.data.invalid_password = 0;
                    }
                }
                else
                {
                    this.data.invalid_username = 0;
                    this.data.invalid_password = 0;
                    
                    window.location.href = '/#/';
                    
                    this.data.user = result.User;

                    callback();
                }
                this.trigger(this.data);
            }
        });
    },
    
    logout: function()
    {
        
        $.ajax(
        {
            url: 'user',
            cache: false,
            context: this,
            method: 'POST',
            data: {type: 'logout'},
            success: function(data) 
            {
                location.reload();
            }
        });
    }
});

module.exports = UserStore;