/*
 *  Author:         Bruno Lima
 *  Date Created:   June 15, 2015
 *  Description:    Defines the Home React Component
 */

var React = require('react');
var Reflux = require('reflux');
var Radium = require('radium');
var $ = require('jquery');

var MainTablesControls = require('./MainTablesControls');
var PatientsTable = require('./PatientsTable');

var PatientsActions = require('../actions/PatientsActions');

var Home = React.createClass(
{
    componentDidMount: function()
    {
        PatientsActions.loadPatients();
    },
    render: function() 
    {
        return (
                    <div style={styles.home_styles}>
                        <MainTablesControls height="200" />
                        <PatientsTable height="500" />
                    </div>
                );
    }
});
Home = Radium(Home);
        
var styles = {
    home_styles: {
        width: '1000px',
        height: '700px',
        margin: 'auto',
        border: '1px solid #E8E8E8',
        borderRadius: '10px',
        overflow: 'hidden',
        boxShadow: '1px 1px 2px #EBEBEB'
    }
    
};

module.exports = Home;