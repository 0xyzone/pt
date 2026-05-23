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

        @keyframes slideInUp {
            from { opacity: 0; transform: translateY(30px) scale(0.98); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }
        .animate-row {
            animation: slideInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) both;
        }
        
        .pubg-skew {
            transform: skewX(-12deg);
        }
        .pubg-unskew {
            transform: skewX(12deg);
        }

        .glass-panel {
            background: rgba(8, 12, 24, 0.78);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(250, 204, 21, 0.15);
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.7);
        }

        /* Tech bracket corner designs */
        .bracket-corner::before,
        .bracket-corner::after,
        .bracket-inner::before,
        .bracket-inner::after {
            content: '';
            position: absolute;
            width: 14px;
            height: 14px;
            border-color: rgba(250, 204, 21, 0.75);
            border-style: solid;
            pointer-events: none;
        }
        .bracket-corner::before { top: -1px; left: -1px; border-width: 2.5px 0 0 2.5px; }
        .bracket-corner::after { top: -1px; right: -1px; border-width: 2.5px 2.5px 0 0; }
        .bracket-inner::before { bottom: -1px; left: -1px; border-width: 0 0 2.5px 2.5px; }
        .bracket-inner::after { bottom: -1px; right: -1px; border-width: 0 2.5px 2.5px 0; }

        @keyframes scanline {
            0% { transform: translateY(-100%); }
            100% { transform: translateY(100%); }
        }
        .scan-sweep {
            position: absolute;
            inset: 0;
            background: linear-gradient(to bottom, transparent 45%, rgba(250, 204, 21, 0.04) 50%, transparent 55%);
            animation: scanline 10s infinite linear;
            pointer-events: none;
        }

        .text-glow-gold {
            text-shadow: 0 0 10px rgba(250, 204, 21, 0.6), 0 0 20px rgba(250, 204, 21, 0.3);
        }
    </style>

    <div class="w-full h-full p-8 font-esports text-slate-100 relative overflow-hidden z-10 flex flex-col justify-between">
        
        {{-- High-tech background grid lines --}}
        <div class="absolute inset-0 z-0 pointer-events-none opacity-20">
            <div class="absolute inset-0 bg-[linear-gradient(rgba(255,255,255,0.015)_1px,transparent_1px),linear-gradient(90deg,rgba(255,255,255,0.015)_1px,transparent_1px)] bg-[size:40px_40px]"></div>
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_center,transparent_30%,#020617_90%)]"></div>
            <div class="absolute top-[10%] left-[20%] w-[500px] h-[500px] bg-amber-500/5 blur-[120px] rounded-full"></div>
            <div class="absolute bottom-[10%] right-[20%] w-[600px] h-[600px] bg-orange-600/5 blur-[150px] rounded-full"></div>
        </div>

        {{-- Dynamic scanning sweep --}}
        <div class="scan-sweep z-0"></div>

        {{-- TOP: Header Branding HUD --}}
        <div class="relative z-10 w-full max-w-[1800px] mx-auto flex justify-between items-end border-b border-yellow-400/20 pb-4">
            <div class="flex items-center gap-6">
                <div class="bg-slate-950/90 border border-yellow-400/40 p-2.5 rounded-lg shadow-lg">
                    <img src="{{ $activeMatch->tournament->logo_image ? asset('storage/' . $activeMatch->tournament->logo_image) : asset('img/defult_team_logo.png') }}"
                        class="w-14 h-14 object-contain">
                </div>
                <div class="flex flex-col text-left">
                    <span class="text-lg font-bold text-yellow-400 tracking-[0.35em] uppercase leading-none">MATCH COMPLETED</span>
                    <h1 class="text-4xl font-black italic tracking-tight uppercase mt-1 leading-none text-slate-100">
                        {{ $activeMatch->tournament->name }}
                    </h1>
                </div>
            </div>

            <div class="flex gap-4 items-center">
                <div class="pubg-skew bg-slate-900/60 border border-slate-700/50 px-5 py-1.5 flex gap-3 text-center items-center shadow-lg">
                    <span class="pubg-unskew text-xs uppercase tracking-[0.25em] text-slate-400 font-bold">Map</span>
                    <span class="pubg-unskew text-xl font-black uppercase italic text-yellow-500 font-esports leading-none">{{ $activeMatch->map }}</span>
                </div>
                <div class="flex flex-col items-end text-right pl-4">
                    <span class="text-[11px] font-bold text-slate-500 tracking-[0.3em] uppercase leading-none">SYS // RESULT_SUMMARY</span>
                    <div class="text-4xl font-black text-transparent bg-clip-text bg-gradient-to-r from-yellow-400 via-orange-400 to-amber-500 drop-shadow-[0_0_10px_rgba(245,158,11,0.3)] uppercase tracking-wider mt-1 leading-none italic">
                        {{ $activeMatch->name }}
                    </div>
                </div>
            </div>
        </div>

        @php
            $allStats = $activeMatch->matchStats->sortBy(function($stat) {
                return $stat->placement == 0 ? 999 : $stat->placement;
            })->values();

            $winner = $allStats->firstWhere('placement', 1) ?? $allStats->first();
            $remainingStats = $allStats->reject(fn($s) => $s->id === ($winner->id ?? null))->values();
            
            $leftColumn = $remainingStats->take(8);
            $rightColumn = $remainingStats->slice(8)->take(8);
        @endphp

        {{-- MIDDLE: Split Dashboard (Spotlight Left, Dual Leaderboard Right) --}}
        <div class="relative z-10 w-full max-w-[1800px] mx-auto grid grid-cols-12 gap-8 my-6 flex-1 items-stretch">
            
            {{-- Winner Spotlight (Left 4 cols) --}}
            <div class="col-span-4 flex flex-col">
                <div class="glass-panel flex-1 rounded-xl p-8 flex flex-col items-center justify-between border-t-8 border-yellow-400 relative shadow-2xl overflow-hidden">
                    <div class="absolute top-3 left-4 text-[9px] font-bold text-slate-500 tracking-widest uppercase">MATCH_WINNER // WWCD</div>
                    <div class="absolute bottom-2 right-4 text-[9px] font-bold text-slate-600 tracking-widest uppercase font-mono">0x48EF1A</div>

                    {{-- Winner Title Skew Badge --}}
                    <div class="bg-gradient-to-r from-yellow-400 to-amber-500 px-12 py-2 pubg-skew shadow-lg border border-white/20 mt-4">
                        <span class="pubg-unskew block text-black font-black uppercase text-xl tracking-[0.2em] italic leading-none">Chicken Dinner</span>
                    </div>

                    {{-- Large Logo --}}
                    <div class="w-40 h-40 bg-slate-950/80 p-4 border border-yellow-400/40 rounded-full flex items-center justify-center shadow-[0_0_35px_rgba(250,204,21,0.2)] my-8">
                        <img src="{{ $winner && $winner->tournamentTeam->logo_image ? asset('storage/' . $winner->tournamentTeam->logo_image) : asset('img/defult_team_logo.png') }}" class="max-w-full max-h-full object-contain filter drop-shadow-md">
                    </div>

                    {{-- Team Name --}}
                    <div class="text-center w-full">
                        <h2 class="text-5xl font-black italic uppercase text-slate-100 tracking-widest leading-none text-glow-gold">
                            {{ $winner ? $winner->tournamentTeam->name : 'No Winner Yet' }}
                        </h2>
                        <span class="text-lg text-slate-500 font-bold tracking-widest uppercase font-body-esports mt-2 block">
                            {{ $winner ? $winner->tournamentTeam->short_name : '' }}
                        </span>
                    </div>

                    {{-- Stats Summary --}}
                    <div class="flex gap-12 mt-6 w-full justify-center border-t border-slate-800/80 pt-6">
                        <div class="flex flex-col items-center border-r border-slate-800/80 pr-8">
                            <span class="text-yellow-400 text-xs font-black uppercase tracking-[0.25em] mb-1">Elims</span>
                            <span class="text-4xl font-black text-white font-mono" style="font-family: 'Orbitron', sans-serif;">{{ $winner ? $winner->kills : 0 }}</span>
                        </div>
                        <div class="flex flex-col items-center border-r border-slate-800/80 pr-8">
                            <span class="text-yellow-400 text-xs font-black uppercase tracking-[0.25em] mb-1">Place Pts</span>
                            <span class="text-4xl font-black text-white font-mono" style="font-family: 'Orbitron', sans-serif;">{{ $winner ? ($winner->points - $winner->kills) : 0 }}</span>
                        </div>
                        <div class="flex flex-col items-center">
                            <span class="text-yellow-400 text-xs font-black uppercase tracking-[0.25em] mb-1">Total Pts</span>
                            <span class="text-4xl font-black text-yellow-500 font-mono" style="font-family: 'Orbitron', sans-serif;">{{ $winner ? $winner->points : 0 }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Leaderboard 2-16 (Right 8 cols) --}}
            <div class="col-span-8 grid grid-cols-2 gap-6 items-start">
                
                {{-- Column Left (Ranks 2-9) --}}
                <div class="flex flex-col gap-2">
                    {{-- Table header --}}
                    <div class="pubg-skew bg-slate-950/90 border-b border-yellow-400/40 px-4 py-2 flex items-center gap-4 text-xs font-black uppercase tracking-wider text-slate-400 shadow-md">
                        <div class="pubg-unskew flex items-center w-full gap-3">
                            <span class="w-10 text-center">Rank</span>
                            <span class="w-8 shrink-0"></span>
                            <span class="flex-1 text-left text-slate-200">Team Name</span>
                            <span class="w-12 text-center">Elims</span>
                            <span class="w-16 text-center">Place Pts</span>
                            <span class="w-14 text-right text-orange-400">Total Pts</span>
                        </div>
                    </div>

                    {{-- Rows --}}
                    @foreach ($leftColumn as $match)
                        @php $rank = $loop->iteration + 1; @endphp
                        <div class="animate-row pubg-skew glass-panel hover:bg-slate-900/90 border border-slate-800 hover:border-yellow-400/50 px-4 py-2 flex items-center gap-4 transition-all duration-300 hover:scale-[1.01] {{ $rank === 2 ? 'border-l-4 border-l-slate-300' : ($rank === 3 ? 'border-l-4 border-l-orange-500' : '') }}" 
                             style="animation-delay: {{ $loop->index * 0.08 }}s;">
                            <div class="pubg-unskew flex items-center w-full gap-3">
                                
                                {{-- Rank --}}
                                <div class="w-10 flex justify-center items-center font-black italic text-xl">
                                    @if($rank === 2)
                                        <span class="text-2xl text-slate-300 drop-shadow-[0_0_5px_rgba(148,163,184,0.5)]">02</span>
                                    @elseif($rank === 3)
                                        <span class="text-2xl text-orange-500 drop-shadow-[0_0_5px_rgba(217,119,6,0.5)]">03</span>
                                    @else
                                        <span class="text-lg text-slate-400 font-semibold font-mono">{{ str_pad($rank, 2, '0', STR_PAD_LEFT) }}</span>
                                    @endif
                                </div>

                                {{-- Logo --}}
                                <div class="w-8 h-8 bg-slate-950/80 p-1 border border-slate-800 rounded flex-shrink-0 flex items-center justify-center">
                                    <img src="{{ $match->tournamentTeam->logo_image ? asset('storage/' . $match->tournamentTeam->logo_image) : asset('img/defult_team_logo.png') }}" class="w-full h-full object-contain filter drop-shadow-md">
                                </div>

                                {{-- Team Full Name (No rigid cuts) --}}
                                <div class="flex-1 flex flex-col justify-center min-w-0 pr-2">
                                    <span class="font-black uppercase tracking-tight text-lg text-slate-100 leading-none whitespace-normal break-words">
                                        {{ $match->tournamentTeam->name }}
                                    </span>
                                    <span class="text-[10px] text-slate-500 font-semibold tracking-wider font-body-esports mt-0.5 uppercase leading-none">
                                        {{ $match->tournamentTeam->short_name }}
                                    </span>
                                </div>

                                {{-- Stats --}}
                                <span class="w-12 text-center font-bold text-base text-slate-300 font-mono">{{ $match->kills }}</span>
                                <span class="w-16 text-center font-bold text-base text-slate-450 font-mono">{{ $match->points - $match->kills }}</span>
                                <span class="w-14 text-right font-black text-2xl text-orange-400 font-mono leading-none">{{ $match->points }}</span>

                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Column Right (Ranks 10+) --}}
                <div class="flex flex-col gap-2">
                    {{-- Table header --}}
                    <div class="pubg-skew bg-slate-950/90 border-b border-yellow-400/40 px-4 py-2 flex items-center gap-4 text-xs font-black uppercase tracking-wider text-slate-400 shadow-md">
                        <div class="pubg-unskew flex items-center w-full gap-3">
                            <span class="w-10 text-center">Rank</span>
                            <span class="w-8 shrink-0"></span>
                            <span class="flex-1 text-left text-slate-200">Team Name</span>
                            <span class="w-12 text-center">Elims</span>
                            <span class="w-16 text-center">Place Pts</span>
                            <span class="w-14 text-right text-orange-400">Total Pts</span>
                        </div>
                    </div>

                    {{-- Rows --}}
                    @foreach ($rightColumn as $match)
                        @php $rank = $loop->iteration + 9; @endphp
                        <div class="animate-row pubg-skew glass-panel hover:bg-slate-900/90 border border-slate-800 hover:border-yellow-400/50 px-4 py-2 flex items-center gap-4 transition-all duration-300 hover:scale-[1.01]" 
                             style="animation-delay: {{ ($loop->iteration + 9) * 0.08 }}s;">
                            <div class="pubg-unskew flex items-center w-full gap-3">
                                
                                {{-- Rank --}}
                                <div class="w-10 flex justify-center items-center font-black italic text-lg text-slate-400 font-mono">
                                    {{ str_pad($rank, 2, '0', STR_PAD_LEFT) }}
                                </div>

                                {{-- Logo --}}
                                <div class="w-8 h-8 bg-slate-950/80 p-1 border border-slate-800 rounded flex-shrink-0 flex items-center justify-center">
                                    <img src="{{ $match->tournamentTeam->logo_image ? asset('storage/' . $match->tournamentTeam->logo_image) : asset('img/defult_team_logo.png') }}" class="w-full h-full object-contain filter drop-shadow-md">
                                </div>

                                {{-- Team Full Name --}}
                                <div class="flex-1 flex flex-col justify-center min-w-0 pr-2">
                                    <span class="font-black uppercase tracking-tight text-lg text-slate-100 leading-none whitespace-normal break-words">
                                        {{ $match->tournamentTeam->name }}
                                    </span>
                                    <span class="text-[10px] text-slate-500 font-semibold tracking-wider font-body-esports mt-0.5 uppercase leading-none">
                                        {{ $match->tournamentTeam->short_name }}
                                    </span>
                                </div>

                                {{-- Stats --}}
                                <span class="w-12 text-center font-bold text-base text-slate-300 font-mono">{{ $match->kills }}</span>
                                <span class="w-16 text-center font-bold text-base text-slate-450 font-mono">{{ $match->points - $match->kills }}</span>
                                <span class="w-14 text-right font-black text-2xl text-orange-400 font-mono leading-none">{{ $match->points }}</span>

                            </div>
                        </div>
                    @endforeach
                </div>

            </div>

        </div>

        {{-- BOTTOM: High-tech footer coordinate bar --}}
        <div class="relative z-10 w-full max-w-[1800px] mx-auto flex items-center justify-between text-xs font-bold text-slate-500 tracking-[0.4em] uppercase pt-2 border-t border-slate-800/60">
            <span>SYS_LOC // 0x58BF12</span>
            <div class="h-[1px] w-40 bg-linear-to-r from-transparent via-yellow-400/30 to-transparent"></div>
            <span class="text-yellow-400/70 font-black drop-shadow-[0_0_5px_rgba(250,204,21,0.3)]">Official match summary overlay</span>
            <div class="h-[1px] w-40 bg-linear-to-r from-transparent via-yellow-400/30 to-transparent"></div>
            <span>SYS_VER_3.5.2</span>
        </div>

    </div>

    <script type="module">
        document.addEventListener("DOMContentLoaded", function () {
            Echo.channel('user-screens.{{ $activeMatch->tournament->user_id }}')
                .listen('.RefreshScreens', (e) => {
                    window.location.reload();
                })
                .listen('.TournamentMatchUpdated', (e) => {
                    window.location.reload();
                });

            Echo.channel('active-match.{{ $activeMatch->id }}')
                .listen('.MatchStatsUpdated', (e) => {
                    window.location.reload();
                });
        });
    </script>
</x-base>