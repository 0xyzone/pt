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
        .glow-active {
            animation: pulseGlowGold 2.5s infinite;
        }
    </style>
    <div class="w-full h-full p-4 md:p-8 font-sans text-slate-100 relative overflow-hidden z-10 flex flex-col">
        <!-- Premium Abstract Background Elements -->
        <div class="fixed top-0 left-0 w-full h-full z-0 pointer-events-none">
            <div class="absolute top-[-20%] left-[-10%] w-[50%] h-[50%] bg-orange-600/20 blur-[120px] rounded-full"></div>
            <div class="absolute bottom-[-20%] right-[-10%] w-[40%] h-[60%] bg-yellow-600/10 blur-[150px] rounded-full"></div>
            <!-- CRT/Grid overlay for esports vibe -->
            <div class="absolute inset-0 bg-[linear-gradient(rgba(255,255,255,0.03)_1px,transparent_1px),linear-gradient(90deg,rgba(255,255,255,0.03)_1px,transparent_1px)] bg-[size:40px_40px] [mask-image:radial-gradient(ellipse_60%_60%_at_50%_50%,#000_70%,transparent_100%)] opacity-30"></div>
        </div>

        <!-- Header Section -->
        <div class="relative z-10 mx-auto flex flex-col items-center justify-center text-center max-w-full">
            <div class="inline-flex items-center gap-3 px-4 py-1.5 rounded-sm bg-orange-950/50 border border-orange-500/30 text-orange-400 text-sm font-bold tracking-[0.2em] uppercase mb-4 backdrop-blur-md shadow-[0_0_15px_rgba(249,115,22,0.2)]">
                <span class="relative flex h-2.5 w-2.5">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-orange-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-orange-500"></span>
                </span>
                Match Result
            </div>
            
            <h1 class="text-5xl md:text-6xl lg:text-7xl font-black italic tracking-tighter uppercase leading-none drop-shadow-2xl text-slate-100 whitespace-nowrap">
                {{ $activeMatch->tournament->name }}
                <span class="block mt-2 text-transparent bg-clip-text bg-gradient-to-r from-yellow-300 via-orange-400 to-amber-600 drop-shadow-[0_0_10px_rgba(249,115,22,0.4)]">
                    {{ $activeMatch->name }}
                </span>
            </h1>

            <div class="mt-4 px-6 py-2 bg-slate-900/60 backdrop-blur-xl border-y border-slate-700/50 flex gap-4 text-center items-center rounded-sm shadow-lg inline-block whitespace-nowrap">
                <span class="text-[10px] lg:text-xs uppercase tracking-[0.3em] text-slate-400 font-bold">Map</span>
                <span class="text-xl lg:text-2xl font-black uppercase italic text-yellow-500">{{ $activeMatch->map }}</span>
            </div>
        </div>

        @php
            $allStats = $activeMatch->matchStats->sortBy(function($stat) {
                return $stat->placement == 0 ? 999 : $stat->placement;
            })->values();
            $leftColumn = $allStats->take(8);
            $rightColumn = $allStats->slice(8);
        @endphp

        <!-- Main Panels Layout -->
        <div class="relative z-10 grid grid-cols-1 lg:grid-cols-2 gap-4 lg:gap-6 xl:gap-8 mx-auto w-full max-w-[95rem] flex-1 items-start mt-6">
            
            {{-- Left Side (1-8) --}}
            <div class="overflow-hidden rounded-sm border border-slate-700/50 bg-slate-900/60 backdrop-blur-2xl shadow-[0_8px_32px_rgba(0,0,0,0.8)] flex flex-col shadow-orange-900/10">
                <table class="w-full text-left border-collapse relative z-10">
                    <thead>
                        <tr class="bg-gradient-to-r from-slate-900/90 to-slate-800/90 text-slate-400 border-b border-orange-900/30 shadow-md">
                            <th class="w-16 px-4 py-4 text-[11px] lg:text-xs font-bold uppercase tracking-widest text-center">Rank</th>
                            <th class="px-4 py-4 text-[11px] lg:text-xs font-bold uppercase tracking-widest">Team</th>
                            <th class="w-20 px-4 py-4 text-[11px] lg:text-xs font-bold uppercase tracking-widest text-center">Elims</th>
                            <th class="w-20 px-4 py-4 text-[11px] lg:text-xs font-bold uppercase tracking-widest text-center">Place Pts</th>
                            <th class="w-24 px-4 py-4 text-[11px] lg:text-xs font-bold uppercase tracking-widest text-center text-yellow-500 drop-shadow-[0_0_5px_rgba(251,191,36,0.5)] whitespace-nowrap">Pts</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-700/30">
                        @foreach ($leftColumn as $match)
                            @php $rank = $loop->iteration; @endphp
                            <tr class="animate-row group transition-all duration-300 hover:bg-slate-800/80 {{ $rank === 1 ? 'bg-gradient-to-r from-yellow-500/10 to-transparent border-l-4 border-l-yellow-400' : 'border-l-4 border-l-transparent' }}" style="animation-delay: {{ $loop->index * 0.1 }}s;">
                                <td class="px-4 py-3 font-black italic text-center w-16">
                                    @if($rank === 1)
                                        <span class="text-4xl lg:text-5xl drop-shadow-[0_0_10px_rgba(251,191,36,0.5)] text-transparent bg-clip-text bg-gradient-to-b from-yellow-200 to-yellow-500">1</span>
                                    @elseif($rank === 2)
                                        <span class="text-3xl lg:text-4xl drop-shadow-[0_0_10px_rgba(148,163,184,0.5)] text-transparent bg-clip-text bg-gradient-to-b from-slate-200 to-slate-400">2</span>
                                    @elseif($rank === 3)
                                        <span class="text-3xl lg:text-4xl drop-shadow-[0_0_10px_rgba(217,119,6,0.5)] text-transparent bg-clip-text bg-gradient-to-b from-orange-500 to-orange-700">3</span>
                                    @else
                                        <span class="text-xl lg:text-2xl text-slate-200">{{ $rank }}</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 w-full">
                                    <div class="flex items-center gap-3 lg:gap-4 whitespace-nowrap">
                                        <div class="relative w-10 h-10 lg:w-12 lg:h-12 flex-shrink-0 {{ $rank === 1 ? 'glow-active rounded-sm' : 'rounded-sm' }}">
                                             <img src="{{ $match->tournamentTeam->logo_image ? asset('storage/' . $match->tournamentTeam->logo_image) : asset('img/defult_team_logo.png') }}" class="w-full h-full object-contain filter drop-shadow-md">
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="font-black uppercase tracking-tight text-lg lg:text-xl {{ $rank === 1 ? 'text-yellow-400' : 'text-slate-100' }} leading-tight">{{ $match->tournamentTeam->name }}</span>
                                            <span class="text-[10px] lg:text-xs text-slate-400 font-semibold tracking-wider font-mono">{{ $match->tournamentTeam->short_name }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-center font-bold text-lg lg:text-xl text-slate-100 bg-black/10">{{ $match->kills }}</td>
                                <td class="px-4 py-3 text-center font-bold text-lg lg:text-xl text-slate-300">{{ $match->points - $match->kills }}</td>
                                <td class="px-4 py-3 text-center font-black text-2xl lg:text-3xl {{ $rank === 1 ? 'text-yellow-400 drop-shadow-[0_0_10px_rgba(251,191,36,0.5)]' : 'text-orange-400 drop-shadow-[0_0_8px_rgba(249,115,22,0.4)]' }}">{{ $match->points }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Right Side (9+) --}}
            <div class="overflow-hidden rounded-sm border border-slate-700/50 bg-slate-900/60 backdrop-blur-2xl shadow-[0_8px_32px_rgba(0,0,0,0.8)] flex flex-col shadow-orange-900/5">
                <table class="w-full text-left border-collapse relative z-10">
                    <thead>
                        <tr class="bg-gradient-to-r from-slate-900/90 to-slate-800/90 text-slate-400 border-b border-orange-900/30 shadow-md">
                            <th class="w-14 px-2 py-4 text-[11px] lg:text-xs font-bold uppercase tracking-widest text-center">Rank</th>
                            <th class="px-4 py-4 text-[11px] lg:text-xs font-bold uppercase tracking-widest">Team</th>
                            <th class="w-16 px-2 py-4 text-[11px] lg:text-xs font-bold uppercase tracking-widest text-center">Elims</th>
                            <th class="w-16 px-2 py-4 text-[11px] lg:text-xs font-bold uppercase tracking-widest text-center">Place Pts</th>
                            <th class="w-20 px-2 lg:px-4 py-4 text-[11px] lg:text-xs font-bold uppercase tracking-widest text-right text-yellow-500 drop-shadow-[0_0_5px_rgba(251,191,36,0.5)] whitespace-nowrap">Pts</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-700/30">
                        @foreach ($rightColumn as $match)
                            @php $rank = $loop->iteration + 8; @endphp
                            <tr class="animate-row group transition-all duration-300 hover:bg-slate-800/80 border-l-4 border-l-transparent" style="animation-delay: {{ ($loop->iteration + 8) * 0.1 }}s;">
                                <td class="px-2 py-2.5 font-black italic text-lg lg:text-xl text-slate-200 text-center w-14">{{ $rank }}</td>
                                <td class="px-4 py-2.5 w-full">
                                    <div class="flex items-center gap-3 whitespace-nowrap">
                                        <div class="relative w-6 h-6 lg:w-8 lg:h-8 flex-shrink-0">
                                            <img src="{{ $match->tournamentTeam->logo_image ? asset('storage/' . $match->tournamentTeam->logo_image) : asset('img/defult_team_logo.png') }}" class="w-full h-full object-contain filter drop-shadow-sm">
                                        </div>
                                        <span class="font-bold uppercase tracking-tight text-slate-200 text-[13px] lg:text-sm">{{ $match->tournamentTeam->name }}</span>
                                    </div>
                                </td>
                                <td class="px-2 py-2.5 text-center font-bold text-sm lg:text-lg text-slate-200 bg-black/10">{{ $match->kills }}</td>
                                <td class="px-2 py-2.5 text-center font-bold text-sm lg:text-lg text-slate-400">{{ $match->points - $match->kills }}</td>
                                <td class="px-2 lg:px-4 py-2.5 text-right font-black text-xl lg:text-2xl text-orange-400 drop-shadow-[0_0_5px_rgba(249,115,22,0.3)] group-hover:text-orange-300 transition-colors">{{ $match->points }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
        </div>

        <div class="mt-4 relative z-10 flex justify-between items-center opacity-60 px-4 w-full max-w-[95rem] mx-auto">
            <div class="h-px flex-1 bg-gradient-to-r from-transparent via-orange-500 to-transparent"></div>
            <div class="px-6 text-[10px] font-bold uppercase tracking-[0.5em] text-orange-300 drop-shadow-[0_0_5px_rgba(249,115,22,0.8)] glow-pulse">Official PUBG Result</div>
            <div class="h-px flex-1 bg-gradient-to-r from-transparent via-orange-500 to-transparent"></div>
        </div>
    </div>
    

</x-base>