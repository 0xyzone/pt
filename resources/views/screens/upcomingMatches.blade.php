<x-base>
    <style>
        .pubg-skew {
            transform: skewX(-12deg);
        }

        .pubg-unskew {
            transform: skewX(12deg);
        }

        .glass-panel {
            background: rgba(4, 6, 12, 0.9);
            border-top: 3px solid #facc15;
            backdrop-filter: blur(15px);
        }

        @keyframes pulse-yellow {
            0%, 100% {
                opacity: 0.95;
                text-shadow: 0 0 10px #facc15, 0 0 25px rgba(250, 204, 21, 0.6);
            }
            50% {
                opacity: 1;
                text-shadow: 0 0 18px #facc15, 0 0 45px rgba(250, 204, 21, 0.9);
            }
        }

        .animate-pulse-yellow {
            animation: pulse-yellow 2.5s infinite ease-in-out;
        }

        .match-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .match-card:hover {
            transform: translateY(-10px) scale(1.03);
            box-shadow: 0 15px 30px rgba(250, 204, 21, 0.25);
            border-color: #facc15;
        }
        
        .sponsor-slide {
            transition: opacity 0.8s ease-in-out, transform 0.8s ease-in-out;
        }
    </style>

    <div id="hud-root" class="w-full h-full relative font-main text-white overflow-hidden bg-transparent">

        {{-- L-SHAPE LEFT SIDEBAR --}}
        <div class="fixed left-0 top-0 w-120 h-full z-30 flex flex-col bg-slate-950/98 backdrop-blur-xl border-r-3 border-yellow-400 p-8 shadow-[15px_0_40px_rgba(0,0,0,0.8)]">
            
            {{-- Tournament Header --}}
            <div class="flex items-center gap-6 mb-8 mt-4">
                <div class="w-auto bg-slate-900 border-2 border-yellow-400 p-3 rounded-2xl shadow-2xl drop-shadow-[0_0_15px_rgba(250,204,21,0.2)] shrink-0">
                    <img src="{{ $tournament->logo_image ? asset('storage/' . $tournament->logo_image) : asset('img/defult_team_logo.png') }}"
                        class="w-24 h-24 object-contain">
                </div>
                <div class="flex flex-col">
                    <span class="text-5xl font-black uppercase text-gray-400 italic tracking-wider leading-none">PUBG MOBILE</span>
                    <span class="text-4xl font-black uppercase text-yellow-400 tracking-widest mt-2 drop-shadow-md leading-tight">{{ $tournament->name }}</span>
                </div>
            </div>

            <div class="w-full h-1 bg-linear-to-r from-yellow-400 to-transparent mb-8"></div>

            {{-- SPONSORS & PARTNERS AD SLOT --}}
            <div class="flex-1 flex flex-col justify-center items-center my-6">
                <span class="text-2xl font-black uppercase text-yellow-400 tracking-[0.25em] mb-4">Official Sponsors</span>
                <div class="relative w-full h-80 rounded-2xl bg-slate-900/95 border-2 border-slate-800 p-6 flex items-center justify-center overflow-hidden shadow-[inset_0_0_30px_rgba(0,0,0,0.9)]">
                    @if($sponsors->isEmpty())
                        <div class="text-center text-slate-500">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-16 h-16 mx-auto mb-3 opacity-20">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.34 15.84c-.68-.3-1.43-.69-2.06-1.2m0 0a10.99 10.99 0 01-5.07-8.76 5.99 5.99 0 0011.99 0 10.99 10.99 0 01-5.07 8.76zm0 0c-.3.68-.69 1.43-1.2 2.06m0 0a10.99 10.99 0 01-8.76 5.07 5.99 5.99 0 000-11.99 10.99 10.99 0 018.76 5.07zm0 0c.68.3 1.43.69 2.06 1.2m0 0a10.99 10.99 0 015.07 8.76 5.99 5.99 0 00-11.99 0 10.99 10.99 0 015.07-8.76z" />
                            </svg>
                            <span class="text-xs uppercase font-black tracking-widest text-slate-500">Ad Showcase Slot</span>
                        </div>
                    @else
                        @foreach($sponsors as $idx => $sponsor)
                            <div class="sponsor-slide absolute inset-0 flex flex-col items-center justify-center p-8 transition-all duration-1000 opacity-0 transform translate-y-4 {{ $idx === 0 ? 'active opacity-100 translate-y-0' : '' }}" data-index="{{ $idx }}">
                                <img src="{{ $sponsor->logo_image ? asset('storage/' . $sponsor->logo_image) : asset('img/defult_team_logo.png') }}" class="max-w-[85%] max-h-40 object-contain drop-shadow-[0_5px_15px_rgba(0,0,0,0.6)]">
                                <span class="text-slate-200 font-black tracking-widest uppercase mt-6 text-3xl">{{ $sponsor->name }}</span>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>

            {{-- BOLD AND CLEAR DYNAMIC TIMER --}}
            <div id="timer-container" class="w-full bg-slate-950 border-4 border-yellow-400 rounded-2xl py-8 px-6 mb-4 flex flex-col items-center justify-center shadow-[0_0_35px_rgba(250,204,21,0.35)] transition-all duration-300 {{ !$timerState['visible'] ? 'hidden' : '' }}">
                <span class="text-3xl font-extrabold uppercase text-yellow-400 tracking-[0.3em] mb-2">SHOWTIME IN</span>
                <div id="timer-display" class="font-timer text-8xl font-black text-yellow-400 tracking-widest animate-pulse-yellow select-none" style="text-shadow: 0 0 10px #facc15, 0 0 25px rgba(250,204,21,0.6); font-weight: 900;">
                    00:00
                </div>
                <div class="h-2 w-32 bg-yellow-400 rounded-full mt-4 shadow-[0_0_15px_#facc15]"></div>
            </div>

        </div>

        {{-- L-SHAPE BOTTOM BAR --}}
        <div class="fixed bottom-0 left-0 w-full h-65 z-20 flex">
            {{-- Offset to dodge the Left Sidebar --}}
            <div class="w-120 h-full shrink-0 bg-transparent border-none pointer-events-none"></div>

            {{-- Match Cards Container --}}
            <div class="flex-1 glass-panel flex items-center px-12 relative overflow-visible shadow-[0_-15px_40px_rgba(0,0,0,0.8)]">

                {{-- Mascot --}}
                <img src="{{ asset('img/pubg_mascot.png') }}"
                    class="absolute z-10 -left-30 -top-40 h-90 object-contain drop-shadow-[0_15px_30px_rgba(0,0,0,0.9)] pointer-events-none">

                {{-- Recap/Schedule Badge --}}
                <div class="bg-yellow-400 py-3.5 px-14 pubg-skew inline-block ml-32 shadow-2xl absolute -top-8 z-10 border-2 border-black/20">
                    <span class="pubg-unskew block text-black font-black uppercase text-3xl tracking-widest italic">Upcoming matches</span>
                </div>

                {{-- VIEWPORT CONTAINER --}}
                <div class="w-full overflow-hidden h-60 flex items-center pl-36">
                    {{-- CAROUSEL TRACK --}}
                    <div id="matches-track" class="flex items-center gap-6 w-max shrink-0">
                        @php
                            $matchesCount = $matches->count();
                            $displayMatches = $matches->sortBy('id');
                            if ($matchesCount > 4) {
                                $cloned = $displayMatches->take(4);
                                $displayMatches = $displayMatches->concat($cloned);
                            }
                        @endphp

                        @foreach($displayMatches as $index => $match)
                            <div class="match-card h-52.5 w-85 shrink-0 relative overflow-hidden group rounded-xl border-2 border-yellow-400/20 hover:border-yellow-400 shadow-2xl">
                                {{-- Map Background Gradient Overlay --}}
                                <div class="absolute inset-0 bg-linear-to-t from-slate-950 via-slate-950/80 to-slate-950/30 z-10 group-hover:from-slate-950 group-hover:via-slate-950/60 transition-all"></div>
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
                                    <div class="absolute inset-0 z-30 flex flex-col items-center justify-center bg-black/60 backdrop-blur-[2px]">
                                        <div class="relative mb-2">
                                            <img src="{{ $winner->logo_image ? asset('storage/' . $winner->logo_image) : asset('img/defult_team_logo.png') }}"
                                                class="w-16 h-16 object-contain border-2 border-yellow-400 bg-slate-950 rounded-lg p-1.5 shadow-2xl animate-pulse">
                                            <div class="absolute -top-3 -right-9 bg-yellow-400 px-2 py-0.5 pubg-skew shadow-lg border border-black/10">
                                                <span class="pubg-unskew block text-[13px] font-black text-black uppercase">WWCD</span>
                                            </div>
                                        </div>
                                        <span class="text-xl font-black uppercase italic text-yellow-400 tracking-widest drop-shadow-[0_2px_4px_rgba(0,0,0,0.8)]">{{ $winner->short_name }}</span>
                                    </div>
                                @endif

                                {{-- Match Info --}}
                                <div class="relative z-20 p-6 h-full flex flex-col justify-between {{ ($match->is_completed && $winner) ? 'opacity-25 grayscale' : '' }}">
                                    <div class="flex justify-between items-start">
                                        <div class="bg-yellow-400 px-5 py-2 pubg-skew shadow-md">
                                            <span class="pubg-unskew text-black font-black uppercase text-xl tracking-wider">{{ $match->name }}</span>
                                        </div>
                                    </div>
                                    
                                    <span class="text-white font-black uppercase text-[17px] tracking-wider bg-slate-950/70 border border-slate-800/80 px-3 py-1 rounded w-fit" style="text-shadow: 1px 1px 2px #000;">
                                        {{ \Carbon\Carbon::parse($match->match_date)->format('M d') }} - {{ \Carbon\Carbon::parse($match->match_time)->format('h:i A') }}
                                    </span>

                                    <div class="flex flex-col">
                                        <span class="text-5xl lg:text-6xl font-display font-black uppercase italic tracking-widest text-yellow-400" style="text-shadow: 2px 2px 0px #000, -2px -2px 0px #000, 2px -2px 0px #000, -2px 2px 0px #000, 0px 4px 8px rgba(0,0,0,0.95);">{{ $match->map }}</span>
                                        <div class="w-24 h-1 bg-yellow-400 mt-2 shadow-[0_0_12px_#facc15]"></div>
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

        {{-- Center highlight space is kept completely transparent for OBS overlay --}}
    </div>

    {{-- Carousel Auto Scroll --}}
    @if($matchesCount > 4)
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
        });
    </script>
</x-base>