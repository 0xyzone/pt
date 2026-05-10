<x-base>
    <style>
        .pubg-skew { transform: skewX(-12deg); }
        .pubg-unskew { transform: skewX(12deg); }
        
        .glass-panel { background: rgba(5, 8, 15, 0.95); border: 1px solid rgba(255, 255, 255, 0.1); }
        
        /* Transition Animations */
        .hud-slide-right { animation: slideRight 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        .hud-slide-down { animation: slideDown 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        
        @keyframes slideRight { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
        @keyframes slideDown { from { transform: translateY(-30px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }

        /* Readability Sharpness */
        .text-sharp { text-shadow: -1px -1px 0 #000, 1px -1px 0 #000, -1px 1px 0 #000, 1px 1px 0 #000; }
    </style>
    
    @php
        $allStats = $activeMatch->matchStats->sortByDesc(['points'])->values();
        $aliveTeams = $allStats->where('alive', '>', 0);
        $aliveTeamsCount = $aliveTeams->count();
        $showFinalFour = $aliveTeamsCount > 1 && $aliveTeamsCount <= 4;
        $isWWCD = $aliveTeamsCount === 1;
        $winner = $isWWCD ? $aliveTeams->first() : null;
    @endphp

    <!-- HUD ROOT -->
    <div id="hud-root" class="w-full h-full relative font-main text-white overflow-hidden">

        <!-- ELIMINATION TOASTER (Compact) -->
        <div id="elimination-toaster" class="fixed left-0 w-full flex justify-center top-[120px] z-[100] transition-all duration-700 ease-in-out opacity-0 pointer-events-none">
            <div class="flex items-center p-2.5 glass-panel border-b-4 border-red-600 rounded-b-lg shadow-2xl min-w-[340px]">
                <div class="w-10 h-10 mr-4 bg-red-600 rounded flex items-center justify-center p-1.5 border border-white/20">
                    <img id="toaster-logo" src="" class="w-full h-full object-contain brightness-0 invert">
                </div>
                <div class="flex flex-col">
                    <span class="text-red-500 text-[8px] font-black tracking-[0.4em] uppercase">Squad Eliminated</span>
                    <span id="toaster-team-name" class="text-xl font-black italic uppercase text-white tracking-tighter text-sharp"></span>
                </div>
            </div>
        </div>

        @if($isWWCD)
            <!-- WWCD OVERLAY (Focused) -->
            <div class="fixed inset-0 z-[200] flex flex-col items-center justify-center bg-black/70 backdrop-blur-md">
                <div class="flex flex-col items-center animate-bounce-short">
                    <div class="bg-yellow-400 px-32 py-5 mb-8 pubg-skew shadow-[0_0_80px_rgba(250,204,21,0.4)]">
                        <div class="pubg-unskew flex flex-col items-center">
                            <span class="text-black text-xl font-black uppercase tracking-[0.3em] mb-1">Winner Winner</span>
                            <span class="text-black text-5xl font-black italic uppercase tracking-tighter">Chicken Dinner</span>
                        </div>
                    </div>
                    <div class="w-[500px] glass-panel p-10 rounded-sm flex flex-col items-center border-t-8 border-yellow-400 shadow-2xl">
                        <div class="w-40 h-40 bg-white/5 rounded-full p-8 border-4 border-yellow-400 mb-8 flex items-center justify-center">
                            <img src="{{ $winner->tournamentTeam->logo_image ? asset('storage/' . $winner->tournamentTeam->logo_image) : asset('img/defult_team_logo.png') }}" class="w-full h-full object-contain">
                        </div>
                        <h2 class="text-6xl font-black italic uppercase text-white tracking-tighter mb-6 text-sharp">{{ $winner->tournamentTeam->name }}</h2>
                        <div class="flex gap-16">
                            <div class="flex flex-col items-center">
                                <span class="text-yellow-400 text-xs font-black uppercase tracking-widest mb-1">Kills</span>
                                <span class="text-5xl font-black text-white italic leading-none text-sharp">{{ $winner->kills }}</span>
                            </div>
                            <div class="flex flex-col items-center">
                                <span class="text-yellow-400 text-xs font-black uppercase tracking-widest mb-1">Points</span>
                                <span class="text-5xl font-black text-white italic leading-none text-sharp">{{ $winner->points }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @elseif($showFinalFour)
            <!-- COMPACT FINAL FOUR HUD (Minimal Screen Coverage) -->
            <div id="final-four-container" class="fixed top-8 left-0 w-full z-50 flex flex-col items-center">
                <div class="mb-6 bg-yellow-400 px-24 py-1.5 pubg-skew border-b-4 border-black shadow-lg">
                    <span class="pubg-unskew block text-xl font-black uppercase tracking-[0.4em] text-black italic">Final Duel</span>
                </div>

                <div class="flex justify-center gap-3">
                    @foreach ($aliveTeams->take(4) as $match)
                    @php
                        $weight = ($match->alive * 30) + ($match->points * 0.5);
                        $totalWeight = $aliveTeams->sum(fn($m) => ($m->alive * 30) + ($m->points * 0.5));
                        $winProb = round(($weight / max(1, $totalWeight)) * 100);
                    @endphp
                    <div class="relative w-[240px] glass-panel p-3 px-4 rounded-sm border-l-4 border-yellow-400 shadow-xl flex flex-col gap-3">
                        <div class="flex justify-between items-center bg-white/5 -mx-4 -mt-3 p-1 px-4 border-b border-white/5">
                            <span class="text-[8px] font-black text-white/40 uppercase tracking-widest">Team Performance</span>
                            <div class="flex items-center gap-1">
                                <span class="text-[8px] font-black text-yellow-400 uppercase tracking-tighter">WIN %</span>
                                <span class="text-sm font-black text-yellow-400 font-mono">{{ $winProb }}</span>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-white p-1 rounded-sm flex-shrink-0">
                                <img src="{{ $match->tournamentTeam->logo_image ? asset('storage/' . $match->tournamentTeam->logo_image) : asset('img/defult_team_logo.png') }}" class="w-full h-full object-contain">
                            </div>
                            <div class="flex flex-col flex-1 overflow-hidden">
                                <span class="block text-lg font-black uppercase tracking-tighter text-white truncate leading-none text-sharp">{{ $match->tournamentTeam->short_name }}</span>
                                <div class="flex gap-1 mt-1.5">
                                    @for($i = 0; $i < 4; $i++)
                                        <div class="w-3 h-3.5 {{ $i < $match->alive ? 'bg-yellow-400 shadow-sm' : 'bg-black border border-white/10' }} rounded-xs skew-x-[-15deg]"></div>
                                    @endfor
                                </div>
                            </div>
                            <div class="flex flex-col items-end">
                                <span class="text-[8px] font-black text-yellow-400 uppercase leading-none mb-1">PTS</span>
                                <span class="text-2xl font-black italic text-white leading-none text-sharp">{{ $match->points }}</span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        @else
            <!-- SIDE HUD -->
            <div id="side-list-container" class="fixed bottom-10 right-10 w-[200px] flex flex-col gap-1 z-40">
                <div class="bg-yellow-400 py-1.5 px-4 border-t-2 border-black">
                    <div class="flex justify-between items-center text-black font-black italic uppercase text-[10px]">
                        <span>Leaderboard</span>
                        <span class="opacity-60">{{ $aliveTeamsCount }} Teams</span>
                    </div>
                </div>
                <div class="flex flex-col gap-0.5" id="live-standings">
                    @foreach ($allStats as $index => $match)
                    @php $isEliminated = $match->alive == 0; @endphp
                    <div data-team-id="{{ $match->tournament_team_id }}" class="list-item relative glass-panel rounded-xs {{ $isEliminated ? 'opacity-40 grayscale' : '' }}">
                        <div class="flex items-center px-3 py-1 gap-3">
                            <span class="text-[9px] font-black italic text-slate-500 w-3">{{ $index + 1 }}</span>
                            <img src="{{ $match->tournamentTeam->logo_image ? asset('storage/' . $match->tournamentTeam->logo_image) : asset('img/defult_team_logo.png') }}" class="w-4 h-4 object-contain">
                            <span class="flex-1 font-black uppercase text-[9px] truncate {{ $isEliminated ? 'line-through text-slate-500' : 'text-white' }}">{{ $match->tournamentTeam->short_name }}</span>
                            <span class="font-black italic text-md text-yellow-400 w-6 text-right">{{ $match->points }}</span>
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
                toasterWrapper.style.top = '180px'; toasterWrapper.style.opacity = '1';
                setTimeout(() => {
                    toasterWrapper.style.top = '120px'; toasterWrapper.style.opacity = '0';
                    setTimeout(() => { isProcessing = false; processQueue(); }, 800);
                }, 5500);
            }

            const initialList = document.getElementById('side-list-container');
            const initialCards = document.getElementById('final-four-container');
            if(initialList) initialList.classList.add('hud-slide-right');
            if(initialCards) initialCards.classList.add('hud-slide-down');

            Echo.channel('active-match.{{ $activeMatch->id }}')
                .listen('MatchStatsUpdated', (e) => {
                    fetch(window.location.href, { cache: 'no-store' })
                        .then(response => response.text())
                        .then(html => {
                            const parser = new DOMParser();
                            const doc = parser.parseFromString(html, 'text/html');
                            const newHud = doc.getElementById('hud-root');
                            if (newHud) {
                                document.getElementById('hud-root').innerHTML = newHud.innerHTML;
                                if (!!newHud.querySelector('#final-four-container')) document.getElementById('final-four-container')?.classList.add('hud-slide-down');
                                if (!!newHud.querySelector('#side-list-container')) document.getElementById('side-list-container')?.classList.add('hud-slide-right');
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
