<x-base title="POINT SYSTEM">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700;900&family=Rajdhani:wght@600;700;900&family=Orbitron:wght@700;800;900&display=swap" rel="stylesheet">

    <style>
        * {
            box-sizing: border-box;
        }

        body,
        html {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            background: transparent;
        }

        .font-esports {
            font-family: 'Rajdhani', sans-serif;
        }

        .font-body {
            font-family: 'Inter', sans-serif;
        }

        .font-hud {
            font-family: 'Orbitron', sans-serif;
        }

        /* ══════════════════════════════════════════════════════
           ENTRANCE & EXIT ANIMATIONS — obsMaster compatible
        ══════════════════════════════════════════════════════ */
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-44px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .slide-down {
            animation: slideDown 0.72s cubic-bezier(0.22, 1, 0.36, 1) both;
            will-change: transform, opacity;
        }

        @keyframes cardRevealLeft {
            from {
                opacity: 0;
                transform: translateX(-60px) scale(0.98);
                filter: blur(8px);
            }

            to {
                opacity: 1;
                transform: translateX(0) scale(1);
                filter: blur(0);
            }
        }

        @keyframes cardRevealRight {
            from {
                opacity: 0;
                transform: translateX(60px) scale(0.98);
                filter: blur(8px);
            }

            to {
                opacity: 1;
                transform: translateX(0) scale(1);
                filter: blur(0);
            }
        }

        .card-left-reveal {
            animation: cardRevealLeft 1.2s cubic-bezier(0.075, 0.82, 0.165, 1) both;
            will-change: transform, opacity;
        }

        .card-right-reveal {
            animation: cardRevealRight 1.2s cubic-bezier(0.075, 0.82, 0.165, 1) both;
            will-change: transform, opacity;
        }

        @keyframes gridItemReveal {
            from {
                opacity: 0;
                transform: translateY(24px) scale(0.96);
                filter: blur(4px);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
                filter: blur(0);
            }
        }

        .grid-item-animate {
            animation: gridItemReveal 0.8s cubic-bezier(0.075, 0.82, 0.165, 1) both;
            will-change: transform, opacity;
        }

        @keyframes slideUpFooter {
            from {
                opacity: 0;
                transform: translateY(32px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .slide-up-footer {
            animation: slideUpFooter 0.62s cubic-bezier(0.22, 1, 0.36, 1) both;
            animation-delay: 0.85s;
            will-change: transform, opacity;
        }

        /* ══════════════════════════════════════════════════════
           BACKGROUND & CYBER HUD EFFECTS
           ══════════════════════════════════════════════════════ */
        .cyber-bg {
            background-color: #030712;
            background-image:
                radial-gradient(at 15% 15%, rgba(16, 185, 129, 0.08) 0px, transparent 60%),
                radial-gradient(at 85% 85%, rgba(6, 182, 212, 0.06) 0px, transparent 60%);
        }

        .grid-bg {
            position: absolute;
            inset: 0;
            z-index: 0;
            background-size: 50px 50px;
            background-image:
                linear-gradient(to right, rgba(255, 255, 255, 0.012) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(255, 255, 255, 0.012) 1px, transparent 1px);
            pointer-events: none;
        }

        .glass-header {
            background: rgba(2, 4, 16, 0.92);
            border-bottom: 1.5px solid rgba(16, 185, 129, 0.3);
            backdrop-filter: blur(16px);
        }

        .glass-footer {
            background: rgba(2, 4, 16, 0.9);
            border-top: 1px solid rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(16px);
        }

        /* Premium Glass HUD Panel */
        .hud-card {
            background: linear-gradient(135deg, rgba(8, 14, 28, 0.92) 0%, rgba(4, 7, 16, 0.98) 100%);
            border: 1.5px solid rgba(16, 185, 129, 0.25);
            border-left: 6px solid rgba(16, 185, 129, 0.7) !important;
            backdrop-filter: blur(20px);
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.5), inset 0 1px 1px rgba(255, 255, 255, 0.05);
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .hud-card:hover {
            border-color: rgba(16, 185, 129, 0.5);
            box-shadow: 0 25px 60px rgba(16, 185, 129, 0.1), inset 0 1px 1px rgba(255, 255, 255, 0.1);
        }

        /* Scanline sweeps inside HUD */
        @keyframes scanline {
            0% {
                transform: translateY(-100%);
            }

            100% {
                transform: translateY(100%);
            }
        }

        .scanline-sweep::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(to bottom, transparent, rgba(16, 185, 129, 0.05), transparent);
            animation: scanline 4s linear infinite;
            pointer-events: none;
        }

        /* Micro tiny grid */
        .bg-grid-tiny {
            background-size: 8px 8px;
            background-image:
                linear-gradient(to right, rgba(255, 255, 255, 0.02) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(255, 255, 255, 0.02) 1px, transparent 1px);
        }

        /* Target locking pulse */
        @keyframes reticlePulse {

            0%,
            100% {
                transform: scale(1);
                opacity: 0.8;
            }

            50% {
                transform: scale(1.05);
                opacity: 1;
            }
        }

        .reticle-glow {
            animation: reticlePulse 3.5s ease-in-out infinite;
        }

        .text-glow-emerald {
            text-shadow: 0 0 10px rgba(16, 185, 129, 0.5), 0 0 20px rgba(16, 185, 129, 0.2);
        }

        .text-glow-yellow {
            text-shadow: 0 0 10px rgba(250, 204, 21, 0.5), 0 0 20px rgba(250, 204, 21, 0.2);
        }

        .text-glow-cyan {
            text-shadow: 0 0 10px rgba(6, 182, 212, 0.5), 0 0 20px rgba(6, 182, 212, 0.2);
        }

        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

    </style>

    {{-- Main screen wrapper --}}
    <div id="screen-wrapper" class="w-full h-full min-h-screen {{ $bgType === 'animated' ? 'cyber-bg' : 'bg-transparent' }} relative flex flex-col justify-between overflow-hidden text-slate-100 font-esports select-none">

        {{-- Background Video --}}
        <video id="bg-video" autoplay loop muted playsinline class="absolute inset-0 w-full h-full object-cover z-0 {{ ($bgType === 'custom' && $customVideo) ? '' : 'hidden' }}">
            @if($bgType === 'custom' && $customVideo)
            <source src="{{ asset('storage/' . $customVideo) }}" type="video/mp4">
            @endif
        </video>
        <div id="bg-video-overlay" class="absolute inset-0 bg-slate-950/85 z-0 backdrop-blur-[1px] {{ ($bgType === 'custom' && $customVideo) ? '' : 'hidden' }}"></div>

        {{-- Interactive Grid Overlay --}}
        <div id="bg-grid" class="grid-bg z-0 opacity-30 {{ $bgType === 'transparent' ? 'hidden' : '' }}"></div>

        {{-- ─── HEADER ──────────────────────────────────────── --}}
        <div class="slide-down glass-header relative z-10 flex justify-between items-center px-12 py-3.5" style="animation-delay:0s;">
            <div class="flex items-center gap-5">
                <div class="p-2 bg-slate-900 border border-emerald-500/40 rounded-xl shadow-lg shrink-0">
                    <img src="{{ $tournament && $tournament->logo_image ? asset('storage/'.$tournament->logo_image) : asset('img/defult_team_logo.png') }}" class="w-11 h-11 object-contain">
                </div>
                <div>
                    <div class="text-emerald-400 text-xs font-black uppercase tracking-[0.4em] font-body leading-none">TOURNAMENT RULES</div>
                    <h1 class="text-3xl font-black uppercase tracking-wider leading-none text-white mt-1.5">{{ $tournament ? $tournament->name : 'PUBG Mobile' }}</h1>
                </div>
            </div>
            <div class="text-right">
                <div class="text-slate-500 text-[10px] font-black uppercase tracking-[0.3em] font-body leading-none">POINT MATRIX HUB</div>
                <div class="text-2xl font-black uppercase tracking-widest mt-1.5 text-emerald-400 leader-text-pulse">
                    SYSTEM OVERVIEW
                </div>
            </div>
        </div>

        {{-- ─── MAIN CONTENT ────────────────────────────────── --}}
        <div class="relative z-10 flex-1 flex px-12 py-8 gap-8 min-h-0 items-stretch justify-center">

            {{-- LEFT COLUMN: Elimination Points (Dynamic HUD scope design) --}}
            <div class="w-2/5 flex flex-col justify-center">
                <div class="card-left-reveal hud-card scanline-sweep relative overflow-hidden rounded-2xl flex flex-col justify-between p-8 h-137.5" style="animation-delay: 0.15s;">
                    <div class="absolute inset-0 bg-grid-tiny opacity-10 pointer-events-none"></div>

                    {{-- Header telemetry line --}}
                    <div>
                        <div class="flex justify-between items-center border-b border-emerald-500/20 pb-3">
                            <span class="text-xs font-black text-slate-400/80 tracking-widest uppercase font-hud">SECTION // 01</span>
                            <span class="text-[9px] font-black text-emerald-400/60 uppercase font-hud bg-emerald-950/40 border border-emerald-500/30 px-2 py-0.5 rounded">ELIMINATION SPECS</span>
                        </div>

                        <div class="mt-6">
                            <h2 class="text-glow-emerald text-3.5xl font-black italic tracking-wide text-emerald-400 font-esports uppercase leading-none">
                                ELIMINATION RULES
                            </h2>
                            <p class="text-slate-400 text-xs mt-2.5 leading-relaxed tracking-wider font-body">
                                Squad kills contribute directly to the team's tournament standing in real time. Tactical aggression is locked in via this point matrix:
                            </p>
                        </div>

                        {{-- Highly readable clean rule grid --}}
                        <div class="grid grid-cols-2 gap-3 mt-4.5 select-none">
                            <div class="bg-slate-950/70 border border-emerald-500/15 rounded-xl p-3 flex flex-col justify-center shadow-inner">
                                <span class="text-[8px] font-black text-slate-500 uppercase tracking-widest font-hud">RULE CATEGORY</span>
                                <span class="text-emerald-400 font-hud text-xs font-black uppercase mt-1">KILL MATRIX</span>
                            </div>
                            <div class="bg-slate-950/70 border border-emerald-500/15 rounded-xl p-3 flex flex-col justify-center shadow-inner">
                                <span class="text-[8px] font-black text-slate-500 uppercase tracking-widest font-hud">VALUE PER KILL</span>
                                <span class="text-white font-hud text-xs font-black uppercase mt-1">+{{ $tournamentSetting->kill_points ?? 1 }} POINT</span>
                            </div>
                        </div>
                    </div>

                    {{-- Premium tactical lock scope visualization --}}
                    <div class="flex justify-center items-center relative py-2 my-auto">
                        {{-- Futuristic cyber scope SVG --}}
                        <div class="relative w-36 h-36 flex items-center justify-center reticle-glow">
                            <svg class="w-full h-full text-emerald-400" viewBox="0 0 100 100">
                                <circle cx="50" cy="50" r="44" stroke="currentColor" stroke-width="0.8" stroke-dasharray="4 8" fill="none" opacity="0.4" />
                                <circle cx="50" cy="50" r="36" stroke="currentColor" stroke-width="1.5" stroke-dasharray="32 16 8 16" fill="none" opacity="0.85" />
                                <circle cx="50" cy="50" r="12" stroke="currentColor" stroke-width="0.5" stroke-dasharray="2 2" fill="none" opacity="0.3" />

                                {{-- Scope ticks --}}
                                <line x1="50" y1="5" x2="50" y2="15" stroke="currentColor" stroke-width="2" />
                                <line x1="50" y1="85" x2="50" y2="95" stroke="currentColor" stroke-width="2" />
                                <line x1="5" y1="50" x2="15" y2="50" stroke="currentColor" stroke-width="2" />
                                <line x1="85" y1="50" x2="95" y2="50" stroke="currentColor" stroke-width="2" />

                                {{-- Target locking box --}}
                                <path d="M 38,38 L 42,38 M 38,38 L 38,42" stroke="currentColor" stroke-width="1.5" fill="none" />
                                <path d="M 62,38 L 58,38 M 62,38 L 62,42" stroke="currentColor" stroke-width="1.5" fill="none" />
                                <path d="M 38,62 L 42,62 M 38,62 L 38,58" stroke="currentColor" stroke-width="1.5" fill="none" />
                                <path d="M 62,62 L 58,62 M 62,62 L 62,58" stroke="currentColor" stroke-width="1.5" fill="none" />
                            </svg>

                            {{-- Center Point Display --}}
                            <div class="absolute flex flex-col items-center justify-center">
                                <span class="text-slate-400 font-hud text-[7px] uppercase tracking-widest font-black leading-none mb-0.5">EACH</span>
                                <span class="text-glow-emerald text-emerald-400 text-3.5xl font-black italic leading-none font-esports">
                                    +{{ $tournamentSetting->kill_points ?? 1 }}
                                </span>
                                <span class="text-white font-hud text-[7px] uppercase tracking-widest leading-none mt-1.5 bg-emerald-500/20 px-1.5 py-0.5 rounded border border-emerald-400/30">
                                    POINT
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- Technical footer layout --}}
                    <div class="border-t border-emerald-500/10 pt-4 flex justify-between items-center">
                        <div class="flex flex-col">
                            <span class="text-slate-500 font-hud text-[8px] uppercase tracking-widest">METRIC_SYSTEM</span>
                            <span class="text-emerald-400 text-xs font-black tracking-wider uppercase font-esports mt-0.5">PUBG_SUPER_STANDARD</span>
                        </div>
                        <div class="text-right">
                            <span class="text-slate-500 font-hud text-[8px] uppercase tracking-widest">RULE_REF_01</span>
                            <span class="text-slate-300 text-xs font-black tracking-wider uppercase font-esports mt-0.5">ELIM_CRITERIA</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- RIGHT COLUMN: Placement Points (Adaptive dynamic grid layout) --}}
            <div class="w-3/5 flex flex-col justify-center">
                <div class="card-right-reveal hud-card scanline-sweep relative overflow-hidden rounded-2xl flex flex-col p-8 h-137.5" style="animation-delay: 0.3s;">
                    <div class="absolute inset-0 bg-grid-tiny opacity-10 pointer-events-none"></div>

                    {{-- Header telemetry line --}}
                    <div class="flex justify-between items-center border-b border-emerald-500/20 pb-3 mb-6 shrink-0">
                        <span class="text-xs font-black text-slate-400/80 tracking-widest uppercase font-hud">PLACEMENT_SPEC_MATRIX</span>
                        <span class="text-[9px] font-black text-cyan-400 bg-cyan-950/40 border border-cyan-500/30 px-2 py-0.5 rounded">DYNAMIC_GRID</span>
                    </div>

                    {{-- Grid scrollable or fixed container --}}
                    <div class="flex-1 overflow-y-auto no-scrollbar pr-1 select-none">
                        @if($placementPoints->isEmpty())
                        {{-- Visual stunning empty fallback --}}
                        <div class="h-full flex flex-col justify-center items-center">
                            <svg class="w-16 h-16 text-slate-700 mb-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />
                            </svg>
                            <span class="text-slate-400 font-hud text-sm">NO PLACEMENT POINTS DEFINED</span>
                            <span class="text-slate-500 font-body text-[10px] mt-1 uppercase tracking-widest">PLEASE CHECK TOURNAMENT SETTINGS</span>
                        </div>
                        @else
                        <div class="grid grid-cols-4 gap-3">
                            @foreach($placementPoints as $pt)
                            @php
                            // Aesthetic styles for different tiers of placements
                            $bgClass = 'bg-slate-950/60 border-slate-800/80 hover:border-slate-700/60';
                            $glowClass = 'text-white';
                            $crown = '';

                            if ($pt->placement == 1) {
                            $bgClass = 'bg-gradient-to-br from-yellow-500/10 to-amber-500/5 border-yellow-500/40 hover:border-yellow-400';
                            $glowClass = 'text-yellow-400 text-glow-yellow';
                            $crown = '👑 ';
                            } elseif ($pt->placement == 2) {
                            $bgClass = 'bg-gradient-to-br from-slate-200/10 to-slate-300/5 border-slate-300/45 hover:border-slate-200';
                            $glowClass = 'text-slate-200';
                            } elseif ($pt->placement == 3) {
                            $bgClass = 'bg-gradient-to-br from-amber-600/15 to-amber-700/5 border-amber-600/40 hover:border-amber-500';
                            $glowClass = 'text-amber-500';
                            } elseif ($pt->placement <= 8) { $bgClass='bg-slate-950/80 border-cyan-500/20 hover:border-cyan-500/40' ; $glowClass='text-cyan-400 text-glow-cyan' ; } $itemDelay=0.08 * $loop->iteration;
                                @endphp
                                <div class="grid-item-animate {{ $bgClass }} border rounded-xl p-3 flex flex-col justify-between items-center transition-all duration-300 transform hover:-translate-y-1 shadow-md relative" style="animation-delay: {{ $itemDelay }}s; height: 95px;">

                                    <div class="absolute inset-0 bg-grid-tiny opacity-5 pointer-events-none rounded-xl"></div>
                                    <span class="text-[9px] font-black text-slate-400/60 font-hud tracking-[0.2em] uppercase leading-none mt-1">
                                        RANK {{ str_pad($pt->placement, 2, '0', STR_PAD_LEFT) }}
                                    </span>

                                    <div class="text-glow flex items-center gap-1 my-1">
                                        <span class="text-sm font-black tracking-widest uppercase font-hud truncate max-w-full {{ $glowClass }}">
                                            {!! $crown !!}{{ $pt->placement == 1 ? 'WINNER' : ($pt->placement == 2 ? '2ND PLACE' : ($pt->placement == 3 ? '3RD PLACE' : $pt->placement . 'TH')) }}
                                        </span>
                                    </div>

                                    <span class="font-hud text-xl font-black tracking-wider {{ $pt->placement == 1 ? 'text-yellow-400 text-glow-yellow' : ($pt->placement <= 8 ? 'text-cyan-400' : 'text-emerald-400 text-glow-emerald') }} mb-1">
                                        {{ $pt->points }} <span class="text-[9px] font-bold text-slate-500/80">PTS</span>
                                    </span>
                                </div>
                                @endforeach
                        </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>

        {{-- ─── FOOTER ─────────────────────────────────────── --}}
        <div class="slide-up-footer glass-footer relative z-10 flex items-center justify-between px-12 py-3.5 font-body text-[10px] font-bold text-slate-500 tracking-[0.4em] uppercase">
            <span>SYS_LOC // 0x58BF12</span>
            <div class="h-px w-48 shrink-0 bg-linear-to-r from-transparent via-emerald-400/20 to-transparent"></div>
            <span class="text-emerald-400/60 font-black">Official Point System & Breakdown</span>
            <div class="h-px w-48 shrink-0 bg-linear-to-r from-transparent via-emerald-400/20 to-transparent"></div>
            <span>SYS_VER_3.5.2</span>
        </div>

    </div>

    {{-- Live updates listener --}}
    <script type="module">
        document.addEventListener("DOMContentLoaded", function () {
            const wrapper = document.getElementById('screen-wrapper');
            const grid = document.getElementById('bg-grid');
            const video = document.getElementById('bg-video');
            const videoOverlay = document.getElementById('bg-video-overlay');

            function applyBackground(bgType, customVideoUrl) {
                if (!wrapper) return;
                
                // Reset classes
                wrapper.classList.remove('cyber-bg', 'bg-transparent');
                
                if (bgType === 'animated') {
                    wrapper.classList.add('cyber-bg');
                    if (grid) grid.classList.remove('hidden');
                    if (video) video.classList.add('hidden');
                    if (videoOverlay) videoOverlay.classList.add('hidden');
                } else if (bgType === 'custom') {
                    wrapper.classList.add('bg-transparent');
                    if (grid) grid.classList.add('hidden');
                    if (video) {
                        video.classList.remove('hidden');
                        if (customVideoUrl) {
                            const source = video.querySelector('source') || document.createElement('source');
                            source.src = customVideoUrl;
                            source.type = 'video/mp4';
                            if (!video.contains(source)) video.appendChild(source);
                            video.load();
                            video.play().catch(err => console.log('Video play interrupted:', err));
                        }
                    }
                    if (videoOverlay) videoOverlay.classList.remove('hidden');
                } else {
                    // transparent
                    wrapper.classList.add('bg-transparent');
                    if (grid) grid.classList.add('hidden');
                    if (video) video.classList.add('hidden');
                    if (videoOverlay) videoOverlay.classList.add('hidden');
                }
            }

            // Apply active background state initially
            applyBackground("{{ $bgType }}", "{{ $customVideo ? asset('storage/' . $customVideo) : '' }}");

            // Echo channel events for real-time background switching
            Echo.channel('user-screens.{{ $user->id }}')
                .listen('.BackgroundChanged', (e) => {
                    console.log('BackgroundChanged received:', e);
                    applyBackground(e.bgType, e.customVideoUrl);
                });

            // Prevent reload listeners from executing inside the parent OBS Master View context
            if (!window.isObsMaster) {
                Echo.channel('user-screens.{{ $user->id }}')
                    .listen('.RefreshScreens', (e) => { window.location.reload(); })
                    .listen('.TournamentMatchUpdated', (e) => { window.location.reload(); });
            }
        });
    </script>
</x-base>
