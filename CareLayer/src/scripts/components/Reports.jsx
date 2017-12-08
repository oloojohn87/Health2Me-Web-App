/*
 *  Author:         Bruno Lima
 *  Date Created:   June 15, 2015
 *  Description:    Defines the Reports React Component
 */

var React = require('react');
var Radium = require('radium');

var Reports = React.createClass(
{
    render: function() 
    {
        return (<div style={styles.reports_styles}>Reports</div>);
    }
});
Reports = Radium(Reports);
        
var styles = {
    reports_styles: {
        width: '1000px',
        height: '400px',
        paddingTop: '300px',
        margin: 'auto',
        backgroundColor: '#F2F2F2',
        color: '#888',
        textAlign: 'center',
        fontFamily: "'Helvetica Neue', 'Helvetica', sans-serif",
        border: '1px solid #E8E8E8',
        borderRadius: '5px'
    }
};

module.exports = Reports;