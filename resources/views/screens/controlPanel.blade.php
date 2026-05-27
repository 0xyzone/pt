<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>OBS Director Console</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .neon-border-yellow {
            box-shadow: 0 0 15px rgba(250, 204, 21, 0.15);
            border-color: rgba(250, 204, 21, 0.2);
        }

        .neon-border-yellow:hover {
            box-shadow: 0 0 25px rgba(250, 204, 21, 0.35);
            border-color: rgba(250, 204, 21, 0.6);
        }

        @keyframes pulse-timer {

            0%,
            100% {
                opacity: 1;
                text-shadow: 0 0 10px rgba(250, 204, 21, 0.4);
            }

            50% {
                opacity: 0.8;
                text-shadow: 0 0 20px rgba(250, 204, 21, 0.7);
            }
        }

        .pulse-timer {
            animation: pulse-timer 2s infinite ease-in-out;
        }

    </style>
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen font-sans selection:bg-yellow-500 selection:text-black">

    @php $obsPassword = $obsPassword ?? null; @endphp
    @if($obsPassword)
    {{-- ============================================================ --}}
    {{-- PASSWORD GATE OVERLAY                                        --}}
    {{-- ============================================================ --}}
    <div id="obs-auth-overlay" class="fixed inset-0 z-9999 flex items-center justify-center bg-slate-950/95 backdrop-blur-xl" style="display:flex!important">
        <div class="relative flex flex-col items-center gap-6 w-full max-w-sm mx-4">

            {{-- Animated glow ring --}}
            <div class="absolute inset-0 rounded-3xl bg-yellow-500/5 blur-3xl pointer-events-none"></div>

            {{-- Card --}}
            <div class="relative w-full bg-slate-900/80 border border-slate-700/60 rounded-3xl p-8 shadow-2xl backdrop-blur-sm" id="auth-card">

                {{-- Lock icon --}}
                <div class="flex flex-col items-center mb-6">
                    <div class="w-16 h-16 rounded-2xl bg-yellow-400/10 border border-yellow-400/20 flex items-center justify-center mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-8 h-8 text-yellow-400" id="lock-icon">
                            <path fill-rule="evenodd" d="M12 1.5a5.25 5.25 0 00-5.25 5.25v3a3 3 0 00-3 3v6.75a3 3 0 003 3h10.5a3 3 0 003-3v-6.75a3 3 0 00-3-3v-3c0-2.9-2.35-5.25-5.25-5.25zm3.75 8.25v-3a3.75 3.75 0 10-7.5 0v3h7.5z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <h2 class="text-xl font-black uppercase tracking-widest text-white">Director Access</h2>
                    <p class="text-xs text-slate-400 font-medium mt-1 text-center uppercase tracking-wider">OBS Control Panel is password protected</p>
                </div>

                {{-- Input --}}
                <div class="flex flex-col gap-3">
                    <div class="relative">
                        <input type="password" id="obs-password-input" placeholder="Enter password" autocomplete="current-password" class="w-full bg-slate-950 border border-slate-700 focus:border-yellow-400 focus:ring-1 focus:ring-yellow-400 rounded-xl px-4 py-3 text-white font-bold text-sm placeholder-slate-500 outline-none transition-all pr-12" onkeydown="if(event.key==='Enter') submitObsPassword()">
                        <button type="button" onclick="togglePwVisibility()" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-500 hover:text-yellow-400 transition-colors">
                            <svg id="pw-eye-show" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <svg id="pw-eye-hide" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 hidden">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                            </svg>
                        </button>
                    </div>

                    <p id="auth-error-msg" class="text-rose-400 text-xs font-bold uppercase tracking-wider text-center hidden">
                        Incorrect password. Try again.
                    </p>

                    <button onclick="submitObsPassword()" class="w-full py-3 bg-yellow-400 hover:bg-yellow-300 text-black font-black uppercase tracking-widest text-sm rounded-xl transition-all hover:scale-[1.02] active:scale-95 shadow-lg shadow-yellow-400/20">
                        Unlock Console
                    </button>
                </div>
            </div>

            <p class="text-[10px] text-slate-600 uppercase tracking-widest font-bold">Session valid for 24 hours</p>
        </div>
    </div>

    <style>
        @keyframes auth-shake {

            0%,
            100% {
                transform: translateX(0);
            }

            15% {
                transform: translateX(-8px);
            }

            30% {
                transform: translateX(8px);
            }

            45% {
                transform: translateX(-6px);
            }

            60% {
                transform: translateX(6px);
            }

            75% {
                transform: translateX(-3px);
            }

            90% {
                transform: translateX(3px);
            }
        }

        .auth-shake {
            animation: auth-shake 0.5s ease;
        }

    </style>

    <script>
        (function() {
            const STORAGE_KEY = 'obs_cp_auth_{{ $user->id }}';
            const CORRECT_PW = @json($obsPassword);
            const TTL_MS = 24 * 60 * 60 * 1000; // 24 hours

            function isAuthenticated() {
                try {
                    const raw = localStorage.getItem(STORAGE_KEY);
                    if (!raw) return false;
                    const {
                        password
                        , timestamp
                    } = JSON.parse(raw);
                    return password === CORRECT_PW && (Date.now() - timestamp) < TTL_MS;
                } catch {
                    return false;
                }
            }

            function hideOverlay() {
                const overlay = document.getElementById('obs-auth-overlay');
                if (overlay) {
                    overlay.style.transition = 'opacity 0.4s ease';
                    overlay.style.opacity = '0';
                    setTimeout(() => overlay.remove(), 400);
                }
            }

            // Check session immediately on load
            if (isAuthenticated()) {
                hideOverlay();
            }

            window.submitObsPassword = function() {
                const input = document.getElementById('obs-password-input');
                const entered = input.value;

                if (entered === CORRECT_PW) {
                    // Store session
                    localStorage.setItem(STORAGE_KEY, JSON.stringify({
                        password: entered
                        , timestamp: Date.now()
                    }));
                    // Unlock icon
                    const lockIcon = document.getElementById('lock-icon');
                    if (lockIcon) {
                        lockIcon.innerHTML = '<path fill-rule="evenodd" d="M18 1.5c2.9 0 5.25 2.35 5.25 5.25v3.75a.75.75 0 01-1.5 0V6.75a3.75 3.75 0 10-7.5 0v3a3 3 0 013 3v6.75a3 3 0 01-3 3H3.75a3 3 0 01-3-3v-6.75a3 3 0 013-3h9v-3c0-2.9 2.35-5.25 5.25-5.25z" clip-rule="evenodd" />';
                    }
                    hideOverlay();
                } else {
                    // Wrong password
                    const card = document.getElementById('auth-card');
                    const errMsg = document.getElementById('auth-error-msg');
                    const inputEl = document.getElementById('obs-password-input');

                    errMsg.classList.remove('hidden');
                    card.classList.remove('auth-shake');
                    void card.offsetWidth; // reflow
                    card.classList.add('auth-shake');
                    inputEl.value = '';
                    inputEl.focus();

                    inputEl.classList.add('border-rose-500', 'focus:border-rose-500');
                    setTimeout(() => inputEl.classList.remove('border-rose-500', 'focus:border-rose-500'), 1500);
                }
            };

            window.togglePwVisibility = function() {
                const input = document.getElementById('obs-password-input');
                const showIcon = document.getElementById('pw-eye-show');
                const hideIcon = document.getElementById('pw-eye-hide');
                if (input.type === 'password') {
                    input.type = 'text';
                    showIcon.classList.add('hidden');
                    hideIcon.classList.remove('hidden');
                } else {
                    input.type = 'password';
                    showIcon.classList.remove('hidden');
                    hideIcon.classList.add('hidden');
                }
            };
        })();

    </script>
    @endif

    <div class="min-h-screen p-6 md:p-12 flex flex-col items-center w-full max-w-7xl mx-auto">

        {{-- Header Section --}}
        <div class="text-center w-full mb-8">
            <h1 class="text-4xl md:text-5xl font-black italic tracking-tighter uppercase drop-shadow-md pb-2 text-transparent bg-clip-text bg-linear-to-r from-yellow-400 to-amber-500">
                OBS Director Console
            </h1>
            <p class="text-slate-400 font-bold uppercase tracking-[0.2em] text-xs mt-3 border border-slate-800 bg-slate-900/60 rounded-xl py-2 inline-block px-5">
                Active User Environment: <span class="text-yellow-400 font-extrabold">{{ $user->name }}</span>
            </p>
        </div>

        @if(session('status'))
        <div class="w-full bg-emerald-500/10 text-emerald-400 p-4 rounded-xl mb-8 border border-emerald-500/20 text-center font-bold tracking-wide shadow-[0_0_15px_rgba(16,185,129,0.15)] animate-pulse">
            {{ session('status') }}
        </div>
        @endif

        {{-- Segregated Quick Overlays Actions --}}
        <div class="w-full grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            
            {{-- PRE-MATCH SETUP & INFO CONTROLS --}}
            <div class="bg-slate-900/60 p-6 rounded-2xl border border-slate-800 shadow-xl flex flex-col justify-between">
                <div>
                    <h3 class="text-emerald-400 text-xs font-black uppercase tracking-[0.2em] mb-4 flex items-center gap-2 font-esports">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        Pre-Match Setup & Info Overlays
                    </h3>
                    <div class="grid grid-cols-3 gap-3">
                        <!-- ROADMAP BTN -->
                        <form action="{{ route('screens.switchview', ['user_id' => $user->id]) }}" method="POST" class="h-full">
                            @csrf
                            <input type="hidden" name="view" value="roadmap">
                            <button type="submit" class="w-full h-28 flex flex-col items-center justify-center gap-2 bg-slate-950/80 hover:bg-emerald-950/20 hover:border-emerald-500 border border-slate-850 rounded-xl text-emerald-400 transition-all shadow-md active:scale-[0.96] group">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor" class="w-7 h-7 group-hover:scale-108 transition-transform">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 6.75L12 3m0 0l3 3m-3-3v12m-9-2.25h18" />
                                </svg>
                                <span class="text-[10px] font-black uppercase tracking-wider text-center leading-tight">Roadmap</span>
                            </button>
                        </form>

                        <!-- MAP POOL BTN -->
                        <form action="{{ route('screens.switchview', ['user_id' => $user->id]) }}" method="POST" class="h-full">
                            @csrf
                            <input type="hidden" name="view" value="mappool">
                            <button type="submit" class="w-full h-28 flex flex-col items-center justify-center gap-2 bg-slate-950/80 hover:bg-emerald-950/20 hover:border-emerald-500 border border-slate-850 rounded-xl text-emerald-400 transition-all shadow-md active:scale-[0.96] group">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor" class="w-7 h-7 group-hover:scale-108 transition-transform">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 6.75L3 9v11.25l6-2.25m0-12l6 2.25m-6-2.25V20.25m6-11.25l6-2.25V18l-6 2.25m0-11.25V20.25" />
                                </svg>
                                <span class="text-[10px] font-black uppercase tracking-wider text-center leading-tight">Map Pool</span>
                            </button>
                        </form>

                        <!-- POINTS SYSTEM BTN -->
                        <form action="{{ route('screens.switchview', ['user_id' => $user->id]) }}" method="POST" class="h-full">
                            @csrf
                            <input type="hidden" name="view" value="pointsystem">
                            <button type="submit" class="w-full h-28 flex flex-col items-center justify-center gap-2 bg-slate-950/80 hover:bg-emerald-950/20 hover:border-emerald-500 border border-slate-850 rounded-xl text-emerald-400 transition-all shadow-md active:scale-[0.96] group">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor" class="w-7 h-7 group-hover:scale-108 transition-transform">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.03 0 1.9.693 2.166 1.638m-7.3 8.359a9 9 0 110-11.25" />
                                </svg>
                                <span class="text-[10px] font-black uppercase tracking-wider text-center leading-tight">Point System</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- POST-MATCH RESULTS & OUTROS CONTROLS --}}
            <div class="bg-slate-900/60 p-6 rounded-2xl border border-slate-800 shadow-xl flex flex-col justify-between">
                <div>
                    <h3 class="text-amber-500 text-xs font-black uppercase tracking-[0.2em] mb-4 flex items-center gap-2 font-esports">
                        <span class="w-2.5 h-2.5 rounded-full bg-amber-500 animate-pulse"></span>
                        Post-Match Results & Leaderboard Overlays
                    </h3>
                    <div class="grid grid-cols-4 gap-2.5">
                        <!-- POST MATCH BTN -->
                        <form action="{{ route('screens.switchview', ['user_id' => $user->id]) }}" method="POST" class="h-full">
                            @csrf
                            <input type="hidden" name="view" value="postmatch">
                            <button type="submit" class="w-full h-28 flex flex-col items-center justify-center gap-2 bg-slate-950/80 hover:bg-yellow-950/20 hover:border-yellow-500 border border-slate-850 rounded-xl text-yellow-500 transition-all shadow-md active:scale-[0.96] group">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor" class="w-7 h-7 group-hover:scale-108 transition-transform">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                                </svg>
                                <span class="text-[9.5px] font-black uppercase tracking-wider text-center leading-tight">Post Match</span>
                            </button>
                        </form>

                        <!-- OVERALL RANKING BTN -->
                        <form action="{{ route('screens.switchview', ['user_id' => $user->id]) }}" method="POST" class="h-full">
                            @csrf
                            <input type="hidden" name="view" value="overallranking">
                            <button type="submit" class="w-full h-28 flex flex-col items-center justify-center gap-2 bg-slate-950/80 hover:bg-orange-950/20 hover:border-orange-500 border border-slate-850 rounded-xl text-orange-400 transition-all shadow-md active:scale-[0.96] group">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor" class="w-7 h-7 group-hover:scale-108 transition-transform">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z" />
                                </svg>
                                <span class="text-[9.5px] font-black uppercase tracking-wider text-center leading-tight">Overall Rank</span>
                            </button>
                        </form>

                        <!-- HEAD TO HEAD BTN -->
                        <form action="{{ route('screens.switchview', ['user_id' => $user->id]) }}" method="POST" class="h-full">
                            @csrf
                            <input type="hidden" name="view" value="headtohead">
                            <button type="submit" class="w-full h-28 flex flex-col items-center justify-center gap-2 bg-slate-950/80 hover:bg-cyan-950/20 hover:border-cyan-500 border border-slate-850 rounded-xl text-cyan-400 transition-all shadow-md active:scale-[0.96] group">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor" class="w-7 h-7 group-hover:scale-108 transition-transform">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21L3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5" />
                                </svg>
                                <span class="text-[9.5px] font-black uppercase tracking-wider text-center leading-tight">Head to Head</span>
                            </button>
                        </form>

                        <!-- TOP 5 FRAGGERS BTN -->
                        <form action="{{ route('screens.switchview', ['user_id' => $user->id]) }}" method="POST" class="h-full">
                            @csrf
                            <input type="hidden" name="view" value="topfraggers">
                            <button type="submit" class="w-full h-28 flex flex-col items-center justify-center gap-2 bg-slate-950/80 hover:bg-purple-950/20 hover:border-purple-500 border border-slate-850 rounded-xl text-purple-400 transition-all shadow-md active:scale-[0.96] group">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor" class="w-7 h-7 group-hover:scale-108 transition-transform">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.362 5.214A8.252 8.252 0 0112 21 8.25 8.25 0 016.038 7.048 8.287 8.287 0 009 9.6a8.983 8.983 0 013.361-6.867 8.21 8.21 0 003 2.48z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 18a3.75 3.75 0 00.495-7.467 5.99 5.99 0 00-1.925 3.546 5.974 5.974 0 01-2.133-1A3.75 3.75 0 0012 18z" />
                                </svg>
                                <span class="text-[9.5px] font-black uppercase tracking-wider text-center leading-tight">Top Fraggers</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>

        </div>

        {{-- MAIN SYSTEM ACTIONS CONTROLS --}}
        <div class="w-full grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- CLEAR / EMPTY SCREEN BTN -->
            <form action="{{ route('screens.switchview', ['user_id' => $user->id]) }}" method="POST" class="w-full">
                @csrf
                <input type="hidden" name="view" value="empty">
                <button type="submit" class="w-full py-4.5 flex items-center justify-center gap-3 bg-slate-900/60 hover:bg-slate-900 hover:border-slate-600 border border-slate-800 rounded-2xl text-slate-400 hover:text-slate-200 transition-all font-black uppercase tracking-widest active:scale-95 text-xs shadow-md">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4.5 h-4.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                    </svg>
                    Clear Master Screen (Empty)
                </button>
            </form>

            <!-- LEADERBOARD HUD VISIBILITY TOGGLE -->
            <form action="{{ route('screens.togglevisibility', ['user_id' => $user->id]) }}" method="POST" class="w-full">
                @csrf
                <input type="hidden" name="visible" value="{{ $activeMatch && $activeMatch->is_leaderboard_visible ? '0' : '1' }}">
                <button type="submit" class="w-full py-4.5 flex items-center justify-center gap-3 {{ $activeMatch && $activeMatch->is_leaderboard_visible ? 'bg-rose-950/20 hover:bg-rose-950/40 border-rose-500/30 hover:border-rose-500 text-rose-400' : 'bg-emerald-950/20 hover:bg-emerald-950/40 border-emerald-500/30 hover:border-emerald-500 text-emerald-400' }} border rounded-2xl transition-all font-black uppercase tracking-widest active:scale-95 text-xs shadow-md">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4.5 h-4.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    {{ $activeMatch && $activeMatch->is_leaderboard_visible ? 'Hide Leaderboard HUD' : 'Show Leaderboard HUD' }}
                </button>
            </form>

            <!-- FORCE REFRESH ALL CLIENTS -->
            <form action="{{ route('screens.refresh', ['user_id' => $user->id]) }}" method="POST" class="w-full">
                @csrf
                <button type="submit" class="w-full py-4.5 flex items-center justify-center gap-3 bg-indigo-950/20 hover:bg-indigo-950/40 border border-indigo-500/30 hover:border-indigo-500 rounded-2xl text-indigo-400 transition-all font-black uppercase tracking-widest active:scale-95 text-xs shadow-md">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4.5 h-4.5 animate-spin-slow">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                    </svg>
                    Force Refresh Screens
                </button>
            </form>
        </div>



        {{-- REAL-TIME COUNTDOWN TIMER & CONTROLS HUD --}}
        <div class="w-full bg-slate-900/50 border border-slate-800 rounded-2xl p-6 mb-8 shadow-xl">
            <h2 class="text-yellow-400 text-lg font-black uppercase tracking-wider mb-6 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Countdown Timer & HUD Controls
            </h2>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-center">

                {{-- Left: Live Timer Display & Visibility State --}}
                <div class="flex flex-col items-center justify-center bg-slate-950 rounded-2xl border border-slate-850 p-6 shadow-inner relative overflow-hidden group">
                    <div class="absolute top-2 right-3 text-[9px] uppercase tracking-widest font-black" id="visibility-status-badge">
                        @if($timerState['visible'])
                        <span class="text-emerald-400">VISIBLE ON OVERLAYS</span>
                        @else
                        <span class="text-rose-500">HIDDEN ON OVERLAYS</span>
                        @endif
                    </div>
                    <span class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-500 mb-1">Director View Clock</span>
                    <div class="font-mono text-5xl font-black text-yellow-400 tracking-wider pulse-timer" id="console-timer-display">
                        00:00
                    </div>
                    <span class="text-[9px] font-bold uppercase tracking-wider text-slate-400 mt-2" id="timer-status-badge">
                        Status: <span class="uppercase font-black text-yellow-400">{{ $timerState['status'] }}</span>
                    </span>
                </div>

                {{-- Middle: Configure Timer duration --}}
                <div class="flex flex-col gap-4">
                    <div class="flex flex-col">
                        <label class="text-xs font-black uppercase tracking-wider text-slate-400 mb-1.5">Timer Duration (H:M:S)</label>
                        <div class="flex gap-2 items-center">
                            <div class="flex flex-col w-16">
                                <span class="text-[8px] text-slate-500 font-bold uppercase mb-1">Hours</span>
                                <input type="number" id="timer-hours-input" min="0" max="23" value="{{ floor($timerState['duration'] / 3600) }}" class="bg-slate-950 border border-slate-800 rounded-xl px-2 py-2.5 text-white font-mono font-black text-center focus:outline-none focus:border-yellow-400 focus:ring-1 focus:ring-yellow-400 w-full">
                            </div>
                            <div class="text-slate-600 font-black pt-4">:</div>
                            <div class="flex flex-col w-16">
                                <span class="text-[8px] text-slate-500 font-bold uppercase mb-1">Minutes</span>
                                <input type="number" id="timer-minutes-input" min="0" max="59" value="{{ floor(($timerState['duration'] % 3600) / 60) }}" class="bg-slate-950 border border-slate-800 rounded-xl px-2 py-2.5 text-white font-mono font-black text-center focus:outline-none focus:border-yellow-400 focus:ring-1 focus:ring-yellow-400 w-full">
                            </div>
                            <div class="text-slate-600 font-black pt-4">:</div>
                            <div class="flex flex-col w-16">
                                <span class="text-[8px] text-slate-500 font-bold uppercase mb-1">Seconds</span>
                                <input type="number" id="timer-seconds-input" min="0" max="59" value="{{ $timerState['duration'] % 60 }}" class="bg-slate-950 border border-slate-800 rounded-xl px-2 py-2.5 text-white font-mono font-black text-center focus:outline-none focus:border-yellow-400 focus:ring-1 focus:ring-yellow-400 w-full">
                            </div>
                            <button onclick="setTimerDuration()" class="self-end bg-yellow-400 hover:bg-yellow-500 text-black font-black uppercase tracking-wider px-4 py-2.5 rounded-xl transition-all hover:scale-[1.02] text-xs shrink-0 active:scale-95 shadow-md">
                                Apply
                            </button>
                        </div>
                    </div>

                    {{-- Toggle timer visibility --}}
                    <div class="grid grid-cols-2 gap-3 mt-1">
                        <button onclick="toggleTimerVisibility(1)" id="btn-show-timer" class="py-2.5 bg-emerald-950/20 border border-emerald-500/30 hover:border-emerald-500 text-emerald-400 font-bold uppercase text-xs tracking-wider rounded-xl transition-all hover:scale-[1.01] active:scale-95">
                            Show Timer
                        </button>
                        <button onclick="toggleTimerVisibility(0)" id="btn-hide-timer" class="py-2.5 bg-rose-950/20 border border-rose-500/30 hover:border-rose-500 text-rose-400 font-bold uppercase text-xs tracking-wider rounded-xl transition-all hover:scale-[1.01] active:scale-95">
                            Hide Timer
                        </button>
                    </div>
                </div>

                {{-- Right: Direct Countdown Controls --}}
                <div class="flex flex-col gap-3.5">
                    <button onclick="triggerTimerAction('start')" class="w-full py-4.5 bg-yellow-400 hover:bg-yellow-500 text-black font-black uppercase tracking-widest text-sm rounded-xl transition-all hover:scale-[1.01] active:scale-95 shadow-md flex items-center justify-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4">
                            <path fill-rule="evenodd" d="M4.5 5.653c0-1.426 1.529-2.33 2.779-1.643l11.54 6.348c1.295.712 1.295 2.573 0 3.285L7.28 19.991c-1.25.687-2.779-.217-2.779-1.643V5.653z" clip-rule="evenodd" />
                        </svg>
                        Start Countdown
                    </button>

                    <div class="grid grid-cols-2 gap-4">
                        <button onclick="triggerTimerAction('pause')" class="py-3 bg-orange-500/10 hover:bg-orange-500/20 border border-orange-500/30 hover:border-orange-500 text-orange-400 font-black uppercase text-xs tracking-widest rounded-xl transition-all hover:scale-[1.01] active:scale-95 flex items-center justify-center gap-1.5">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-3.5 h-3.5">
                                <path fill-rule="evenodd" d="M6.75 5.25a.75.75 0 01.75-.75H9a.75.75 0 01.75.75v13.5a.75.75 0 01-.75.75H7.5a.75.75 0 01-.75-.75V5.25zm7.5 0A.75.75 0 0115 4.5h1.5a.75.75 0 01.75.75v13.5a.75.75 0 01-.75.75H15a.75.75 0 01-.75-.75V5.25z" clip-rule="evenodd" />
                            </svg>
                            Pause
                        </button>
                        <button onclick="triggerTimerAction('reset')" class="py-3 bg-rose-500/10 hover:bg-rose-500/20 border border-rose-500/30 hover:border-rose-500 text-rose-400 font-black uppercase text-xs tracking-widest rounded-xl transition-all hover:scale-[1.01] active:scale-95 flex items-center justify-center gap-1.5">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-3.5 h-3.5">
                                <path fill-rule="evenodd" d="M4.5 7.5a3 3 0 013-3h9a3 3 0 013 3v9a3 3 0 01-3 3h-9a3 3 0 01-3-3v-9z" clip-rule="evenodd" />
                            </svg>
                            Reset Timer
                        </button>
                    </div>
                </div>

            </div>
        </div>

        {{-- INTERMISSION BACKGROUND CONTROLLER --}}
        <div class="w-full bg-slate-900/50 border border-slate-800 rounded-2xl p-6 mb-8 shadow-xl">
            <h2 class="text-yellow-400 text-lg font-black uppercase tracking-wider mb-6 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.53 16.122a3 3 0 00-5.78 1.128 2.25 2.25 0 01-2.4 2.245 4.5 4.5 0 008.4-1.242 2.25 2.25 0 012.25-2.25 2.25 2.25 0 002.25-2.25 4.5 4.5 0 00-1.242-3.12 2.25 2.25 0 010-3.18 4.5 4.5 0 00-6.364 0 2.25 2.25 0 01-3.182 0 4.5 4.5 0 00-6.364 0 2.25 2.25 0 01-3.181 0 4.5 4.5 0 00-6.364 6.364 2.25 2.25 0 010 3.181z" />
                </svg>
                Intermission Background Type (Starting & Ending Screens)
            </h2>

            <div class="flex flex-col gap-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-center">
                    <div class="text-slate-400 text-sm">
                        Configure whether the Starting Soon and Ending intermission screens should have a fully transparent background (for OBS overlays), the built-in cyber animated theme, or your custom uploaded animated video.
                        <div class="mt-2 text-xs uppercase font-black text-slate-500">
                            Current Status: <span class="text-yellow-400 font-extrabold" id="bg-type-status-badge">{{ strtoupper($bgType) }}</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-3">
                        <button onclick="updateBackgroundType('transparent')" id="btn-bg-transparent" class="py-3 px-2 rounded-xl font-black uppercase text-[10px] tracking-wider transition-all active:scale-95 shadow-md flex flex-col items-center justify-center gap-1.5 border {{ $bgType === 'transparent' ? 'bg-yellow-400 border-yellow-500 text-black font-black' : 'bg-slate-950/60 border-slate-850 hover:border-yellow-500 text-yellow-500' }}">
                            <span>Transparent</span>
                            <span class="text-[8px] opacity-75 font-semibold">(OBS Layers)</span>
                        </button>
                        <button onclick="updateBackgroundType('animated')" id="btn-bg-animated" class="py-3 px-2 rounded-xl font-black uppercase text-[10px] tracking-wider transition-all active:scale-95 shadow-md flex flex-col items-center justify-center gap-1.5 border {{ $bgType === 'animated' ? 'bg-yellow-400 border-yellow-500 text-black font-black' : 'bg-slate-950/60 border-slate-850 hover:border-yellow-500 text-yellow-500' }}">
                            <span>Cyber Theme</span>
                            <span class="text-[8px] opacity-75 font-semibold">(Built-in)</span>
                        </button>
                        <button onclick="updateBackgroundType('custom')" id="btn-bg-custom" @if(!$customVideo) disabled title="Please upload a custom video first" @endif class="py-3 px-2 rounded-xl font-black uppercase text-[10px] tracking-wider transition-all active:scale-95 shadow-md flex flex-col items-center justify-center gap-1.5 border {{ $bgType === 'custom' ? 'bg-yellow-400 border-yellow-500 text-black font-black' : ($customVideo ? 'bg-slate-950/60 border-slate-850 hover:border-yellow-500 text-yellow-500' : 'bg-slate-950/20 border-slate-900 text-slate-600 cursor-not-allowed') }}">
                            <span>Custom Video</span>
                            <span class="text-[8px] opacity-75 font-semibold">(Uploaded)</span>
                        </button>
                    </div>
                </div>

                {{-- Upload & Custom Video Management Zone --}}
                <div class="border-t border-slate-800/80 pt-6">
                    <h3 class="text-xs uppercase font-black tracking-widest text-slate-400 mb-4 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4 text-yellow-400">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                        </svg>
                        Custom Animated Video Settings
                    </h3>

                    @if($customVideo)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-center bg-slate-950/40 p-4 rounded-xl border border-slate-800">
                        <div class="flex items-center gap-4">
                            <div class="w-32 h-20 bg-slate-900 rounded-lg overflow-hidden border border-slate-800 relative flex items-center justify-center shrink-0">
                                <video autoplay loop muted playsinline class="w-full h-full object-cover">
                                    <source src="{{ asset('storage/' . $customVideo) }}" type="video/mp4">
                                </video>
                            </div>
                            <div class="flex flex-col overflow-hidden">
                                <span class="text-xs text-slate-500 font-bold uppercase tracking-wider">Active Background Video</span>
                                <span class="text-sm font-semibold text-slate-200 truncate mt-1">{{ basename($customVideo) }}</span>
                                <span class="text-[10px] text-emerald-400 font-black uppercase mt-1 tracking-wider">Ready for Broadcast</span>
                            </div>
                        </div>
                        <div class="flex flex-col sm:flex-row justify-end gap-3">
                            {{-- Replace video triggers standard file upload --}}
                            <form action="{{ route('screens.uploadvideo', ['user_id' => $user->id]) }}" method="POST" enctype="multipart/form-data" class="flex items-center gap-2">
                                @csrf
                                <label class="px-4 py-2.5 bg-slate-900 hover:bg-slate-850 text-slate-300 border border-slate-800 hover:border-slate-700 rounded-lg text-xs uppercase font-bold tracking-wider cursor-pointer transition-all active:scale-95 flex items-center gap-1.5 w-full sm:w-auto justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                                    </svg>
                                    Replace Video
                                    <input type="file" name="video" accept="video/mp4,video/webm,video/quicktime" class="hidden" onchange="this.form.submit()">
                                </label>
                            </form>

                            <form action="{{ route('screens.deletevideo', ['user_id' => $user->id]) }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full sm:w-auto px-4 py-2.5 bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 border border-rose-500/30 hover:border-rose-400 rounded-lg text-xs uppercase font-bold tracking-wider transition-all active:scale-95 flex items-center justify-center gap-1.5">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                    </svg>
                                    Delete Video
                                </button>
                            </form>
                        </div>
                    </div>
                    @else
                    <form action="{{ route('screens.uploadvideo', ['user_id' => $user->id]) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="flex flex-col items-center justify-center border-2 border-dashed border-slate-800 hover:border-slate-700 bg-slate-950/20 rounded-xl p-8 transition-colors text-center relative group">
                            <input type="file" name="video" accept="video/mp4,video/webm,video/quicktime" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" onchange="this.form.submit()">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8 text-slate-500 group-hover:text-yellow-400 transition-colors mb-3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V9.75m0 0l3 3m-3-3l-3 3M6.75 19.5a4.5 4.5 0 01-1.41-8.775 5.25 5.25 0 0110.233-2.33 3 3 0 013.758 3.848A3.752 3.752 0 0118 19.5H6.75z" />
                            </svg>
                            <span class="text-xs text-slate-400 font-bold uppercase tracking-wider">Drag & drop or click to upload video</span>
                            <span class="text-[10px] text-slate-500 mt-1 uppercase">Supports MP4, WebM, MOV (Max 50MB)</span>
                        </div>
                    </form>
                    @endif

                    @error('video')
                    <div class="text-rose-500 text-xs font-bold mt-2 uppercase tracking-wide">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
            </div>
        </div>

        {{-- FORCE REFRESH ALL SCREENS --}}
        <div class="w-full mb-8">
            <form action="{{ route('screens.refresh', ['user_id' => $user->id]) }}" method="POST">
                @csrf
                <button type="submit" class="w-full py-4.5 flex items-center justify-center gap-3 bg-indigo-900/20 hover:bg-indigo-900/40 border border-indigo-500/30 hover:border-indigo-500 rounded-2xl text-indigo-400 transition-all font-black uppercase tracking-wider text-sm active:scale-[0.99] shadow-md">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 animate-spin-slow">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                    </svg>
                    Force Refresh All Screens
                </button>
            </form>
        </div>

        {{-- LIVE STATS CONTROL LINK --}}
        <a href="{{ route('screens.statscontrol', ['user_id' => $user->id]) }}" class="w-full py-5 flex items-center justify-center gap-3 bg-amber-950/20 hover:bg-amber-950/40 border border-amber-500/30 hover:border-amber-500 rounded-2xl text-amber-400 transition-all font-black uppercase tracking-widest active:scale-[0.99] text-base shadow-md">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-9.75 0h9.75" />
            </svg>
            Open Live Stats Control Panel
        </a>

        {{-- Overlays Links Grid --}}
        <div class="mt-12 w-full">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6 border-b border-slate-800 pb-3">
                <div>
                    <h3 class="text-yellow-400 text-xl font-black uppercase tracking-wider font-esports">Active OBS Broadcast Overlays</h3>
                    <p class="text-slate-500 text-xs mt-1 uppercase font-bold tracking-wider">Categorized production screens for live streams</p>
                </div>
                <div class="flex items-center gap-2">
                    <span class="px-2.5 py-1 rounded bg-slate-900 border border-slate-800 text-[10px] font-black uppercase tracking-wider text-slate-400">Total: 16 Screens</span>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @php
                    $overlays = [
                        [
                            'name' => 'Main Master OBS',
                            'route' => route('screens.obsmaster', ['user_id' => $user->id]),
                            'category' => 'Console',
                            'badge_color' => 'bg-indigo-500/10 text-indigo-400 border-indigo-500/30',
                            'desc' => 'Dynamic screen switcher supporting entry and exit transitions. Central broadcast feed.',
                            'dimensions' => '1920 x 1080',
                            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17.25v1.007a3 3 0 0 1-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0 1 15 18.257V17.25m6-12V15a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 15V5.25m18 0A2.25 2.25 0 0 0 18.75 3H5.25A2.25 2.25 0 0 0 3 5.25m18 0V12a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 12V5.25" /></svg>'
                        ],
                        [
                            'name' => 'Upcoming Matches',
                            'route' => route('screens.upcomingmatches', ['user_id' => $user->id]),
                            'category' => 'Pre-Match / Setup',
                            'badge_color' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/30',
                            'desc' => 'L-shaped advertisement frame with upcoming schedule and scrolling sponsors.',
                            'dimensions' => '1920 x 1080',
                            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" /></svg>'
                        ],
                        [
                            'name' => 'Starting Soon',
                            'route' => route('screens.startingsoon', ['user_id' => $user->id]),
                            'category' => 'Pre-Match / Setup',
                            'badge_color' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/30',
                            'desc' => 'Beautiful pre-stream landing with large countdown clock and partner showcase slides.',
                            'dimensions' => '1920 x 1080',
                            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0z" /></svg>'
                        ],
                        [
                            'name' => 'Ending Screen',
                            'route' => route('screens.ending', ['user_id' => $user->id]),
                            'category' => 'Post-Match / Outro',
                            'badge_color' => 'bg-amber-500/10 text-amber-400 border-amber-500/30',
                            'desc' => 'Ending closure screen with dynamic social handles, countdown, and active timer.',
                            'dimensions' => '1920 x 1080',
                            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M5.636 5.636a9 9 0 1 0 12.728 0M12 3v9" /></svg>'
                        ],
                        [
                            'name' => 'Live Ranking HUD',
                            'route' => route('screens.activematch', ['user_id' => $user->id]),
                            'category' => 'Post-Match / Results',
                            'badge_color' => 'bg-amber-500/10 text-amber-400 border-amber-500/30',
                            'desc' => 'Left-side scrolling leaderboard display HUD for active tournament matches.',
                            'dimensions' => '1920 x 1080',
                            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 5.25h16.5m-16.5 4.5h16.5m-16.5 4.5h16.5m-16.5 4.5h16.5" /></svg>'
                        ],
                        [
                            'name' => 'Map Screen HUD',
                            'route' => route('screens.mapscreen', ['user_id' => $user->id]),
                            'category' => 'Post-Match / Results',
                            'badge_color' => 'bg-amber-500/10 text-amber-400 border-amber-500/30',
                            'desc' => '1080x1080 map framing with active rosters, players alive, and scoreboard HUD.',
                            'dimensions' => '1920 x 1080',
                            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 6.75V15m6-6v8m-9-3h12" /></svg>'
                        ],
                        [
                            'name' => 'Casters Overlay',
                            'route' => route('screens.castersscreen', ['user_id' => $user->id]),
                            'category' => 'Pre-Match / Setup',
                            'badge_color' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/30',
                            'desc' => 'Sleek 2-caster dual camera layout with custom transparent feeds overlay.',
                            'dimensions' => '1920 x 1080',
                            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 18.75a6 6 0 0 0 6-6v-1.5m-6 7.5a6 6 0 0 1-6-6v-1.5m6 7.5v3.75m-3.75 0h7.5M12 15.75a3 3 0 0 1-3-3V4.5a3 3 0 1 1 6 0v8.25a3 3 0 0 1-3 3z" /></svg>'
                        ],
                        [
                            'name' => 'Head-to-Head Duel',
                            'route' => route('screens.headtohead', ['user_id' => $user->id]),
                            'category' => 'Post-Match / Results',
                            'badge_color' => 'bg-amber-500/10 text-amber-400 border-amber-500/30',
                            'desc' => 'Cinematic diagonal split comparative dashboard between the top 2 teams in real time.',
                            'dimensions' => '1920 x 1080',
                            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12c0-1.232-.046-2.453-.138-3.662a4.006 4.006 0 0 0-3.7-3.7 48.656 48.656 0 0 0-7.324 0 4.006 4.006 0 0 0-3.7 3.7c-.017.22-.032.441-.046.662M19.5 12l3-3m-3 3l-3-3M4.5 12a48.654 48.654 0 0 1 .138-3.662 4.006 4.006 0 0 1 3.7-3.7 48.656 48.656 0 0 1 7.324 0 4.006 4.006 0 0 1 3.7 3.7c.017.22.032.441.046.662M4.5 12l-3 3m3-3l3-3" /></svg>'
                        ],
                        [
                            'name' => 'Top 5 Fraggers',
                            'route' => route('screens.topfraggers', ['user_id' => $user->id]),
                            'category' => 'Post-Match / Results',
                            'badge_color' => 'bg-amber-500/10 text-amber-400 border-amber-500/30',
                            'desc' => 'Interactive statistics dashboard highlighting top 5 killers of the match.',
                            'dimensions' => '1920 x 1080',
                            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.362 5.214A8.252 8.252 0 0 1 12 21 8.25 8.25 0 0 1 6.038 7.048 8.287 8.287 0 0 0 9 9.6a8.983 8.983 0 0 1 3.361-6.867 8.21 8.21 0 0 0 3 2.48z" /><path stroke-linecap="round" stroke-linejoin="round" d="M12 18a3.75 3.75 0 0 0 .495-7.467 5.99 5.99 0 0 0-1.925 3.546 5.974 5.974 0 0 1-2.133-1A3.75 3.75 0 0 0 12 18z" /></svg>'
                        ],
                        [
                            'name' => 'Post-Match Stats',
                            'route' => route('screens.postmatch', ['user_id' => $user->id]),
                            'category' => 'Post-Match / Results',
                            'badge_color' => 'bg-amber-500/10 text-amber-400 border-amber-500/30',
                            'desc' => 'High-tech post-match summary displaying final team standings and elims.',
                            'dimensions' => '1920 x 1080',
                            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 0 1 3-3h-15a3 3 0 0 1 3 3m9 0v3.75m-9 0v-3.75m9 0h3.75m-12.75 0H3.75m9.75-13.5A3.75 3.75 0 0 0 9.75 9.75v1.5a3.75 3.75 0 0 0 3.75 3.75M12 5.25a3.75 3.75 0 0 1 3.75 3.75v1.5a3.75 3.75 0 0 1-3.75 3.75" /></svg>'
                        ],
                        [
                            'name' => 'Overall Standings',
                            'route' => route('screens.overallranking', ['user_id' => $user->id]),
                            'category' => 'Post-Match / Results',
                            'badge_color' => 'bg-amber-500/10 text-amber-400 border-amber-500/30',
                            'desc' => 'Official overall standings showing total matches, kills, place points, and WWCDs.',
                            'dimensions' => '1920 x 1080',
                            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 14.25v2.25m3-4.5v4.5m3-6.75v6.75m3-9v9M6 20.25h12A2.25 2.25 0 0 0 20.25 18V6A2.25 2.25 0 0 0 18 3.75H6A2.25 2.25 0 0 0 3.75 6v12A2.25 2.25 0 0 0 6 20.25z" /></svg>'
                        ],
                        [
                            'name' => 'Lobby Slot List',
                            'route' => route('screens.slotlist', ['user_id' => $user->id]),
                            'category' => 'Pre-Match / Setup',
                            'badge_color' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/30',
                            'desc' => 'Visual slot list displaying lobby allocations starting from slot 2.',
                            'dimensions' => '1920 x 1080',
                            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0zM3.75 12h.007v.008H3.75V12zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0zm-.375 5.25h.007v.008H3.75v-.008zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0z" /></svg>'
                        ],
                        [
                            'name' => 'Map Pool Schedule',
                            'route' => route('screens.mappool', ['user_id' => $user->id]),
                            'category' => 'Pre-Match / Setup',
                            'badge_color' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/30',
                            'desc' => 'Broadcast board highlighting maps layout, modes, and winners of current rounds.',
                            'dimensions' => '1920 x 1080',
                            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 6.75L3 9v11.25l6-2.25m0-12l6 2.25m-6-2.25V20.25m6-11.25l6-2.25V18l-6 2.25m0-11.25V20.25" /></svg>'
                        ],
                        [
                            'name' => 'Point System Rules',
                            'route' => route('screens.pointsystem', ['user_id' => $user->id]),
                            'category' => 'Pre-Match / Setup',
                            'badge_color' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/30',
                            'desc' => 'Broadcast point system breakdown explaining placement and elim rules.',
                            'dimensions' => '1920 x 1080',
                            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.03 0 1.9.693 2.166 1.638m-7.3 8.359a9 9 0 110-11.25" /></svg>'
                        ],
                        [
                            'name' => 'Tournament Roadmap',
                            'route' => route('screens.roadmap', ['user_id' => $user->id]),
                            'category' => 'Pre-Match / Setup',
                            'badge_color' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/30',
                            'desc' => 'High-fidelity timeline roadmap displaying stages, deadlines, and tournament roadmap.',
                            'dimensions' => '1920 x 1080',
                            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 6.75L12 3m0 0l3 3m-3-3v12m-9-2.25h18" /></svg>'
                        ],
                        [
                            'name' => 'Team Elimination HUD',
                            'route' => route('screens.teamelimination', ['user_id' => $user->id]),
                            'category' => 'Post-Match / Results',
                            'badge_color' => 'bg-amber-500/10 text-amber-400 border-amber-500/30',
                            'desc' => 'Real-time broadcast HUD showing team-by-team status and eliminations dynamically.',
                            'dimensions' => '1920 x 1080',
                            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0z" /></svg>'
                        ]
                    ];
                @endphp

                @foreach($overlays as $idx => $overlay)
                    <div class="bg-slate-900/60 p-5 rounded-2xl border border-slate-800 flex flex-col justify-between shadow-md hover:border-slate-700 transition-all">
                        <div>
                            <div class="flex items-center justify-between gap-2 mb-3.5">
                                <span class="px-2.5 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider border {{ $overlay['badge_color'] }}">
                                    {{ $overlay['category'] }}
                                </span>
                                <span class="text-slate-500 text-[10px] font-bold font-mono tracking-wider">{{ $overlay['dimensions'] }}</span>
                            </div>
                            <div class="flex items-start gap-3">
                                <div class="p-2.5 rounded-xl bg-slate-950/80 text-yellow-400 border border-slate-850 self-start">
                                    {!! $overlay['icon'] !!}
                                </div>
                                <div class="flex-1">
                                    <h4 class="text-white text-base font-black uppercase tracking-wide leading-tight">{{ $overlay['name'] }}</h4>
                                    <p class="text-slate-400 text-xs mt-1.5 leading-relaxed line-clamp-2">{{ $overlay['desc'] }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-2 mt-5 border-t border-slate-850 pt-4">
                            <a href="{{ $overlay['route'] }}" target="_blank" class="px-3 py-2 bg-yellow-500/10 hover:bg-yellow-500/20 text-yellow-400 border border-yellow-500/20 hover:border-yellow-500/50 rounded-xl text-[10px] uppercase font-black tracking-wider text-center transition-all whitespace-nowrap flex items-center justify-center gap-1.5 shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" /></svg>
                                <span>Open Overlay</span>
                            </a>
                            <button type="button" onclick="copyObsLink('{{ $overlay['route'] }}', this, event)" class="px-3 py-2 bg-slate-950 hover:bg-emerald-600 text-slate-300 hover:text-white border border-slate-850 hover:border-transparent rounded-xl text-[10px] uppercase font-black tracking-wider text-center transition-all flex items-center justify-center gap-1.5 shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.666 3.888A2.25 2.25 0 0 0 13.5 2.25h-3c-1.03 0-1.9.693-2.166 1.638m7.332 0c.055.194.084.4.084.612v0a.75.75 0 0 1-.75.75H9a.75.75 0 0 1-.75-.75v0c0-.212.03-.418.084-.612m7.332 0c.346.102.637.318.806.622.196.353.312.76.312 1.193v12.25a2.25 2.25 0 0 1-2.25 2.25H9a2.25 2.25 0 0 1-2.25-2.25V5.5c0-.433.116-.84.312-1.193.17-.304.46-.52.806-.622" /></svg>
                                <span>Copy Link</span>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Script for AJAX Real-time sync --}}
    <script>
        function copyObsLink(url, button, event) {
            function doCopy() {
                if (navigator.clipboard && window.isSecureContext) {
                    return navigator.clipboard.writeText(url);
                } else {
                    return new Promise((resolve, reject) => {
                        const textArea = document.createElement("textarea");
                        textArea.value = url;
                        textArea.style.top = "0";
                        textArea.style.left = "0";
                        textArea.style.position = "fixed";
                        document.body.appendChild(textArea);
                        textArea.focus();
                        textArea.select();
                        try {
                            const successful = document.execCommand('copy');
                            if (successful) resolve();
                            else reject(new Error('Copy command failed'));
                        } catch (err) {
                            reject(err);
                        }
                        document.body.removeChild(textArea);
                    });
                }
            }

            doCopy().then(() => {
                const textSpan = button.querySelector('span');
                const svgNode = button.querySelector('svg');

                const originalText = textSpan.innerText;
                const originalSvg = svgNode.innerHTML;

                // Success feedback
                textSpan.innerText = 'Copied!';
                svgNode.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />';

                button.classList.remove('bg-slate-800', 'text-slate-300', 'border-slate-700/60');
                button.classList.add('bg-emerald-600', 'text-white', 'border-transparent');

                setTimeout(() => {
                    textSpan.innerText = originalText;
                    svgNode.innerHTML = originalSvg;

                    button.classList.add('bg-slate-800', 'text-slate-300', 'border-slate-700/60');
                    button.classList.remove('bg-emerald-600', 'text-white', 'border-transparent');
                }, 1500);

                // Spawn cursor tooltip above the button
                showCursorTooltip(button, 'Link Copied!');
            }).catch(err => {
                console.error('Could not copy link: ', err);
                showCursorTooltip(button, 'Copy Failed!');
            });
        }

        function showCursorTooltip(element, text) {
            const tooltip = document.createElement('div');
            tooltip.innerText = text;
            tooltip.style.position = 'fixed';
            tooltip.style.background = '#10b981'; // Emerald
            tooltip.style.color = '#ffffff';
            tooltip.style.padding = '6px 12px';
            tooltip.style.borderRadius = '6px';
            tooltip.style.fontSize = '12px';
            tooltip.style.fontWeight = 'bold';
            tooltip.style.pointerEvents = 'none';
            tooltip.style.zIndex = '99999';
            tooltip.style.boxShadow = '0 4px 10px rgba(0, 0, 0, 0.3)';
            tooltip.style.transition = 'all 0.4s cubic-bezier(0.16, 1, 0.3, 1)';
            tooltip.style.opacity = '0';
            tooltip.style.transform = 'translateY(8px)';

            document.body.appendChild(tooltip);

            // Calculate element position in viewport
            const rect = element.getBoundingClientRect();
            const tipRect = tooltip.getBoundingClientRect();

            // Position centered above the element
            const x = rect.left + (rect.width / 2) - (tipRect.width / 2);
            const y = rect.top - tipRect.height - 8;

            tooltip.style.left = x + 'px';
            tooltip.style.top = y + 'px';

            // Force reflow
            tooltip.offsetHeight;

            // Animate in
            tooltip.style.opacity = '1';
            tooltip.style.transform = 'translateY(0)';

            // Fade out and remove
            setTimeout(() => {
                tooltip.style.opacity = '0';
                tooltip.style.transform = 'translateY(-12px)';
                setTimeout(() => {
                    if (tooltip.parentNode) {
                        tooltip.parentNode.removeChild(tooltip);
                    }
                }, 400);
            }, 1000);
        }

    </script>

    <script type="module">
        let timerDuration = {{ $timerState['duration'] }};
        let timerStatus = "{{ $timerState['status'] }}";
        let timerEndsAt = {{ $timerState['endsAt'] }};
        let timerRemaining = {{ $timerState['remainingSeconds'] }};
        let timerVisible = {{ $timerState['visible'] ? 'true' : 'false' }};
        let timerShowHours = {{ $timerState['showHours'] ? 'true' : 'false' }};
        let timerInterval = null;

        function formatTime(seconds, showHoursFlag = false) {
            const hrs = Math.floor(seconds / 3600);
            const mins = Math.floor((seconds % 3600) / 60);
            const secs = seconds % 60;
            
            if (showHoursFlag || hrs > 0) {
                return `${hrs.toString().padStart(2, '0')}:${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
            }
            return `${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
        }

        function updateControlPanelUI() {
            // Update live clock view
            const display = document.getElementById('console-timer-display');
            if (display) {
                if (timerStatus === 'running') {
                    const now = Math.floor(Date.now() / 1000);
                    const remaining = Math.max(0, timerEndsAt - now);
                    display.innerText = formatTime(remaining, timerShowHours);
                    if (remaining <= 0) {
                        display.innerText = timerShowHours ? "00:00:00" : "00:00";
                        timerStatus = 'stopped';
                        clearInterval(timerInterval);
                    }
                } else if (timerStatus === 'paused') {
                    display.innerText = formatTime(timerRemaining, timerShowHours);
                } else {
                    display.innerText = formatTime(timerDuration, timerShowHours);
                }
            }

            // Update status text
            const statusBadge = document.getElementById('timer-status-badge');
            if (statusBadge) {
                statusBadge.innerHTML = `Status: <span class="uppercase font-black text-yellow-400">${timerStatus}</span>`;
            }

            // Update visibility badge
            const visBadge = document.getElementById('visibility-status-badge');
            if (visBadge) {
                if (timerVisible) {
                    visBadge.innerHTML = `<span class="text-emerald-400">VISIBLE ON OVERLAYS</span>`;
                } else {
                    visBadge.innerHTML = `<span class="text-rose-500">HIDDEN ON OVERLAYS</span>`;
                }
            }
        }

        window.triggerTimerAction = async function(action, payload = {}) {
            try {
                const response = await fetch('{{ route("screens.updatetimer", ["user_id" => $user->id]) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ action, ...payload })
                });

                if (response.ok) {
                    const data = await response.json();
                    timerStatus = data.status;
                    timerDuration = data.duration;
                    timerRemaining = data.remainingSeconds;
                    timerEndsAt = data.endsAt;
                    timerVisible = data.visible;
                    timerShowHours = data.showHours;
                    
                    updateControlPanelUI();
                    if (timerStatus === 'running') {
                        startTimerLoop();
                    } else if (timerStatus === 'stopped' || timerStatus === 'paused') {
                        clearInterval(timerInterval);
                    }
                } else {
                    console.error('Action failed:', response.statusText);
                }
            } catch (err) {
                console.error('Network error during action:', err);
            }
        }

        window.updateBackgroundType = async function(bgType) {
            try {
                const response = await fetch('{{ route("screens.updatebg", ["user_id" => $user->id]) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ bg_type: bgType })
                });

                if (response.ok) {
                    const data = await response.json();
                    
                    // Update state badge
                    const statusBadge = document.getElementById('bg-type-status-badge');
                    if (statusBadge) {
                        statusBadge.innerText = data.bg_type.toUpperCase();
                    }

                    // Toggle button styling
                    const btnTransparent = document.getElementById('btn-bg-transparent');
                    const btnAnimated = document.getElementById('btn-bg-animated');
                    const btnCustom = document.getElementById('btn-bg-custom');
                    
                    const activeClass = "py-3 px-2 rounded-xl font-black uppercase text-[10px] tracking-wider transition-all active:scale-95 shadow-md flex flex-col items-center justify-center gap-1.5 border bg-yellow-400 border-yellow-500 text-black font-black";
                    const inactiveClass = "py-3 px-2 rounded-xl font-black uppercase text-[10px] tracking-wider transition-all active:scale-95 shadow-md flex flex-col items-center justify-center gap-1.5 border bg-slate-950/60 border-slate-850 hover:border-yellow-500 text-yellow-500";
                    const disabledClass = "py-3 px-2 rounded-xl font-black uppercase text-[10px] tracking-wider transition-all active:scale-95 shadow-md flex flex-col items-center justify-center gap-1.5 border bg-slate-950/20 border-slate-900 text-slate-600 cursor-not-allowed";

                    if (btnTransparent) btnTransparent.className = data.bg_type === 'transparent' ? activeClass : inactiveClass;
                    if (btnAnimated) btnAnimated.className = data.bg_type === 'animated' ? activeClass : inactiveClass;
                    if (btnCustom) {
                        if (btnCustom.hasAttribute('disabled')) {
                            btnCustom.className = disabledClass;
                        } else {
                            btnCustom.className = data.bg_type === 'custom' ? activeClass : inactiveClass;
                        }
                    }
                } else {
                    console.error('Failed to update background type');
                }
            } catch (err) {
                console.error('Error during background update:', err);
            }
        }

        window.setTimerDuration = function() {
            const hInput = document.getElementById('timer-hours-input');
            const mInput = document.getElementById('timer-minutes-input');
            const sInput = document.getElementById('timer-seconds-input');
            if (!hInput || !mInput || !sInput) return;
            
            const hours = parseInt(hInput.value) || 0;
            const minutes = parseInt(mInput.value) || 0;
            const seconds = parseInt(sInput.value) || 0;
            
            triggerTimerAction('set-duration', { hours, minutes, seconds });
        }

        window.toggleTimerVisibility = function(visible) {
            triggerTimerAction('toggle-visibility', { visible: visible });
        }

        function startTimerLoop() {
            clearInterval(timerInterval);
            updateControlPanelUI();
            timerInterval = setInterval(() => {
                updateControlPanelUI();
            }, 1000);
        }

        // Init loops
        if (timerStatus === 'running') {
            startTimerLoop();
        } else {
            updateControlPanelUI();
        }

        // Connect Laravel Echo channels for real-time synchronization if another director modifies state
        document.addEventListener("DOMContentLoaded", function () {
            Echo.channel('user-screens.{{ $user->id }}')
                .listen('.TimerUpdated', (e) => {
                    console.log('TimerUpdated received in control panel:', e);
                    timerStatus = e.status;
                    timerDuration = e.duration;
                    timerRemaining = e.remainingSeconds;
                    timerEndsAt = e.endsAt;
                    timerVisible = e.visible;
                    timerShowHours = e.showHours;
                    
                    const hInput = document.getElementById('timer-hours-input');
                    const mInput = document.getElementById('timer-minutes-input');
                    const sInput = document.getElementById('timer-seconds-input');
                    
                    if (hInput && mInput && sInput) {
                        hInput.value = Math.floor(timerDuration / 3600);
                        mInput.value = Math.floor((timerDuration % 3600) / 60);
                        sInput.value = timerDuration % 60;
                    }

                    updateControlPanelUI();
                    if (timerStatus === 'running') {
                        startTimerLoop();
                    } else {
                        clearInterval(timerInterval);
                    }
                });
        });
    </script>
</body>
</html>
