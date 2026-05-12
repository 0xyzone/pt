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
        body { font-family: 'Inter', system-ui, sans-serif; }

        .stat-btn {
            width: 36px; height: 36px;
            display: inline-flex; align-items: center; justify-content: center;
            border-radius: 8px; font-weight: 900; font-size: 18px;
            cursor: pointer; border: none; transition: all 0.15s ease;
            user-select: none;
        }
        .stat-btn:active { transform: scale(0.9); }
        .stat-btn:disabled { opacity: 0.3; cursor: not-allowed; }
        .stat-btn.danger { background: rgba(239,68,68,0.15); color: #ef4444; }
        .stat-btn.danger:hover:not(:disabled) { background: rgba(239,68,68,0.3); }
        .stat-btn.success { background: rgba(34,197,94,0.15); color: #22c55e; }
        .stat-btn.success:hover:not(:disabled) { background: rgba(34,197,94,0.3); }

        .stat-value {
            font-size: 20px; font-weight: 900; min-width: 32px;
            text-align: center; font-variant-numeric: tabular-nums;
        }

        .team-row {
            transition: background 0.3s ease;
        }
        .team-row.eliminated {
            opacity: 0.35;
        }
        .team-row.flash {
            background: rgba(250, 204, 21, 0.08) !important;
        }

        .placement-select {
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.15);
            color: #fff;
            padding: 6px 10px;
            border-radius: 8px;
            font-weight: 700;
            font-size: 14px;
            cursor: pointer;
            appearance: none;
            -webkit-appearance: none;
            text-align: center;
            min-width: 60px;
        }
        .placement-select:focus { outline: none; border-color: #facc15; }

        .winner-toggle {
            width: 20px; height: 20px;
            border-radius: 50%;
            border: 2px solid rgba(255,255,255,0.2);
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex; align-items: center; justify-content: center;
        }
        .winner-toggle.active {
            border-color: #facc15;
            background: #facc15;
        }
        .winner-toggle.active::after {
            content: '✓';
            color: #000;
            font-size: 12px;
            font-weight: 900;
        }

        .pip-alive { background: #facc15; box-shadow: 0 0 6px rgba(250,204,21,0.4); }
        .pip-dead { background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.2); }
    </style>
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen">

    <div class="max-w-7xl mx-auto p-4 md:p-8">
        {{-- Header --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
            <div>
                <h1 class="text-3xl md:text-4xl font-black italic tracking-tighter uppercase text-transparent bg-clip-text bg-gradient-to-r from-yellow-400 to-amber-500">
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

        </div>

        {{-- Connection Status --}}
        <div id="connection-status" class="hidden mb-4 px-4 py-2 rounded-xl text-center font-bold text-sm uppercase tracking-widest border"></div>

        {{-- Stats Table --}}
        <div class="bg-slate-900/80 rounded-2xl border border-slate-800 overflow-hidden shadow-2xl">
            {{-- Table Header --}}
            <div class="grid grid-cols-[1fr_80px_140px_140px_80px_60px_70px] items-center gap-2 px-4 py-3 bg-slate-800/50 border-b border-slate-700 text-xs font-black uppercase tracking-[0.15em] text-slate-400">
                <span>Team</span>
                <span class="text-center">Alive</span>
                <span class="text-center">Kills</span>
                <span class="text-center">Placement</span>
                <span class="text-center">Winner</span>
                <span class="text-center">Elim</span>
                <span class="text-right">Points</span>
            </div>

            {{-- Team Rows --}}
            @foreach($activeMatch->matchStats->sortByDesc('points') as $stat)
            <div class="team-row grid grid-cols-[1fr_80px_140px_140px_80px_60px_70px] items-center gap-2 px-4 py-3 border-b border-slate-800/50 hover:bg-slate-800/30 {{ $stat->alive == 0 ? 'eliminated' : '' }}"
                 data-stat-id="{{ $stat->id }}"
                 data-team-id="{{ $stat->tournament_team_id }}">

                {{-- Team Name --}}
                <div class="flex items-center gap-3">
                    <img src="{{ $stat->tournamentTeam->logo_image ? asset('storage/' . $stat->tournamentTeam->logo_image) : asset('img/defult_team_logo.png') }}"
                         class="w-8 h-8 object-contain flex-shrink-0">
                    <div class="flex flex-col">
                        <span class="font-black text-sm uppercase tracking-wide leading-tight truncate">{{ $stat->tournamentTeam->name }}</span>
                        <span class="text-[10px] text-slate-500 font-bold uppercase tracking-widest">{{ $stat->tournamentTeam->short_name }}</span>
                    </div>
                </div>

                {{-- Alive Controls --}}
                <div class="flex items-center justify-center gap-1">
                    <button class="stat-btn danger" onclick="updateStat({{ $stat->id }}, 'alive', Math.max(0, {{ $stat->alive }} - 1))" {{ $stat->alive <= 0 ? 'disabled' : '' }}>−</button>
                    <span class="stat-value text-emerald-400" data-field="alive">{{ $stat->alive }}</span>
                    <button class="stat-btn success" onclick="updateStat({{ $stat->id }}, 'alive', Math.min(4, {{ $stat->alive }} + 1))" {{ $stat->alive >= 4 ? 'disabled' : '' }}>+</button>
                </div>

                {{-- Kills Controls --}}
                <div class="flex items-center justify-center gap-1">
                    <button class="stat-btn danger" onclick="updateStat({{ $stat->id }}, 'kills', Math.max(0, parseInt(this.parentElement.querySelector('[data-field=kills]').textContent) - 1))">−</button>
                    <span class="stat-value text-red-400" data-field="kills">{{ $stat->kills }}</span>
                    <button class="stat-btn success" onclick="updateStat({{ $stat->id }}, 'kills', parseInt(this.parentElement.querySelector('[data-field=kills]').textContent) + 1)">+</button>
                </div>

                {{-- Placement Select --}}
                <div class="flex justify-center">
                    <select class="placement-select" data-field="placement" onchange="updateStat({{ $stat->id }}, 'placement', this.value)">
                        <option value="0" {{ $stat->placement == 0 ? 'selected' : '' }}>0</option>
                        @foreach($placementOptions as $p)
                            <option value="{{ $p }}" {{ $stat->placement == $p ? 'selected' : '' }}>{{ $p }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Winner Toggle --}}
                <div class="flex justify-center">
                    <div class="winner-toggle {{ $stat->is_winner ? 'active' : '' }}"
                         data-field="is_winner"
                         onclick="updateStat({{ $stat->id }}, 'is_winner', !this.classList.contains('active'))">
                    </div>
                </div>

                {{-- Manual Elimination Trigger --}}
                <div class="flex justify-center">
                    <button class="stat-btn danger" title="Trigger Elimination Banner"
                            onclick="triggerElimination({{ $stat->id }})">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/>
                        </svg>
                    </button>
                </div>

                {{-- Points --}}
                <div class="text-right">
                    <span class="stat-value text-yellow-400" data-field="points">{{ $stat->points }}</span>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Alive Pips Legend --}}
        <div class="mt-6 flex items-center gap-6 text-xs text-slate-500 font-bold uppercase tracking-widest">
            <div class="flex items-center gap-2">
                <div class="pip-alive" style="width:10px; height:14px; border-radius:2px;"></div>
                <span>Alive</span>
            </div>
            <div class="flex items-center gap-2">
                <div class="pip-dead" style="width:10px; height:14px; border-radius:2px;"></div>
                <span>Dead</span>
            </div>
            <span class="text-slate-600">|</span>
            <span>All changes broadcast live to OBS screens</span>
        </div>
    </div>

    <script type="module">
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        const updateUrl = "{{ route('screens.updatestat', ['user_id' => $user->id]) }}";
        let pendingRequests = 0;

        // Expose globally
        window.updateStat = async function(statId, field, value) {
            const row = document.querySelector(`[data-stat-id="${statId}"]`);
            if (row) {
                row.classList.add('flash');
                setTimeout(() => row.classList.remove('flash'), 600);
            }

            pendingRequests++;
            try {
                const resp = await fetch(updateUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ stat_id: statId, field, value }),
                });
                const data = await resp.json();
                if (!data.success) console.error('Update failed:', data);
            } catch (err) {
                console.error('Network error:', err);
            } finally {
                pendingRequests--;
            }
        };

        window.triggerElimination = async function(statId) {
            try {
                await fetch(updateUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ stat_id: statId, field: 'alive', value: 0 }),
                });
            } catch (err) {
                console.error('Elimination trigger error:', err);
            }
        };

        // Live updates via Echo
        document.addEventListener("DOMContentLoaded", function () {
            const statusEl = document.getElementById('connection-status');

            function refreshPage() {
                fetch(window.location.href, { cache: 'no-store' })
                    .then(r => r.text())
                    .then(html => {
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');

                        // Update all team rows
                        doc.querySelectorAll('.team-row').forEach(newRow => {
                            const statId = newRow.getAttribute('data-stat-id');
                            const oldRow = document.querySelector(`[data-stat-id="${statId}"]`);
                            if (oldRow) {
                                // Update values
                                ['alive', 'kills', 'points'].forEach(field => {
                                    const newVal = newRow.querySelector(`[data-field="${field}"]`);
                                    const oldVal = oldRow.querySelector(`[data-field="${field}"]`);
                                    if (newVal && oldVal) oldVal.textContent = newVal.textContent;
                                });

                                // Update placement select
                                const newSelect = newRow.querySelector('[data-field="placement"]');
                                const oldSelect = oldRow.querySelector('[data-field="placement"]');
                                if (newSelect && oldSelect) oldSelect.value = newSelect.value;

                                // Update winner toggle
                                const newWinner = newRow.querySelector('[data-field="is_winner"]');
                                const oldWinner = oldRow.querySelector('[data-field="is_winner"]');
                                if (newWinner && oldWinner) {
                                    oldWinner.className = newWinner.className;
                                }

                                // Update eliminated state
                                if (newRow.classList.contains('eliminated')) {
                                    oldRow.classList.add('eliminated');
                                } else {
                                    oldRow.classList.remove('eliminated');
                                }

                                // Update alive buttons disabled state
                                const aliveVal = parseInt(oldRow.querySelector('[data-field="alive"]').textContent);
                                const aliveButtons = oldRow.querySelectorAll('.stat-btn');
                                // First alive button (decrease)
                                const aliveBtns = oldRow.children[1].querySelectorAll('.stat-btn');
                                if (aliveBtns[0]) aliveBtns[0].disabled = aliveVal <= 0;
                                if (aliveBtns[1]) aliveBtns[1].disabled = aliveVal >= 4;

                                // Update onclick handlers for alive
                                if (aliveBtns[0]) aliveBtns[0].setAttribute('onclick', `updateStat(${statId}, 'alive', Math.max(0, ${aliveVal} - 1))`);
                                if (aliveBtns[1]) aliveBtns[1].setAttribute('onclick', `updateStat(${statId}, 'alive', Math.min(4, ${aliveVal} + 1))`);

                                // Flash updated row
                                oldRow.classList.add('flash');
                                setTimeout(() => oldRow.classList.remove('flash'), 600);
                            }
                        });
                    });
            }

            Echo.channel('active-match.{{ $activeMatch->id }}')
                .listen('.MatchStatsUpdated', (e) => {
                    // Only refresh if we didn't initiate this update
                    if (pendingRequests === 0) {
                        refreshPage();
                    } else {
                        // Still refresh to sync, but with a small delay
                        setTimeout(() => refreshPage(), 500);
                    }
                });
        });
    </script>
</body>
</html>
