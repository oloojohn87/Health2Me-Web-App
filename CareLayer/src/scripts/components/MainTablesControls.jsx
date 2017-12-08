/*
 *  Author:         Bruno Lima
 *  Date Created:   June 19, 2015
 *  Description:    Defines the MainTablesControls React component, which provides controls for the tables in the home component
 */

var React = require('react');
var Radium = require('radium');

var MainTablesControls = React.createClass(
{
    render: function() 
    {
        return (
            <div style={[styles.main_tables_controls, {height: (this.props.height - 80) + 'px'}]}>
                Table's Controls Go Here
            </div>
        );
    }
});

MainTablesControls = Radium(MainTablesControls);

var styles = {
    main_tables_controls: {
        width: '100%',
        backgroundColor: '#555',
        paddingTop: '80px',
        color: '#F2F2F2',
        fontSize: '18px',
        fontFamily: "'Helvetica Neue', 'Helvetica', sans-serif",
        textAlign: 'center'
    }
};

module.exports = MainTablesControls;

