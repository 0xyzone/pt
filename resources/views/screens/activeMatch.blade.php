<x-base>
    <style>
        .pubg-skew { transform: skewX(-10deg); }
        .pubg-unskew { transform: skewX(10deg); }
        .dead-team { filter: grayscale(100%); opacity: 0.75; }
    </style>
    
    <!-- Top Left Widget Container for Live Broadcast HUD -->
    <div class="fixed top-10 left-10 w-[360px] font-sans text-slate-100 z-50 flex flex-col gap-1.5">
        
        <!-- Header -->
        <div class="relative w-full bg-gradient-to-r from-yellow-500 to-orange-600 pubg-skew rounded-sm shadow-[0_0_15px_rgba(245,158,11,0.5)] border-l-4 border-yellow-300">
            <div class="px-4 py-2 flex justify-between items-center pubg-unskew">
                <span class="font-black italic uppercase tracking-wider text-slate-900 drop-shadow-sm text-xl">
                    Live Updates
                </span>
                <span class="font-bold text-[10px] uppercase tracking-[0.2em] text-orange-950 bg-yellow-400 px-2 py-0.5 rounded-sm shadow-inner drop-shadow-md">
                    {{ $activeMatch->name }}
                </span>
            </div>
        </div>

        <!-- Header Columns -->
        <div class="flex items-center px-4 py-2 bg-slate-900/80 backdrop-blur-md rounded-sm border-y border-slate-700/50 mt-1 mb-1 pubg-skew shadow-lg">
            <div class="pubg-unskew flex w-full text-xs font-bold uppercase tracking-[0.15em] text-slate-400">
                <div class="w-24 pl-1">Team</div>
                <div class="flex-1 text-center">Status</div>
                <div class="w-14 text-right pr-1 text-yellow-500 drop-shadow-[0_0_2px_rgba(250,204,21,0.8)]">Pts</div>
            </div>
        </div>

        <!-- Teams List -->
        <div class="flex flex-col gap-1.5 w-full" id="live-standings">
            @foreach ($activeMatch->matchStats->sortByDesc(['points'])->values() as $index => $match)
            @php
                $isEliminated = $match->alive == 0;
                $isTop3 = $index < 3;
            @endphp
            
            <div data-team-id="{{ $match->tournament_team_id }}" class="list-item relative w-full bg-slate-900/85 backdrop-blur-md border border-slate-700/50 rounded-sm pubg-skew transition-colors duration-300 {{ $isEliminated ? 'border-red-900/40 bg-red-950/40 shadow-none' : ($isTop3 ? 'border-yellow-600/40 shadow-[0_4px_15px_rgba(0,0,0,0.5)]' : 'shadow-lg border-slate-600/30') }}">
                
                @if($isEliminated)
                    <!-- Red elimination overlay -->
                    <div class="absolute inset-0 bg-gradient-to-r from-red-600/10 to-transparent pointer-events-none rounded-sm"></div>
                @endif
                
                <div class="flex items-center px-3 py-1.5 pubg-unskew {{ $isEliminated ? 'dead-team text-slate-500' : 'text-slate-100' }}">
                    
                    <!-- Team Logo & Name -->
                    <div class="flex items-center gap-2.5 w-24">
                        <div class="w-7 h-7 flex-shrink-0 relative {{ !$isEliminated ? 'drop-shadow-[0_0_3px_rgba(255,255,255,0.4)]' : '' }}">
                            <img src="{{ $match->tournamentTeam->logo_image ? asset('storage/' . $match->tournamentTeam->logo_image) : asset('img/defult_team_logo.png') }}" class="w-full h-full object-contain">
                        </div>
                        <span class="font-black uppercase tracking-tight text-base truncate {{ $isEliminated ? 'text-red-400/80 line-through decoration-red-600/60' : ($isTop3 ? 'text-yellow-400' : 'text-slate-100') }}">
                            {{ $match->tournamentTeam->short_name }}
                        </span>
                    </div>

                    <!-- ALIVE PIPS -->
                    <div class="flex-1 flex justify-center items-center gap-1.5">
                        @if($isEliminated)
                            <span class="text-[9px] font-black text-red-500 tracking-[0.25em] uppercase italic px-2 py-0.5 border border-red-500/30 bg-red-500/10 rounded-sm">Eliminated</span>
                        @else
                            @for($i = 0; $i < 4; $i++)
                                @if($i < $match->alive)
                                    <div class="w-2.5 h-4 bg-yellow-400 rounded-sm shadow-[0_0_8px_rgba(250,204,21,0.7)] skew-x-[-15deg]"></div>
                                @else
                                    <div class="w-2.5 h-4 bg-slate-700/60 rounded-sm border border-slate-600 skew-x-[-15deg] shadow-inner"></div>
                                @endif
                            @endfor
                        @endif
                    </div>

                    <!-- POINTS -->
                    <div class="w-14 text-right pr-1 font-black italic text-2xl {{ $isEliminated ? 'text-red-400/80' : 'text-yellow-400 drop-shadow-[0_0_5px_rgba(250,204,21,0.6)]' }}">
                        {{ $match->points }}
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
    </div>

    <script type="module">
        document.addEventListener("DOMContentLoaded", function () {
            Echo.channel('active-match.{{ $activeMatch->id }}')
                .listen('MatchStatsUpdated', (e) => {
                    fetch(window.location.href, { cache: 'no-store', headers: {'Cache-Control': 'no-cache'} })
                        .then(response => response.text())
                        .then(html => {
                            const parser = new DOMParser();
                            const doc = parser.parseFromString(html, 'text/html');
                            const newList = doc.getElementById('live-standings');
                            
                            if (newList) {
                                const currentList = document.getElementById('live-standings');
                                
                                // 1. Set up FLIP animation - Measure First
                                const oldChildren = Array.from(currentList.querySelectorAll('.list-item'));
                                const oldRects = new Map();
                                oldChildren.forEach(child => {
                                    const teamId = child.getAttribute('data-team-id');
                                    if(teamId) oldRects.set(teamId, child.getBoundingClientRect());
                                });

                                // 2. Perform swap DOM update
                                currentList.innerHTML = newList.innerHTML;

                                // 3. Measure Last & Invert transforms
                                const newChildren = Array.from(currentList.querySelectorAll('.list-item'));
                                newChildren.forEach(child => {
                                    const teamId = child.getAttribute('data-team-id');
                                    if(teamId && oldRects.has(teamId)) {
                                        const oldRect = oldRects.get(teamId);
                                        const newRect = child.getBoundingClientRect();
                                        const deltaY = oldRect.top - newRect.top;

                                        if(deltaY !== 0) {
                                            child.style.transform = `translateY(${deltaY}px)`;
                                            child.style.transition = 'none';

                                            // 4. Play fluid animation
                                            requestAnimationFrame(() => {
                                                requestAnimationFrame(() => {
                                                    child.style.transform = '';
                                                    child.style.transition = 'transform 0.6s cubic-bezier(0.16, 1, 0.3, 1)';
                                                });
                                            });
                                        }
                                    }
                                });
                            }
                        });
                });
        });
    </script>
</x-base>
