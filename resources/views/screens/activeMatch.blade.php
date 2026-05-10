<x-base>
    <style>
        .pubg-skew { transform: skewX(-12deg); }
        .pubg-unskew { transform: skewX(12deg); }
        
        .glass-panel { 
            background: rgba(8, 12, 24, 0.96); 
            border: 1px solid rgba(255,255,255,0.08); 
        }
        
        /* Entry Animations */
        .hud-slide-right { animation: slideFromRight 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        .hud-slide-down { animation: slideFromTop 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        .wwcd-pop { animation: wwcdEntry 1s cubic-bezier(0.34, 1.56, 0.64, 1) forwards; }
        
        @keyframes slideFromRight { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
        @keyframes slideFromTop { from { transform: translateY(-50px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
        @keyframes wwcdEntry { 
            0% { transform: scale(0.6); opacity: 0; }
            100% { transform: scale(1); opacity: 1; }
        }
        
        /* Alive pip styling */
        .pip-alive { background: #facc15; box-shadow: 0 0 8px rgba(250,204,21,0.5); }
        .pip-dead { background: rgba(0,0,0,0.6); border: 1px solid rgba(255,255,255,0.1); }
    </style>
    
    @php
        $allStats = $activeMatch->matchStats->sortByDesc(['points'])->values();
        $aliveTeams = $allStats->where('alive', '>', 0);
        $aliveTeamsCount = $aliveTeams->count();
        $showFinalFour = $aliveTeamsCount > 1 && $aliveTeamsCount <= 4;
        $isWWCD = $aliveTeamsCount === 1;
        $winner = $isWWCD ? $aliveTeams->first() : null;
    @endphp

    <!-- ELIMINATION TOASTER (Outside hud-root, position controlled by JS) -->
    <div id="elimination-toaster" style="position:fixed; left:50%; transform:translateX(-50%); top:-120px; z-index:300; transition:all 0.7s ease-in-out; opacity:0; pointer-events:none;">
        <div class="glass-panel shadow-2xl" style="display:flex; align-items:center; gap:16px; padding:14px 24px; border-bottom:5px solid #dc2626; min-width:440px; border-radius:0 0 8px 8px;">
            <div style="width:52px; height:52px; background:#fff; border-radius:6px; display:flex; align-items:center; justify-content:center; padding:6px; flex-shrink:0; border:3px solid #dc2626;">
                <img id="toaster-logo" src="" style="width:100%; height:100%; object-fit:contain;">
            </div>
            <div style="display:flex; flex-direction:column;">
                <span style="color:#ef4444; font-size:12px; font-weight:900; letter-spacing:0.3em; text-transform:uppercase;">Squad Eliminated</span>
                <span id="toaster-team-name" style="font-size:32px; font-weight:900; font-style:italic; text-transform:uppercase; color:#fff; letter-spacing:-0.03em; line-height:1;"></span>
            </div>
        </div>
    </div>

    <!-- HUD ROOT -->
    <div id="hud-root" class="w-full h-full relative font-main text-white overflow-hidden" data-view="{{ $isWWCD ? 'wwcd' : ($showFinalFour ? 'cards' : 'list') }}">

        @if($isWWCD)
            {{-- ========== WINNER WINNER CHICKEN DINNER ========== --}}
            <div id="wwcd-view" class="fixed inset-0 z-[200] flex flex-col items-center justify-center bg-black/75 backdrop-blur-lg">
                <div class="flex flex-col items-center">
                    <div class="bg-yellow-400 px-36 py-6 mb-10 pubg-skew shadow-[0_0_80px_rgba(250,204,21,0.4)]">
                        <div class="pubg-unskew flex flex-col items-center">
                            <span class="text-black text-xl font-black uppercase tracking-[0.4em] mb-1">Winner Winner</span>
                            <span class="text-black text-6xl font-black italic uppercase tracking-tighter">Chicken Dinner</span>
                        </div>
                    </div>
                    <div class="glass-panel p-10 flex flex-col items-center border-t-8 border-yellow-400 shadow-2xl" style="width:550px;">
                        <div class="w-44 h-44 bg-white/5 rounded-full p-8 border-4 border-yellow-400/50 mb-8 flex items-center justify-center">
                            <img src="{{ $winner->tournamentTeam->logo_image ? asset('storage/' . $winner->tournamentTeam->logo_image) : asset('img/defult_team_logo.png') }}" class="w-full h-full object-contain">
                        </div>
                        <h2 class="text-7xl font-black italic uppercase text-white tracking-tighter mb-6">{{ $winner->tournamentTeam->name }}</h2>
                        <div class="flex gap-16 mt-2">
                            <div class="flex flex-col items-center">
                                <span class="text-yellow-400 text-xs font-black uppercase tracking-widest mb-2">Total Kills</span>
                                <span class="text-5xl font-black text-white italic leading-none">{{ $winner->kills }}</span>
                            </div>
                            <div class="flex flex-col items-center">
                                <span class="text-yellow-400 text-xs font-black uppercase tracking-widest mb-2">Match Points</span>
                                <span class="text-5xl font-black text-white italic leading-none">{{ $winner->points }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        @elseif($showFinalFour)
            {{-- ========== FINAL FOUR CARD VIEW ========== --}}
            <div id="final-four-container" style="position:fixed; top:40px; left:0; width:100%; z-index:50; display:flex; flex-direction:column; align-items:center;">
                <div class="mb-8 bg-yellow-400 px-28 py-2 pubg-skew border-b-8 border-black shadow-2xl">
                    <span class="pubg-unskew block text-3xl font-black uppercase tracking-[0.4em] text-black italic">Final Duel</span>
                </div>

                <div style="display:flex; justify-content:center; gap:20px;">
                    @foreach ($aliveTeams->take(4) as $match)
                    @php
                        $weight = ($match->alive * 30) + ($match->points * 0.5);
                        $totalWeight = $aliveTeams->sum(fn($m) => ($m->alive * 30) + ($m->points * 0.5));
                        $winProb = round(($weight / max(1, $totalWeight)) * 100);
                    @endphp
                    <div class="glass-panel shadow-2xl" style="width:300px; border-left:6px solid #facc15; display:flex; flex-direction:column;">
                        {{-- Card Header: Win % --}}
                        <div style="display:flex; justify-content:space-between; align-items:center; padding:8px 16px; background:rgba(255,255,255,0.03); border-bottom:1px solid rgba(255,255,255,0.05);">
                            <span style="font-size:9px; font-weight:900; color:rgba(255,255,255,0.3); text-transform:uppercase; letter-spacing:0.15em;">Performance</span>
                            <div style="display:flex; align-items:center; gap:6px; background:rgba(250,204,21,0.15); padding:2px 10px; border-radius:2px; border:1px solid rgba(250,204,21,0.2);">
                                <span style="font-size:9px; font-weight:900; color:#facc15; text-transform:uppercase;">Win</span>
                                <span style="font-size:18px; font-weight:900; color:#facc15; font-family:monospace; line-height:1;">{{ $winProb }}%</span>
                            </div>
                        </div>

                        {{-- Card Body --}}
                        <div style="padding:20px; display:flex; flex-direction:column; gap:16px;">
                            <div style="display:flex; align-items:center; gap:16px;">
                                <div style="width:56px; height:56px; background:#fff; border-radius:4px; padding:5px; flex-shrink:0; border-bottom:4px solid rgba(0,0,0,0.3);">
                                    <img src="{{ $match->tournamentTeam->logo_image ? asset('storage/' . $match->tournamentTeam->logo_image) : asset('img/defult_team_logo.png') }}" style="width:100%; height:100%; object-fit:contain;">
                                </div>
                                <div style="flex:1; overflow:hidden; display:flex; flex-direction:column; gap:8px;">
                                    <span style="font-size:28px; font-weight:900; text-transform:uppercase; color:#fff; letter-spacing:-0.05em; line-height:1; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $match->tournamentTeam->short_name }}</span>
                                    <div style="display:flex; gap:6px;">
                                        @for($i = 0; $i < 4; $i++)
                                            <div class="{{ $i < $match->alive ? 'pip-alive' : 'pip-dead' }}" style="width:14px; height:20px; border-radius:2px; transform:skewX(-12deg);"></div>
                                        @endfor
                                    </div>
                                </div>
                            </div>

                            <div style="display:flex; align-items:flex-end; justify-content:space-between; padding-top:12px; border-top:1px solid rgba(255,255,255,0.05);">
                                <span style="font-size:10px; font-weight:900; color:rgba(255,255,255,0.3); text-transform:uppercase; letter-spacing:0.15em;">Match Score</span>
                                <span style="font-size:48px; font-weight:900; font-style:italic; color:#facc15; line-height:1;">{{ $match->points }}</span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

        @else
            {{-- ========== BOTTOM-RIGHT LIST VIEW ========== --}}
            <div id="side-list-container" style="position:fixed; bottom:40px; right:40px; width:280px; display:flex; flex-direction:column; gap:4px; z-index:40;">
                <div class="bg-yellow-400 py-2.5 px-5 pubg-skew shadow-xl" style="border-top:3px solid rgba(0,0,0,0.2);">
                    <div class="pubg-unskew flex justify-between items-center">
                        <span class="font-black italic uppercase text-sm text-black">Live Standings</span>
                        <span class="text-[10px] font-black text-black/50 italic">{{ $aliveTeamsCount }} Teams</span>
                    </div>
                </div>

                <div style="display:flex; flex-direction:column; gap:3px;" id="live-standings">
                    @foreach ($allStats as $index => $match)
                    @php $isEliminated = $match->alive == 0; @endphp
                    <div data-team-id="{{ $match->tournament_team_id }}" class="list-item glass-panel pubg-skew transition-all duration-500 {{ $isEliminated ? 'opacity-40 grayscale' : '' }}" style="border-radius:2px;">
                        <div class="pubg-unskew" style="display:flex; align-items:center; padding:10px 16px; gap:12px;">
                            <span style="font-size:11px; font-weight:900; font-style:italic; color:rgba(250,204,21,0.5); width:16px; text-align:center;">{{ $index + 1 }}</span>
                            <img src="{{ $match->tournamentTeam->logo_image ? asset('storage/' . $match->tournamentTeam->logo_image) : asset('img/defult_team_logo.png') }}" style="width:28px; height:28px; object-fit:contain; flex-shrink:0;">
                            {{-- Name + Alive pips on same row --}}
                            <div style="flex:1; overflow:hidden; display:flex; align-items:center; gap:8px;">
                                <span style="font-weight:900; text-transform:uppercase; font-size:20px; line-height:1; letter-spacing:-0.03em; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; {{ $isEliminated ? 'color:rgb(100,116,139); text-decoration:line-through;' : 'color:#fff;' }}">{{ $match->tournamentTeam->short_name }}</span>
                                @if(!$isEliminated)
                                <div style="display:flex; gap:3px; flex-shrink:0;">
                                    @for($i = 0; $i < 4; $i++)
                                        <div class="{{ $i < $match->alive ? 'pip-alive' : 'pip-dead' }}" style="width:8px; height:12px; border-radius:1px;"></div>
                                    @endfor
                                </div>
                                @endif
                            </div>
                            <span style="font-weight:900; font-style:italic; font-size:28px; color:#facc15; width:44px; text-align:right; line-height:1;">{{ $match->points }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        @endif

    </div>

    <script type="module">
        document.addEventListener("DOMContentLoaded", function () {
            const toasterWrapper = document.getElementById('elimination-toaster');
            const teamNameSpan = document.getElementById('toaster-team-name');
            const logoImg = document.getElementById('toaster-logo');
            let eliminationQueue = [];
            let isProcessing = false;

            function getCurrentView() {
                const hudRoot = document.getElementById('hud-root');
                if (hudRoot?.querySelector('#final-four-container')) return 'cards';
                if (hudRoot?.querySelector('#wwcd-view')) return 'wwcd';
                return 'list';
            }

            function processQueue() {
                if (eliminationQueue.length === 0 || isProcessing) return;
                isProcessing = true;
                const matchData = eliminationQueue.shift();
                teamNameSpan.innerText = matchData.teamName;
                logoImg.src = matchData.teamLogo ? "/storage/" + matchData.teamLogo : "{{ asset('img/defult_team_logo.png') }}";

                const view = getCurrentView();

                if (view === 'cards') {
                    // Cards view: appear BELOW the cards (cards end ~280px)
                    toasterWrapper.style.top = '340px';
                    toasterWrapper.style.opacity = '0';
                    requestAnimationFrame(() => {
                        toasterWrapper.style.top = '380px';
                        toasterWrapper.style.opacity = '1';
                    });
                    setTimeout(() => {
                        toasterWrapper.style.top = '340px';
                        toasterWrapper.style.opacity = '0';
                        setTimeout(() => { isProcessing = false; processQueue(); }, 800);
                    }, 5500);
                } else {
                    // List view: slide DOWN from very top of the page
                    toasterWrapper.style.top = '-120px';
                    toasterWrapper.style.opacity = '0';
                    requestAnimationFrame(() => {
                        toasterWrapper.style.top = '15px';
                        toasterWrapper.style.opacity = '1';
                    });
                    setTimeout(() => {
                        toasterWrapper.style.top = '-120px';
                        toasterWrapper.style.opacity = '0';
                        setTimeout(() => { isProcessing = false; processQueue(); }, 800);
                    }, 5500);
                }
            }

            // Initial entrance animations
            const initialList = document.getElementById('side-list-container');
            const initialCards = document.getElementById('final-four-container');
            if (initialList) initialList.classList.add('hud-slide-right');
            if (initialCards) initialCards.classList.add('hud-slide-down');

            Echo.channel('active-match.{{ $activeMatch->id }}')
                .listen('MatchStatsUpdated', (e) => {
                    fetch(window.location.href, { cache: 'no-store' })
                        .then(response => response.text())
                        .then(html => {
                            const parser = new DOMParser();
                            const doc = parser.parseFromString(html, 'text/html');
                            const newHud = doc.getElementById('hud-root');
                            if (!newHud) return;

                            const hudRoot = document.getElementById('hud-root');

                            const hadList = !!hudRoot.querySelector('#side-list-container');
                            const hadCards = !!hudRoot.querySelector('#final-four-container');
                            const hadWWCD = !!hudRoot.querySelector('#wwcd-view');
                            const hasList = !!newHud.querySelector('#side-list-container');
                            const hasCards = !!newHud.querySelector('#final-four-container');
                            const hasWWCD = !!newHud.querySelector('#wwcd-view');

                            // FLIP: measure old positions
                            const oldPositions = new Map();
                            if (hadList && hasList) {
                                Array.from(hudRoot.querySelectorAll('.list-item')).forEach(el => {
                                    const id = el.getAttribute('data-team-id');
                                    if (id) oldPositions.set(id, el.getBoundingClientRect());
                                });
                            }

                            // Swap content + data-view attribute
                            hudRoot.innerHTML = newHud.innerHTML;
                            hudRoot.setAttribute('data-view', newHud.getAttribute('data-view') || 'list');

                            // Animate ONLY on view-type change
                            if (hasList && !hadList) {
                                document.getElementById('side-list-container')?.classList.add('hud-slide-right');
                            }
                            if (hasCards && !hadCards) {
                                document.getElementById('final-four-container')?.classList.add('hud-slide-down');
                            }
                            if (hasWWCD && !hadWWCD) {
                                document.getElementById('wwcd-view')?.classList.add('wwcd-pop');
                            }

                            // FLIP: animate list reorder
                            if (hasList && hadList) {
                                Array.from(hudRoot.querySelectorAll('.list-item')).forEach(el => {
                                    const id = el.getAttribute('data-team-id');
                                    if (id && oldPositions.has(id)) {
                                        const oldRect = oldPositions.get(id);
                                        const newRect = el.getBoundingClientRect();
                                        const dy = oldRect.top - newRect.top;
                                        if (dy !== 0) {
                                            el.style.transform = `translateY(${dy}px)`;
                                            el.style.transition = 'none';
                                            requestAnimationFrame(() => {
                                                requestAnimationFrame(() => {
                                                    el.style.transform = '';
                                                    el.style.transition = 'transform 0.6s cubic-bezier(0.16, 1, 0.3, 1)';
                                                });
                                            });
                                        }
                                    }
                                });
                            }
                        });
                })
                .listen('.TeamEliminated', (e) => {
                    console.log('Elimination Received:', e);
                    eliminationQueue.push(e);
                    // Delay to let MatchStatsUpdated swap the DOM first,
                    // so getCurrentView() detects the correct view
                    setTimeout(() => processQueue(), 1500);
                });
        });
    </script>
</x-base>
