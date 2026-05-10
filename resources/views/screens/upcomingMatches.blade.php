<x-base>
    <style>
        .pubg-skew {
            transform: skewX(-12deg);
        }

        .pubg-unskew {
            transform: skewX(12deg);
        }

        .glass-panel {
            background: rgba(8, 12, 24, 0.5);
            border: 1px solid rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
        }

        .border-l-accent {
            border-left: 6px solid #facc15;
        }

        @keyframes pulse-yellow {
            0% {
                text-shadow: 0 0 10px rgba(250, 204, 21, 0);
            }

            50% {
                text-shadow: 0 0 20px rgba(250, 204, 21, 0.5);
            }

            100% {
                text-shadow: 0 0 10px rgba(250, 204, 21, 0);
            }
        }

        .animate-pulse-yellow {
            animation: pulse-yellow 2s infinite;
        }

        .match-card {
            transition: all 0.3s ease;
        }

        .match-card:hover {
            background: rgba(255, 255, 255, 0.05);
            transform: translateY(-5px);
        }
    </style>

    <div id="hud-root" class="w-full h-full relative font-main text-white overflow-hidden bg-transparent">

        {{-- LEFT BAR (L-Shape Side) --}}
        <div class="fixed left-0 top-0 w-[480px] h-full z-10 flex flex-col pointer-events-none">
            {{-- Tournament Info --}}
            <div class="p-12 pb-0">
                <div class="flex items-center gap-6 mb-12">
                    <div class="w-auto bg-white rounded-lg shadow-2xl">
                        <img src="{{ $tournament->logo_image ? asset('storage/' . $tournament->logo_image) : asset('img/defult_team_logo.png') }}"
                            class="w-32 aspect-square object-contain">
                    </div>
                    <div class="flex flex-col">
                        <span
                            class="text-4xl font-black uppercase text-gray-400 italic tracking-widest leading-none">PUBG
                            MOBILE</span>
                        <span
                            class="text-2xl font-bold uppercase text-yellow-400 tracking-widest">{{ $tournament->name }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- BOTTOM BAR (L-Shape Base) --}}
        <div class="fixed bottom-0 left-0 w-full h-[240px] z-20 flex">
            {{-- Left corner filler (to connect L) --}}
            <div class="w-[480px] h-full"></div>

            {{-- Match Cards Container --}}
            <div
                class="flex-1 glass-panel border-t-4 border-yellow-400 flex items-center px-12 gap-6 no-scrollbar relative overflow-visible">

                {{-- Mascot --}}
                <img src="{{ asset('img/pubg_mascot.png') }}"
                    class="absolute z-10 -left-[720px] -top-[250px] w-full h-[390px] object-contain drop-shadow-[0_20px_50px_rgba(0,0,0,0.8)]">

                {{-- Recap/Schedule Badge --}}
                <div class="bg-yellow-400 py-3 px-12 pubg-skew inline-block ml-12 shadow-2xl absolute -top-10 z-10">
                    <span
                        class="pubg-unskew block text-black font-black uppercase text-2xl tracking-widest italic">Upcoming
                        Schedule</span>
                </div>
                @foreach($matches->sortBy('id')->take(6) as $index => $match)
                    <div class="match-card h-[160px] min-w-[280px] relative overflow-hidden group">
                        {{-- Map Background --}}
                        <div class="absolute inset-0 bg-black/60 z-10 group-hover:bg-black/40 transition-all"></div>
                        @php
                            $mapImage = match (strtolower($match->map)) {
                                'erangle' => asset('/img/erangel_thumb.jpg'),
                                'miramar' => asset('/img/miramar_thumb.jpg'),
                                'sanhok' => asset('/img/sanhok_thumb.jpg'),
                                default => null
                            };

                            $winner = null;
                            if ($match->is_completed) {
                                $winnerStat = $match->matchStats->where('is_winner', true)->first();
                                if ($winnerStat) {
                                    $winner = $winnerStat->tournamentTeam;
                                }
                            }
                        @endphp
                        <img src="{{ $mapImage }}"
                            class="absolute inset-0 w-full h-full object-fill group-hover:scale-110 transition-transform duration-700">

                        {{-- Winner Overlay (if completed) --}}
                        @if($match->is_completed && $winner)
                            <div
                                class="absolute inset-0 z-30 flex flex-col items-center justify-center bg-black/40 backdrop-blur-[1px]">
                                <div class="relative mb-2">
                                    <img src="{{ $winner->logo_image ? asset('storage/' . $winner->logo_image) : asset('img/defult_team_logo.png') }}"
                                        class="w-16 aspect-square object-contain border border-yellow-400">
                                    <div
                                        class="absolute -top-3 -right-10 bg-yellow-400 px-2 py-0.5 pubg-skew shadow-lg border border-black/20">
                                        <span class="pubg-unskew block text-[16px] font-black text-black uppercase">WWCD</span>
                                    </div>
                                </div>
                                <span
                                    class="text-xl font-black uppercase italic text-yellow-400 tracking-tighter">{{ $winner->short_name }}</span>
                            </div>
                        @endif

                        {{-- Match Info --}}
                        <div
                            class="relative z-20 p-6 h-full flex flex-col justify-between {{ ($match->is_completed && $winner) ? 'opacity-30 grayscale' : '' }}">
                            <div class="flex justify-between items-start">
                                <div class="bg-yellow-400 px-3 py-1 pubg-skew">
                                    <span
                                        class="pubg-unskew text-black font-black uppercase text-xl">{{ $match->name }}</span>
                                </div>
                            </div>
                            <span
                                class="text-white font-black uppercase text-xl">{{ \Carbon\Carbon::parse($match->match_date)->format('M d, Y') }}
                                - {{ \Carbon\Carbon::parse($match->match_time)->format('h:i A') }}</span>

                            <div class="flex flex-col">
                                <span class="text-2xl font-black uppercase italic tracking-widest">{{ $match->map }}</span>
                                <div class="w-12 h-1 border-b-2 border-yellow-400 mt-1"></div>
                            </div>
                        </div>
                    </div>
                @endforeach

                {{-- Fill remaining space if few matches --}}
                @if($matches->count() < 6)
                    <div class="flex-1"></div>
                @endif
            </div>
        </div>

        {{-- Empty space for Video Feed is handled by the overall layout --}}
    </div>

    <style>
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
    <script type="module">
        document.addEventListener("DOMContentLoaded", function () {
            // Listen for refreshes from the model or manual refreshes
            Echo.channel('user-screens.{{ $user->id }}')
                .listen('.ObsViewSwitched', (e) => {
                    console.log('ObsViewSwitched received:', e);
                    if (e.viewName === 'refresh') {
                        window.location.reload();
                    }
                })
                .listen('.MatchStatsUpdated', (e) => {
                    console.log('MatchStatsUpdated received (User Channel):', e);
                    window.location.reload();
                })
                .listen('.TournamentMatchUpdated', (e) => {
                    console.log('TournamentMatchUpdated received:', e);
                    window.location.reload();
                });
        });
    </script>
</x-base>