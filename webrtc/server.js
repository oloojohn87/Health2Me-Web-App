var args = process.argv.slice(2);

// defaults
var port = 8889;
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

var rooms = {};
// define socket.io actions
io.sockets.on('connection', function(socket){
    
    
    
    socket.on('__jn', function(data)
    {
        socket.identifier = data.id;
        socket.join(data.id);
    });
    socket.on('__lv', function(data)
    {
        socket.leave(data.id);
    });
    socket.on('JoinRoom', function(data)
    {
        var room_id = 'room_' + ((data.data.id_1 * data.data.id_2) + Math.floor(Math.pow(Math.abs(data.data.id_1 - data.data.id_2) - 1, 2) / 4.0)).toString();
        socket.room = room_id;
        if(!rooms.hasOwnProperty(room_id) || rooms[room_id].length == 0)
        {
            rooms[room_id] = [data.id];
        }
        else if(rooms.hasOwnProperty(room_id) && rooms[room_id].length >= 2)
        {
            io.to(data.id).emit('Error', 'Room is already full');
            console.log('ERROR: Room Full');
        }
        else
        {
            io.to(data.id).emit('CreateOffer', true);
            rooms[room_id].push(data.id);
        }
        
        //console.log('JOIN ROOM - ROOMS: ' + JSON.stringify(rooms));
    });
    socket.on('LeaveRoom', function(data)
    {
        var room_id = 'room_' + ((data.data.id_1 * data.data.id_2) + Math.floor(Math.pow(Math.abs(data.data.id_1 - data.data.id_2) - 1, 2) / 4.0)).toString();
        if(rooms.hasOwnProperty(room_id))
        {
            var index = rooms[room_id].indexOf(data.id);
            if(index > -1)
            {
                rooms[room_id].splice(index, 1);
            }
        }
        io.to(data.data.id_2).emit('LeftRoom', data.id);
        //console.log('LEAVE ROOM - ROOMS: ' + JSON.stringify(rooms));
    });
    socket.on('Offer', function(data)
    {
        io.to(data.id).emit('Offer', data.data);
        
    });
    socket.on('IceCandidate', function(data)
    {
        io.to(data.id).emit('IceCandidate', data.data);
        
    });
    socket.on('Answer', function(data)
    {
        io.to(data.id).emit('Answer', data.data);
        
    });
    socket.on('disconnect', function()
    { 
        console.log('client disconnected: ' + socket.identifier);
        if(socket.hasOwnProperty('identifier') && socket.hasOwnProperty('room'))
        {
            if(rooms.hasOwnProperty(socket.room))
            {
                var index = rooms[socket.room].indexOf(socket.identifier);
                if(index > -1)
                {
                    rooms[socket.room].splice(index, 1);
                }
            }
        }
        //console.log('DISCONNECT - ROOMS: ' + JSON.stringify(rooms));
    });
});

// make the server listen on the port provided
server.listen(port);

console.log("Running Signaling Server on Port " + port);