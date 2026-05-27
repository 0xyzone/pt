@php
    $userIdForBase = request()->route('user_id');
    $webSocketSync = true;
    if ($userIdForBase) {
        $userForBase = \App\Models\User::find($userIdForBase);
        if ($userForBase) {
            $webSocketSync = \App\Services\SubscriptionService::can($userForBase, 'websocket_sync');
        }
    }
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title ?? 'Screen' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700;900&family=Rajdhani:wght@600;700;900&family=Orbitron:wght@700;800;900&display=swap" rel="stylesheet">
    @if(!$webSocketSync)
    <script>
        Object.defineProperty(window, 'Echo', {
            get: function() {
                return {
                    channel: function() { return { listen: function() { return this; } }; },
                    private: function() { return { listen: function() { return this; } }; }
                };
            },
            set: function(val) {
                // Do nothing, prevent overwriting
            },
            configurable: true
        });
    </script>
    @endif
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-transparent overflow-hidden font-main select-none" style="width: 1920px; height: 1080px; position: relative;">
    {{ $slot }}
</body>
</html>
