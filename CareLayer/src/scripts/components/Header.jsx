/*
 *  Author:         Bruno Lima
 *  Date Created:   June 15, 2015
 *  Description:    Defines the Header React Component, which includes the application's main menu
 */

var React = require('react');
var Reflux = require('reflux');
var Radium = require('radium');

var UserActions = require('../actions/UserActions');
var UserStore = require('../stores/UserStore');

// Helper component for the menu's buttons
var MenuButton = React.createClass(
{
    click: function()
    {
        if(this.props.hasOwnProperty('link'))
        {
            window.location.href = this.props.link;
        }
        else if(this.props.hasOwnProperty('action'))
        {
            this.props.action();
        }
    },
    
    render: function() 
    {
        return (<button style={[styles.menu_button_styles, (this.props.right && styles.menu_button_right)]} onClick={this.click}>
                    {this.props.icon && <i className={'fa ' + this.props.icon}></i>}
                    {this.props.icon && '\u0020'}
                    {this.props.name}
                </button>);
    }
});
MenuButton = Radium(MenuButton);

var Header = React.createClass(
{
    mixins: [Reflux.connect(UserStore, 'userStore')],
    render: function() 
    {
        return (
            <div style={styles.header_styles}>
                <MenuButton name="Logout" right="1" icon="fa-sign-out" action={UserActions.logout} />
                {
                    (this.state.userStore.user.Name && this.state.userStore.user.Surname) && 
                    <div style={styles.doctor_label_styles}>
                        Welcome Dr. {this.state.userStore.user.Name + ' ' + this.state.userStore.user.Surname}
                    </div>
                }
                <MenuButton name="Home" icon="fa-home" link="/#/home" />
                <MenuButton name="Reports" icon="fa-bar-chart" link="/#/reports" />
                <MenuButton name="Configuration" icon="fa-gears" link="/#/configuration" />
                <MenuButton name="Notifications" icon="fa-bell" link="/#/home" />
                <MenuButton name="Help" icon="fa-life-saver" link="/#/home" />
            </div>
        );
    }
});
Header = Radium(Header);
        
var styles = {
    menu_button_styles: {
        height: '40px',
        paddingLeft: '20px',
        paddingRight: '20px',
        color: '#54BC00',
        backgroundColor: '#FDFDFD',
        border: '0px solid #FFF',
        borderRight: '1px solid #D8D8D8',
        float: 'left',
        outline: 'none',
        fontSize: '14px',
        fontWeight: 'bold',
        fontFamily: "'Helvetica Neue', 'Helvetica', sans-serif",
        cursor: 'pointer',
        ':hover': {
            backgroundColor: '#54BC00',
            color: '#FDFDFD'
        }
    },
    menu_button_right: {
        float: 'right',
        borderRight: '0px solid #D8D8D8',
        borderLeft: '1px solid #D8D8D8',
    },
    header_styles: {
        width: '100%',
        height: '40px',
        backgroundColor: '#FDFDFD',
        boxShadow: '0px 2px 2px #EBEBEB'
    },
    doctor_label_styles: {
        height: '28px',
        paddingLeft: '20px',
        paddingRight: '20px',
        paddingTop: '12px',
        color: '#888',
        backgroundColor: '#FDFDFD',
        float: 'right',
        fontSize: '14px',
        fontWeight: 'bold',
        fontFamily: "'Helvetica Neue', 'Helvetica', sans-serif",
    }
};

module.exports = Header;