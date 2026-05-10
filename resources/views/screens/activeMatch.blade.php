<x-base>
    <style>
        .pubg-skew { transform: skewX(-12deg); }
        .pubg-unskew { transform: skewX(12deg); }
        .glass-panel { background: rgba(15, 23, 42, 0.7); backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.1); }
        
        /* Revised Transition Animations */
        .hud-slide-left { animation: slideLeft 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        .hud-slide-down { animation: slideDown 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        
        @keyframes slideLeft { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
        /* Simpler slide down for full-width container */
        @keyframes slideDown { 
            from { transform: translateY(-30px); opacity: 0; } 
            to { transform: translateY(0); opacity: 1; } 
        }

        .final-tab-skew { transform: skewX(-20deg); }
        .final-tab-unskew { transform: skewX(20deg); }
    </style>
    
    @php
        $allStats = $activeMatch->matchStats->sortByDesc(['points'])->values();
        $aliveTeams = $allStats->where('alive', '>', 0);
        $aliveTeamsCount = $aliveTeams->count();
        $showFinalFour = $aliveTeamsCount > 0 && $aliveTeamsCount <= 4;
    @endphp

    <!-- ELIMINATION TOASTER (Persistent Center Bottom) -->
    <div id="elimination-toaster" class="fixed left-0 w-full flex justify-center top-[150px] z-30 transition-all duration-700 ease-in-out opacity-0 pointer-events-none">
        <div class="flex items-center p-3 glass-panel border-b-4 border-red-600 rounded-b-xl shadow-2xl min-w-[380px]">
            <div class="w-12 h-12 mr-4 bg-slate-950/80 rounded-lg p-1.5 border border-red-500/30 flex-shrink-0">
                <img id="toaster-logo" src="" class="w-full h-full object-contain filter drop-shadow-md">
            </div>
            <div class="flex flex-col text-left">
                <div class="flex items-center gap-2 mb-0.5">
                    <span class="text-red-500 text-[9px] font-black tracking-[0.4em] uppercase">Squad Eliminated</span>
                </div>
                <span id="toaster-team-name" class="text-2xl font-black italic uppercase text-white tracking-tight"></span>
            </div>
        </div>
    </div>

    <!-- HUD ROOT -->
    <div id="hud-root" class="w-full h-full relative font-main text-slate-100 overflow-hidden">

        @if(!$showFinalFour)
            <!-- SIDE HUD (Bottom-Right) -->
            <div id="side-list-container" class="fixed bottom-10 right-10 w-[260px] flex flex-col gap-1.5 z-40">
                <!-- Header -->
                <div class="relative w-full bg-gradient-to-r from-yellow-500 to-orange-600 pubg-skew rounded-xs shadow-xl border-t border-yellow-300">
                    <div class="px-3 py-1.5 flex justify-between items-center pubg-unskew">
                        <span class="font-black italic uppercase tracking-tighter text-slate-950 text-xs">Live Standings</span>
                        <span class="font-black text-[9px] text-black/30">{{ $aliveTeamsCount }} Live</span>
                    </div>
                </div>

                <!-- List Content -->
                <div class="flex flex-col gap-1 w-full p-1 rounded-sm" id="live-standings">
                    @foreach ($allStats as $index => $match)
                    @php
                        $isEliminated = $match->alive == 0;
                        $isTop3 = $index < 3;
                    @endphp
                    
                    <div data-team-id="{{ $match->tournament_team_id }}" class="list-item relative w-full glass-panel rounded-xs pubg-skew transition-all duration-300 {{ $isEliminated ? 'opacity-30 grayscale' : ($isTop3 ? 'border-yellow-500/20' : '') }}">
                        <div class="flex items-center px-3 py-1.5 pubg-unskew">
                            <div class="w-5 h-5 flex-shrink-0 mr-2">
                                <img src="{{ $match->tournamentTeam->logo_image ? asset('storage/' . $match->tournamentTeam->logo_image) : asset('img/defult_team_logo.png') }}" class="w-full h-full object-contain">
                            </div>
                            <span class="flex-1 font-black uppercase tracking-tighter text-[12px] truncate {{ $isEliminated ? 'line-through decoration-red-900/50' : ($isTop3 ? 'text-yellow-400' : 'text-slate-100') }}">
                                {{ $match->tournamentTeam->short_name }}
                            </span>
                            <div class="flex gap-0.5 mx-2">
                                @if(!$isEliminated)
                                    @for($i = 0; $i < 4; $i++)
                                        <div class="w-1.5 h-3.5 {{ $i < $match->alive ? 'bg-yellow-400 shadow-[0_0_5px_rgba(250,204,21,0.5)]' : 'bg-slate-800' }} rounded-xs"></div>
                                    @endfor
                                @endif
                            </div>
                            <span class="font-black italic text-lg w-7 text-right {{ $isEliminated ? 'text-slate-600' : 'text-yellow-400' }}">
                                {{ $match->points }}
                            </span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        @else
            <!-- FINAL FOUR HUD (Reliable Full-Width Centering) -->
            <div id="final-four-container" class="fixed top-12 left-0 w-full z-50 flex flex-col items-center">
                
                <!-- Finalists Heading -->
                <div class="mb-6 bg-yellow-500 px-20 py-2.5 final-tab-skew shadow-[0_0_40px_rgba(245,158,11,0.6)] border-b-4 border-yellow-300">
                    <span class="final-tab-unskew block text-2xl font-black uppercase tracking-[0.5em] text-slate-950 italic drop-shadow-md">
                        Grand Final Battle
                    </span>
                </div>

                <div class="flex items-center justify-center gap-5">
                    @foreach ($aliveTeams->take(4) as $match)
                    <div class="relative flex items-center gap-5 glass-panel p-4 px-6 rounded-sm border-l-8 border-yellow-500 shadow-2xl w-[320px]">
                        <!-- Team Info -->
                        <div class="w-12 h-12 flex-shrink-0 bg-slate-900/50 rounded p-1 border border-white/10">
                            <img src="{{ $match->tournamentTeam->logo_image ? asset('storage/' . $match->tournamentTeam->logo_image) : asset('img/defult_team_logo.png') }}" class="w-full h-full object-contain filter drop-shadow-lg">
                        </div>
                        <div class="flex flex-col flex-1 gap-1">
                            <span class="text-xl font-black uppercase tracking-tighter text-white drop-shadow-md truncate w-36 leading-none">
                                {{ $match->tournamentTeam->short_name }}
                            </span>
                            <div class="flex items-center justify-between mt-1">
                                <div class="flex gap-1">
                                    @for($i = 0; $i < 4; $i++)
                                        <div class="w-3 h-4.5 {{ $i < $match->alive ? 'bg-yellow-400 shadow-[0_0_8px_rgba(245,158,11,0.5)]' : 'bg-slate-800' }} rounded-xs skew-x-[-15deg]"></div>
                                    @endfor
                                </div>
                                <div class="flex items-baseline gap-1 bg-yellow-500/10 px-2 py-0.5 rounded-xs border border-yellow-500/20">
                                    <span class="text-xs font-black text-yellow-500 opacity-50 uppercase leading-none">Pts</span>
                                    <span class="text-lg font-black italic text-yellow-500 leading-none">{{ $match->points }}</span>
                                </div>
                            </div>
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

            function processQueue() {
                if (eliminationQueue.length === 0 || isProcessing) return;
                isProcessing = true;
                const matchData = eliminationQueue.shift();
                teamNameSpan.innerText = matchData.teamName;
                logoImg.src = matchData.teamLogo ? "/storage/" + matchData.teamLogo : "{{ asset('img/defult_team_logo.png') }}";
                
                toasterWrapper.style.top = '240px'; 
                toasterWrapper.style.opacity = '1';
                
                setTimeout(() => {
                    toasterWrapper.style.top = '150px'; 
                    toasterWrapper.style.opacity = '0';
                    setTimeout(() => { isProcessing = false; processQueue(); }, 800);
                }, 5500);
            }

            // Initial Entrance
            const initialList = document.getElementById('side-list-container');
            const initialCards = document.getElementById('final-four-container');
            if(initialList) initialList.classList.add('hud-slide-left');
            if(initialCards) initialCards.classList.add('hud-slide-down');

            Echo.channel('active-match.{{ $activeMatch->id }}')
                .listen('MatchStatsUpdated', (e) => {
                    fetch(window.location.href, { cache: 'no-store', headers: {'Cache-Control': 'no-cache'} })
                        .then(response => response.text())
                        .then(html => {
                            const parser = new DOMParser();
                            const doc = parser.parseFromString(html, 'text/html');
                            const newHud = doc.getElementById('hud-root');
                            if (newHud) {
                                const currentHud = document.getElementById('hud-root');
                                const hadList = !!currentHud.querySelector('#side-list-container');
                                const hadCards = !!currentHud.querySelector('#final-four-container');
                                const hasNewList = !!newHud.querySelector('#side-list-container');
                                const hasNewCards = !!newHud.querySelector('#final-four-container');

                                // Measure
                                const oldElements = Array.from(currentHud.querySelectorAll('.list-item'));
                                const oldPositions = new Map();
                                oldElements.forEach(el => {
                                    const id = el.getAttribute('data-team-id');
                                    if(id) oldPositions.set(id, el.getBoundingClientRect());
                                });
                                
                                currentHud.innerHTML = newHud.innerHTML;

                                if (!hadCards && hasNewCards) {
                                    document.getElementById('final-four-container').classList.add('hud-slide-down');
                                } else if (!hadList && hasNewList) {
                                    document.getElementById('side-list-container').classList.add('hud-slide-left');
                                }

                                const newElements = Array.from(currentHud.querySelectorAll('.list-item'));
                                newElements.forEach(el => {
                                    const id = el.getAttribute('data-team-id');
                                    if(id && oldPositions.has(id)) {
                                        const oldRect = oldPositions.get(id);
                                        const newRect = el.getBoundingClientRect();
                                        const deltaY = oldRect.top - newRect.top;
                                        if(deltaY !== 0) {
                                            el.style.transform = `translateY(${deltaY}px)`;
                                            el.style.transition = 'none';
                                            requestAnimationFrame(() => { requestAnimationFrame(() => {
                                                el.style.transform = ''; el.style.transition = 'transform 0.7s cubic-bezier(0.16, 1, 0.3, 1)';
                                            }); });
                                        }
                                    }
                                });
                            }
                        });
                })
                .listen('.TeamEliminated', (e) => {
                    console.log('Elimination Received:', e);
                    eliminationQueue.push(e);
                    processQueue();
                });
        });
    </script>
</x-base>
