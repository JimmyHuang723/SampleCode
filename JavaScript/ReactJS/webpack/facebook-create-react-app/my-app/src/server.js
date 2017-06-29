var express = require("express");
var app = express();
var port = 3700;
var io = require('socket.io').listen(app.listen(port));

io.sockets.on('connection', function (socket) {
	console.log("connected!");
	socket.emit('message', { message: 'welcome to the chat' });
	socket.on('send', function (data) {
		io.sockets.emit('message', data);

		if(data.hasOwnProperty('join')){
            socket.name = data.join;
		}
	});

	socket.on('disconnect', function(){
        io.sockets.emit('message', { announcement: socket.name+' has left the chat...' });    
        io.sockets.emit('message', { leave: socket.name });    
    });

});
console.log("Listening on port " + port);