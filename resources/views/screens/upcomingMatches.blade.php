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
        .font-timer {
            font-family: 'Orbitron', sans-serif;
        }

        .pubg-skew {
            transform: none;
        }
        .pubg-unskew {
            transform: none;
        }

        .glass-panel {
            background: rgba(8, 12, 24, 0.85);
            border-top: 3px solid #f59e0b;
            backdrop-filter: blur(15px);
            box-shadow: 0 -15px 40px rgba(0, 0, 0, 0.8);
        }

        @keyframes pulse-yellow {
            0%, 100% {
                opacity: 0.95;
                text-shadow: 0 0 10px #f59e0b, 0 0 25px rgba(245, 158, 11, 0.6);
            }
            50% {
                opacity: 1;
                text-shadow: 0 0 18px #f59e0b, 0 0 45px rgba(245, 158, 11, 0.9);
            }
        }

        .animate-pulse-yellow {
            animation: pulse-yellow 2s infinite ease-in-out;
        }

        .match-card {
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .match-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 15px 30px rgba(245, 158, 11, 0.25);
            border-color: #f59e0b;
        }
        
        .sponsor-slide {
            transition: opacity 0.8s ease-in-out, transform 0.8s ease-in-out;
        }

        /* Tactical HUD Brackets */
        .bracket-corner::before,
        .bracket-corner::after {
            content: '';
            position: absolute;
            width: 12px;
            height: 12px;
            border-color: #f59e0b;
            border-style: solid;
            pointer-events: none;
        }
        .bracket-corner::before { top: -2px; left: -2px; border-width: 2.5px 0 0 2.5px; }
        .bracket-corner::after { top: -2px; right: -2px; border-width: 2.5px 2.5px 0 0; }

        /* Carousel fade-out mask — only covers the left padding zone */
        .carousel-viewport {
            -webkit-mask-image: linear-gradient(
                to right,
                transparent 0%,
                rgba(0,0,0,0.4) 5%,
                black 10%,
                black 92%,
                rgba(0,0,0,0.4) 97%,
                transparent 100%
            );
            mask-image: linear-gradient(
                to right,
                transparent 0%,
                rgba(0,0,0,0.4) 5%,
                black 10%,
                black 92%,
                rgba(0,0,0,0.4) 97%,
                transparent 100%
            );
        }

        /* Frosted blur overlay — sized to match pl-36 (144px) padding only */
        .carousel-left-blur {
            background: linear-gradient(
                to right,
                rgba(8, 12, 24, 0.95) 0%,
                rgba(8, 12, 24, 0.5) 60%,
                transparent 100%
            );
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
        }
    </style>

    <div id="hud-root" class="w-full h-full relative font-esports text-white overflow-hidden bg-transparent select-none">

        {{-- L-SHAPE LEFT SIDEBAR --}}
        <div class="fixed left-0 top-0 w-120 h-full z-30 flex flex-col bg-slate-950/95 backdrop-blur-xl border-r-3 border-yellow-400 p-8 shadow-[15px_0_40px_rgba(0,0,0,0.85)]">
            
            {{-- Tournament Header --}}
            <div class="flex items-center gap-5 mb-6 mt-4">
                <div class="bg-slate-900 border border-yellow-400 p-2.5 rounded-xl shadow-2xl drop-shadow-[0_0_15px_rgba(250,204,21,0.2)] shrink-0">
                    <img src="{{ $tournament->logo_image ? asset('storage/' . $tournament->logo_image) : asset('img/defult_team_logo.png') }}"
                        class="w-20 h-20 object-contain">
                </div>
                <div class="flex flex-col text-left">
                    <span class="text-xs font-bold text-slate-500 tracking-[0.3em] uppercase leading-none">SYS // TOURNAMENT_HUB</span>
                    <span class="text-3xl font-black uppercase text-yellow-455 tracking-wider mt-1.5 leading-tight text-glow-gold italic">{{ $tournament->name }}</span>
                </div>
            </div>

            <div class="w-full h-px bg-linear-to-r from-yellow-400/40 to-transparent mb-8"></div>

            {{-- SPONSORS & PARTNERS AD SLOT --}}
            <div class="flex-1 flex flex-col justify-center items-center my-6">
                <span class="text-xl font-black uppercase text-slate-400 tracking-[0.25em] mb-4">Official Sponsors</span>
                <div class="relative w-full h-64 rounded-xl bg-slate-900/90 border border-slate-800 p-6 flex items-center justify-center overflow-hidden shadow-[inset_0_0_30px_rgba(0,0,0,0.9)]">
                    @if($sponsors->isEmpty())
                        <div class="text-center text-slate-500">
                            <span class="text-xs uppercase font-black tracking-widest text-slate-650">No sponsors found</span>
                        </div>
                    @else
                        @foreach($sponsors as $idx => $sponsor)
                            <div class="sponsor-slide absolute inset-0 flex flex-col items-center justify-center p-6 transition-all duration-1000 opacity-0 transform translate-y-4 {{ $idx === 0 ? 'active opacity-100 translate-y-0' : '' }}" data-index="{{ $idx }}">
                                <img src="{{ $sponsor->logo_image ? asset('storage/' . $sponsor->logo_image) : asset('img/defult_team_logo.png') }}" class="max-w-[85%] max-h-32 object-contain drop-shadow-[0_5px_15px_rgba(0,0,0,0.6)]">
                                <span class="text-slate-300 font-black tracking-widest uppercase mt-5 text-2xl">{{ $sponsor->name }}</span>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>

            {{-- BOLD AND CLEAR DYNAMIC TIMER --}}
            <div id="timer-container" class="w-full bg-slate-950/80 border-2 border-yellow-400/40 rounded-xl py-6 px-5 mb-4 flex flex-col items-center justify-center shadow-[0_0_30px_rgba(250,204,21,0.2)] transition-all duration-300 relative overflow-hidden {{ !$timerState['visible'] ? 'hidden' : '' }}">
                <div class="absolute top-2 left-3 text-[8px] font-bold text-slate-600 tracking-widest uppercase">SYS.TIME_HUD // CD_02</div>
                
                <span class="text-xl font-black uppercase text-yellow-400 tracking-[0.3em] mb-2 leading-none">Showtime In</span>
                <div id="timer-display" class="font-timer text-7xl font-black text-yellow-400 tracking-widest animate-pulse-yellow select-none leading-none" style="text-shadow: 0 0 10px #f59e0b, 0 0 20px rgba(245,158,11,0.5);">
                    00:00
                </div>
            </div>

        </div>

        {{-- L-SHAPE BOTTOM BAR --}}
        <div class="fixed bottom-0 left-0 w-full h-65 z-20 flex">
            {{-- Offset to dodge the Left Sidebar --}}
            <div class="w-120 h-full shrink-0 bg-transparent border-none pointer-events-none"></div>

            {{-- Match Cards Container --}}
            <div class="flex-1 glass-panel flex items-center px-12 relative overflow-visible shadow-[0_-15px_40px_rgba(0,0,0,0.8)] border-r-none border-b-none border-l-none">

                {{-- Mascot --}}
                <img src="{{ asset('img/pubg_mascot.png') }}"
                    class="absolute z-10 -left-24 -top-36 h-85 object-contain drop-shadow-[0_15px_30px_rgba(0,0,0,0.9)] pointer-events-none">

                {{-- Schedule Badge --}}
                <div class="bg-linear-to-r from-yellow-500 to-amber-500 py-2.5 px-12 pubg-skew inline-block ml-36 shadow-2xl absolute -top-6 z-10 border border-white/20">
                    <span class="pubg-unskew block text-black font-black uppercase text-2xl tracking-widest italic leading-none">Upcoming Matches</span>
                </div>

                {{-- VIEWPORT CONTAINER --}}
                <div class="carousel-viewport w-full overflow-hidden h-60 flex items-center pl-36 relative">
                    {{-- Left-edge frosted blur fade overlay --}}
                    <div class="carousel-left-blur absolute left-0 top-0 h-full w-36 z-20 pointer-events-none"></div>
                    {{-- CAROUSEL TRACK --}}
                    <div id="matches-track" class="flex items-center gap-6 w-max shrink-0">
                        @php
                            $matchesCount = $matches->count();
                            $displayMatches = $matches->sortBy('id');
                            if ($matchesCount >= 4) {
                                $cloned = $displayMatches->take(4);
                                $displayMatches = $displayMatches->concat($cloned);
                            }
                        @endphp

                        @foreach($displayMatches as $index => $match)
                            <div class="match-card h-52.5 w-85 shrink-0 relative overflow-hidden group rounded-xl border border-yellow-400/20 hover:border-yellow-400 shadow-2xl">
                                {{-- Map Background Gradient Overlay --}}
                                <div class="absolute inset-0 bg-linear-to-t from-slate-950 via-slate-950/70 to-slate-950/20 z-10 group-hover:from-slate-950 group-hover:via-slate-950/50 transition-all duration-300"></div>
                                @php
                                    $mapImage = match (strtolower($match->map)) {
                                        'erangle', 'erangel' => asset('/img/erangel_thumb.jpg'),
                                        'miramar' => asset('/img/miramar_thumb.jpg'),
                                        'sanhok' => asset('/img/sanhok_thumb.jpg'),
                                        'rondo' => asset('/img/rondo_thumb.jpg'),
                                        'vikendi' => asset('/img/vikendi_thumb.png'),
                                        'taego' => asset('/img/taego_thumb.png'),
                                        'deston' => asset('/img/deston_thumb.png'),
                                        'karakin' => asset('/img/karakin_thumb.png'),
                                        'paramo' => asset('/img/paramo_thumb.png'),
                                        'haven' => asset('/img/haven_thumb.png'),
                                        default => 'https://images.unsplash.com/photo-1542751371-adc38448a05e?auto=format&fit=crop&w=800&q=80'
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
                                    class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">

                                {{-- Winner Overlay (if completed) --}}
                                @if($match->is_completed && $winner)
                                    <div class="absolute inset-0 z-30 flex flex-col items-center justify-center bg-slate-950/75 backdrop-blur-[2px]">
                                        <div class="relative mb-2">
                                            <img src="{{ $winner->logo_image ? asset('storage/' . $winner->logo_image) : asset('img/defult_team_logo.png') }}"
                                                class="w-14 h-14 object-contain border border-yellow-400 bg-slate-950 rounded-lg p-1.5 shadow-2xl">
                                            <div class="absolute -top-2.5 -right-7 bg-yellow-455 px-2 py-0.5 pubg-skew shadow-lg border border-white/20">
                                                <span class="pubg-unskew block text-[10px] font-black text-black uppercase leading-none">WWCD</span>
                                            </div>
                                        </div>
                                        <span class="text-lg font-black uppercase italic text-yellow-400 tracking-wider drop-shadow-md leading-none">{{ $winner->short_name }}</span>
                                    </div>
                                @endif

                                {{-- Match Info --}}
                                <div class="relative z-20 p-5 h-full flex flex-col justify-between {{ ($match->is_completed && $winner) ? 'opacity-35 grayscale' : '' }}">
                                    <div class="flex justify-between items-start">
                                        <div class="bg-yellow-400/90 border border-white/20 px-4 py-1 pubg-skew shadow-md">
                                            <span class="pubg-unskew text-black font-black uppercase text-lg tracking-wider leading-none block">{{ $match->name }}</span>
                                        </div>
                                    </div>
                                    
                                    <span class="text-white font-black uppercase text-[15px] tracking-wider bg-slate-950/80 border border-slate-800/80 px-2.5 py-1 rounded w-fit leading-none" style="text-shadow: 1px 1px 2px #000;">
                                        {{ \Carbon\Carbon::parse($match->match_date)->format('M d') }} - {{ \Carbon\Carbon::parse($match->match_time)->format('h:i A') }}
                                    </span>

                                    <div class="flex flex-col text-left">
                                        <span class="text-4xl font-black uppercase italic tracking-wider text-yellow-400 leading-none drop-shadow-lg">{{ $match->map }}</span>
                                        <div class="w-16 h-0.5 bg-yellow-400 mt-2 shadow-[0_0_10px_#facc15]"></div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        @if($matchesCount < 4)
                            <div class="flex-1"></div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- Carousel Auto Scroll --}}
    @if($matchesCount >= 4)
    <script type="module">
        document.addEventListener("DOMContentLoaded", function () {
            const track = document.getElementById('matches-track');
            if (!track) return;
            
            let currentIndex = 0;
            const cardWidth = 364; // card width (340px) + gap (24px)
            const totalMatches = {{ $matchesCount }};
            
            setInterval(() => {
                currentIndex++;
                track.style.transition = 'transform 0.7s cubic-bezier(0.4, 0, 0.2, 1)';
                track.style.transform = `translateX(-${currentIndex * cardWidth}px)`;
                
                if (currentIndex >= totalMatches) {
                    setTimeout(() => {
                        track.style.transition = 'none';
                        currentIndex = 0;
                        track.style.transform = `translateX(0px)`;
                    }, 700);
                }
            }, 3500);
        });
    </script>
    @endif

    {{-- Sponsors Rotator JS --}}
    <script type="module">
        document.addEventListener("DOMContentLoaded", function () {
            const sponsorSlides = document.querySelectorAll('.sponsor-slide');
            if (sponsorSlides.length > 1) {
                let currentSlide = 0;
                setInterval(() => {
                    sponsorSlides[currentSlide].classList.remove('opacity-100', 'translate-y-0');
                    sponsorSlides[currentSlide].classList.add('opacity-0', 'translate-y-4');
                    currentSlide = (currentSlide + 1) % sponsorSlides.length;
                    sponsorSlides[currentSlide].classList.remove('opacity-0', 'translate-y-4');
                    sponsorSlides[currentSlide].classList.add('opacity-100', 'translate-y-0');
                }, 4000);
            }
        });
    </script>

    {{-- Synchronized Timer Controls JS --}}
    <script type="module">
        document.addEventListener("DOMContentLoaded", function () {
            let timerDuration = {{ $timerState['duration'] }};
            let timerStatus = "{{ $timerState['status'] }}";
            let timerEndsAt = {{ $timerState['endsAt'] }};
            let timerRemaining = {{ $timerState['remainingSeconds'] }};
            let timerInterval = null;

            function formatTime(seconds) {
                const mins = Math.floor(seconds / 60);
                const secs = seconds % 60;
                return `${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
            }

            function updateDisplay() {
                const display = document.getElementById('timer-display');
                if (!display) return;
                
                if (timerStatus === 'running') {
                    const now = Math.floor(Date.now() / 1000);
                    const remaining = Math.max(0, timerEndsAt - now);
                    display.innerText = formatTime(remaining);
                    if (remaining <= 0) {
                        display.innerText = "00:00";
                        timerStatus = 'stopped';
                        clearInterval(timerInterval);
                    }
                } else if (timerStatus === 'paused') {
                    display.innerText = formatTime(timerRemaining);
                } else {
                    display.innerText = formatTime(timerDuration * 60);
                }
            }

            function startTimerLoop() {
                clearInterval(timerInterval);
                updateDisplay();
                timerInterval = setInterval(() => {
                    updateDisplay();
                }, 1000);
            }

            // Init
            startTimerLoop();
            
            // Listen for refreshes or timer events
            Echo.channel('user-screens.{{ $user->id }}')
                .listen('.ObsViewSwitched', (e) => {
                    console.log('ObsViewSwitched received:', e);
                    if (e.viewName === 'refresh') {
                        window.location.reload();
                    }
                })
                .listen('.RefreshScreens', (e) => {
                    console.log('Force refresh received...');
                    window.location.reload();
                })
                .listen('.TournamentMatchUpdated', (e) => {
                    // Reload when a match is marked active/completed so winner overlay and status update
                    window.location.reload();
                })
                .listen('.TimerUpdated', (e) => {
                    console.log('TimerUpdated event received:', e);
                    timerStatus = e.status;
                    timerDuration = e.duration;
                    timerRemaining = e.remainingSeconds;
                    timerEndsAt = e.endsAt;
                    
                    const container = document.getElementById('timer-container');
                    if (container) {
                        if (e.visible) {
                            container.classList.remove('hidden');
                        } else {
                            container.classList.add('hidden');
                        }
                    }
                    updateDisplay();
                });

            // Also listen on the active match channel for stats updates
            @if($activeMatch)
            Echo.channel('active-match.{{ $activeMatch->id }}')
                .listen('.MatchStatsUpdated', (e) => {
                    window.location.reload();
                });
            @endif
        });
    </script>
</x-base>