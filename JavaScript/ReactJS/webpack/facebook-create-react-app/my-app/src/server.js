var corsPrefetch = require('cors-prefetch-middleware');
var imagesUpload = require('images-upload-middleware');
var express = require("express");
var app = express();
var port = 3700;
var portUpload = 3701;
var portHttp = 8081;
var io = require('socket.io').listen(app.listen(port));
var array_names = []; 

io.sockets.on('connection', function (socket) {
	console.log("connected!");
	socket.emit('message', { message: 'welcome to the chat' });	
	socket.emit('message', { name_list: array_names });

	socket.on('send', function (data) {
		io.sockets.emit('message', data);

		if(data.hasOwnProperty('join')){
            socket.name = data.join;
            add_name(socket.name);
		}
	});

	socket.on('disconnect', function(){
        io.sockets.emit('message', { announcement: socket.name+' has left the chat...' });    
        io.sockets.emit('message', { leave: socket.name }); 
        remove_name(socket.name);   
    });

});
console.log("Listening on port " + port);


function add_name(name){
    array_names.push(name);
}

function remove_name(name){
    var indexToRemove = -1;
    array_names.forEach((n, index) => {
      if( n == name){
        indexToRemove = index;
      }  
    });

    if(indexToRemove != -1){
        array_names.splice(indexToRemove, 1);
    }
}



// START : File upload
//app.use('/media', express.static('./static/media')); 
app.use(corsPrefetch['default']);

var imagesUploadFunc = imagesUpload['default']; 
app.post('/multiple', imagesUploadFunc(
    '../build/static/media', // folder permission?
    'http://jimmyh.hopto.org:' + portHttp + '/static/media',
    true
));
 
app.post('/single', imagesUploadFunc(
    '../build/static/media', // folder permission?
    'http://jimmyh.hopto.org:' + portHttp + '/static/media'
));
 
app.listen(portUpload, () => {
    console.log('Listen: ' + portUpload);
});
// END : File upload




