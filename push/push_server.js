var args = process.argv.slice(2);

// defaults
var port = 3955;
var secure = false;
var key = '/etc/ssl/key/comodokey.key';
var cert = '/etc/ssl/key/comodocrt.crt';
var ca = ['/etc/ssl/key/ca1.crt', '/etc/ssl/key/ca1.crt'];

// parse command line arguments
var ca_overwritten = false;
for(var i = 0; i < args.length; i++)
{
    if(args[i] == '-p' || args[i] == '-P')
    {
        port = args[i + 1];
        i += 1;
    }
    else if(args[i] == '-s' || args[i] == '-S')
    {
        secure = true;
    }
    else if(args[i].toLowerCase() == '-key')
    {
        key = args[i + 1];
        i += 1;
    }
    else if(args[i].toLowerCase() == '-cert')
    {
        cert = args[i + 1];
        i += 1;
    }
    else if(args[i].toLowerCase() == '-ca')
    {
        if(!ca_overwritten)
        {
            ca_overwritten = true;
            ca = [];
        }
        ca.push(args[i + 1]);
        i += 1;
    }
}

// create app
var express = require('express');
var app = express();
app.use(function(req, res, next) 
{
    res.header("Access-Control-Allow-Origin", "*");
    res.header("Access-Control-Allow-Headers", "X-Requested-With");
    res.header("Access-Control-Allow-Headers", "Content-Type");
    res.header("Access-Control-Allow-Methods", "PUT, GET, POST, DELETE, OPTIONS");
    next();
});

// create server
var fs = require('fs');
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
    
    socket.on('__jn', function(data)
    {
        socket.join(data.id);
    });
    socket.on('__lv', function(data)
    {
        socket.leave(data.id);
    });
    socket.on('__msg', function(data)
    {
        io.to(data.id).emit(data.title, data.data);
    });
    socket.on('__bdc', function(data)
    {
        io.broadcast.emit(data.title, data.data);
    });
    socket.on('disconnect', function(){ });
});

// make the server listen on the port provided
server.listen(port);

// delete unix socket if it already exists
var unix_socket = "/var/run/push_socket.socket";
if (fs.existsSync(unix_socket)) 
{
    fs.unlinkSync(unix_socket);
}

// create a local server to listen to local messages send from a php call
var localServer = net.createServer(function(stream) 
{
    stream.on('data', function(c) 
    {
        var obj = JSON.parse(c);
        if(obj.type == '__msg')
        {
            io.to(obj.id).emit(obj.title, obj.data);
        }
        else if(obj.type == '__bdc')
        {
            io.broadcast.emit(obj.title, obj.data);
        }
    });
});

// make local server listen on a local unix socket
localServer.listen(unix_socket);

// give appropriate permissions to the unix socket
fs.chmodSync(unix_socket, 0777);

console.log("Running Push on Port " + port);