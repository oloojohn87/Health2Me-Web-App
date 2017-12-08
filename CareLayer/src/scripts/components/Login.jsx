/*
 *  Author:         Bruno Lima
 *  Date Created:   June 15, 2015
 *  Description:    Defines the Login React Component
 */

var React = require('react/addons');
var Reflux = require('reflux');
var Radium = require('radium');
var $ = require('jquery');

var UserActions = require('../actions/UserActions');
var UserStore = require('../stores/UserStore');

var ReactCSSTransitionGroup = React.addons.CSSTransitionGroup;

var Login = React.createClass(
{
    // Connect to the UserStore store
    mixins: [Reflux.connect(UserStore, 'userStore')],
    
    getInitialState: function()
    {
        return {username: '', password: ''};
    },
    usernameChanged: function(event)
    {
        this.setState({username: event.target.value});
    },
    passwordChanged: function(event)
    {
        this.setState({password: this.refs.password.getDOMNode().value});
    },
    keyPressed: function(event)
    {
        var enter = 13;
        if(event.keyCode == enter) 
        {
            this.login();
        }
    },
    login: function()
    {
        UserActions.login(this.state.username, this.state.password, this.props.onSuccess); 
    },
    render: function()
    {
        return (
                <ReactCSSTransitionGroup transitionName="slideDownFade" component="div" transitionAppear={true}>
                    <div style={styles.login_styles}>
                        <div style={styles.logo_styles}>CareLayer</div>
                        <div style={styles.margin_container}>
                            <input type="text" placeholder="Username / Email" onChange={this.usernameChanged} onKeyDown={this.keyPressed} style={[styles.text_styles, styles.text_top_styles, (this.state.userStore.invalid_username && styles.red_border)]}  />
                            <input ref="password" type="password" placeholder="Password" onChange={this.passwordChanged} onKeyDown={this.keyPressed} style={[styles.text_styles, styles.text_bottom_styles, (this.state.userStore.invalid_password && styles.red_border)]} />
                            <button style={styles.login_button_styles} onClick={this.login} >Login</button>
                        </div>
                    </div>
                </ReactCSSTransitionGroup>
                );
    }
});

Login = Radium(Login);

var styles = {
    login_styles: {
        width: '400px',
        height: '400px',
        borderRadius: '10px',
        backgroundColor: '#FDFDFD',
        border: '1px solid #EEE',
        boxShadow: '0px 2px 3px #E2E2E2',
        margin: 'auto',
        marginTop: '100px'
    },
    margin_container: {
        width: '80%',
        margin: 'auto'
    },
    text_styles: {
        width: '100%',
        height: '20px',
        padding: '20px',
        border: '1px solid #E8E8E8',
        outline: 'none',
        fontSize: '14px'
    },
    login_button_styles: {
        width: '100%',
        height: '35px',
        padding: '2%',
        backgroundColor: '#22AEFF',
        borderRadius: '10px',
        color: '#FFF',
        outline: 'none',
        border: '0px solid #FFF',
        margin: 'auto',
        fontSize: '14px',
        cursor: 'pointer',
        marginTop: '20px'
        
    },
    text_top_styles: {
        borderTopLeftRadius: '10px',
        borderTopRightRadius: '10px'
    },
    text_bottom_styles: {
        borderBottomLeftRadius: '10px',
        borderBottomRightRadius: '10px'
    },
    red_border: {
        border: '1px solid #F00',
    },
    logo_styles: {
        width: '80%;',
        height: '100px',
        margin: 'auto',
        marginTop: '60px',
        marginBottom: '20px',
        fontWeight: 'bold',
        fontFamily: '"Helvetica Neue", "Helvetica", "Arial", sans-serif',
        fontSize: '36px',
        textAlign: 'center'
    }
    
};

module.exports = Login;