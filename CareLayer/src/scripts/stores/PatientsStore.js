/*
 *  Author:         Bruno Lima
 *  Date Created:   June 19, 2015
 *  Description:    Defines the PatientsStore class that stores the logged in doctor's acessible patients
 */

var React = require('react');
var Reflux = require('reflux');
var $ = require('jquery');

var PatientsActions = require('../actions/PatientsActions');


var PatientsStore = Reflux.createStore(
{
    listenables: [PatientsActions],
    patients: [],
    
    loadPatients: function()
    {
        $.ajax(
        {
            url: 'patients',
            cache: false,
            context: this,
            method: 'POST',
            data: {type: 'loadPatients'},
            success: function(result) 
            {
                if(!result.hasOwnProperty('error') || result.error != 'Session Not Set')
                {
                    this.patients = result;
                    this.trigger(this.patients);
                }
                
            }
        });
    },

    getInitialState: function()
    {
        return this.patients;
    }
});

module.exports = PatientsStore;