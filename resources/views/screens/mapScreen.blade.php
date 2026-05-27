<x-base title="MAP OVERLAY">
    {{-- High-End Typography & Theme Imports --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700;900&family=Rajdhani:wght@600;700;900&display=swap" rel="stylesheet">

    <style>
        .font-esports {
            font-family: 'Rajdhani', sans-serif;
        }
        .font-body-esports {
            font-family: 'Inter', sans-serif;
        }

        .pubg-skew { transform: none; }
        .pubg-unskew { transform: none; }
        
        @keyframes teamCardPulse {
            0%, 100% {
                border-color: rgba(250, 204, 21, 0.2);
                box-shadow: 0 10px 25px rgba(0,0,0,0.7);
            }
            50% {
                border-color: rgba(250, 204, 21, 0.45);
                box-shadow: 0 10px 25px rgba(250, 204, 21, 0.08);
            }
        }

        .team-card {
            display: flex;
            flex-direction: column;
            width: 100%;
            height: 100%;
            background: rgba(8, 12, 24, 0.75);
            border-radius: 4px;
            border: 1px solid rgba(250, 204, 21, 0.2);
            animation: teamCardPulse 5s infinite ease-in-out;
        }

        .team-card:nth-child(even) {
            animation-delay: 2.5s;
        }
        
        .team-card-top {
            flex: 1;
            background: rgba(4, 6, 12, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 12px;
            position: relative;
        }
        
        .team-card-bottom {
            height: 52px;
            background: rgba(15, 23, 42, 0.95);
            display: flex;
            align-items: center;
            justify-content: center;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 0 0 4px 4px;
        }

        /* Abstract diagonal grids */
        .decor-grid {
            background-image: repeating-linear-gradient(
                -45deg,
                rgba(255,255,255,0.015),
                rgba(255,255,255,0.015) 10px,
                transparent 10px,
                transparent 20px
            );
        }
    </style>

    <div class="w-full h-full flex font-esports bg-transparent overflow-hidden relative select-none">

        {{-- LEFT COLUMN (420px) --}}
        <div class="w-105 h-full flex flex-col pt-8 px-6 pb-6 z-10 relative bg-slate-950/80 backdrop-blur-md border-r border-yellow-400/20 shadow-2xl">
            <div class="absolute inset-0 decor-grid z-0 opacity-40 pointer-events-none"></div>

            {{-- Tournament Branding --}}
            <div class="flex items-center gap-5 mb-8 pl-1 relative z-10">
                <div class="bg-slate-900 border border-yellow-400/40 p-2 rounded-lg shadow-lg">
                    <img src="{{ $tournament->logo_image ? asset('storage/' . $tournament->logo_image) : asset('img/defult_team_logo.png') }}" 
                         class="w-14 h-14 object-contain">
                </div>
                <div class="flex flex-col text-left">
                    <span class="text-xs font-bold text-yellow-400 tracking-[0.3em] uppercase leading-none">SYS // MAP_STREAM</span>
                    <span class="text-2xl font-black uppercase text-slate-100 tracking-wider mt-1.5 leading-none">{{ $tournament->name }}</span>
                </div>
            </div>

            <div class="w-full h-px bg-linear-to-r from-yellow-400/30 to-transparent mb-6 relative z-10"></div>

            {{-- Left Teams Grid (First 8 teams) --}}
            <div class="grid grid-cols-2 grid-rows-4 gap-x-4 gap-y-5 w-full flex-1 mb-2 relative z-10">
                @foreach($teams->take(8) as $team)
                    <div class="team-card overflow-hidden">
                        <div class="team-card-top">
                            <img src="{{ $team->logo_image ? asset('storage/' . $team->logo_image) : asset('img/defult_team_logo.png') }}" class="max-w-[80%] max-h-[80%] object-contain filter drop-shadow-md">
                        </div>
                        <div class="team-card-bottom">
                            <span class="font-black text-2xl uppercase tracking-wider text-slate-100">{{ $team->short_name }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- MIDDLE COLUMN (1080px wide) --}}
        {{-- Totally transparent container to capture clean OBS game maps --}}
        <div class="w-270 h-full shrink-0 relative bg-transparent pointer-events-none">
            {{-- Sleek glowing framing border for the OBS map capture --}}
            <div class="absolute inset-y-0 left-0 w-px bg-linear-to-b from-transparent via-yellow-400/25 to-transparent"></div>
            <div class="absolute inset-y-0 right-0 w-px bg-linear-to-b from-transparent via-yellow-400/25 to-transparent"></div>
            <div class="absolute inset-0 shadow-[inset_0_0_60px_rgba(0,0,0,0.85)]"></div>
        </div>

        {{-- RIGHT COLUMN (420px) --}}
        <div class="w-105 h-full flex flex-col pt-8 px-6 pb-6 z-10 relative bg-slate-950/80 backdrop-blur-md border-l border-yellow-400/20 shadow-2xl">
            <div class="absolute inset-0 decor-grid z-0 opacity-40 pointer-events-none"></div>

            {{-- Match Details --}}
            <div class="flex flex-col items-end mb-8 pr-1 relative z-10">
                <span class="text-xs font-bold text-slate-500 tracking-[0.3em] uppercase leading-none">SYS // ACTIVE_PHASE</span>
                <span class="text-3xl font-black uppercase text-slate-200 tracking-widest mt-2 leading-none font-esports">{{ $activeMatch->name }}</span>
                <div class="pubg-skew bg-linear-to-r from-yellow-500 to-amber-500 border border-white/20 px-5 py-1.5 inline-block mt-3 shadow-md">
                    <span class="pubg-unskew block text-black font-black uppercase text-xl tracking-wider font-esports leading-none italic">{{ $activeMatch->map }}</span>
                </div>
            </div>

            <div class="w-full h-px bg-linear-to-l from-yellow-400/30 to-transparent mb-6 relative z-10"></div>

            {{-- Right Teams Grid (Next 8 teams) --}}
            <div class="grid grid-cols-2 grid-rows-4 gap-x-4 gap-y-5 w-full flex-1 mb-2 relative z-10">
                @foreach($teams->slice(8)->take(8) as $team)
                    <div class="team-card overflow-hidden">
                        <div class="team-card-top">
                            <img src="{{ $team->logo_image ? asset('storage/' . $team->logo_image) : asset('img/defult_team_logo.png') }}" class="max-w-[80%] max-h-[80%] object-contain filter drop-shadow-md">
                        </div>
                        <div class="team-card-bottom">
                            <span class="font-black text-2xl uppercase tracking-wider text-slate-100">{{ $team->short_name }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    </div>

    {{-- Live updates listener --}}
    <script type="module">
        document.addEventListener("DOMContentLoaded", function () {
            Echo.channel('user-screens.{{ $user->id }}')
                .listen('.TournamentMatchUpdated', (e) => {
                    console.log('Match activated/updated, reloading map screen...');
                    window.location.reload();
                })
                .listen('.RefreshScreens', (e) => {
                    console.log('Force refresh received...');
                    window.location.reload();
                });

            Echo.channel('active-match.{{ $activeMatch->id }}')
                .listen('.MatchStatsUpdated', (e) => {
                    fetch(window.location.href, { cache: 'no-store' })
                        .then(response => response.text())
                        .then(html => {
                            const parser = new DOMParser();
                            const doc = parser.parseFromString(html, 'text/html');
                            document.querySelector('body').innerHTML = doc.querySelector('body').innerHTML;
                        });
                });
        });
    </script>
</x-base>
