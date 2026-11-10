console.log('Script Loaded');

import Echo from "laravel-echo";
import io from "socket.io-client";

window.io = io;

window.Echo = new Echo({
    broadcaster: 'socket.io',
    host: window.location.hostname + ':6001',// Update to match your Echo server's host and port
    encrypted: false,
    transports: ['websocket', 'polling', 'flashsocket'], // Ensure WebSocket is included
    wsHost: window.location.hostname, // Ensure it matches your Laravel Echo server host
    wsPort: 6001,
    disableStats: true,
});

console.log('Echo configured:', window.Echo);


