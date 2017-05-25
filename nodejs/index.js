var io = require('socket.io')(3000);
var redis = require('socket.io-redis');
io.adapter(redis({ host: 'localhost', port: 6379 }));

io.on('connection', (socket) => {
    console.log('connected ', socket.id);
    socket.on('message', (data) => {
        console.log('message', data);
    });
});

console.log('socket.io starting');