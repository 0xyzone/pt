<x-base>
    {{-- High-End Typography & Theme Imports --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700;900&family=Rajdhani:wght@600;700;900&display=swap" rel="stylesheet">
    
    <style>
        /* CSS resets & custom premium styles */
        .font-esports {
            font-family: 'Rajdhani', sans-serif;
        }
        .font-body-esports {
            font-family: 'Inter', sans-serif;
        }

        /* Animated grid overlay */
        .cyber-grid {
            background-size: 50px 50px;
            background-image: 
                linear-gradient(to right, rgba(255, 255, 255, 0.015) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(255, 255, 255, 0.015) 1px, transparent 1px);
            animation: gridMove 20s linear infinite;
        }

        @keyframes gridMove {
            0% { background-position: 0 0; }
            100% { background-position: 50px 50px; }
        }

        /* Cyberpunk diagonal scanning line */
        .laser-sweep {
            position: absolute;
            inset: 0;
            background: linear-gradient(to bottom, transparent 40%, rgba(250, 204, 21, 0.05) 50%, transparent 60%);
            animation: sweep 8s infinite linear;
            pointer-events: none;
        }

        @keyframes sweep {
            0% { transform: translateY(-100%); }
            100% { transform: translateY(100%); }
        }

        /* Pulsing neon lighting */
        .neon-glow-gold {
            box-shadow: 0 0 35px rgba(250, 204, 21, 0.15), inset 0 0 25px rgba(250, 204, 21, 0.05);
            border: 1px solid rgba(250, 204, 21, 0.3);
        }

        /* Tech bracket corner designs */
        .bracket-corner::before,
        .bracket-corner::after,
        .bracket-inner::before,
        .bracket-inner::after {
            content: '';
            position: absolute;
            width: 16px;
            height: 16px;
            border-color: rgba(250, 204, 21, 0.85);
            border-style: solid;
            pointer-events: none;
        }
        
        /* Top-Left Corner */
        .bracket-corner::before {
            top: -2px;
            left: -2px;
            border-width: 3px 0 0 3px;
        }
        /* Top-Right Corner */
        .bracket-corner::after {
            top: -2px;
            right: -2px;
            border-width: 3px 3px 0 0;
        }
        /* Bottom-Left Corner */
        .bracket-inner::before {
            bottom: -2px;
            left: -2px;
            border-width: 0 0 3px 3px;
        }
        /* Bottom-Right Corner */
        .bracket-inner::after {
            bottom: -2px;
            right: -2px;
            border-width: 0 3px 3px 0;
        }

        .pubg-skew {
            transform: none;
        }

        .pubg-unskew {
            transform: none;
        }

        /* Animated radial lighting */
        .cyber-bg {
            background-color: #030712;
            background-image: 
                radial-gradient(at 15% 15%, rgba(250, 204, 21, 0.06) 0px, transparent 60%),
                radial-gradient(at 85% 85%, rgba(249, 115, 22, 0.06) 0px, transparent 60%);
        }

        /* Glassmorphic Sponsor Panel */
        .glass-panel {
            background: rgba(8, 13, 24, 0.65);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.04);
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3);
        }

        .sponsor-slide {
            transition: opacity 0.8s cubic-bezier(0.4, 0, 0.2, 1), transform 0.8s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Scanning telemetry styling */
        .telemetry-text {
            font-size: 10px;
            letter-spacing: 0.2em;
            color: rgba(250, 204, 21, 0.5);
            font-weight: 700;
        }
    </style>

    {{-- Main screen wrapper — id used for live bg switching --}}
    <div id="screen-wrapper" class="w-full h-full min-h-screen {{ $bgType === 'animated' ? 'cyber-bg' : 'bg-transparent' }} relative flex flex-col justify-between items-center py-16 px-16 overflow-hidden text-white font-esports select-none">
        
        {{-- Custom background video layer (always in DOM, shown/hidden by JS) --}}
        <video id="bg-video" autoplay loop muted playsinline
            class="absolute inset-0 w-full h-full object-cover z-0 {{ ($bgType === 'custom' && $customVideo) ? '' : 'hidden' }}">
            @if($bgType === 'custom' && $customVideo)
                <source src="{{ asset('storage/' . $customVideo) }}" type="video/mp4">
            @endif
        </video>
        <div id="bg-video-overlay" class="absolute inset-0 bg-slate-950/80 z-0 backdrop-blur-[1px] {{ ($bgType === 'custom' && $customVideo) ? '' : 'hidden' }}"></div>

        {{-- Interactive Scanning laser overlay (always in DOM, shown/hidden by JS) --}}
        <div id="bg-grid" class="absolute inset-0 cyber-grid z-0 opacity-40 {{ $bgType === 'transparent' ? 'hidden' : '' }}"></div>
        <div id="bg-sweep" class="laser-sweep z-0 {{ $bgType === 'transparent' ? 'hidden' : '' }}"></div>

        {{-- TOP: Professional Tournament Branding HUD --}}
        <div class="w-full max-w-6xl flex justify-between items-center z-10">
            {{-- Telemetry Info (Left) --}}
            <div class="hidden md:flex flex-col gap-1 text-left">
                <span class="telemetry-text">SYSTEM // STATUS_OK</span>
                <div class="w-24 h-1 bg-yellow-400/35 rounded-full overflow-hidden">
                    <div class="w-2/3 h-full bg-yellow-400 animate-pulse"></div>
                </div>
                <span class="text-xs text-slate-500 font-bold tracking-widest uppercase">BROADCAST_LNK_01</span>
            </div>

            {{-- Central Branding Container --}}
            <div class="flex items-center gap-6 justify-center mx-auto md:mx-0">
                <div class="bg-slate-950/90 border-2 border-yellow-400/80 p-3.5 rounded-2xl shadow-xl drop-shadow-[0_0_15px_rgba(250,204,21,0.2)]">
                    <img src="{{ $tournament->logo_image ? asset('storage/' . $tournament->logo_image) : asset('img/defult_team_logo.png') }}"
                        class="w-20 h-20 object-contain">
                </div>
                <div class="flex flex-col items-start text-left">
                    <span class="text-3xl font-black uppercase text-slate-400 italic tracking-widest leading-none">PUBG MOBILE</span>
                    <span class="text-3xl font-black uppercase text-yellow-400 tracking-wider mt-1 drop-shadow-md">{{ $tournament->name }}</span>
                    <span class="text-[10px] font-bold text-slate-500 tracking-[0.35em] uppercase mt-1">OFFICIAL CHAMPIONSHIP TOURNAMENT</span>
                </div>
            </div>

            {{-- Live Broadcast skew label (Right) --}}
            <div class="bg-yellow-400/10 border border-yellow-400/30 px-6 py-2.5 rounded-lg pubg-skew hidden md:block">
                <span class="pubg-unskew block text-yellow-400 font-black uppercase tracking-[0.25em] text-sm animate-pulse">LIVE STREAM</span>
            </div>
        </div>

        {{-- MIDDLE: Premium glassmorphic telemetry HUD and Giant Countdown Timer --}}
        <div class="flex flex-col items-center justify-center my-auto z-10 w-full max-w-4xl relative mt-8">
            
            <div class="text-slate-400 text-sm font-black uppercase tracking-[0.45em] mb-4 flex items-center gap-3">
                <span class="w-1.5 h-1.5 rounded-full bg-yellow-400 animate-ping"></span>
                MATCHES WILL BEGIN IN
            </div>

            {{-- Telemetry Details Title --}}
            <h1 class="text-7xl md:text-8xl font-black italic tracking-tighter uppercase mb-8 drop-shadow-2xl text-center leading-none">
                STARTING <span class="text-yellow-400 drop-shadow-[0_0_15px_rgba(250,204,21,0.35)]">SOON</span>
            </h1>

            {{-- Giant Timer HUD Container --}}
            <div id="timer-container" class="neon-glow-gold bracket-corner bracket-inner w-full max-w-3xl bg-slate-950/80 rounded-2xl py-12 px-16 flex flex-col items-center justify-center shadow-2xl relative overflow-hidden backdrop-blur-md {{ !$timerState['visible'] ? 'hidden' : '' }}">
                
                {{-- Decorative Tech Grids inside the timer box --}}
                <div class="absolute top-2 left-4 text-[8px] font-bold text-slate-600 tracking-widest uppercase">SYS.TIME_COUNTDOWN // CH-1</div>
                <div class="absolute bottom-2 right-4 text-[8px] font-bold text-slate-600 tracking-widest uppercase">PUBG_OB_SYS // LIVE</div>

                <div id="timer-display" class="font-esports text-[11rem] md:text-[13rem] font-black text-yellow-400 tracking-widest select-none leading-none" style="text-shadow: 0 0 25px rgba(250, 204, 21, 0.7); font-weight: 900;">
                    00:00
                </div>
                
                <span class="text-lg uppercase font-bold text-slate-400 tracking-[0.35em] mt-2 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 text-yellow-400 animate-spin-slow">
                        <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25zM12.75 6a.75.75 0 00-1.5 0v6c0 .414.336.75.75.75h4.5a.75.75 0 000-1.5h-3.75V6z" clip-rule="evenodd" />
                    </svg>
                    Ready for showtime
                </span>
            </div>
        </div>

        {{-- BOTTOM: Sponsor Advertisement Slot --}}
        <div class="w-full max-w-5xl z-10 flex flex-col items-center mt-auto">
            <span class="text-xs font-black uppercase text-slate-500 tracking-[0.3em] mb-3">PRESENTED BY OUR PARTNERS & SPONSORS</span>
            
            <div class="w-full h-24 glass-panel rounded-2xl flex items-center justify-center overflow-hidden relative shadow-lg">
                {{-- Decorative Side Flaps --}}
                <div class="absolute left-0 inset-y-0 w-1.5 bg-yellow-400"></div>
                <div class="absolute right-0 inset-y-0 w-1.5 bg-yellow-400"></div>

                @if($sponsors->isEmpty())
                    <span class="text-slate-600 uppercase font-black text-sm tracking-[0.25em] opacity-40">COMMERCIAL PARTNERSHIP AD SLOT</span>
                @else
                    @foreach($sponsors as $idx => $sponsor)
                        <div class="sponsor-slide absolute inset-0 flex items-center justify-center p-4 transition-all duration-1000 opacity-0 transform translate-y-0 {{ $idx === 0 ? 'active opacity-100 translate-y-0' : '' }}" data-index="{{ $idx }}">
                            <div class="flex items-center gap-6">
                                <div class="">
                                    <img src="{{ $sponsor->logo_image ? asset('storage/' . $sponsor->logo_image) : asset('img/defult_team_logo.png') }}" class="h-10 object-contain drop-shadow-md">
                                </div>
                                {{-- <span class="text-white font-black tracking-widest uppercase text-xl border-l-2 border-yellow-400 pl-5 leading-none">{{ $sponsor->name }}</span> --}}
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>

    </div>

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
                }, 4500);
            }
        });
    </script>

    {{-- Timer Real-time Sync JS --}}
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

            // --- Live background switcher ---
            function applyBackground(bgType, customVideoUrl) {
                const wrapper = document.getElementById('screen-wrapper');
                const video = document.getElementById('bg-video');
                const videoOverlay = document.getElementById('bg-video-overlay');
                const grid = document.getElementById('bg-grid');
                const sweep = document.getElementById('bg-sweep');

                // Reset wrapper background classes
                wrapper.classList.remove('cyber-bg', 'bg-transparent');

                if (bgType === 'animated') {
                    wrapper.classList.add('cyber-bg');
                    if (grid) grid.classList.remove('hidden');
                    if (sweep) sweep.classList.remove('hidden');
                    if (video) video.classList.add('hidden');
                    if (videoOverlay) videoOverlay.classList.add('hidden');
                } else if (bgType === 'custom' && customVideoUrl) {
                    wrapper.classList.add('bg-transparent');
                    if (grid) grid.classList.remove('hidden');
                    if (sweep) sweep.classList.remove('hidden');
                    if (video) {
                        // Update video source if changed
                        const source = video.querySelector('source');
                        if (source && source.src !== customVideoUrl) {
                            source.src = customVideoUrl;
                            video.load();
                        }
                        video.classList.remove('hidden');
                        video.play().catch(() => {});
                    }
                    if (videoOverlay) videoOverlay.classList.remove('hidden');
                } else {
                    // transparent
                    wrapper.classList.add('bg-transparent');
                    if (grid) grid.classList.add('hidden');
                    if (sweep) sweep.classList.add('hidden');
                    if (video) video.classList.add('hidden');
                    if (videoOverlay) videoOverlay.classList.add('hidden');
                }
            }

            // Echo channel
            Echo.channel('user-screens.{{ $user->id }}')
                .listen('.BackgroundChanged', (e) => {
                    console.log('BackgroundChanged received:', e);
                    applyBackground(e.bgType, e.customVideoUrl);
                })
                .listen('.ObsViewSwitched', (e) => {
                    if (e.viewName === 'refresh') {
                        window.location.reload();
                    }
                })
                .listen('.RefreshScreens', (e) => {
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
