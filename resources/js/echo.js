import Echo from 'laravel-echo';

import Pusher from 'pusher-js';
window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    forceTLS: true,
    enabledTransports: ['ws', 'wss'],
});

// Connection state logging
if (window.Echo.connector && window.Echo.connector.pusher) {
    window.Echo.connector.pusher.connection.bind('state_change', function(states) {
        console.log(`[Broadcasting] Connection: ${states.previous} → ${states.current}`);
    });

    window.Echo.connector.pusher.connection.bind('connected', function () {
        console.log('✅ [Broadcasting] Connected to Pusher.');
    });

    window.Echo.connector.pusher.connection.bind('disconnected', function () {
        console.warn('⚠️ [Broadcasting] Disconnected from Pusher.');
    });

    window.Echo.connector.pusher.connection.bind('error', function (err) {
        console.error('❌ [Broadcasting] Connection error:', err);
    });
}
