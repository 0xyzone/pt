<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Stats Control — {{ $activeMatch->name }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { box-sizing: border-box; }
        body { font-family: 'Inter', system-ui, sans-serif; background-color: #020617; } /* slate-950 */

        .stat-btn {
            width: 32px; height: 32px;
            display: inline-flex; align-items: center; justify-content: center;
            border-radius: 8px; font-weight: 900;
            cursor: pointer; border: 1px solid transparent; transition: all 0.15s ease;
            user-select: none;
        }
        .stat-btn:active { transform: scale(0.9); }
        .stat-btn:disabled { opacity: 0.3; cursor: not-allowed; }
        .stat-btn.danger { background: rgba(239,68,68,0.1); color: #ef4444; border-color: rgba(239,68,68,0.2); }
        .stat-btn.danger:hover:not(:disabled) { background: rgba(239,68,68,0.25); border-color: rgba(239,68,68,0.4); }
        .stat-btn.success { background: rgba(34,197,94,0.1); color: #22c55e; border-color: rgba(34,197,94,0.2); }
        .stat-btn.success:hover:not(:disabled) { background: rgba(34,197,94,0.25); border-color: rgba(34,197,94,0.4); }
        .stat-btn.warning { background: rgba(245,158,11,0.1); color: #f59e0b; border-color: rgba(245,158,11,0.2); }
        .stat-btn.warning:hover:not(:disabled) { background: rgba(245,158,11,0.25); border-color: rgba(245,158,11,0.4); }

        .stat-value {
            font-size: 20px; font-weight: 900; min-width: 36px;
            text-align: center; font-variant-numeric: tabular-nums;
        }

        .team-card {
            transition: all 0.3s ease;
            position: relative;
            background: #0f172a; /* slate-900 */
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
        .custom-select-wrapper { position: relative; user-select: none; width: 100%; }
        .custom-select {
            display: flex; justify-content: space-between; align-items: center;
            background: rgba(15, 23, 42, 0.8); /* slate-900 */
            border: 1px solid rgba(51, 65, 85, 0.5); /* slate-700 */
            color: #f1f5f9; /* slate-100 */
            padding: 8px 12px; border-radius: 8px; font-weight: 700; font-size: 14px;
            cursor: pointer; transition: all 0.2s ease;
        }
        .custom-select:hover { border-color: #64748b; }
        .custom-select.open { border-color: #facc15; box-shadow: 0 0 0 1px #facc15; }
        .custom-select-options {
            position: absolute; top: calc(100% + 4px); left: 0; right: 0; z-index: 50;
            background: #0f172a; border: 1px solid #334155; border-radius: 8px;
            max-height: 200px; overflow-y: auto; display: none;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.5);
        }
        .custom-select-options.show { display: block; }
        .custom-select-option {
            padding: 8px 12px; font-weight: 600; font-size: 14px; cursor: pointer; color: #cbd5e1;
            transition: background 0.1s ease;
        }
        .custom-select-option:hover { background: rgba(250, 204, 21, 0.15); color: #facc15; }
        .custom-select-option.selected { background: rgba(250, 204, 21, 0.2); color: #facc15; }

        .winner-toggle {
            width: 24px; height: 24px;
            border-radius: 6px;
            border: 2px solid rgba(255,255,255,0.2);
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex; align-items: center; justify-content: center;
            background: rgba(0,0,0,0.2);
        }
        .winner-toggle.active {
            border-color: #facc15;
            background: #facc15;
            box-shadow: 0 0 10px rgba(250, 204, 21, 0.4);
        }
        .winner-toggle.active::after {
            content: '';
            width: 10px; height: 6px;
            border-left: 2px solid #000; border-bottom: 2px solid #000;
            transform: rotate(-45deg);
            margin-bottom: 2px;
        }
        
        /* Custom Scrollbar for dropdowns */
        .custom-select-options::-webkit-scrollbar { width: 6px; }
        .custom-select-options::-webkit-scrollbar-track { background: #0f172a; border-radius: 4px; }
        .custom-select-options::-webkit-scrollbar-thumb { background: #334155; border-radius: 4px; }
        .custom-select-options::-webkit-scrollbar-thumb:hover { background: #475569; }

    </style>
</head>
<body class="text-slate-100 min-h-screen">

    <div class="max-w-7xl mx-auto p-4 md:p-8">
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
            <div id="connection-status" class="hidden px-4 py-2 rounded-xl text-center font-bold text-xs uppercase tracking-widest border border-slate-700 bg-slate-800/50"></div>
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
            <div class="team-card bg-slate-900 border border-slate-800 rounded-2xl p-5 shadow-lg flex flex-col gap-5 {{ $stat->alive == 0 ? 'eliminated' : '' }}"
                 data-stat-id="{{ $stat->id }}"
                 data-team-id="{{ $stat->tournament_team_id }}">

                {{-- Header: Logo, Name, Points --}}
                <div class="card-header flex items-center justify-between border-b border-slate-800/80 pb-4 transition-all duration-300">
                    <div class="flex items-center gap-3 w-3/4">
                        <div class="shrink-0">
                            <img src="{{ $stat->tournamentTeam->logo_image ? asset('storage/' . $stat->tournamentTeam->logo_image) : asset('img/defult_team_logo.png') }}"
                                 class="w-12 h-12 object-contain drop-shadow-md bg-slate-800/50 rounded-lg p-1 border border-slate-700/50">
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
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3.5 h-3.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" /></svg>
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
                                    <button class="flex items-center justify-center w-6 h-6 rounded-full transition-all duration-150 border {{ $player->pivot->is_alive ? 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20 hover:bg-emerald-500/20' : 'bg-red-500/10 text-red-500 border-red-500/20 hover:bg-red-500/20' }}"
                                            onclick="togglePlayerAlive({{ $stat->id }}, {{ $player->id }}, {{ $player->pivot->is_alive ? 0 : 1 }})"
                                            data-player-status-id="{{ $player->id }}"
                                            title="{{ $player->pivot->is_alive ? 'Mark as Dead' : 'Revive Player' }}">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $player->pivot->is_alive ? 'bg-emerald-400' : 'bg-red-500' }}"></span>
                                    </button>

                                    {{-- Kills Controller --}}
                                    <div class="flex items-center bg-slate-950/40 rounded-lg p-0.5 border border-slate-800/60">
                                        <button class="stat-btn danger w-6! h-6! rounded-md!" 
                                                onclick="updatePlayerKills({{ $stat->id }}, {{ $player->id }}, Math.max(0, parseInt(this.nextElementSibling.textContent) - 1))">
                                            <span class="font-extrabold text-xs">-</span>
                                        </button>
                                        <span class="text-xs font-black text-red-400 w-5 text-center font-mono" data-player-kills-id="{{ $player->id }}">{{ $player->pivot->kills }}</span>
                                        <button class="stat-btn success w-6! h-6! rounded-md!" 
                                                onclick="updatePlayerKills({{ $stat->id }}, {{ $player->id }}, parseInt(this.previousElementSibling.textContent) + 1)">
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
                                    <div class="custom-select-option {{ $stat->placement == 0 ? 'selected' : '' }}" onclick="selectPlacement({{ $stat->id }}, 0)">#0 (Unranked)</div>
                                    @foreach($placementOptions as $p)
                                        @if(!in_array($p, $takenPlacements) || $p == $stat->placement)
                                            <div class="custom-select-option {{ $stat->placement == $p ? 'selected' : '' }}" onclick="selectPlacement({{ $stat->id }}, {{ $p }})">#{{ $p }}</div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        {{-- Winner Toggle --}}
                        <div class="flex flex-col items-center justify-between h-full pb-1">
                            <span class="text-[9px] text-slate-500 font-bold uppercase tracking-widest mb-1">Win</span>
                            <div class="winner-toggle {{ $stat->is_winner ? 'active' : '' }}"
                                 data-field="is_winner"
                                 onclick="updateStat({{ $stat->id }}, 'is_winner', !this.classList.contains('active'))"
                                 title="Mark as Match Winner">
                            </div>
                        </div>

                        {{-- Eliminate Button --}}
                        <div class="flex flex-col justify-end h-full">
                            <button class="stat-btn warning w-9.5! h-9.5! rounded-lg! flex items-center justify-center border border-amber-500/30" title="Eliminate Team"
                                    onclick="triggerElimination({{ $stat->id }})">
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
        // Echo real-time sync — only updates DOM when no local request is
        // pending for that stat. With X-Socket-ID on every fetch, the server
        // already excludes the originating client from its own broadcasts,
        // so this guard mainly protects against overlapping rapid clicks.
        // ─────────────────────────────────────────────────────────────────
        document.addEventListener("DOMContentLoaded", function () {
            Echo.channel('active-match.{{ $activeMatch->id }}')
                .listen('.MatchStatsUpdated', (e) => {
                    if (e && e.matchStat) {
                        const statId = e.matchStat.id;
                        // Skip if we are currently waiting for an AJAX response
                        // for this stat — our response will apply the final state.
                        if (pendingUpdates.has(statId)) return;

                        window.updateCardDOM(statId, e.matchStat);

                        // Flash card to signal an update from another client
                        const card = document.querySelector(`[data-stat-id="${statId}"]`);
                        if (card) {
                            card.classList.add('flash');
                            setTimeout(() => card.classList.remove('flash'), 600);
                        }
                    }
                });
        });

        </script>
</body>
</html>
