<x-base>
    {{-- High-End Typography & Theme Imports --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700;900&family=Rajdhani:wght@600;700;900&display=swap" rel="stylesheet">

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
                    <img src="{{ $tournament->logo_image ? asset('storage/' . $tournament->logo_image) : asset('img/defult_team_logo.png') }}"
                        class="w-14 h-14 object-contain">
                </div>
                <div class="flex flex-col text-left">
                    <span class="text-lg font-bold text-yellow-400 tracking-[0.35em] uppercase leading-none">OFFICIAL LEADERBOARD</span>
                    <h1 class="text-4xl font-black italic tracking-tight uppercase mt-1 leading-none text-slate-100">
                        {{ $tournament->name }}
                    </h1>
                </div>
            </div>

            <div class="flex flex-col items-end text-right">
                <span class="text-[11px] font-bold text-slate-500 tracking-[0.3em] uppercase leading-none">SYS // STANDINGS_OVERALL</span>
                <div class="text-4xl font-black text-transparent bg-clip-text bg-gradient-to-r from-yellow-400 via-orange-400 to-amber-500 drop-shadow-[0_0_10px_rgba(245,158,11,0.3)] uppercase tracking-wider mt-1 leading-none italic">
                    Overall Rankings
                </div>
            </div>
        </div>

        @php
            $leftColumn = $rankings->take(8);
            $rightColumn = $rankings->slice(8)->take(8);
        @endphp

        {{-- MIDDLE: Dynamic Dual Columns Standing Board --}}
        <div class="relative z-10 w-full max-w-[1800px] mx-auto grid grid-cols-2 gap-8 my-6 flex-1 items-start">
            
            {{-- Column Left (1-8) --}}
            <div class="flex flex-col gap-2.5">
                {{-- Flex Table Header --}}
                <div class="pubg-skew bg-slate-950/90 border-b border-yellow-400/40 px-4 py-2.5 flex items-center gap-4 text-xs font-black uppercase tracking-wider text-slate-400 shadow-md">
                    <div class="pubg-unskew flex items-center w-full gap-4">
                        <span class="w-12 text-center">Rank</span>
                        <span class="w-10 shrink-0"></span>
                        <span class="flex-1 text-left text-slate-200">Team Name</span>
                        <span class="w-14 text-center">Played</span>
                        <span class="w-16 text-center text-yellow-400">WWCD</span>
                        <span class="w-16 text-center">Elims</span>
                        <span class="w-16 text-center">Place Pts</span>
                        <span class="w-20 text-right text-orange-400">Total Pts</span>
                    </div>
                </div>

                {{-- Rows --}}
                @foreach ($leftColumn as $item)
                    @php $rank = $loop->iteration; @endphp
                    <div class="animate-row glass-panel hover:bg-slate-900/90 border border-slate-800 hover:border-yellow-400/50 px-4 py-3 flex items-center gap-4 transition-all duration-300 hover:scale-[1.01] {{ $rank === 1 ? 'border-l-4 border-l-yellow-400 shadow-[0_0_20px_rgba(251,191,36,0.15)]' : '' }}" 
                         style="animation-delay: {{ $loop->index * 0.08 }}s;">
                        <div class="flex items-center w-full gap-4">
                            
                            {{-- Rank Badging --}}
                            <div class="w-12 flex justify-center items-center font-black italic text-2xl">
                                @if($rank === 1)
                                    <span class="text-4xl text-transparent bg-clip-text bg-gradient-to-b from-yellow-200 to-yellow-500 drop-shadow-[0_0_8px_rgba(251,191,36,0.6)]">01</span>
                                @elseif($rank === 2)
                                    <span class="text-3xl text-transparent bg-clip-text bg-gradient-to-b from-slate-200 to-slate-400 drop-shadow-[0_0_8px_rgba(148,163,184,0.6)]">02</span>
                                @elseif($rank === 3)
                                    <span class="text-3xl text-transparent bg-clip-text bg-gradient-to-b from-orange-400 to-orange-700 drop-shadow-[0_0_8px_rgba(217,119,6,0.6)]">03</span>
                                @else
                                    <span class="text-2xl text-slate-300 font-semibold font-mono">{{ str_pad($rank, 2, '0', STR_PAD_LEFT) }}</span>
                                @endif
                            </div>

                            {{-- Logo --}}
                            <div class="w-10 h-10 bg-slate-950/80 p-1 border border-slate-800 rounded flex-shrink-0 flex items-center justify-center">
                                <img src="{{ $item['team']->logo_image ? asset('storage/' . $item['team']->logo_image) : asset('img/defult_team_logo.png') }}" class="w-full h-full object-contain filter drop-shadow-md">
                            </div>

                            {{-- Team Full & Short Name (Grows dynamically - NO truncation cutoffs!) --}}
                            <div class="flex-1 flex flex-col justify-center min-w-0 pr-4">
                                <span class="font-black uppercase tracking-tight text-xl lg:text-2xl {{ $rank === 1 ? 'text-yellow-400' : 'text-slate-100' }} leading-none whitespace-normal break-words">
                                    {{ $item['team']->name }}
                                </span>
                                <span class="text-xs text-slate-500 font-semibold tracking-wider font-body-esports mt-0.5 uppercase leading-none">
                                    {{ $item['team']->short_name }}
                                </span>
                            </div>

                            {{-- Stats --}}
                            <span class="w-14 text-center font-bold text-lg text-slate-400 font-mono">{{ $item['matches_played'] }}</span>
                            <span class="w-16 text-center font-black text-xl text-yellow-500 font-mono">{{ $item['total_wins'] }}</span>
                            <span class="w-16 text-center font-bold text-lg text-slate-300 font-mono">{{ $item['total_kills'] }}</span>
                            <span class="w-16 text-center font-bold text-lg text-slate-400 font-mono">{{ $item['total_placement_points'] }}</span>
                            <span class="w-20 text-right font-black text-3xl {{ $rank === 1 ? 'text-yellow-400 drop-shadow-[0_0_8px_rgba(251,191,36,0.6)]' : 'text-orange-400 drop-shadow-[0_0_8px_rgba(249,115,22,0.4)]' }} font-mono leading-none">{{ $item['total_points'] }}</span>

                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Column Right (9-16) --}}
            <div class="flex flex-col gap-2.5">
                {{-- Flex Table Header --}}
                <div class="pubg-skew bg-slate-950/90 border-b border-yellow-400/40 px-4 py-2.5 flex items-center gap-4 text-xs font-black uppercase tracking-wider text-slate-400 shadow-md">
                    <div class="pubg-unskew flex items-center w-full gap-4">
                        <span class="w-12 text-center">Rank</span>
                        <span class="w-10 shrink-0"></span>
                        <span class="flex-1 text-left text-slate-200">Team Name</span>
                        <span class="w-14 text-center">Played</span>
                        <span class="w-16 text-center text-yellow-400">WWCD</span>
                        <span class="w-16 text-center">Elims</span>
                        <span class="w-16 text-center">Place Pts</span>
                        <span class="w-20 text-right text-orange-400">Total Pts</span>
                    </div>
                </div>

                {{-- Rows --}}
                @foreach ($rightColumn as $item)
                    @php $rank = $loop->iteration + 8; @endphp
                    <div class="animate-row glass-panel hover:bg-slate-900/90 border border-slate-800 hover:border-yellow-400/50 px-4 py-3 flex items-center gap-4 transition-all duration-300 hover:scale-[1.01]" 
                         style="animation-delay: {{ ($loop->iteration + 8) * 0.08 }}s;">
                        <div class="flex items-center w-full gap-4">
                            
                            {{-- Rank --}}
                            <div class="w-12 flex justify-center items-center font-black italic text-2xl text-slate-400 font-mono">
                                {{ str_pad($rank, 2, '0', STR_PAD_LEFT) }}
                            </div>

                            {{-- Logo --}}
                            <div class="w-10 h-10 bg-slate-950/80 p-1 border border-slate-800 rounded flex-shrink-0 flex items-center justify-center">
                                <img src="{{ $item['team']->logo_image ? asset('storage/' . $item['team']->logo_image) : asset('img/defult_team_logo.png') }}" class="w-full h-full object-contain filter drop-shadow-md">
                            </div>

                            {{-- Team Full & Short Name --}}
                            <div class="flex-1 flex flex-col justify-center min-w-0 pr-4">
                                <span class="font-black uppercase tracking-tight text-xl lg:text-2xl text-slate-100 leading-none whitespace-normal break-words">
                                    {{ $item['team']->name }}
                                </span>
                                <span class="text-xs text-slate-500 font-semibold tracking-wider font-body-esports mt-0.5 uppercase leading-none">
                                    {{ $item['team']->short_name }}
                                </span>
                            </div>

                            {{-- Stats --}}
                            <span class="w-14 text-center font-bold text-lg text-slate-400 font-mono">{{ $item['matches_played'] }}</span>
                            <span class="w-16 text-center font-black text-xl text-yellow-500 font-mono">{{ $item['total_wins'] }}</span>
                            <span class="w-16 text-center font-bold text-lg text-slate-300 font-mono">{{ $item['total_kills'] }}</span>
                            <span class="w-16 text-center font-bold text-lg text-slate-400 font-mono">{{ $item['total_placement_points'] }}</span>
                            <span class="w-20 text-right font-black text-3xl text-orange-400 drop-shadow-[0_0_8px_rgba(249,115,22,0.3)] font-mono leading-none">{{ $item['total_points'] }}</span>

                        </div>
                    </div>
                @endforeach
            </div>

        </div>

        {{-- BOTTOM: High-tech footer coordinate bar --}}
        <div class="relative z-10 w-full max-w-[1800px] mx-auto flex items-center justify-between text-xs font-bold text-slate-500 tracking-[0.4em] uppercase pt-2 border-t border-slate-800/60">
            <span>SYS_LOC // 0x48FA90</span>
            <div class="h-[1px] w-40 bg-linear-to-r from-transparent via-yellow-400/30 to-transparent"></div>
            <span class="text-yellow-400/70 font-black drop-shadow-[0_0_5px_rgba(250,204,21,0.3)]">Official standings stream overlay</span>
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
        });
    </script>
</x-base>

