/*
 *  Author:         Bruno Lima
 *  Date Created:   June 15, 2015
 *  Description:    Defines the Footer React Component
 */

var React = require('react');
var Radium = require('radium');

var Footer = React.createClass(
{

    render: function() 
    {
        
        return (
            <div style={styles.footer_styles}>
                © Copyright 2015 Inmers LLC. CareLayer is a property of Inmers LLC. All Rights Reserved.
            </div>
        );
    }
});
Footer = Radium(Footer);
        
var styles = {
    footer_styles: {
        width: '100%',
        height: '28px',
        paddingTop: '12px',
        backgroundColor: '#FDFDFD',
        textAlign: 'center',
        color: '#999',
        fontFamily: "'Helvetica Neue', 'Helvetica', sans-serif",
        fontSize: '12px',
        boxShadow: '0px -2px 2px #EBEBEB'
    }
};

module.exports = Footer;