/*
 *  Author:         Bruno Lima
 *  Date Created:   June 15, 2015
 *  Description:    Main Server
 */

var fs = require('fs');

var config = JSON.parse(fs.readFileSync(__dirname + '/config.json'));

// defaults
var port = config.port;
var secure = config.secure;
var ssl = config.ssl;
var key = ssl.key;
var cert = ssl.cert;
var ca = ssl.ca;

// create app
var express = require('express');
var mysql = require('mysql');
var bodyParser = require('body-parser');
var sessions = require("client-sessions");
var app = express();
app.use(function(req, res, next) 
{
    res.header("Access-Control-Allow-Origin", "*");
    res.header("Access-Control-Allow-Headers", "X-Requested-With");
    res.header("Access-Control-Allow-Headers", "Content-Type");
    res.header("Access-Control-Allow-Methods", "PUT, GET, POST, DELETE, OPTIONS");
    next();
});
app.use(bodyParser());

// create pool of mysql connections
var pool = mysql.createPool(
{
    connectionLimit : 100,
    host     : config.database.host,
    user     : config.database.user,
    password : config.database.password,
    database : config.database.database,
    debug    :  false
});

// Set initial sessions configuration
app.use(sessions({
    cookieName: 'CareLayerSessionCookie',
    secret: 'hgjwk87579hi2lxejz06bivslocbqld8297fhfeou38fh338fhui2ud',
    duration: 10 * 60 * 1000, // 10 minutes
    activeDuration: 5 * 60 * 1000 // 5 minutes
}));

// define express routes
app.use(express.static(__dirname));

app.get('/', function(req, res)
{
    res.sendFile(__dirname + '/index.html');
});

// include modules
var main_dictionary = {
    app: app,
    conn: pool
};
var User = require('./modules/User')(main_dictionary);
var Patients = require('./modules/Patients')(main_dictionary);

// create server
var net = require('net');
var server = null;
if(secure)
{
    var options = 
    {
        key: fs.readFileSync(key),
        cert: fs.readFileSync(cert),
        ca: [],
        requestCert: true
    };
    for(var i = 0; i < ca.length; i++)
    {
        options.ca.push(fs.readFileSync(ca[i]));
    }
    server = require('https').createServer(options, app);
}
else
{
    server = require('http').createServer(app);
}

// launch socket.io, make it listen on the server
var io = require('socket.io').listen(server, {log: false, origins: '*:*'});

// define socket.io actions
io.sockets.on('connection', function(socket){
    socket.on('disconnect', function(){ });
});

// make the server listen on the port provided
server.listen(port);