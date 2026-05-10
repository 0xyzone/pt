<x-base>
    <style>
        .pubg-skew { transform: skewX(-12deg); }
        .pubg-unskew { transform: skewX(12deg); }
        
        .team-card {
            display: flex;
            flex-direction: column;
            width: 100%;
            height: 100%;
            box-shadow: 0 4px 10px rgba(0,0,0,0.5);
            background: white;
            border-radius: 2px;
        }
        
        .team-card-top {
            flex: 1;
            background: #1e293b; /* slate-800 */
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 8px 12px;
            position: relative;
        }
        
        .team-card-bottom {
            height: 32px;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .map-bg {
            background: #0f172a; /* slate-900 */
            /* Add abstract diagonal lines for the sides */
            background-image: repeating-linear-gradient(
                -45deg,
                rgba(255,255,255,0.02),
                rgba(255,255,255,0.02) 10px,
                transparent 10px,
                transparent 20px
            );
        }
    </style>

    <div class="w-full h-full flex font-sans map-bg overflow-hidden relative">

        {{-- LEFT COLUMN (420px) --}}
        <div class="w-[420px] h-full flex flex-col pt-8 px-6 pb-6 z-10 relative bg-black/40 border-r border-slate-700/50">
            {{-- Tournament Branding --}}
            <div class="flex items-center gap-4 mb-10 pl-2">
                <img src="{{ $tournament->logo_image ? asset('storage/' . $tournament->logo_image) : asset('img/defult_team_logo.png') }}" class="w-20 aspect-square object-contain drop-shadow-xl">
                <div class="flex flex-col">
                    <span class="text-3xl font-black uppercase text-white tracking-widest leading-none drop-shadow-md">PUBG MOBILE</span>
                    <span class="text-lg font-bold uppercase text-white bg-white/20 px-2 py-0.5 inline-block mt-1 tracking-widest">{{ $tournament->name }}</span>
                </div>
            </div>

            {{-- Left Teams Grid (First 8 teams) --}}
            <div class="grid grid-cols-2 grid-rows-4 gap-x-4 gap-y-6 w-full flex-1 mt-6 mb-2">
                @foreach($teams->take(8) as $team)
                    <div class="team-card overflow-hidden">
                        <div class="team-card-top">
                            <img src="{{ $team->logo_image ? asset('storage/' . $team->logo_image) : asset('img/defult_team_logo.png') }}" class="max-w-full max-h-full object-contain filter drop-shadow-md">
                        </div>
                        <div class="team-card-bottom">
                            <span class="font-black text-[15px] uppercase tracking-widest">{{ $team->short_name }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- MIDDLE COLUMN (1080x1080) --}}
        {{-- This area is kept completely transparent for OBS map source --}}
        <div class="w-[1080px] h-[1080px] shrink-0 relative bg-transparent">
            {{-- Optional: We can add an inner glow or border to frame the map --}}
            <div class="absolute inset-0 shadow-[inset_0_0_50px_rgba(0,0,0,0.8)] pointer-events-none border-x border-slate-800/50"></div>
        </div>

        {{-- RIGHT COLUMN (420px) --}}
        <div class="w-[420px] h-full flex flex-col pt-8 px-6 pb-6 z-10 relative bg-black/40 border-l border-slate-700/50">
            {{-- Match Details --}}
            <div class="flex flex-col items-end mb-10 pr-2">
                <span class="text-3xl font-black uppercase text-orange-500 tracking-widest drop-shadow-md leading-tight">{{ $activeMatch->name }}</span>
                <span class="text-2xl font-black uppercase text-slate-200 tracking-widest drop-shadow-md mt-1">{{ $activeMatch->map }}</span>
            </div>

            {{-- Right Teams Grid (Next 8 teams) --}}
            <div class="grid grid-cols-2 grid-rows-4 gap-x-4 gap-y-6 w-full flex-1 mt-6 mb-2">
                @foreach($teams->slice(8)->take(8) as $team)
                    <div class="team-card overflow-hidden">
                        <div class="team-card-top">
                            <img src="{{ $team->logo_image ? asset('storage/' . $team->logo_image) : asset('img/defult_team_logo.png') }}" class="max-w-full max-h-full object-contain filter drop-shadow-md">
                        </div>
                        <div class="team-card-bottom">
                            <span class="font-black text-[15px] uppercase tracking-widest">{{ $team->short_name }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    </div>

    {{-- Live updates listener --}}
    <script type="module">
        document.addEventListener("DOMContentLoaded", function () {
            Echo.channel('active-match.{{ $activeMatch->id }}')
                .listen('.MatchStatsUpdated', (e) => {
                    // Usually map teams don't change, but if they do, we can reload
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
