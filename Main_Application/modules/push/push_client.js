
function Push(id, address)
{
    this.id = id;
    this.socket = io.connect(address);
    this.socket.emit('__jn', {id: this.id});

    this.bind = function(title, callback)
    {
        this.socket.on(title, function(data)
        {
            callback(data);
        });
    };

    this.send = function(id, title, data)
    {
        this.socket.emit('__msg', {id: id, title: title, data: data});
    }

    this.broadcast = function(title, data)
    {
        this.socket.emit('__bdc', {title: title, data: data});
    }
}
