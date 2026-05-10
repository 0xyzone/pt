import Echo from 'laravel-echo';

import Pusher from 'pusher-js';
window.Pusher = Pusher;

let echoOptions = {
    broadcaster: "pusher",
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    forceTLS: true,
    enabledTransports: ["ws", "wss"],
};

// Only override wsHost if a custom host (like Soketi/Reverb) is specified in .env
if (import.meta.env.VITE_PUSHER_HOST) {
    echoOptions.wsHost = import.meta.env.VITE_PUSHER_HOST;
    echoOptions.wsPort = import.meta.env.VITE_PUSHER_PORT ?? 80;
    echoOptions.wssPort = import.meta.env.VITE_PUSHER_PORT ?? 443;
    echoOptions.forceTLS = (import.meta.env.VITE_PUSHER_SCHEME ?? 'https') === 'https';
}

window.Echo = new Echo(echoOptions);
