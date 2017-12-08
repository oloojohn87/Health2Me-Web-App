/*
 *  Author:         Bruno Lima
 *  Date Created:   June 15, 2015
 *  Description:    Defines the main app Router
 */

var React = require('react/addons');
var Router = require('react-router');
var Route = Router.Route;
var DefaultRoute = Router.DefaultRoute;
var RouteHandler = Router.RouteHandler;
var Home = require('./components/Home');
var Reports = require('./components/Reports');
var Configuration = require('./components/Configuration');

var ReactCSSTransitionGroup = React.addons.CSSTransitionGroup;

// Wrapper component for routes
var Layout = React.createClass(
{
    render: function() 
    {
        return (<RouteHandler />);
        
        // The following commented out code was used for animating the transitions between routes
        
        // return (<ReactCSSTransitionGroup component="div" transitionName="switchPage" transitionLeave={false}>
        //            <RouteHandler key={this.props.pathname}  />  
        //        </ReactCSSTransitionGroup>);
    }
});

// Routes definition
var routes = 
(
        <Route path="/" handler={Layout}>
            <DefaultRoute handler={Home} />
            <Route path="/home" handler={Home} />
            <Route path="/configuration" handler={Configuration} />
            <Route path="/reports" handler={Reports} />
        </Route>
);

// Start router
exports.start = function() 
{
    Router.run(routes, function (Handler, state) 
    {
        React.render(<Handler {...state} />, document.getElementById('content'));
    });
}