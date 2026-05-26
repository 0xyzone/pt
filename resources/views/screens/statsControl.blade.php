<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Stats Control — {{ $activeMatch->name }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', system-ui, sans-serif;
            background-color: #020617;
        }

        /* slate-950 */

        .stat-btn {
            width: 32px;
            height: 32px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            font-weight: 900;
            cursor: pointer;
            border: 1px solid transparent;
            transition: all 0.15s ease;
            user-select: none;
        }

        .stat-btn:active {
            transform: scale(0.9);
        }

        .stat-btn:disabled {
            opacity: 0.3;
            cursor: not-allowed;
        }

        .stat-btn.danger {
            background: rgba(239, 68, 68, 0.1);
            color: #ef4444;
            border-color: rgba(239, 68, 68, 0.2);
        }

        .stat-btn.danger:hover:not(:disabled) {
            background: rgba(239, 68, 68, 0.25);
            border-color: rgba(239, 68, 68, 0.4);
        }

        .stat-btn.success {
            background: rgba(34, 197, 94, 0.1);
            color: #22c55e;
            border-color: rgba(34, 197, 94, 0.2);
        }

        .stat-btn.success:hover:not(:disabled) {
            background: rgba(34, 197, 94, 0.25);
            border-color: rgba(34, 197, 94, 0.4);
        }

        .stat-btn.warning {
            background: rgba(245, 158, 11, 0.1);
            color: #f59e0b;
            border-color: rgba(245, 158, 11, 0.2);
        }

        .stat-btn.warning:hover:not(:disabled) {
            background: rgba(245, 158, 11, 0.25);
            border-color: rgba(245, 158, 11, 0.4);
        }

        .stat-value {
            font-size: 20px;
            font-weight: 900;
            min-width: 36px;
            text-align: center;
            font-variant-numeric: tabular-nums;
        }

        .team-card {
            transition: all 0.3s ease;
            position: relative;
            background: #0f172a;
            /* slate-900 */
        }

        .team-card.eliminated .card-header {
            opacity: 0.4;
            filter: grayscale(100%);
        }

        .team-card.eliminated {
            border-color: rgba(239, 68, 68, 0.2) !important;
            background: rgba(15, 23, 42, 0.6);
        }

        .team-card.flash {
            box-shadow: 0 0 15px rgba(250, 204, 21, 0.5);
            border-color: #facc15 !important;
        }

        /* Custom Dropdown Styles */
        .custom-select-wrapper {
            position: relative;
            user-select: none;
            width: 100%;
        }

        .custom-select {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: rgba(15, 23, 42, 0.8);
            /* slate-900 */
            border: 1px solid rgba(51, 65, 85, 0.5);
            /* slate-700 */
            color: #f1f5f9;
            /* slate-100 */
            padding: 8px 12px;
            border-radius: 8px;
            font-weight: 700;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .custom-select:hover {
            border-color: #64748b;
        }

        .custom-select.open {
            border-color: #facc15;
            box-shadow: 0 0 0 1px #facc15;
        }

        .custom-select-options {
            position: absolute;
            top: calc(100% + 4px);
            left: 0;
            right: 0;
            z-index: 50;
            background: #0f172a;
            border: 1px solid #334155;
            border-radius: 8px;
            max-height: 200px;
            overflow-y: auto;
            display: none;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.5);
        }

        .custom-select-options.show {
            display: block;
        }

        .custom-select-option {
            padding: 8px 12px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            color: #cbd5e1;
            transition: background 0.1s ease;
        }

        .custom-select-option:hover {
            background: rgba(250, 204, 21, 0.15);
            color: #facc15;
        }

        .custom-select-option.selected {
            background: rgba(250, 204, 21, 0.2);
            color: #facc15;
        }

        .winner-toggle {
            width: 24px;
            height: 24px;
            border-radius: 6px;
            border: 2px solid rgba(255, 255, 255, 0.2);
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(0, 0, 0, 0.2);
        }

        .winner-toggle.active {
            border-color: #facc15;
            background: #facc15;
            box-shadow: 0 0 10px rgba(250, 204, 21, 0.4);
        }

        .winner-toggle.active::after {
            content: '';
            width: 10px;
            height: 6px;
            border-left: 2px solid #000;
            border-bottom: 2px solid #000;
            transform: rotate(-45deg);
            margin-bottom: 2px;
        }

        /* Custom Scrollbar for dropdowns */
        .custom-select-options::-webkit-scrollbar {
            width: 6px;
        }

        .custom-select-options::-webkit-scrollbar-track {
            background: #0f172a;
            border-radius: 4px;
        }

        .custom-select-options::-webkit-scrollbar-thumb {
            background: #334155;
            border-radius: 4px;
        }

        .custom-select-options::-webkit-scrollbar-thumb:hover {
            background: #475569;
        }

        /* Complete Match Button Styles */
        #btn-complete-match:disabled {
            background: rgba(51, 65, 85, 0.15) !important;
            color: #64748b !important;
            border: 1px solid rgba(51, 65, 85, 0.25) !important;
            box-shadow: none !important;
            cursor: not-allowed !important;
            pointer-events: none !important;
            opacity: 0.6;
        }

        #btn-complete-match:not(:disabled) {
            background: rgba(34, 197, 94, 0.15) !important;
            color: #4ade80 !important;
            border: 1px solid rgba(34, 197, 94, 0.4) !important;
            box-shadow: 0 0 15px rgba(34, 197, 94, 0.15) !important;
            cursor: pointer !important;
        }

        #btn-complete-match:not(:disabled):hover {
            background: rgba(34, 197, 94, 0.25) !important;
            border-color: rgba(34, 197, 94, 0.6) !important;
            box-shadow: 0 0 20px rgba(34, 197, 94, 0.3) !important;
            transform: translateY(-1px);
        }

        #btn-complete-match:not(:disabled):active {
            transform: translateY(1px);
        }

    </style>
