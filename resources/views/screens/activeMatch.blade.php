<x-base>
    {{-- High-End Typography & Theme Imports --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700;900&family=Rajdhani:wght@600;700;900&family=Orbitron:wght@800;900&display=swap" rel="stylesheet">
    
    <style>
        .font-esports {
            font-family: 'Rajdhani', sans-serif;
        }
        .font-body-esports {
            font-family: 'Inter', sans-serif;
        }
        
        .pubg-skew { transform: none; }
        .pubg-unskew { transform: none; }
        
        .glass-panel { 
            background: rgba(8, 12, 24, 0.85); 
            border: 1px solid rgba(250, 204, 21, 0.25); 
            backdrop-filter: blur(12px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.8);
        }
        
        /* Entry Animations */
        .hud-slide-right { animation: slideFromRight 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        .hud-slide-down { animation: slideFromTop 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        .wwcd-pop { animation: wwcdEntry 1.2s cubic-bezier(0.34, 1.56, 0.64, 1) forwards; }
        
        @keyframes slideFromRight { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
        @keyframes slideFromTop { from { transform: translateY(-50px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
        @keyframes wwcdEntry { 
            0% { transform: scale(0.7) rotate(-2deg); opacity: 0; filter: blur(10px); }
            100% { transform: scale(1) rotate(0deg); opacity: 1; filter: blur(0); }
        }
        
        /* Alive pips - glowing high tech indicators */
        .pip-alive { 
            background: #f59e0b; 
            box-shadow: 0 0 10px rgba(245, 158, 11, 0.8), 0 0 20px rgba(245, 158, 11, 0.4); 
            border: 1px solid #fff;
        }
        .pip-dead { 
            background: rgba(255,255,255,0.06); 
            border: 1px solid rgba(255,255,255,0.15); 
        }

        /* Lower Third */
        .lower-third-slide { animation: slideFromLeft 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        @keyframes slideFromLeft { from { transform: translateX(-110%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }

        /* Scanning telemetry line */
        @keyframes sweep {
            0% { transform: translateY(-100%); }
            100% { transform: translateY(100%); }
        }
        .laser-sweep {
            position: absolute;
            inset: 0;
            background: linear-gradient(to bottom, transparent 40%, rgba(250, 204, 21, 0.05) 50%, transparent 60%);
            animation: sweep 6s infinite linear;
            pointer-events: none;
        }

        .text-glow-gold {
            text-shadow: 0 0 10px rgba(250, 204, 21, 0.6), 0 0 20px rgba(250, 204, 21, 0.3);
        }
    </style>
    
    @php
        // 1. Retrieve all matches in the same round (or tournament, if no round)
        $currentRound = $activeMatch->tournamentRound;
        if ($currentRound) {
            $matches = $currentRound->tournamentMatches()->with('matchStats')->get();
        } else {
            $matches = $activeMatch->tournament->tournamentMatches()->with('matchStats')->get();
        }

        // 2. Aggregate points and kills per team ID across those matches
        $roundPoints = [];
        $roundKills = [];
        foreach ($matches as $match) {
            foreach ($match->matchStats as $stat) {
                $teamId = $stat->tournament_team_id;
                $roundPoints[$teamId] = ($roundPoints[$teamId] ?? 0) + $stat->points;
                $roundKills[$teamId] = ($roundKills[$teamId] ?? 0) + $stat->kills;
            }
        }

        // 3. Map these overall round standings points and kills to matchStats
        foreach ($activeMatch->matchStats as $stat) {
            $teamId = $stat->tournament_team_id;
            $stat->round_points = $roundPoints[$teamId] ?? $stat->points;
            $stat->round_kills = $roundKills[$teamId] ?? $stat->kills;
            // Back up the current match points for win probability weights
            $stat->match_points = $stat->points;
            // Override the points attribute so references to $stat->points output overall round points
            $stat->points = $stat->round_points;
        }

        // 4. Sort primarily by overall round points, then by overall round kills
        $allStats = $activeMatch->matchStats->sortBy([
            ['round_points', 'desc'],
            ['round_kills', 'desc'],
        ])->values();

        $aliveTeams = $allStats->where('alive', '>', 0);
        $aliveTeamsCount = $aliveTeams->count();
        $showFinalFour = $aliveTeamsCount > 1 && $aliveTeamsCount <= 4;
        $isWWCD = $aliveTeamsCount === 1;
        $winner = $isWWCD ? $aliveTeams->first() : null;
    @endphp

    <!-- ELIMINATION TOASTER (Outside hud-root, position controlled by JS) -->
    <div id="elimination-toaster" style="position:fixed; left:50%; transform:translateX(-50%); top:-120px; z-index:300; transition:all 0.7s ease-in-out; opacity:0; pointer-events:none;">
        <div class="glass-panel" style="display:flex; align-items:center; gap:20px; padding:16px 28px; border-bottom:6px solid #e11d48; min-width:480px; border-radius:0 0 12px 12px; box-shadow: 0 20px 50px rgba(0,0,0,0.9);">
            <div style="width:60px; height:60px; background:#080c18; border-radius:8px; display:flex; align-items:center; justify-content:center; flex-shrink:0; border:2.5px solid #e11d48; padding:3px;">
                <img id="toaster-logo" src="" style="width:100%; height:100%; object-fit:contain;">
            </div>
            <div style="display:flex; flex-direction:column; text-align:left;">
                <span class="font-esports" style="color:#f43f5e; font-size:15px; font-weight:900; letter-spacing:0.4em; text-transform:uppercase;">Squad Eliminated</span>
                <span id="toaster-team-name" class="font-esports" style="font-size:38px; font-weight:900; font-style:italic; text-transform:uppercase; color:#fff; letter-spacing:0.05em; line-height:1.1;"></span>
            </div>
        </div>
    </div>

    <!-- HUD ROOT -->
    <div id="hud-root" class="w-full h-full relative font-esports text-white overflow-hidden" data-view="{{ $isWWCD ? 'wwcd' : ($showFinalFour ? 'cards' : 'list') }}">

        @if($isWWCD)
            {{-- ========== WINNER WINNER CHICKEN DINNER ========== --}}
            <div id="wwcd-view" class="fixed inset-0 z-200 flex flex-col items-center justify-center bg-slate-950/85 backdrop-blur-md">
                
                {{-- Decorative light rays --}}
                <div class="absolute inset-0 bg-[radial-gradient(circle_at_center,rgba(250,204,21,0.12)_0px,transparent_60%)] pointer-events-none"></div>
                <div class="laser-sweep"></div>

                <div class="flex flex-col items-center wwcd-pop relative z-10">
                    
                    {{-- Double-skewed gold bar --}}
                    <div class="bg-linear-to-r from-yellow-400 via-amber-500 to-yellow-500 px-40 py-7 mb-10 pubg-skew shadow-[0_0_80px_rgba(250,204,21,0.5)] border-y-2 border-white/50">
                        <div class="pubg-unskew flex flex-col items-center">
                            <span class="text-black text-2xl font-black uppercase tracking-[0.45em] mb-1 font-esports">Winner Winner</span>
                            <span class="text-black text-7xl font-black uppercase tracking-widest font-esports italic">Chicken Dinner</span>
                        </div>
                    </div>

                    {{-- Winner Stats Card --}}
                    <div class="glass-panel p-12 flex flex-col items-center border-t-8 border-yellow-400 shadow-2xl relative" style="width:600px; border-radius: 4px;">
                        
                        <div class="absolute top-3 left-4 text-[9px] font-bold text-slate-500 tracking-widest uppercase">MATCH_VICTORY_HUD // CH_01</div>
                        
                        <div class="w-48 h-48 bg-slate-950/80 border border-yellow-400/40 mb-8 flex items-center justify-center shadow-[0_0_40px_rgba(250,204,21,0.2)] overflow-hidden">
                            <img src="{{ $winner->tournamentTeam->logo_image ? asset('storage/' . $winner->tournamentTeam->logo_image) : asset('img/defult_team_logo.png') }}" class="w-full aspect-square object-contain">
                        </div>
                        
                        <h2 class="text-7xl font-black italic uppercase text-slate-100 tracking-widest mb-8 text-glow-gold text-center">{{ $winner->tournamentTeam->name }}</h2>
                        
                        <div class="flex gap-20 mt-2 w-full justify-center">
                            <div class="flex flex-col items-center border-r border-slate-800 pr-12">
                                <span class="text-yellow-400 text-lg font-black uppercase tracking-[0.2em] mb-2">Total Kills</span>
                                <span class="text-6xl font-black text-white italic leading-none font-mono" style="font-family: 'Orbitron', sans-serif;">{{ $winner->kills }}</span>
                            </div>
                            <div class="flex flex-col items-center pl-12">
                                <span class="text-yellow-400 text-lg font-black uppercase tracking-[0.2em] mb-2">Total Points</span>
                                <span class="text-6xl font-black text-white italic leading-none font-mono" style="font-family: 'Orbitron', sans-serif;">{{ $winner->points }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        @elseif($showFinalFour)
            {{-- ========== FINAL FOUR CARD VIEW ========== --}}
            <div id="final-four-container" style="position:fixed; top:40px; left:0; width:100%; z-index:50; display:flex; flex-direction:column; align-items:center;">
                
                {{-- Duel Title Badge --}}
                <div class="mb-8 bg-linear-to-r from-red-600 via-orange-500 to-red-600 px-24 py-2.5 pubg-skew border-b-4 border-black/35 shadow-[0_10px_25px_rgba(239,68,68,0.3)]">
                    <span class="pubg-unskew block text-3xl font-black uppercase tracking-[0.45em] text-white italic text-glow-gold">Final Duel</span>
                </div>

                <div style="display:flex; justify-content:center; gap:20px;">
                    @foreach ($aliveTeams->take(4) as $match)
                    @php
                        $weight = ($match->alive * 30) + ($match->match_points * 0.5);
                        $totalWeight = $aliveTeams->sum(fn($m) => ($m->alive * 30) + ($m->match_points * 0.5));
                        $winProb = round(($weight / max(1, $totalWeight)) * 100);
                    @endphp
                    <div class="glass-panel" style="width:280px; border-left:6px solid #f59e0b; display:flex; flex-direction:column; border-radius: 4px;">
                        
                        {{-- Card Header: Win Prob Bar --}}
                        <div style="display:flex; justify-content:space-between; align-items:center; padding:8px 16px; background:rgba(255,255,255,0.02); border-bottom:1px solid rgba(255,255,255,0.05);">
                            <span style="font-size:13px; font-weight:900; color:rgba(255,255,255,0.4); text-transform:uppercase; letter-spacing:0.15em;">WIN PROB</span>
                            <div style="display:flex; align-items:center; gap:6px; background:rgba(245,158,11,0.15); padding:2px 8px; border-radius:3px; border:1px solid rgba(245,158,11,0.3);">
                                <span style="font-size:15px; font-weight:900; color:#f59e0b; font-family:'Orbitron', sans-serif; line-height:1;">{{ $winProb }}%</span>
                            </div>
                        </div>

                        {{-- Card Body --}}
                        <div style="padding:16px; display:flex; flex-direction:column; gap:12px; text-align:left;">
                            
                            {{-- Team Logo and Short Name --}}
                            <div style="display:flex; align-items:center; gap:12px;">
                                <div style="width:48px; height:48px; background:rgba(0,0,0,0.4); p:1; border:1px solid rgba(255,255,255,0.1); border-radius:4px; flex-shrink:0; display:flex; align-items:center; justify-content:center;">
                                    <img src="{{ $match->tournamentTeam->logo_image ? asset('storage/' . $match->tournamentTeam->logo_image) : asset('img/defult_team_logo.png') }}" style="width:100%; height:100%; object-fit:contain;">
                                </div>
                                <div style="flex:1; overflow:hidden; display:flex; flex-direction:column; gap:6px;">
                                    <span style="font-size:26px; font-weight:900; text-transform:uppercase; color:#fff; letter-spacing:0.05em; line-height:1; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $match->tournamentTeam->short_name }}</span>
                                    {{-- Alive Pips --}}
                                    <div style="display:flex; gap:5px;">
                                        @for($i = 0; $i < 4; $i++)
                                            <div class="{{ $i < $match->alive ? 'pip-alive' : 'pip-dead' }}" style="width:12px; height:16px; border-radius:2px; transform:skewX(-12deg);"></div>
                                        @endfor
                                    </div>
                                </div>
                            </div>

                            {{-- Stats block --}}
                            <div style="display:flex; align-items:flex-end; justify-content:space-between; padding-top:12px; border-top:1px solid rgba(255,255,255,0.06);">
                                <div style="display:flex; flex-direction:column; gap:2px;">
                                    <span style="font-size:12px; font-weight:700; color:rgba(255,255,255,0.4); text-transform:uppercase; letter-spacing:0.1em;">Kills</span>
                                    <span style="font-size:36px; font-weight:900; color:#fff; line-height:1; font-family:'Orbitron', sans-serif;">{{ $match->kills }}</span>
                                </div>
                                <div style="display:flex; flex-direction:column; align-items:flex-end; gap:2px;">
                                    <span style="font-size:12px; font-weight:700; color:rgba(255,255,255,0.4); text-transform:uppercase; letter-spacing:0.1em;">Points</span>
                                    <span style="font-size:36px; font-weight:900; color:#f59e0b; line-height:1; font-family:'Orbitron', sans-serif;">{{ $match->points }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

        @else
            {{-- ========== BOTTOM-RIGHT LIST VIEW ========== --}}
            <div id="side-list-container" style="position:fixed; bottom:30px; right:30px; width:330px; display:flex; flex-direction:column; gap:4px; z-index:40;">
                
                {{-- Title bar --}}
                <div class="bg-linear-to-r from-amber-500 via-yellow-400 to-amber-500 py-1.5 px-5 pubg-skew shadow-xl" style="border-bottom:2px solid rgba(0,0,0,0.3);">
                    <div class="pubg-unskew flex justify-between items-center">
                        <span class="font-black italic uppercase text-xl text-black">Live Standings</span>
                        <span class="text-sm font-black text-black/60 italic uppercase tracking-wider">{{ $aliveTeamsCount }} Teams Alive</span>
                    </div>
                </div>

                {{-- Column Headers --}}
                <div class="pubg-skew" style="display:flex; align-items:center; padding:3px 14px; gap:8px; background:rgba(0,0,0,0.4);">
                    <div class="pubg-unskew flex items-center w-full gap-2 text-[10px] font-black uppercase tracking-wider text-slate-400">
                        <span style="width:28px; flex-shrink:0;"></span>
                        <span style="flex:1; text-align:left;">Team</span>
                        <span style="width:50px; text-align:center;">Alive</span>
                        <span style="width:34px; text-align:center;">Kills</span>
                        <span style="width:40px; text-align:right;">Pts</span>
                    </div>
                </div>

                {{-- Scroll List --}}
                <div style="display:flex; flex-direction:column; gap:3px;" id="live-standings">
                    @foreach ($allStats as $index => $match)
                    @php $isEliminated = $match->alive == 0; @endphp
                    <div data-team-id="{{ $match->tournament_team_id }}" class="list-item glass-panel pubg-skew transition-all duration-500 {{ $isEliminated ? 'opacity-35 grayscale scale-[0.98]' : 'hover:border-yellow-400/40' }}" style="border-radius:2px; border: 1px solid {{ $isEliminated ? 'rgba(255,255,255,0.03)' : 'rgba(250,204,21,0.15)' }};">
                        <div class="pubg-unskew" style="display:flex; align-items:center; padding:8px 14px; gap:8px;">
                            
                            {{-- Logo --}}
                            <div style="width:28px; height:28px; background:rgba(0,0,0,0.3); border:1px solid rgba(255,255,255,0.08); border-radius:3px; flex-shrink:0; display:flex; align-items:center; justify-content:center;">
                                <img src="{{ $match->tournamentTeam->logo_image ? asset('storage/' . $match->tournamentTeam->logo_image) : asset('img/defult_team_logo.png') }}" style="width:100%; height:100%; object-fit:contain;">
                            </div>
                            
                            {{-- Team Short Name --}}
                            <span class="font-black uppercase" style="flex:1; text-align:left; font-size:22px; line-height:1; letter-spacing:0.05em; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; {{ $isEliminated ? 'color:rgba(255,255,255,0.25); text-decoration:line-through;' : 'color:#fff;' }}">{{ $match->tournamentTeam->short_name }}</span>
                            
                            {{-- Alive indicators --}}
                            <div style="display:flex; gap:3px; flex-shrink:0; width:50px; justify-content:center;">
                                @if(!$isEliminated)
                                    @for($i = 0; $i < 4; $i++)
                                        <div class="{{ $i < $match->alive ? 'pip-alive' : 'pip-dead' }}" style="width:7px; height:12px; border-radius:1px;"></div>
                                    @endfor
                                @else
                                    <span style="font-size:10px; font-weight:900; color:#ef4444; letter-spacing:0.1em; text-transform:uppercase;">OUT</span>
                                @endif
                            </div>
                            
                            {{-- Kills --}}
                            <span class="font-bold font-mono" style="font-size:19px; color:rgba(255,255,255,0.6); width:34px; text-align:center; line-height:1;">{{ $match->kills }}</span>
                            
                            {{-- Total Points --}}
                            <span class="font-black font-mono text-glow-gold" style="font-size:24px; color:#f59e0b; width:40px; text-align:right; line-height:1;">{{ $match->points }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        @endif

    </div>

    <!-- LOWER THIRD: Tournament Branding (Outside hud-root so it persists through data updates) -->
    @php
        $sponsors = $activeMatch->tournament->tournamentSponsors;
    @endphp
    <div id="lower-third" class="lower-third-slide" style="position:fixed; bottom:0; left:0; z-index:60; display:flex; align-items:stretch; gap:0;">
        
        {{-- Sponsor Logo Carousel --}}
        <div id="sponsor-carousel" style="width:110px; height:110px; background:rgba(4, 6, 12, 0.9); border:1px solid rgba(250,204,21,0.2); border-right:none; position:relative; flex-shrink:0; overflow:hidden;">
            @if($sponsors->count() > 0)
                @foreach($sponsors as $index => $sponsor)
                    <img 
                        class="sponsor-logo" 
                        src="{{ asset('storage/' . $sponsor->logo_image) }}" 
                        alt="{{ $sponsor->name }}"
                        style="position:absolute; inset:0; width:100%; height:100%; object-fit:contain; padding:12px; transition:opacity 0.8s ease-in-out; opacity:{{ $index === 0 ? '1' : '0' }};"
                    >
                @endforeach
            @else
                <span class="font-esports" style="position:absolute; inset:0; display:flex; align-items:center; justify-content:center; color:rgba(255,255,255,0.15); font-size:11px; font-weight:900; text-transform:uppercase; letter-spacing:0.15em;">PARTNERS</span>
            @endif
        </div>
        
        {{-- Tournament Info --}}
        <div class="glass-panel" style="display:flex; align-items:center; gap:20px; padding:14px 32px 14px 20px; border-left:6px solid #f59e0b; min-height:110px; border-top:1px solid rgba(250,204,21,0.2); border-bottom:none; border-right:none; border-radius:0 8px 0 0;">
            <div style="width:68px; height:68px; flex-shrink:0; display:flex; align-items:center; justify-content:center; background:rgba(0,0,0,0.3); p:1; border:1px solid rgba(255,255,255,0.06); border-radius:6px;">
                <img src="{{ $activeMatch->tournament->logo_image ? asset('storage/' . $activeMatch->tournament->logo_image) : asset('img/defult_team_logo.png') }}" style="max-width:100%; max-h-100%; object-fit:contain;">
            </div>
            <div style="display:flex; flex-direction:column; gap:2px; text-align:left;">
                <span class="font-esports text-slate-400" style="font-size:18px; font-weight:700; text-transform:uppercase; letter-spacing:0.15em; line-height:1;">{{ $activeMatch->tournament->name }}</span>
                <span class="font-esports italic text-glow-gold" style="font-size:28px; font-weight:900; text-transform:uppercase; color:#f59e0b; letter-spacing:0.05em; line-height:1.1;">{{ $activeMatch->name }}</span>
            </div>
        </div>
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
                    // Cards view: appear well BELOW the cards
                    toasterWrapper.style.top = '340px';
                    toasterWrapper.style.opacity = '0';
                    requestAnimationFrame(() => {
                        toasterWrapper.style.top = '360px';
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
                        toasterWrapper.style.top = '25px';
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
                .listen('.MatchStatsUpdated', (e) => {
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

            Echo.channel('user-screens.' + {{ $activeMatch->tournament->user_id }})
                .listen('.ActiveMatchVisibilityToggled', (e) => {
                    console.log('Visibility Toggled:', e);
                    const hudRoot = document.getElementById('hud-root');
                    if (hudRoot) {
                        hudRoot.style.transition = 'opacity 0.5s ease-in-out';
                        hudRoot.style.opacity = e.isVisible ? '1' : '0';
                        setTimeout(() => {
                            hudRoot.style.visibility = e.isVisible ? 'visible' : 'hidden';
                        }, e.isVisible ? 0 : 500);
                    }
                })
                .listen('.RefreshScreens', (e) => {
                    console.log('Force refresh received...');
                    window.location.reload();
                })
                .listen('.TournamentMatchUpdated', (e) => {
                    console.log('Match updated/activated, reloading...');
                    window.location.reload();
                });
            
            // Sponsor Logo Carousel — fade cycle every 4 seconds
            const sponsorLogos = document.querySelectorAll('.sponsor-logo');
            if (sponsorLogos.length > 1) {
                let currentSponsor = 0;
                setInterval(() => {
                    sponsorLogos[currentSponsor].style.opacity = '0';
                    currentSponsor = (currentSponsor + 1) % sponsorLogos.length;
                    sponsorLogos[currentSponsor].style.opacity = '1';
                }, 4000);
            }
        });
    </script>
</x-base>
