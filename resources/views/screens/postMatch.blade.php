<x-base>
    <style>
        @keyframes slideInUp {
            from { opacity: 0; transform: translateY(30px) scale(0.98); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }
        .animate-row {
            animation: slideInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) backwards;
        }
        @keyframes pulseGlow {
            0%, 100% { box-shadow: 0 0 20px rgba(6, 182, 212, 0.5); }
            50% { box-shadow: 0 0 40px rgba(6, 182, 212, 0.8); }
        }
        .glow-active {
            animation: pulseGlow 2s infinite;
        }
    </style>
    <div class="w-full h-full p-4 md:p-8 font-sans text-slate-100 relative overflow-hidden z-10 flex flex-col">
        <!-- Premium Abstract Background Elements -->
        <div class="fixed top-0 left-0 w-full h-full z-0 pointer-events-none">
            <div class="absolute top-[-20%] left-[-10%] w-[50%] h-[50%] bg-cyan-600/20 blur-[120px] rounded-full"></div>
            <div class="absolute bottom-[-20%] right-[-10%] w-[40%] h-[60%] bg-blue-600/10 blur-[150px] rounded-full"></div>
            <!-- CRT/Grid overlay for esports vibe -->
            <div class="absolute inset-0 bg-[linear-gradient(rgba(255,255,255,0.03)_1px,transparent_1px),linear-gradient(90deg,rgba(255,255,255,0.03)_1px,transparent_1px)] bg-[size:40px_40px] [mask-image:radial-gradient(ellipse_60%_60%_at_50%_50%,#000_70%,transparent_100%)] opacity-30"></div>
        </div>

        <!-- Header Section -->
        <div class="relative z-10 mb-6 flex flex-col items-center justify-center text-center">
            <div class="inline-flex items-center gap-3 px-4 py-1.5 rounded-full bg-cyan-950/50 border border-cyan-500/30 text-cyan-400 text-sm font-bold tracking-[0.2em] uppercase mb-4 backdrop-blur-md shadow-[0_0_15px_rgba(6,182,212,0.2)]">
                <span class="relative flex h-2.5 w-2.5">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-cyan-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-cyan-500"></span>
                </span>
                Post Match Results
            </div>
            
            <h1 class="text-5xl md:text-7xl font-black italic tracking-tighter uppercase leading-none drop-shadow-2xl">
                {{ $activeMatch->tournament->name }}
                <span class="block mt-2 text-transparent bg-clip-text bg-linear-to-r from-cyan-300 via-cyan-400 to-blue-600 drop-shadow-[0_0_10px_rgba(6,182,212,0.4)]">
                    {{ $activeMatch->name }}
                </span>
            </h1>

            <div class="mt-4 px-6 py-2 bg-slate-900/60 backdrop-blur-xl border-y border-slate-700/50 flex gap-4 text-center items-center rounded-xl shadow-lg inline-block">
                <span class="text-xs uppercase tracking-[0.3em] text-slate-400 font-bold">Map</span>
                <span class="text-2xl font-black uppercase italic text-amber-400">{{ $activeMatch->map }}</span>
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
        <div class="relative z-10 grid grid-cols-1 lg:grid-cols-2 gap-6 xl:gap-10 mx-auto w-full max-w-7xl flex-1 items-start">
            
            {{-- Left Side (1-8) --}}
            <div class="overflow-hidden rounded-2xl border border-slate-700/50 bg-slate-900/40 backdrop-blur-2xl shadow-[0_8px_32px_rgba(0,0,0,0.5)] flex flex-col shadow-cyan-900/10">
                <table class="w-full text-left border-collapse table-fixed relative z-10">
                    <thead>
                        <tr class="bg-gradient-to-r from-slate-900/90 to-slate-800/90 text-slate-400 border-b border-cyan-900/30 shadow-md">
                            <th class="w-20 px-4 py-4 text-xs font-bold uppercase tracking-widest text-center">Rank</th>
                            <th class="px-4 py-4 text-xs font-bold uppercase tracking-widest">Team</th>
                            <th class="w-24 px-4 py-4 text-xs font-bold uppercase tracking-widest text-center">Kills</th>
                            <th class="w-28 px-4 py-4 text-xs font-bold uppercase tracking-widest text-center text-cyan-300 drop-shadow-[0_0_5px_rgba(6,182,212,0.5)]">Pts</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-700/30">
                        @foreach ($leftColumn as $match)
                            @php $rank = $loop->iteration; @endphp
                            <tr class="animate-row group transition-all duration-300 hover:bg-slate-800/60 {{ $rank === 1 ? 'bg-gradient-to-r from-amber-500/10 to-transparent border-l-4 border-l-amber-400' : 'border-l-4 border-l-transparent' }}" style="animation-delay: {{ $loop->index * 0.05 }}s;">
                                <td class="px-4 py-3 font-black italic text-center w-20">
                                    @if($rank === 1)
                                        <span class="text-5xl drop-shadow-[0_0_10px_rgba(251,191,36,0.5)] text-transparent bg-clip-text bg-gradient-to-b from-amber-200 to-amber-500">1</span>
                                    @elseif($rank === 2)
                                        <span class="text-4xl drop-shadow-[0_0_10px_rgba(148,163,184,0.5)] text-transparent bg-clip-text bg-gradient-to-b from-slate-200 to-slate-400">2</span>
                                    @elseif($rank === 3)
                                        <span class="text-4xl drop-shadow-[0_0_10px_rgba(217,119,6,0.5)] text-transparent bg-clip-text bg-gradient-to-b from-amber-600 to-amber-800">3</span>
                                    @else
                                        <span class="text-2xl text-slate-500">{{ $rank }}</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 w-full">
                                    <div class="flex items-center gap-4">
                                        <div class="relative w-12 h-12 flex-shrink-0 {{ $rank === 1 ? 'glow-active rounded-full' : '' }}">
                                             <img src="{{ $match->tournamentTeam->logo_image ? asset('storage/' . $match->tournamentTeam->logo_image) : asset('img/defult_team_logo.png') }}" class="w-full h-full object-contain filter drop-shadow-md">
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="font-black uppercase tracking-tight text-xl {{ $rank === 1 ? 'text-amber-400' : 'text-slate-100' }}">{{ $match->tournamentTeam->name }}</span>
                                            <span class="text-xs text-slate-400 font-semibold tracking-wider font-mono">{{ $match->tournamentTeam->short_name }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-center font-bold text-xl text-rose-400">{{ $match->kills }}</td>
                                <td class="px-4 py-3 text-center font-black text-3xl {{ $rank === 1 ? 'text-amber-400 drop-shadow-[0_0_10px_rgba(251,191,36,0.5)]' : 'text-cyan-400 drop-shadow-[0_0_8px_rgba(6,182,212,0.4)]' }}">{{ $match->points }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Right Side (9+) --}}
            <div class="overflow-hidden rounded-2xl border border-slate-700/50 bg-slate-900/40 backdrop-blur-2xl shadow-[0_8px_32px_rgba(0,0,0,0.5)] flex flex-col shadow-cyan-900/5">
                <table class="w-full text-left border-collapse table-fixed relative z-10">
                    <thead>
                        <tr class="bg-gradient-to-r from-slate-900/90 to-slate-800/90 text-slate-400 border-b border-cyan-900/30 shadow-md">
                            <th class="w-16 px-4 py-4 text-xs font-bold uppercase tracking-widest text-center">Rank</th>
                            <th class="px-4 py-4 text-xs font-bold uppercase tracking-widest">Team</th>
                            <th class="w-20 px-2 py-4 text-xs font-bold uppercase tracking-widest text-center">Kills</th>
                            <th class="w-28 px-4 py-4 text-xs font-bold uppercase tracking-widest text-right text-cyan-300 drop-shadow-[0_0_5px_rgba(6,182,212,0.5)]">Pts</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-700/30">
                        @foreach ($rightColumn as $match)
                            @php $rank = $loop->iteration + 8; @endphp
                            <tr class="animate-row group transition-all duration-300 hover:bg-slate-800/60 border-l-4 border-l-transparent" style="animation-delay: {{ ($loop->iteration + 8) * 0.05 }}s;">
                                <td class="px-4 py-2.5 font-black italic text-xl text-slate-500 text-center w-16">{{ $rank }}</td>
                                <td class="px-4 py-2.5 w-full">
                                    <div class="flex items-center gap-3">
                                        <div class="h-8 w-8 rounded bg-slate-800 border border-slate-600 flex items-center justify-center text-sm font-bold group-hover:border-cyan-500/50 group-hover:text-cyan-300 transition-colors text-slate-300 shadow-inner">
                                            {{ substr($match->tournamentTeam->name, 0, 1) }}
                                        </div>
                                        <span class="font-bold uppercase tracking-tight text-slate-200 text-sm truncate">{{ $match->tournamentTeam->name }}</span>
                                    </div>
                                </td>
                                <td class="px-2 py-2.5 text-center font-bold text-lg text-rose-500">{{ $match->kills }}</td>
                                <td class="px-4 py-2.5 text-right font-black text-2xl text-cyan-400 drop-shadow-[0_0_5px_rgba(6,182,212,0.3)] group-hover:text-cyan-300 transition-colors">{{ $match->points }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
        </div>

        <div class="mt-8 relative z-10 flex justify-between items-center opacity-40 px-4 w-full max-w-7xl mx-auto">
            <div class="h-px flex-1 bg-linear-to-r from-transparent via-cyan-500 to-transparent"></div>
            <div class="px-6 text-[10px] font-bold uppercase tracking-[0.5em] text-cyan-300 drop-shadow-[0_0_5px_rgba(6,182,212,0.8)] glow-pulse">Official Match Report</div>
            <div class="h-px flex-1 bg-linear-to-r from-transparent via-cyan-500 to-transparent"></div>
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