</head>
<body class="text-slate-100 min-h-screen">

    @php $obsPassword = $obsPassword ?? null; @endphp
    @if($obsPassword)
    {{-- ============================================================ --}}
    {{-- PASSWORD GATE OVERLAY                                        --}}
    {{-- ============================================================ --}}
    <div id="obs-auth-overlay" class="fixed inset-0 z-9999 flex items-center justify-center bg-slate-950/95 backdrop-blur-xl" style="display:flex!important">
        <div class="relative flex flex-col items-center gap-6 w-full max-w-sm mx-4">

            <div class="absolute inset-0 rounded-3xl bg-yellow-500/5 blur-3xl pointer-events-none"></div>

            <div class="relative w-full bg-slate-900/80 border border-slate-700/60 rounded-3xl p-8 shadow-2xl backdrop-blur-sm" id="auth-card">

                <div class="flex flex-col items-center mb-6">
                    <div class="w-16 h-16 rounded-2xl bg-yellow-400/10 border border-yellow-400/20 flex items-center justify-center mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-8 h-8 text-yellow-400" id="lock-icon">
                            <path fill-rule="evenodd" d="M12 1.5a5.25 5.25 0 00-5.25 5.25v3a3 3 0 00-3 3v6.75a3 3 0 003 3h10.5a3 3 0 003-3v-6.75a3 3 0 00-3-3v-3c0-2.9-2.35-5.25-5.25-5.25zm3.75 8.25v-3a3.75 3.75 0 10-7.5 0v3h7.5z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <h2 class="text-xl font-black uppercase tracking-widest text-white">Director Access</h2>
                    <p class="text-xs text-slate-400 font-medium mt-1 text-center uppercase tracking-wider">Live Stats Control is password protected</p>
                </div>

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
            const STORAGE_KEY = 'obs_stats_auth_{{ $user->id }}';
            const CORRECT_PW = @json($obsPassword);
            const TTL_MS = 24 * 60 * 60 * 1000;

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

            if (isAuthenticated()) {
                hideOverlay();
            }

            window.submitObsPassword = function() {
                const input = document.getElementById('obs-password-input');
                const entered = input.value;

                if (entered === CORRECT_PW) {
                    localStorage.setItem(STORAGE_KEY, JSON.stringify({
                        password: entered
                        , timestamp: Date.now()
                    }));
                    hideOverlay();
                } else {
                    const card = document.getElementById('auth-card');
                    const errMsg = document.getElementById('auth-error-msg');
                    const inputEl = document.getElementById('obs-password-input');

                    errMsg.classList.remove('hidden');
                    card.classList.remove('auth-shake');
                    void card.offsetWidth;
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

    <div class="max-w-375 mx-auto p-4 md:p-8">
        {{-- Header --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
            <div>
                <h1 class="text-3xl md:text-4xl font-black italic tracking-tighter uppercase text-transparent bg-clip-text bg-linear-to-r from-yellow-400 to-amber-500 drop-shadow-sm">
                    Live Stats Control
                </h1>
                <div class="flex items-center gap-3 mt-2">
                    <span class="text-slate-400 text-sm font-bold uppercase tracking-widest">{{ $activeMatch->tournament->name }}</span>
                    <span class="text-slate-600">•</span>
                    <span class="text-yellow-400 text-sm font-bold uppercase tracking-widest">{{ $activeMatch->name }}</span>
                    <span class="text-slate-600">•</span>
                    <span class="text-slate-500 text-sm font-bold uppercase tracking-widest">{{ ucfirst($activeMatch->map) }}</span>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <div id="connection-status" class="hidden px-4 py-2 rounded-xl text-center font-bold text-xs uppercase tracking-widest border border-slate-700 bg-slate-800/50"></div>

                <div id="match-complete-container">
                    @if(!$activeMatch->is_completed)
                    <button id="btn-complete-match" class="px-6 py-2.5 rounded-xl font-black text-sm uppercase tracking-wider transition-all duration-300 shadow-md flex items-center gap-2 cursor-pointer disabled:cursor-not-allowed" onclick="markMatchComplete()" disabled>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Mark Match Complete</span>
                    </button>
                    @else
                    <div class="flex items-center gap-3">
                        <div class="px-6 py-2.5 rounded-xl font-black text-sm uppercase tracking-wider border border-emerald-500/20 bg-emerald-500/10 text-emerald-400 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4">
                                <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12zm13.36-1.814a.75.75 0 10-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 00-1.06 1.06l2.25 2.25a.75.75 0 001.14-.094l3.74-5.24z" clip-rule="evenodd" />
                            </svg>
                            <span>Match Completed</span>
                        </div>
                        <button id="btn-incomplete-match" class="px-4 py-2.5 rounded-xl font-black text-xs uppercase tracking-wider border border-slate-700 bg-slate-800/40 hover:bg-slate-800/80 text-slate-400 hover:text-slate-200 transition-all duration-150 cursor-pointer" onclick="markMatchIncomplete()">
                            Mark Incomplete
                        </button>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Stats Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="stats-grid">
            @php
            // Map team IDs to their slot number
            // Sort matchStats by the slot number mapped for each team
            $sortedStats = $activeMatch->matchStats->sortBy(function($stat) use ($teamSlots) {
            return $teamSlots[$stat->tournament_team_id] ?? 999;
            });

            // Get all placements that are already assigned (excluding 0)
            $takenPlacements = $activeMatch->matchStats->where('placement', '>', 0)->pluck('placement')->toArray();
            @endphp
            @foreach($sortedStats as $stat)
            <div class="team-card bg-slate-900 border border-slate-800 rounded-2xl p-5 shadow-lg flex flex-col gap-5 {{ $stat->alive == 0 ? 'eliminated' : '' }}" data-stat-id="{{ $stat->id }}" data-team-id="{{ $stat->tournament_team_id }}">

                {{-- Header: Logo, Name, Points --}}
                <div class="card-header flex items-center justify-between border-b border-slate-800/80 pb-4 transition-all duration-300">
                    <div class="flex items-center gap-3 w-3/4">
                        <div class="shrink-0">
                            <img src="{{ $stat->tournamentTeam->logo_image ? asset('storage/' . $stat->tournamentTeam->logo_image) : asset('img/defult_team_logo.png') }}" class="w-12 h-12 object-contain drop-shadow-md bg-slate-800/50 rounded-lg p-1 border border-slate-700/50">
                        </div>
                        <div class="flex flex-col min-w-0">
                            <div class="flex items-center gap-2">
                                <span class="bg-yellow-500 text-black text-[10px] font-black px-1.5 py-0.5 rounded leading-none uppercase">Slot {{ $teamSlots[$stat->tournament_team_id] ?? '?' }}</span>
                                <span class="text-[11px] text-yellow-500 font-bold uppercase tracking-widest">{{ $stat->tournamentTeam->short_name }}</span>
                            </div>
                            <span class="font-black text-lg uppercase tracking-wide leading-tight truncate text-white" title="{{ $stat->tournamentTeam->name }}">{{ $stat->tournamentTeam->name }}</span>
                        </div>
                    </div>
                    <div class="flex flex-col items-end">
                        <span class="text-[10px] text-slate-500 font-bold uppercase tracking-widest">PTS</span>
                        <span class="text-2xl font-black text-yellow-400 leading-none" data-field="points">{{ $stat->points }}</span>
                    </div>
                </div>

                {{-- Controls Area --}}
                <div class="flex flex-col gap-4 grow">

                    {{-- Overall Squad Status Summary --}}
                    <div class="flex justify-between items-center bg-slate-950/40 rounded-xl px-4 py-2 border border-slate-800/40 text-xs font-bold uppercase tracking-wider">
                        <div class="flex items-center gap-1.5 text-emerald-400">
                            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                            <span>Alive: <span class="font-black text-sm" data-field="alive">{{ $stat->alive }}</span></span>
                        </div>
                        <div class="flex items-center gap-1.5 text-red-400">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3.5 h-3.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" /></svg>
                            <span>Kills: <span class="font-black text-sm" data-field="kills">{{ $stat->kills }}</span></span>
                        </div>
                    </div>

                    {{-- Squad Roster Section --}}
                    <div class="squad-roster-container flex flex-col gap-2 bg-slate-950/20 rounded-xl p-3 border border-slate-800/30">
                        <span class="text-[9px] text-slate-500 font-extrabold uppercase tracking-widest border-b border-slate-800/50 pb-1.5 mb-1">Squad Roster</span>
                        @forelse($stat->players as $player)
                        <div class="flex items-center justify-between gap-3 p-2 rounded-lg hover:bg-slate-800/30 transition-all duration-200" data-player-id="{{ $player->id }}">
                            {{-- Left: Player Name & Role --}}
                            <div class="flex flex-col min-w-0 grow pl-1">
                                <span class="font-extrabold text-sm text-slate-200 truncate tracking-wide uppercase leading-tight" title="{{ $player->name }}">{{ $player->ign }}</span>
                                @if($player->role && $player->role !== 'player')
                                <span class="text-[8px] text-yellow-500 font-extrabold uppercase tracking-widest mt-0.5 leading-none">{{ $player->role }}</span>
                                @endif
                            </div>

                            {{-- Right: Status Toggle & Kills side by side --}}
                            <div class="flex items-center gap-3 shrink-0">
                                {{-- Status Dot / Toggle --}}
                                <button class="flex items-center justify-center w-6 h-6 rounded-full transition-all duration-150 border {{ $player->pivot->is_alive ? 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20 hover:bg-emerald-500/20' : 'bg-red-500/10 text-red-500 border-red-500/20 hover:bg-red-500/20' }}" onclick="togglePlayerAlive({{ $stat->id }}, {{ $player->id }}, {{ $player->pivot->is_alive ? 0 : 1 }})" data-player-status-id="{{ $player->id }}" title="{{ $player->pivot->is_alive ? 'Mark as Dead' : 'Revive Player' }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $player->pivot->is_alive ? 'bg-emerald-400' : 'bg-red-500' }}"></span>
                                </button>

                                {{-- Kills Controller --}}
                                <div class="flex items-center bg-slate-950/40 rounded-lg p-0.5 border border-slate-800/60">
                                    <button class="stat-btn danger w-6! h-6! rounded-md!" onclick="updatePlayerKills({{ $stat->id }}, {{ $player->id }}, Math.max(0, parseInt(this.nextElementSibling.textContent) - 1))">
                                        <span class="font-extrabold text-xs">-</span>
                                    </button>
                                    <span class="text-xs font-black text-red-400 w-5 text-center font-mono" data-player-kills-id="{{ $player->id }}">{{ $player->pivot->kills }}</span>
                                    <button class="stat-btn success w-6! h-6! rounded-md!" onclick="updatePlayerKills({{ $stat->id }}, {{ $player->id }}, parseInt(this.previousElementSibling.textContent) + 1)">
                                        <span class="font-extrabold text-xs">+</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-4 text-slate-500 text-xs font-semibold uppercase tracking-wider">
                            No playing players populated
                        </div>
                        @endforelse
                    </div>

                    {{-- Row 2: Placement & Actions --}}
                    <div class="grid grid-cols-[1fr_auto_auto] gap-3 items-end">

                        {{-- Placement --}}
                        <div class="flex flex-col w-full">
                            <span class="text-[10px] text-slate-500 font-bold uppercase tracking-widest mb-1.5 ml-1">Placement</span>
                            <div class="custom-select-wrapper" data-stat-id="{{ $stat->id }}">
                                <div class="custom-select" onclick="toggleDropdown({{ $stat->id }})">
                                    <span class="selected-text" data-field="placement">#{{ $stat->placement }}</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 text-slate-400">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                    </svg>
                                </div>
                                <div class="custom-select-options">
                                    <div class="custom-select-option {{ $stat->placement == 0 ? 'selected' : '' }}" data-placement-value="0" onclick="selectPlacement({{ $stat->id }}, 0)">#0 (Unranked)</div>
                                    @foreach($placementOptions as $p)
                                    <div class="custom-select-option {{ $stat->placement == $p ? 'selected' : '' }}" data-placement-value="{{ $p }}" onclick="selectPlacement({{ $stat->id }}, {{ $p }})" style="display: {{ (in_array($p, $takenPlacements) && $p != $stat->placement) ? 'none' : 'block' }}">#{{ $p }}</div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        {{-- Winner Toggle --}}
                        <div class="flex flex-col items-center justify-between h-full pb-1">
                            <span class="text-[9px] text-slate-500 font-bold uppercase tracking-widest mb-1">Win</span>
                            <div class="winner-toggle {{ $stat->is_winner ? 'active' : '' }}" data-field="is_winner" onclick="updateStat({{ $stat->id }}, 'is_winner', !this.classList.contains('active'))" title="Mark as Match Winner">
                            </div>
                        </div>

                        {{-- Eliminate Button --}}
                        <div class="flex flex-col justify-end h-full">
                            <button class="stat-btn warning w-9.5! h-9.5! rounded-lg! flex items-center justify-center border border-amber-500/30" title="Eliminate Team" onclick="triggerElimination({{ $stat->id }})">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9V4m0 5h5m-5 0H7m5 0v5m0-5a9 9 0 110 18 9 9 0 010-18z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 14l6-6m0 6l-6-6" />
                                </svg>
                            </button>
                        </div>
                    </div>

                </div>
            </div>
            @endforeach
        </div>

    </div>

    {{-- ============================================================ --}}
    {{-- CUSTOM MODAL DIALOG                                          --}}
    {{-- ============================================================ --}}
    <div id="custom-modal" class="fixed inset-0 z-9999 hidden items-center justify-center bg-slate-950/80 backdrop-blur-xs">
        <div class="relative w-full max-w-md mx-4 bg-slate-900 border border-slate-800 rounded-2xl p-6 shadow-2xl transition-all duration-200 scale-95 opacity-0" id="custom-modal-card">
            <div class="flex flex-col gap-4">
                {{-- Icon & Title --}}
                <div class="flex items-start gap-4">
                    <div id="custom-modal-icon" class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0">
                        {{-- Icon SVG --}}
                    </div>
                    <div class="flex flex-col gap-1 grow">
                        <h3 id="custom-modal-title" class="text-base font-black uppercase tracking-wider text-white">Confirmation</h3>
                        <p id="custom-modal-message" class="text-xs text-slate-300 font-bold uppercase tracking-wider leading-relaxed"></p>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex justify-end gap-3 mt-2" id="custom-modal-actions">
                    <button id="custom-modal-cancel" class="px-5 py-2.5 rounded-xl font-black text-[10px] uppercase tracking-wider border border-slate-700 bg-slate-800/40 text-slate-400 hover:bg-slate-800/80 hover:text-slate-200 transition-all cursor-pointer">Cancel</button>
                    <button id="custom-modal-confirm" class="px-5 py-2.5 rounded-xl font-black text-[10px] uppercase tracking-wider bg-yellow-400 text-black hover:bg-yellow-300 transition-all shadow-md shadow-yellow-400/10 cursor-pointer">Confirm</button>
                </div>
            </div>
        </div>
    </div>

    <script type="module">
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        const updateUrl = "{{ route('screens.updatestat', ['user_id' => $user->id]) }}";

        // ─────────────────────────────────────────────────────────────────
        // pendingUpdates: tracks how many in-flight AJAX requests exist for
        // each statId. While a stat has pending requests, incoming Echo
        // broadcasts for that stat are ignored — the AJAX response itself
        // will apply the authoritative server state when it completes.
        // ─────────────────────────────────────────────────────────────────
        const pendingUpdates = new Map();

        function addPending(statId) {
            pendingUpdates.set(statId, (pendingUpdates.get(statId) ?? 0) + 1);
        }
        function removePending(statId) {
            const n = (pendingUpdates.get(statId) ?? 1) - 1;
            if (n <= 0) pendingUpdates.delete(statId);
            else pendingUpdates.set(statId, n);
        }

        // ─────────────────────────────────────────────────────────────────
        // buildHeaders: creates fetch headers, always including CSRF + JSON.
        // Attaches X-Socket-ID when Echo is connected so the server-side
        // broadcaster excludes this client from receiving its own events.
        // ─────────────────────────────────────────────────────────────────
        function buildHeaders() {
            const h = {
                'Content-Type':  'application/json',
                'X-CSRF-TOKEN':  csrfToken,
                'Accept':        'application/json',
            };
            try {
                const sid = window.Echo?.socketId?.();
                if (sid) h['X-Socket-ID'] = sid;
            } catch (_) {}
            return h;
        }

        // Custom Dropdown Logic
        window.toggleDropdown = function(statId) {
            document.querySelectorAll('.custom-select-options.show').forEach(el => {
                if(el.closest('.custom-select-wrapper').dataset.statId != statId) el.classList.remove('show');
            });
            document.querySelectorAll('.custom-select.open').forEach(el => {
                if(el.closest('.custom-select-wrapper').dataset.statId != statId) el.classList.remove('open');
            });
            
            const wrapper = document.querySelector(`.custom-select-wrapper[data-stat-id="${statId}"]`);
            const select = wrapper.querySelector('.custom-select');
            const options = wrapper.querySelector('.custom-select-options');
            
            select.classList.toggle('open');
            options.classList.toggle('show');
        };

        window.selectPlacement = function(statId, value) {
            updateStat(statId, 'placement', value);
            const wrapper = document.querySelector(`.custom-select-wrapper[data-stat-id="${statId}"]`);
            wrapper.querySelector('.custom-select').classList.remove('open');
            wrapper.querySelector('.custom-select-options').classList.remove('show');
        };

        // Close dropdowns on outside click
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.custom-select-wrapper')) {
                document.querySelectorAll('.custom-select-options.show').forEach(el => el.classList.remove('show'));
                document.querySelectorAll('.custom-select.open').forEach(el => el.classList.remove('open'));
            }
        });

        // ─────────────────────────────────────────────────────────────────
        // updateCardDOM: applies authoritative server state to a team card.
        // Called after every AJAX response AND after Echo broadcasts.
        //
        // STRICT BOOLEAN RULES (to survive any PDO/JSON type ambiguity):
        //   is_winner        → === true   (PHP $casts guarantees true/false)
        //   player.is_alive  → parseInt() === 1  (pivot has no casts, may be "1")
        // ─────────────────────────────────────────────────────────────────
        window.updateCardDOM = function(statId, stat) {
            const card = document.querySelector(`[data-stat-id="${statId}"]`);
            if (!card) return;

            // 1. Team-level numeric stats
            const pointsEl = card.querySelector('[data-field="points"]');
            if (pointsEl) pointsEl.textContent = stat.points;

            const aliveEl = card.querySelector('[data-field="alive"]');
            if (aliveEl) aliveEl.textContent = stat.alive;

            const killsEl = card.querySelector('[data-field="kills"]');
            if (killsEl) killsEl.textContent = stat.kills;

            // Eliminated class
            if (parseInt(stat.alive) === 0) {
                card.classList.add('eliminated');
            } else {
                card.classList.remove('eliminated');
            }

            // Winner toggle — strict: only activate on boolean true
            const winnerToggle = card.querySelector('[data-field="is_winner"]');
            if (winnerToggle) {
                if (stat.is_winner === true) {
                    winnerToggle.classList.add('active');
                    // Deactivate every other winner badge in the grid
                    document.querySelectorAll('.winner-toggle.active').forEach(toggle => {
                        const otherCard = toggle.closest('.team-card');
                        if (otherCard && otherCard.getAttribute('data-stat-id') != statId) {
                            toggle.classList.remove('active');
                        }
                    });
                } else {
                    winnerToggle.classList.remove('active');
                }
            }

            // Placement dropdown text + selected option
            const placementEl = card.querySelector('[data-field="placement"]');
            if (placementEl) placementEl.textContent = '#' + stat.placement;

            const options = card.querySelectorAll('.custom-select-option');
            options.forEach(opt => {
                const match = opt.getAttribute('onclick').match(/selectPlacement\(\s*\d+\s*,\s*(\d+)\s*\)/);
                if (match) {
                    const optVal = parseInt(match[1]);
                    if (optVal === stat.placement) {
                        opt.classList.add('selected');
                    } else {
                        opt.classList.remove('selected');
                    }
                }
            });

            // 2. Squad roster players
            if (stat.players && Array.isArray(stat.players)) {
                stat.players.forEach(player => {
                    const playerRow = card.querySelector(`[data-player-id="${player.id}"]`);
                    if (!playerRow) return;

                    // Kills counter — pivot may return string, show as-is
                    const playerKillsEl = playerRow.querySelector('[data-player-kills-id]');
                    if (playerKillsEl) playerKillsEl.textContent = player.pivot.kills;

                    // Survival status — parseInt handles "0"/"1" strings from the pivot
                    const statusBtn = playerRow.querySelector('[data-player-status-id]');
                    if (statusBtn) {
                        const isAlive = parseInt(player.pivot.is_alive) === 1;
                        if (isAlive) {
                            statusBtn.className = "flex items-center justify-center w-6 h-6 rounded-full transition-all duration-150 border bg-emerald-500/10 text-emerald-400 border-emerald-500/20 hover:bg-emerald-500/20";
                            statusBtn.title = "Mark as Dead";
                        } else {
                            statusBtn.className = "flex items-center justify-center w-6 h-6 rounded-full transition-all duration-150 border bg-red-500/10 text-red-500 border-red-500/20 hover:bg-red-500/20";
                            statusBtn.title = "Revive Player";
                        }
                        statusBtn.setAttribute('onclick', `togglePlayerAlive(${statId}, ${player.id}, ${isAlive ? 0 : 1})`);

                        const dot = statusBtn.querySelector('span');
                        if (dot) {
                            dot.className = isAlive ? "w-1.5 h-1.5 rounded-full bg-emerald-400" : "w-1.5 h-1.5 rounded-full bg-red-500";
                        }
                    }
                });
            }
            window.refreshPlacementDropdowns();
            window.validateMatchCompletion();
        };

        // ─────────────────────────────────────────────────────────────────
        // updateStat: handles team-level field changes (placement, is_winner,
        // alive). Optimistically updates the UI, then syncs to the server.
        // ─────────────────────────────────────────────────────────────────
        window.updateStat = async function(statId, field, value) {
            const card = document.querySelector(`[data-stat-id="${statId}"]`);
            if (card) {
                card.classList.add('flash');
                setTimeout(() => card.classList.remove('flash'), 600);

                // Optimistic UI
                if (field === 'placement') {
                    const placementEl = card.querySelector('[data-field="placement"]');
                    if (placementEl) placementEl.textContent = '#' + value;
                    
                    const options = card.querySelectorAll('.custom-select-option');
                    options.forEach(opt => {
                        const match = opt.getAttribute('onclick').match(/selectPlacement\(\s*\d+\s*,\s*(\d+)\s*\)/);
                        if (match) {
                            const optVal = parseInt(match[1]);
                            opt.classList.toggle('selected', optVal === parseInt(value));
                        }
                    });

                } else if (field === 'is_winner') {
                    const winnerToggle = card.querySelector('[data-field="is_winner"]');
                    if (winnerToggle) {
                        if (value) {
                            winnerToggle.classList.add('active');
                            // Optimistically set placement to #1
                            const placementEl = card.querySelector('[data-field="placement"]');
                            if (placementEl) placementEl.textContent = '#1';
                            card.querySelectorAll('.custom-select-option').forEach(opt => {
                                const match = opt.getAttribute('onclick').match(/selectPlacement\(\s*\d+\s*,\s*(\d+)\s*\)/);
                                if (match) opt.classList.toggle('selected', parseInt(match[1]) === 1);
                            });
                            // Deactivate other winner badges optimistically
                            document.querySelectorAll('.winner-toggle.active').forEach(toggle => {
                                const otherCard = toggle.closest('.team-card');
                                if (otherCard && otherCard.getAttribute('data-stat-id') != statId) {
                                    toggle.classList.remove('active');
                                }
                            });
                        } else {
                            winnerToggle.classList.remove('active');
                        }
                    }

                } else if (field === 'alive') {
                    const aliveEl = card.querySelector('[data-field="alive"]');
                    if (aliveEl) aliveEl.textContent = value;
                    if (parseInt(value) === 0) {
                        card.classList.add('eliminated');
                    } else {
                        card.classList.remove('eliminated');
                    }
                }
                window.refreshPlacementDropdowns();
                window.validateMatchCompletion();
            }

            addPending(statId);
            try {
                const resp = await fetch(updateUrl, {
                    method: 'POST',
                    headers: buildHeaders(),
                    body: JSON.stringify({ stat_id: statId, field, value }),
                });
                const data = await resp.json();
                if (data.success && data.stat) {
                    window.updateCardDOM(statId, data.stat);
                } else {
                    console.error('Update failed:', data);
                }
            } catch (err) {
                console.error('Network error:', err);
            } finally {
                removePending(statId);
            }
        };

        // ─────────────────────────────────────────────────────────────────
        // togglePlayerAlive: toggles a single player's survival status.
        // ─────────────────────────────────────────────────────────────────
        window.togglePlayerAlive = async function(statId, playerId, isAlive) {
            const card = document.querySelector(`[data-stat-id="${statId}"]`);
            if (card) {
                card.classList.add('flash');
                setTimeout(() => card.classList.remove('flash'), 600);

                // Optimistic UI
                const playerRow = card.querySelector(`[data-player-id="${playerId}"]`);
                if (playerRow) {
                    const statusBtn = playerRow.querySelector('[data-player-status-id]');
                    if (statusBtn) {
                        const isAliveBool = !!isAlive;
                        if (isAliveBool) {
                            statusBtn.className = "flex items-center justify-center w-6 h-6 rounded-full transition-all duration-150 border bg-emerald-500/10 text-emerald-400 border-emerald-500/20 hover:bg-emerald-500/20";
                            statusBtn.title = "Mark as Dead";
                        } else {
                            statusBtn.className = "flex items-center justify-center w-6 h-6 rounded-full transition-all duration-150 border bg-red-500/10 text-red-500 border-red-500/20 hover:bg-red-500/20";
                            statusBtn.title = "Revive Player";
                        }
                        statusBtn.setAttribute('onclick', `togglePlayerAlive(${statId}, ${playerId}, ${isAliveBool ? 0 : 1})`);
                        
                        const dot = statusBtn.querySelector('span');
                        if (dot) {
                            dot.className = isAliveBool ? "w-1.5 h-1.5 rounded-full bg-emerald-400" : "w-1.5 h-1.5 rounded-full bg-red-500";
                        }
                    }
                }
            }

            addPending(statId);
            try {
                const resp = await fetch(updateUrl, {
                    method: 'POST',
                    headers: buildHeaders(),
                    body: JSON.stringify({ stat_id: statId, player_id: playerId, field: 'is_alive', value: isAlive }),
                });
                const data = await resp.json();
                if (data.success && data.stat) {
                    window.updateCardDOM(statId, data.stat);
                } else {
                    console.error('Update failed:', data);
                }
            } catch (err) {
                console.error('Network error:', err);
            } finally {
                removePending(statId);
            }
        };

        // ─────────────────────────────────────────────────────────────────
        // updatePlayerKills: adjusts a single player's kill count.
        // ─────────────────────────────────────────────────────────────────
        window.updatePlayerKills = async function(statId, playerId, kills) {
            const card = document.querySelector(`[data-stat-id="${statId}"]`);
            if (card) {
                card.classList.add('flash');
                setTimeout(() => card.classList.remove('flash'), 600);

                // Optimistic UI
                const playerRow = card.querySelector(`[data-player-id="${playerId}"]`);
                if (playerRow) {
                    const killsSpan = playerRow.querySelector('[data-player-kills-id]');
                    if (killsSpan) killsSpan.textContent = kills;
                }
            }

            addPending(statId);
            try {
                const resp = await fetch(updateUrl, {
                    method: 'POST',
                    headers: buildHeaders(),
                    body: JSON.stringify({ stat_id: statId, player_id: playerId, field: 'kills', value: kills }),
                });
                const data = await resp.json();
                if (data.success && data.stat) {
                    window.updateCardDOM(statId, data.stat);
                } else {
                    console.error('Update failed:', data);
                }
            } catch (err) {
                console.error('Network error:', err);
            } finally {
                removePending(statId);
            }
        };

        // ─────────────────────────────────────────────────────────────────
        // triggerElimination: sets the whole team's alive count to 0 and
        // marks every player as dead in one shot.
        // ─────────────────────────────────────────────────────────────────
        window.triggerElimination = async function(statId) {
            const card = document.querySelector(`[data-stat-id="${statId}"]`);
            if (card) {
                const aliveSpan = card.querySelector('[data-field="alive"]');
                if (aliveSpan) aliveSpan.textContent = '0';
                card.classList.add('eliminated');

                // Mark all players dead optimistically
                card.querySelectorAll('[data-player-status-id]').forEach(btn => {
                    btn.className = "flex items-center justify-center w-6 h-6 rounded-full transition-all duration-150 border bg-red-500/10 text-red-500 border-red-500/20 hover:bg-red-500/20";
                    btn.title = "Revive Player";
                    const playerId = btn.getAttribute('data-player-status-id');
                    btn.setAttribute('onclick', `togglePlayerAlive(${statId}, ${playerId}, 1)`);
                    const dot = btn.querySelector('span');
                    if (dot) dot.className = "w-1.5 h-1.5 rounded-full bg-red-500";
                });
            }
            updateStat(statId, 'alive', 0);
        };

        // ─────────────────────────────────────────────────────────────────
        // Placement Dropdown and Match Completion Check Script
        // ─────────────────────────────────────────────────────────────────
        window.refreshPlacementDropdowns = function() {
            const currentPlacements = {};
            document.querySelectorAll('.team-card').forEach(card => {
                const statId = card.getAttribute('data-stat-id');
                const placementEl = card.querySelector('[data-field="placement"]');
                if (placementEl) {
                    const pText = placementEl.textContent.trim().replace('#', '');
                    const placement = parseInt(pText) || 0;
                    currentPlacements[statId] = placement;
                }
            });

            document.querySelectorAll('.team-card').forEach(card => {
                const statId = card.getAttribute('data-stat-id');

                const options = card.querySelectorAll('.custom-select-option');
                options.forEach(opt => {
                    const valAttr = opt.getAttribute('data-placement-value');
                    if (valAttr === null) return;
                    const val = parseInt(valAttr);

                    if (val === 0) {
                        opt.style.display = 'block';
                        return;
                    }

                    let takenByOther = false;
                    for (const [otherStatId, p] of Object.entries(currentPlacements)) {
                        if (otherStatId !== statId && p === val) {
                            takenByOther = true;
                            break;
                        }
                    }

                    if (takenByOther) {
                        opt.style.display = 'none';
                    } else {
                        opt.style.display = 'block';
                    }
                });
            });
        };

        window.validateMatchCompletion = function() {
            const hasWinner = document.querySelector('.winner-toggle.active') !== null;
            const cards = document.querySelectorAll('.team-card');
            const totalTeams = cards.length;
            
            const placements = [];
            let allPlaced = true;

            cards.forEach(card => {
                const placementEl = card.querySelector('[data-field="placement"]');
                if (placementEl) {
                    const pText = placementEl.textContent.trim().replace('#', '');
                    const p = parseInt(pText) || 0;
                    if (p === 0) {
                        allPlaced = false;
                    }
                    placements.push(p);
                } else {
                    allPlaced = false;
                }
            });

            const uniquePlacements = new Set(placements);
            const isUnique = uniquePlacements.size === totalTeams && !uniquePlacements.has(0);

            const completeBtn = document.getElementById('btn-complete-match');
            if (completeBtn) {
                if (hasWinner && allPlaced && isUnique) {
                    completeBtn.removeAttribute('disabled');
                } else {
                    completeBtn.setAttribute('disabled', 'true');
                }
            }
        };

        window.openCustomModal = function({ icon, iconBg, title, message, confirmLabel, confirmClass, onConfirm }) {
            const modal = document.getElementById('custom-modal');
            const card = document.getElementById('custom-modal-card');
            const iconEl = document.getElementById('custom-modal-icon');
            const titleEl = document.getElementById('custom-modal-title');
            const msgEl = document.getElementById('custom-modal-message');
            const cancelBtn = document.getElementById('custom-modal-cancel');
            const confirmBtn = document.getElementById('custom-modal-confirm');

            iconEl.innerHTML = icon || '';
            iconEl.className = `w-12 h-12 rounded-xl flex items-center justify-center shrink-0 ${iconBg || 'bg-yellow-400/10'}`;
            titleEl.textContent = title || 'Confirmation';
            msgEl.textContent = message || '';

            if (confirmLabel) confirmBtn.textContent = confirmLabel;
            if (confirmClass) {
                confirmBtn.className = `px-5 py-2.5 rounded-xl font-black text-[10px] uppercase tracking-wider transition-all shadow-md cursor-pointer ${confirmClass}`;
            } else {
                confirmBtn.className = `px-5 py-2.5 rounded-xl font-black text-[10px] uppercase tracking-wider bg-yellow-400 text-black hover:bg-yellow-300 transition-all shadow-md shadow-yellow-400/10 cursor-pointer`;
            }

            modal.classList.remove('hidden');
            modal.classList.add('flex');
            setTimeout(() => {
                card.classList.remove('scale-95', 'opacity-0');
                card.classList.add('scale-100', 'opacity-100');
            }, 10);

            const closeModal = () => {
                card.classList.remove('scale-100', 'opacity-100');
                card.classList.add('scale-95', 'opacity-0');
                setTimeout(() => {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                }, 200);
            };

            cancelBtn.onclick = closeModal;
            
            // Background click to close
            const bgClickClose = (e) => {
                if (e.target === modal) {
                    closeModal();
                    modal.removeEventListener('click', bgClickClose);
                }
            };
            modal.addEventListener('click', bgClickClose);

            confirmBtn.onclick = () => {
                closeModal();
                if (onConfirm) onConfirm();
            };
        };

        window.openCustomAlert = function({ icon, iconBg, title, message, confirmLabel, confirmClass }) {
            const modal = document.getElementById('custom-modal');
            const card = document.getElementById('custom-modal-card');
            const iconEl = document.getElementById('custom-modal-icon');
            const titleEl = document.getElementById('custom-modal-title');
            const msgEl = document.getElementById('custom-modal-message');
            const cancelBtn = document.getElementById('custom-modal-cancel');
            const confirmBtn = document.getElementById('custom-modal-confirm');

            iconEl.innerHTML = icon || `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-6 h-6 text-rose-400"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" /></svg>`;
            iconEl.className = `w-12 h-12 rounded-xl flex items-center justify-center shrink-0 ${iconBg || 'bg-rose-500/10'}`;
            titleEl.textContent = title || 'Alert';
            msgEl.textContent = message || '';

            confirmBtn.textContent = confirmLabel || 'OK';
            if (confirmClass) {
                confirmBtn.className = `px-5 py-2.5 rounded-xl font-black text-[10px] uppercase tracking-wider transition-all shadow-md cursor-pointer ${confirmClass}`;
            } else {
                confirmBtn.className = `px-5 py-2.5 rounded-xl font-black text-[10px] uppercase tracking-wider bg-rose-500 text-white hover:bg-rose-400 transition-all shadow-md shadow-rose-500/10 cursor-pointer`;
            }

            cancelBtn.classList.add('hidden');

            modal.classList.remove('hidden');
            modal.classList.add('flex');
            setTimeout(() => {
                card.classList.remove('scale-95', 'opacity-0');
                card.classList.add('scale-100', 'opacity-100');
            }, 10);

            const closeModal = () => {
                card.classList.remove('scale-100', 'opacity-100');
                card.classList.add('scale-95', 'opacity-0');
                setTimeout(() => {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                    cancelBtn.classList.remove('hidden'); // Restore for other modals
                }, 200);
            };

            const bgClickClose = (e) => {
                if (e.target === modal) {
                    closeModal();
                    modal.removeEventListener('click', bgClickClose);
                }
            };
            modal.addEventListener('click', bgClickClose);

            confirmBtn.onclick = () => {
                closeModal();
            };
        };

        window.markMatchComplete = async function() {
            window.openCustomModal({
                iconBg: 'bg-emerald-500/10',
                icon: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-6 h-6 text-emerald-400"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>`,
                title: 'Mark Match Complete',
                message: 'This will finalize the scores for this match. Are you sure you want to mark it as completed?',
                confirmLabel: 'Yes, Mark Complete',
                confirmClass: 'bg-emerald-500 text-white hover:bg-emerald-400 shadow-emerald-500/20 shadow-md cursor-pointer',
                onConfirm: async () => {
                    const completeUrl = "{{ route('screens.completematch', ['user_id' => $user->id]) }}";
                    try {
                        const resp = await fetch(completeUrl, {
                            method: 'POST',
                            headers: buildHeaders(),
                        });
                        const data = await resp.json();
                        if (data.success) {
                            const container = document.getElementById('match-complete-container');
                            if (container) {
                                container.innerHTML = `
                                    <div class="flex items-center gap-3">
                                        <div class="px-6 py-2.5 rounded-xl font-black text-sm uppercase tracking-wider border border-emerald-500/20 bg-emerald-500/10 text-emerald-400 flex items-center gap-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4">
                                                <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12zm13.36-1.814a.75.75 0 10-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 00-1.06 1.06l2.25 2.25a.75.75 0 001.14-.094l3.74-5.24z" clip-rule="evenodd" />
                                            </svg>
                                            <span>Match Completed</span>
                                        </div>
                                        <button id="btn-incomplete-match" class="px-4 py-2.5 rounded-xl font-black text-xs uppercase tracking-wider border border-slate-700 bg-slate-800/40 hover:bg-slate-800/80 text-slate-400 hover:text-slate-200 transition-all duration-150 cursor-pointer" onclick="markMatchIncomplete()">
                                            Mark Incomplete
                                        </button>
                                    </div>
                                `;
                            }
                        } else {
                            window.openCustomAlert({
                                title: 'Error',
                                message: 'Failed to complete match: ' + (data.error || data.message || 'Unknown error')
                            });
                        }
                    } catch (err) {
                        console.error('Error completing match:', err);
                        window.openCustomAlert({
                            title: 'Network Error',
                            message: 'Network error completing match. Please try again.'
                        });
                    }
                }
            });
        };

        window.markMatchIncomplete = async function() {
            window.openCustomModal({
                iconBg: 'bg-rose-500/10',
                icon: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-6 h-6 text-rose-400"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>`,
                title: 'Revert Match Completion',
                message: 'Are you sure you want to mark this match as incomplete? This will allow editing stats and rankings.',
                confirmLabel: 'Yes, Revert',
                confirmClass: 'bg-rose-500 text-white hover:bg-rose-400 shadow-rose-500/20 shadow-md cursor-pointer',
                onConfirm: async () => {
                    const incompleteUrl = "{{ route('screens.incompletematch', ['user_id' => $user->id]) }}";
                    try {
                        const resp = await fetch(incompleteUrl, {
                            method: 'POST',
                            headers: buildHeaders(),
                        });
                        const data = await resp.json();
                        if (data.success) {
                            const container = document.getElementById('match-complete-container');
                            if (container) {
                                container.innerHTML = `
                                    <button id="btn-complete-match" class="px-6 py-2.5 rounded-xl font-black text-sm uppercase tracking-wider transition-all duration-300 shadow-md flex items-center gap-2 cursor-pointer disabled:cursor-not-allowed" onclick="markMatchComplete()" disabled>
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span>Mark Match Complete</span>
                                    </button>
                                `;
                            }
                            window.validateMatchCompletion();
                        } else {
                            window.openCustomAlert({
                                title: 'Error',
                                message: 'Failed to revert completion: ' + (data.error || data.message || 'Unknown error')
                            });
                        }
                    } catch (err) {
                        console.error('Error reverting match completion:', err);
                        window.openCustomAlert({
                            title: 'Network Error',
                            message: 'Network error reverting completion. Please try again.'
                        });
                    }
                }
            });
        };

        // ─────────────────────────────────────────────────────────────────
        // Echo real-time sync — only updates DOM when no local request is
        // pending for that stat. With X-Socket-ID on every fetch, the server
        // already excludes the originating client from its own broadcasts,
        // so this guard mainly protects against overlapping rapid clicks.
        // ─────────────────────────────────────────────────────────────────
        document.addEventListener("DOMContentLoaded", function () {
            // Initial call to set correct states on page load
            window.refreshPlacementDropdowns();
            window.validateMatchCompletion();

            Echo.channel('active-match.{{ $activeMatch->id }}')
                .listen('.MatchStatsUpdated', (e) => {
                    if (e && e.matchStat) {
                        const statId = e.matchStat.id;
                        if (pendingUpdates.has(statId)) return;

                        window.updateCardDOM(statId, e.matchStat);

                        const card = document.querySelector(`[data-stat-id="${statId}"]`);
                        if (card) {
                            card.classList.add('flash');
                            setTimeout(() => card.classList.remove('flash'), 600);
                        }
                    }
                });

            Echo.channel('user-screens.{{ $user->id }}')
                .listen('.TournamentMatchUpdated', (e) => {
                    if (e && e.match && e.match.id === {{ $activeMatch->id }}) {
                        const container = document.getElementById('match-complete-container');
                        if (container) {
                            if (e.match.is_completed) {
                                container.innerHTML = `
                                    <div class="flex items-center gap-3">
                                        <div class="px-6 py-2.5 rounded-xl font-black text-sm uppercase tracking-wider border border-emerald-500/20 bg-emerald-500/10 text-emerald-400 flex items-center gap-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4">
                                                <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12zm13.36-1.814a.75.75 0 10-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 00-1.06 1.06l2.25 2.25a.75.75 0 001.14-.094l3.74-5.24z" clip-rule="evenodd" />
                                            </svg>
                                            <span>Match Completed</span>
                                        </div>
                                        <button id="btn-incomplete-match" class="px-4 py-2.5 rounded-xl font-black text-xs uppercase tracking-wider border border-slate-700 bg-slate-800/40 hover:bg-slate-800/80 text-slate-400 hover:text-slate-200 transition-all duration-150 cursor-pointer" onclick="markMatchIncomplete()">
                                            Mark Incomplete
                                        </button>
                                    </div>
                                `;
                            } else {
                                container.innerHTML = `
                                    <button id="btn-complete-match" class="px-6 py-2.5 rounded-xl font-black text-sm uppercase tracking-wider transition-all duration-300 shadow-md flex items-center gap-2 cursor-pointer disabled:cursor-not-allowed" onclick="markMatchComplete()" disabled>
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span>Mark Match Complete</span>
                                    </button>
                                `;
                                window.validateMatchCompletion();
                            }
                        }
                    }
                });
        });

        </script>
</body>
</html>
