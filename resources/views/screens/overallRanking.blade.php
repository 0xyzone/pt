<x-base>
    <style>
        @keyframes slideInUp {
            from { opacity: 0; transform: translateY(40px) scale(0.98); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }
        .animate-row {
            animation: slideInUp 0.9s cubic-bezier(0.2, 0.8, 0.2, 1) backwards;
        }
        @keyframes pulseGlowGold {
            0%, 100% { box-shadow: 0 0 20px rgba(251, 191, 36, 0.5); }
            50% { box-shadow: 0 0 45px rgba(251, 191, 36, 0.9); }
        }
        .glow-active-gold {
            animation: pulseGlowGold 2.5s infinite;
        }
    </style>
    <div class="w-full h-full p-4 md:p-8 font-sans text-slate-100 relative overflow-hidden z-10 flex flex-col">
        <!-- PUBG Themed Abstract Background Elements -->
        <div class="fixed top-0 left-0 w-full h-full z-0 pointer-events-none">
            <div class="absolute top-[-20%] left-[20%] w-[60%] h-[50%] bg-orange-600/10 blur-[130px] rounded-full"></div>
            <div class="absolute bottom-[-10%] right-[10%] w-[50%] h-[50%] bg-amber-600/10 blur-[150px] rounded-full"></div>
            <div class="absolute top-[30%] left-[-10%] w-[30%] h-[40%] bg-yellow-600/10 blur-[120px] rounded-full"></div>
            <!-- Dynamic Grid overlay -->
            <div class="absolute inset-0 bg-[linear-gradient(rgba(255,255,255,0.02)_1px,transparent_1px),linear-gradient(90deg,rgba(255,255,255,0.02)_1px,transparent_1px)] bg-[size:60px_60px] [mask-image:radial-gradient(ellipse_70%_70%_at_50%_50%,#000_60%,transparent_100%)] opacity-30"></div>
        </div>

        <!-- Header Section -->
        <div class="relative z-10 mx-auto flex flex-col items-center justify-center text-center max-w-full">
            <div class="inline-flex items-center gap-3 px-4 py-1.5 rounded-sm bg-orange-950/40 border border-orange-500/30 text-orange-300 text-sm font-bold tracking-[0.2em] uppercase mb-4 backdrop-blur-md shadow-[0_0_15px_rgba(249,115,22,0.2)]">
                <span class="relative flex h-2.5 w-2.5">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-orange-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-orange-500"></span>
                </span>
                Official Standings
            </div>
            
            <h1 class="text-5xl md:text-6xl lg:text-7xl font-black italic tracking-tighter uppercase leading-none drop-shadow-2xl text-slate-100 pb-6 whitespace-nowrap">
                {{ $tournament->name }}
                <span class="block mt-2 text-transparent bg-clip-text bg-gradient-to-r from-yellow-400 via-orange-400 to-amber-500 drop-shadow-[0_0_15px_rgba(245,158,11,0.5)]">
                    Overall Rankings
                </span>
            </h1>
        </div>

        @php
            $leftColumn = $rankings->take(8);
            $rightColumn = $rankings->slice(8);
        @endphp

        <!-- Main Panels Layout -->
        <div class="relative z-10 grid grid-cols-1 lg:grid-cols-2 gap-4 lg:gap-6 xl:gap-8 mx-auto w-full max-w-[95rem] flex-1 items-start">
            
            {{-- Left Side (1-8) --}}
            <div class="overflow-hidden rounded-sm border border-orange-900/60 bg-slate-900/80 backdrop-blur-2xl shadow-[0_10px_40px_rgba(0,0,0,0.8)] flex flex-col shadow-orange-900/20">
                <table class="w-full text-left border-collapse relative z-10">
                    <thead>
                        <tr class="bg-gradient-to-r from-slate-900/90 to-orange-950/60 text-slate-400 border-b border-orange-900/50 shadow-md">
                            <th class="w-16 px-4 py-4 text-[11px] lg:text-xs font-bold uppercase tracking-widest text-center">Rank</th>
                            <th class="px-4 py-4 text-[11px] lg:text-xs font-bold uppercase tracking-widest">Team</th>
                            <th class="w-16 px-4 py-4 text-[11px] lg:text-xs font-bold uppercase tracking-widest text-center" title="Matches Played">Played</th>
                            <th class="w-16 px-4 py-4 text-[11px] lg:text-xs font-bold uppercase tracking-widest text-center text-yellow-500" title="Winner Winner Chicken Dinners">WWCD</th>
                            <th class="w-16 px-4 py-4 text-[11px] lg:text-xs font-bold uppercase tracking-widest text-center">Kills</th>
                            <th class="w-24 px-4 py-4 text-[11px] lg:text-xs font-bold uppercase tracking-widest text-center text-orange-400 drop-shadow-[0_0_5px_rgba(249,115,22,0.5)] whitespace-nowrap">Total pts</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-orange-900/30">
                        @foreach ($leftColumn as $item)
                            @php $rank = $loop->iteration; @endphp
                            <tr class="animate-row group transition-all duration-300 hover:bg-orange-900/30 {{ $rank === 1 ? 'bg-gradient-to-r from-yellow-500/15 to-transparent border-l-4 border-l-yellow-400' : 'border-l-4 border-l-transparent' }}" style="animation-delay: {{ $loop->index * 0.1 }}s;">
                                <td class="px-4 py-3 font-black italic text-center w-16">
                                    @if($rank === 1)
                                        <span class="text-4xl lg:text-5xl drop-shadow-[0_0_10px_rgba(251,191,36,0.6)] text-transparent bg-clip-text bg-gradient-to-b from-yellow-200 to-yellow-500">1</span>
                                    @elseif($rank === 2)
                                        <span class="text-3xl lg:text-4xl drop-shadow-[0_0_10px_rgba(148,163,184,0.6)] text-transparent bg-clip-text bg-gradient-to-b from-slate-200 to-slate-400">2</span>
                                    @elseif($rank === 3)
                                        <span class="text-3xl lg:text-4xl drop-shadow-[0_0_10px_rgba(217,119,6,0.6)] text-transparent bg-clip-text bg-gradient-to-b from-orange-500 to-orange-700">3</span>
                                    @else
                                        <span class="text-xl lg:text-2xl text-slate-200">{{ $rank }}</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 w-full">
                                    <div class="flex items-center gap-3 lg:gap-4 whitespace-nowrap">
                                        <div class="relative w-10 h-10 lg:w-12 lg:h-12 flex-shrink-0 {{ $rank === 1 ? 'glow-active-gold rounded-sm' : 'rounded-sm' }}">
                                             <img src="{{ $item['team']->logo_image ? asset('storage/' . $item['team']->logo_image) : asset('img/defult_team_logo.png') }}" class="w-full h-full object-contain filter drop-shadow-md">
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="font-black uppercase tracking-tight text-lg lg:text-xl {{ $rank === 1 ? 'text-yellow-400' : 'text-slate-100' }} leading-tight">{{ $item['team']->name }}</span>
                                            <span class="text-[10px] lg:text-xs text-slate-400 font-semibold tracking-wider font-mono">{{ $item['team']->short_name }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-center font-bold text-base lg:text-lg text-slate-300 bg-black/20">{{ $item['matches_played'] }}</td>
                                <td class="px-4 py-3 text-center font-black text-lg lg:text-xl text-yellow-500 bg-black/10">{{ $item['total_wins'] }}</td>
                                <td class="px-4 py-3 text-center font-bold text-lg lg:text-xl text-slate-100">{{ $item['total_kills'] }}</td>
                                <td class="px-4 py-3 text-center font-black text-2xl lg:text-3xl {{ $rank === 1 ? 'text-yellow-400 drop-shadow-[0_0_10px_rgba(251,191,36,0.6)]' : 'text-orange-400 drop-shadow-[0_0_10px_rgba(249,115,22,0.5)]' }}">{{ $item['total_points'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Right Side (9+) --}}
            <div class="overflow-hidden rounded-sm border border-orange-900/60 bg-slate-900/80 backdrop-blur-2xl shadow-[0_10px_40px_rgba(0,0,0,0.8)] flex flex-col shadow-orange-900/10">
                <table class="w-full text-left border-collapse relative z-10">
                    <thead>
                        <tr class="bg-gradient-to-r from-slate-900/90 to-orange-950/60 text-slate-400 border-b border-orange-900/50 shadow-md">
                            <th class="w-14 px-2 py-4 text-[11px] lg:text-xs font-bold uppercase tracking-widest text-center">Rank</th>
                            <th class="px-4 py-4 text-[11px] lg:text-xs font-bold uppercase tracking-widest">Team</th>
                            <th class="w-14 px-2 py-4 text-[11px] lg:text-xs font-bold uppercase tracking-widest text-center" title="Matches Played">Pld</th>
                            <th class="w-14 px-2 py-4 text-[11px] lg:text-xs font-bold uppercase tracking-widest text-center text-yellow-500" title="WWCD">WWCD</th>
                            <th class="w-14 px-2 py-4 text-[11px] lg:text-xs font-bold uppercase tracking-widest text-center">Kills</th>
                            <th class="w-20 px-2 lg:px-4 py-4 text-[11px] lg:text-xs font-bold uppercase tracking-widest text-right text-orange-400 drop-shadow-[0_0_5px_rgba(249,115,22,0.5)] whitespace-nowrap">Total pts</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-orange-900/30">
                        @foreach ($rightColumn as $item)
                            @php $rank = $loop->iteration + 8; @endphp
                            <tr class="animate-row group transition-all duration-300 hover:bg-orange-900/30 border-l-4 border-l-transparent" style="animation-delay: {{ ($loop->iteration + 8) * 0.1 }}s;">
                                <td class="px-2 py-2.5 font-black italic text-lg lg:text-xl text-slate-200 text-center w-14">{{ $rank }}</td>
                                <td class="px-4 py-2.5 w-full">
                                    <div class="flex items-center gap-3 whitespace-nowrap">
                                        <div class="relative w-6 h-6 lg:w-8 lg:h-8 flex-shrink-0">
                                            <img src="{{ $item['team']->logo_image ? asset('storage/' . $item['team']->logo_image) : asset('img/defult_team_logo.png') }}" class="w-full h-full object-contain filter drop-shadow-sm">
                                        </div>
                                        <span class="font-bold uppercase tracking-tight text-slate-200 text-[13px] lg:text-sm">{{ $item['team']->name }}</span>
                                    </div>
                                </td>
                                <td class="px-2 py-2.5 text-center font-bold text-xs lg:text-sm text-slate-400 bg-black/20">{{ $item['matches_played'] }}</td>
                                <td class="px-2 py-2.5 text-center font-black text-[13px] lg:text-sm text-yellow-500 bg-black/10">{{ $item['total_wins'] }}</td>
                                <td class="px-2 py-2.5 text-center font-bold text-sm text-slate-200">{{ $item['total_kills'] }}</td>
                                <td class="px-2 lg:px-4 py-2.5 text-right font-black text-xl lg:text-2xl text-orange-400 drop-shadow-[0_0_5px_rgba(249,115,22,0.3)] group-hover:text-orange-300 transition-colors">{{ $item['total_points'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
        </div>

        <div class="mt-4 relative z-10 flex justify-between items-center opacity-60 px-4 w-full max-w-[95rem] mx-auto">
            <div class="h-px flex-1 bg-gradient-to-r from-transparent via-orange-500 to-transparent"></div>
            <div class="px-6 text-[10px] font-bold uppercase tracking-[0.5em] text-orange-300 drop-shadow-[0_0_5px_rgba(249,115,22,0.8)] glow-pulse">Official PUBG Standings</div>
            <div class="h-px flex-1 bg-gradient-to-r from-transparent via-orange-500 to-transparent"></div>
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
                            const newBody = doc.querySelector('body');
                            
                            if (newBody) {
                                document.querySelector('body').innerHTML = newBody.innerHTML;
                            }
                        });
                });
        });
    </script>
</x-base>
