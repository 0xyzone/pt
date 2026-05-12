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
        }
        .team-card.eliminated {
            opacity: 0.4;
            filter: grayscale(100%);
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
                <h1 class="text-3xl md:text-4xl font-black italic tracking-tighter uppercase text-transparent bg-clip-text bg-gradient-to-r from-yellow-400 to-amber-500 drop-shadow-sm">
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
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4" id="stats-grid">
            @foreach($activeMatch->matchStats->sortByDesc('points') as $stat)
            <div class="team-card bg-slate-900 border border-slate-800 rounded-2xl p-5 shadow-lg flex flex-col gap-5 {{ $stat->alive == 0 ? 'eliminated' : '' }}"
                 data-stat-id="{{ $stat->id }}"
                 data-team-id="{{ $stat->tournament_team_id }}">

                {{-- Header: Logo, Name, Points --}}
                <div class="flex items-center justify-between border-b border-slate-800/80 pb-4">
                    <div class="flex items-center gap-3 w-3/4">
                        <img src="{{ $stat->tournamentTeam->logo_image ? asset('storage/' . $stat->tournamentTeam->logo_image) : asset('img/defult_team_logo.png') }}"
                             class="w-10 h-10 object-contain flex-shrink-0 drop-shadow-md bg-slate-800/50 rounded-lg p-1 border border-slate-700/50">
                        <div class="flex flex-col min-w-0">
                            <span class="font-black text-base uppercase tracking-wide leading-tight truncate text-white" title="{{ $stat->tournamentTeam->name }}">{{ $stat->tournamentTeam->name }}</span>
                            <span class="text-[11px] text-yellow-500 font-bold uppercase tracking-widest">{{ $stat->tournamentTeam->short_name }}</span>
                        </div>
                    </div>
                    <div class="flex flex-col items-end">
                        <span class="text-[10px] text-slate-500 font-bold uppercase tracking-widest">PTS</span>
                        <span class="text-2xl font-black text-yellow-400 leading-none" data-field="points">{{ $stat->points }}</span>
                    </div>
                </div>

                {{-- Controls Area --}}
                <div class="flex flex-col gap-4 flex-grow">
                    
                    {{-- Row 1: Alive & Kills --}}
                    <div class="flex justify-between items-center bg-slate-950/50 rounded-xl p-3 border border-slate-800/50">
                        {{-- Alive --}}
                        <div class="flex flex-col items-center w-1/2 border-r border-slate-800/80 pr-2">
                            <span class="text-[10px] text-slate-500 font-bold uppercase tracking-widest mb-2">Alive</span>
                            <div class="flex items-center justify-center gap-2 w-full">
                                <button class="stat-btn danger" onclick="updateStat({{ $stat->id }}, 'alive', Math.max(0, {{ $stat->alive }} - 1))" {{ $stat->alive <= 0 ? 'disabled' : '' }}>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12h-15" /></svg>
                                </button>
                                <span class="stat-value text-emerald-400" data-field="alive">{{ $stat->alive }}</span>
                                <button class="stat-btn success" onclick="updateStat({{ $stat->id }}, 'alive', Math.min(4, {{ $stat->alive }} + 1))" {{ $stat->alive >= 4 ? 'disabled' : '' }}>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                                </button>
                            </div>
                        </div>

                        {{-- Kills --}}
                        <div class="flex flex-col items-center w-1/2 pl-2">
                            <span class="text-[10px] text-slate-500 font-bold uppercase tracking-widest mb-2">Kills</span>
                            <div class="flex items-center justify-center gap-2 w-full">
                                <button class="stat-btn danger" onclick="updateStat({{ $stat->id }}, 'kills', Math.max(0, parseInt(this.parentElement.querySelector('[data-field=kills]').textContent) - 1))">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12h-15" /></svg>
                                </button>
                                <span class="stat-value text-red-400" data-field="kills">{{ $stat->kills }}</span>
                                <button class="stat-btn success" onclick="updateStat({{ $stat->id }}, 'kills', parseInt(this.parentElement.querySelector('[data-field=kills]').textContent) + 1)">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                                </button>
                            </div>
                        </div>
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
                                        <div class="custom-select-option {{ $stat->placement == $p ? 'selected' : '' }}" onclick="selectPlacement({{ $stat->id }}, {{ $p }})">#{{ $p }}</div>
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
                            <button class="stat-btn warning !w-[38px] !h-[38px] !rounded-lg flex items-center justify-center border border-amber-500/30" title="Eliminate Team"
                                    onclick="triggerElimination({{ $stat->id }})">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/>
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
        let pendingRequests = 0;

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

        // Global Update Function
        window.updateStat = async function(statId, field, value) {
            const card = document.querySelector(`[data-stat-id="${statId}"]`);
            if (card) {
                card.classList.add('flash');
                setTimeout(() => card.classList.remove('flash'), 600);
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
            // Instantly update the UI to zero alive
            const card = document.querySelector(`[data-stat-id="${statId}"]`);
            if(card) {
                const aliveSpan = card.querySelector('[data-field="alive"]');
                if(aliveSpan) aliveSpan.textContent = '0';
                card.classList.add('eliminated');
                const aliveBtns = card.querySelectorAll('.stat-btn.danger, .stat-btn.success');
                if(aliveBtns[0]) aliveBtns[0].disabled = true; // minus button
                if(aliveBtns[1]) aliveBtns[1].disabled = false; // plus button (can still revive if mistake)
            }
            // Proceed to update the backend
            updateStat(statId, 'alive', 0);
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

                        // Update all team cards
                        doc.querySelectorAll('.team-card').forEach(newCard => {
                            const statId = newCard.getAttribute('data-stat-id');
                            const oldCard = document.querySelector(`[data-stat-id="${statId}"]`);
                            if (oldCard) {
                                // Update scalar values
                                ['alive', 'kills', 'points'].forEach(field => {
                                    const newVal = newCard.querySelector(`[data-field="${field}"]`);
                                    const oldVal = oldCard.querySelector(`[data-field="${field}"]`);
                                    if (newVal && oldVal) oldVal.textContent = newVal.textContent;
                                });

                                // Update placement select text
                                const newPlacement = newCard.querySelector('[data-field="placement"]');
                                const oldPlacement = oldCard.querySelector('[data-field="placement"]');
                                if (newPlacement && oldPlacement) oldPlacement.textContent = newPlacement.textContent;

                                // Update placement select options (selected state)
                                const newOptions = newCard.querySelectorAll('.custom-select-option');
                                const oldOptions = oldCard.querySelectorAll('.custom-select-option');
                                if(newOptions.length === oldOptions.length) {
                                    for(let i=0; i<newOptions.length; i++) {
                                        oldOptions[i].className = newOptions[i].className;
                                    }
                                }

                                // Update winner toggle
                                const newWinner = newCard.querySelector('[data-field="is_winner"]');
                                const oldWinner = oldCard.querySelector('[data-field="is_winner"]');
                                if (newWinner && oldWinner) {
                                    oldWinner.className = newWinner.className;
                                }

                                // Update eliminated state
                                if (newCard.classList.contains('eliminated')) {
                                    oldCard.classList.add('eliminated');
                                } else {
                                    oldCard.classList.remove('eliminated');
                                }

                                // Update alive buttons disabled state & onclick
                                const aliveVal = parseInt(oldCard.querySelector('[data-field="alive"]').textContent);
                                const aliveBtns = oldCard.querySelectorAll('.stat-btn.danger:not(.warning), .stat-btn.success:not(.warning)');
                                // Assuming first is minus alive, second is plus alive, third is minus kills, fourth is plus kills
                                // We can target them directly using their parent container
                                const aliveContainer = oldCard.querySelector('[data-field="alive"]').parentElement;
                                const currentAliveBtns = aliveContainer.querySelectorAll('.stat-btn');
                                if (currentAliveBtns[0]) {
                                    currentAliveBtns[0].disabled = aliveVal <= 0;
                                    currentAliveBtns[0].setAttribute('onclick', `updateStat(${statId}, 'alive', Math.max(0, ${aliveVal} - 1))`);
                                }
                                if (currentAliveBtns[1]) {
                                    currentAliveBtns[1].disabled = aliveVal >= 4;
                                    currentAliveBtns[1].setAttribute('onclick', `updateStat(${statId}, 'alive', Math.min(4, ${aliveVal} + 1))`);
                                }

                                // Flash updated card
                                oldCard.classList.add('flash');
                                setTimeout(() => oldCard.classList.remove('flash'), 600);
                            }
                        });
                        
                        // Handle sorting order updates if points changed (optional: currently maintaining position for stability, but we can reorder DOM)
                        const newGrid = doc.getElementById('stats-grid');
                        const oldGrid = document.getElementById('stats-grid');
                        if (newGrid && oldGrid) {
                            const newOrderIds = Array.from(newGrid.children).map(c => c.getAttribute('data-stat-id'));
                            const oldOrderIds = Array.from(oldGrid.children).map(c => c.getAttribute('data-stat-id'));
                            
                            // Check if order changed
                            let orderChanged = false;
                            for(let i=0; i<newOrderIds.length; i++) {
                                if(newOrderIds[i] !== oldOrderIds[i]) {
                                    orderChanged = true; break;
                                }
                            }
                            
                            if(orderChanged) {
                                newOrderIds.forEach(id => {
                                    const card = document.querySelector(`[data-stat-id="${id}"]`);
                                    if(card) oldGrid.appendChild(card); // Moves it to the end in the new order
                                });
                            }
                        }
                    });
            }

            Echo.channel('active-match.{{ $activeMatch->id }}')
                .listen('.MatchStatsUpdated', (e) => {
                    if (pendingRequests === 0) {
                        refreshPage();
                    } else {
                        setTimeout(() => refreshPage(), 500);
                    }
                });
        });
    </script>
</body>
</html>
