<x-base title="HEAD TO HEAD MATCHUP">
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
           ENTRANCE ANIMATIONS — GPU-accelerated, expo-out
           ══════════════════════════════════════════════════════ */

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-44px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .slide-down {
            animation: slideDown 0.72s cubic-bezier(0.22, 1, 0.36, 1) both;
            will-change: transform, opacity;
            backface-visibility: hidden;
        }

        @keyframes slideLeft {
            from { opacity: 0; transform: translateX(-55px) scale(0.97); }
            to   { opacity: 1; transform: translateX(0) scale(1); }
        }
        .slide-left {
            animation: slideLeft 0.82s cubic-bezier(0.22, 1, 0.36, 1) both;
            will-change: transform, opacity;
            backface-visibility: hidden;
        }

        @keyframes slideRight {
            from { opacity: 0; transform: translateX(55px) scale(0.97); }
            to   { opacity: 1; transform: translateX(0) scale(1); }
        }
        .slide-right {
            animation: slideRight 0.82s cubic-bezier(0.22, 1, 0.36, 1) both;
            will-change: transform, opacity;
            backface-visibility: hidden;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(44px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .slide-up {
            animation: slideUp 0.76s cubic-bezier(0.22, 1, 0.36, 1) both;
            will-change: transform, opacity;
            backface-visibility: hidden;
        }

        @keyframes slideUpFooter {
            from { opacity: 0; transform: translateY(32px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .slide-up-footer {
            animation: slideUpFooter 0.62s cubic-bezier(0.22, 1, 0.36, 1) both;
            animation-delay: 0.85s;
            will-change: transform, opacity;
        }

        @keyframes logoReveal {
            from {
                opacity: 0;
                transform: scale(0.65);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .logo-reveal {
            animation: logoReveal 0.85s cubic-bezier(0.22, 1, 0.36, 1) both;
            will-change: transform, opacity;
            backface-visibility: hidden;
        }

        /* ══════════════════════════════════════════════════════
           TACTICAL DUAL ARENA BACKGROUNDS
           ══════════════════════════════════════════════════════ */

        .split-bg-left {
            background: linear-gradient(135deg, rgba(2, 6, 23, 0.98) 0%, rgba(6, 182, 212, 0.08) 50%, rgba(2, 6, 23, 0.98) 100%);
        }

        .split-bg-right {
            background: linear-gradient(225deg, rgba(2, 6, 23, 0.98) 0%, rgba(250, 204, 21, 0.07) 50%, rgba(2, 6, 23, 0.98) 100%);
        }

        @keyframes scanline {
            from { transform: translateY(-100%); }
            to { transform: translateY(200%); }
        }

        .scan-sweep {
            position: absolute;
            inset: 0;
            z-index: 1;
            background: linear-gradient(to bottom, transparent 48%, rgba(6, 182, 212, 0.04) 50%, transparent 52%);
            animation: scanline 14s linear infinite;
            pointer-events: none;
        }

        @keyframes gridScroll {
            from { background-position: 0 0; }
            to { background-position: 0 40px; }
        }

        .grid-bg {
            position: absolute;
            inset: 0;
            z-index: 1;
            background-size: 50px 50px;
            background-image:
                linear-gradient(to right, rgba(255, 255, 255, 0.015) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(255, 255, 255, 0.015) 1px, transparent 1px);
            animation: gridScroll 20s linear infinite;
            pointer-events: none;
        }

        @keyframes orbFloat {
            0%, 100% { transform: translateY(0) scale(1); }
            50% { transform: translateY(-15px) scale(1.04); }
        }

        .orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(95px);
            pointer-events: none;
            animation: orbFloat 10s ease-in-out infinite;
        }

        /* Center VS neon indicator pulsing */
        @keyframes versusPulse {
            0%, 100% { text-shadow: 0 0 10px rgba(239, 68, 68, 0.85), 0 0 20px rgba(239, 68, 68, 0.45); }
            50% { text-shadow: 0 0 25px rgba(239, 68, 68, 1), 0 0 40px rgba(239, 68, 68, 0.6); }
        }

        .versus-glow {
            animation: versusPulse 2s ease-in-out infinite;
        }

        /* High-contrast neon text shadows */
        .text-glow-cyan {
            text-shadow: 0 0 12px rgba(6, 182, 212, 0.85), 0 0 22px rgba(6, 182, 212, 0.35);
            color: #06b6d4 !important;
        }

        .text-glow-gold {
            text-shadow: 0 0 12px rgba(250, 204, 21, 0.85), 0 0 22px rgba(250, 204, 21, 0.35);
            color: #facc15 !important;
        }

        .bg-grid-tiny {
            background-size: 8px 8px;
            background-image: 
                linear-gradient(to right, rgba(255, 255, 255, 0.02) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(255, 255, 255, 0.02) 1px, transparent 1px);
        }

        /* Ambient rotating tech graphics */
        @keyframes rotateClockwise {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        .rotate-cw {
            animation: rotateClockwise 25s linear infinite;
        }

    </style>

    {{-- Main screen wrapper — id used for live bg switching --}}
    <div id="screen-wrapper" class="w-full h-full min-h-screen bg-transparent relative flex flex-col justify-between overflow-hidden text-slate-100 font-esports select-none">

        {{-- ─── TACTICAL SPLIT-SCREEN BACKGROUND PANELS ────────── --}}
        {{-- Left Blue/Cyan Arena --}}
        <div id="split-bg-left" class="absolute inset-0 w-full h-full split-bg-left z-0 transition-all duration-300 {{ $bgType === 'transparent' ? 'hidden' : '' }}"></div>
        
        {{-- Right Gold/Amber Arena (Diagonally Clipped) --}}
        <div id="split-bg-right" class="absolute inset-0 left-[45%] w-[55%] h-full split-bg-right z-0 transition-all duration-300 {{ $bgType === 'transparent' ? 'hidden' : '' }}" style="clip-path: polygon(15% 0, 100% 0, 100% 100%, 0 100%); border-left: 2.5px solid rgba(250, 204, 21, 0.25);"></div>

        {{-- Enormous background watermarked crests to organically absorb whitespace --}}
        <img id="bg-watermark-left" src="{{ $stat1 && $stat1->tournamentTeam->logo_image ? asset('storage/'.$stat1->tournamentTeam->logo_image) : asset('img/defult_team_logo.png') }}" onerror="this.src='{{ asset('img/defult_team_logo.png') }}';" class="absolute left-[6%] top-[25%] w-105 h-105 opacity-10 pointer-events-none z-0 object-contain {{ $bgType === 'transparent' ? 'hidden' : '' }}">
        <img id="bg-watermark-right" src="{{ $stat2 && $stat2->tournamentTeam->logo_image ? asset('storage/'.$stat2->tournamentTeam->logo_image) : asset('img/defult_team_logo.png') }}" onerror="this.src='{{ asset('img/defult_team_logo.png') }}';" class="absolute right-[6%] top-[25%] w-105 h-105 opacity-10 pointer-events-none z-0 object-contain {{ $bgType === 'transparent' ? 'hidden' : '' }}">

        {{-- Custom background video layer --}}
        <video id="bg-video" autoplay loop muted playsinline class="absolute inset-0 w-full h-full object-cover z-0 {{ ($bgType === 'custom' && $customVideo) ? '' : 'hidden' }}">
            @if($bgType === 'custom' && $customVideo)
            <source src="{{ asset('storage/' . $customVideo) }}" type="video/mp4">
            @endif
        </video>
        <div id="bg-video-overlay" class="absolute inset-0 bg-slate-950/85 z-0 backdrop-blur-[1px] {{ ($bgType === 'custom' && $customVideo) ? '' : 'hidden' }}"></div>

        {{-- Interactive Scanning laser overlays --}}
        <div id="bg-grid" class="grid-bg z-1 opacity-20 {{ $bgType === 'transparent' ? 'hidden' : '' }}"></div>
        <div id="bg-sweep" class="scan-sweep z-1 {{ $bgType === 'transparent' ? 'hidden' : '' }}"></div>

        {{-- Ambient glows --}}
        <div class="orb z-1" style="width:400px;height:400px;background:rgba(6,182,212,0.04);top:15%;left:8%;"></div>
        <div class="orb z-1" style="width:450px;height:450px;background:rgba(250,204,21,0.03);bottom:15%;right:8%;"></div>

        {{-- ─── HEADER (slides down) ────────────────────────── --}}
        <div class="slide-down tactical-header relative z-10 flex justify-between items-center px-10 py-3.5">
            <div class="flex items-center gap-5">
                <div class="p-2 rounded-lg bg-slate-950/95 border border-cyan-500/35 shadow-inner">
                    <img src="{{ $tournament->logo_image ? asset('storage/'.$tournament->logo_image) : asset('img/defult_team_logo.png') }}" onerror="this.src='{{ asset('img/defult_team_logo.png') }}';" class="w-11 h-11 object-contain">
                </div>
                <div>
                    <div class="text-cyan-400 text-xs font-black uppercase tracking-[0.4em] font-body leading-none">HEAD-TO-HEAD BATTLE</div>
                    <h1 class="text-3xl font-black uppercase tracking-tight leading-none text-white mt-1.5">{{ $tournament->name }}</h1>
                </div>
            </div>
            <div class="flex items-center gap-6">
                <div class="px-5 py-2 rounded-lg text-center bg-slate-950/85 border border-rose-500/20 shadow-inner">
                    <div class="text-rose-400 text-[10px] font-black uppercase tracking-[0.3em] font-body leading-none">ACTIVE MATCH</div>
                    <div class="text-white text-lg font-black uppercase leading-none mt-1.5">{{ $activeMatch->name }}</div>
                </div>
                <div class="text-right">
                    <div class="text-slate-500 text-[9px] font-black uppercase tracking-[0.35em] font-body leading-none">SYS // DATA_MATCHUP</div>
                    <div class="text-2xl font-black uppercase tracking-wider text-rose-500 mt-1.5 versus-glow">VERSUS OVERLAY</div>
                </div>
            </div>
        </div>

        {{-- ─── MAIN CONTENT (CINEMATIC ARENA LAYOUT) ────────── --}}
        <div class="relative z-10 flex-1 flex px-12 py-5 min-h-0 items-center justify-between">

            {{-- ── LEFT FOREGROUND COLUMN: TEAM A (slides from left) ── --}}
            <div class="slide-left flex flex-col items-center justify-center z-10 w-95 ml-[4%] relative" style="animation-delay:0.08s;">
                
                {{-- Rotating Technical Target Crosshair Scope behind the crest logo --}}
                <div class="absolute inset-0 flex items-center justify-center opacity-30 z-0 pointer-events-none -top-16">
                    <svg class="w-64 h-64 text-cyan-500/50 rotate-cw" viewBox="0 0 100 100">
                        <circle cx="50" cy="50" r="44" stroke="currentColor" stroke-width="0.8" stroke-dasharray="10 15" fill="none" />
                        <line x1="50" y1="0" x2="50" y2="100" stroke="currentColor" stroke-width="0.5" stroke-dasharray="5 5" />
                        <line x1="0" y1="50" x2="100" y2="50" stroke="currentColor" stroke-width="0.5" stroke-dasharray="5 5" />
                    </svg>
                </div>

                {{-- Borderless crest logo --}}
                <img src="{{ $stat1 && $stat1->tournamentTeam->logo_image ? asset('storage/'.$stat1->tournamentTeam->logo_image) : asset('img/defult_team_logo.png') }}" onerror="this.src='{{ asset('img/defult_team_logo.png') }}';" class="w-36 h-36 object-contain logo-reveal filter drop-shadow-[0_10px_20px_rgba(6,182,212,0.3)] relative z-10">
                
                {{-- Team nameplate --}}
                <h2 class="text-5xl font-black uppercase text-center text-white leading-none tracking-wide text-shadow-cyan font-esports max-w-full truncate mt-6 relative z-10">
                    {{ $stat1 ? $stat1->tournamentTeam->name : 'TEAM ALPHA' }}
                </h2>
                
                <span class="text-cyan-400 font-bold uppercase tracking-[0.25em] text-xs mt-3.5 bg-cyan-950/50 border border-cyan-900/40 rounded px-4 py-1 shadow-inner leading-none relative z-10">
                    {{ $stat1 ? $stat1->tournamentTeam->short_name : 'T_A' }}
                </span>

                <div class="text-[8px] font-hud text-cyan-500/60 mt-3 tracking-[0.3em] font-black uppercase relative z-10 leading-none">
                    LOC_COORD // A.409
                </div>
            </div>

            {{-- ── CENTER COLUMN: FLOATING TACTICAL HUD TOWER (slides up) ── --}}
            <div class="slide-up w-135 flex flex-col gap-4.5 absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-20" style="animation-delay: 0.16s;">
                
                {{-- Holographic red versus badge --}}
                <div class="bg-slate-900/95 border border-red-500/50 py-1.5 px-4 rounded-full flex items-center justify-center gap-2.5 mx-auto shadow-[0_0_15px_rgba(239,68,68,0.25)] shrink-0 mb-1 leading-none">
                    <span class="w-2 h-2 rounded-full bg-red-500 animate-pulse"></span>
                    <span class="font-hud text-[10px] font-black text-rose-500 tracking-[0.3em] uppercase">ENGAGEMENT ACTIVE</span>
                </div>

                @php
                    $comparisons = [
                        [
                            'title' => 'MATCH ELIMINATIONS',
                            'val1' => $stat1 ? $stat1->kills : 0,
                            'val2' => $stat2 ? $stat2->kills : 0,
                            'format' => '%d',
                        ],
                        [
                            'title' => 'MATCH POINTS',
                            'val1' => $stat1 ? $stat1->points : 0,
                            'val2' => $stat2 ? $stat2->points : 0,
                            'format' => '%d',
                        ],
                        [
                            'title' => 'OVERALL POINTS',
                            'val1' => $team1Overall['points'] ?? 0,
                            'val2' => $team2Overall['points'] ?? 0,
                            'format' => '%d',
                        ],
                        [
                            'title' => 'OVERALL ELIMINATIONS',
                            'val1' => $team1Overall['kills'] ?? 0,
                            'val2' => $team2Overall['kills'] ?? 0,
                            'format' => '%d',
                        ],
                        [
                            'title' => 'WWCD (WINS)',
                            'val1' => $team1Overall['wwcd'] ?? 0,
                            'val2' => $team2Overall['wwcd'] ?? 0,
                            'format' => '%d',
                        ]
                    ];
                @endphp

                @foreach($comparisons as $idx => $comp)
                @php
                    $isLead1 = $comp['val1'] > $comp['val2'];
                    $isLead2 = $comp['val2'] > $comp['val1'];
                @endphp
                {{-- Floating Glass Stats Capsule (No Progress Bars, High-Impact digital counts) --}}
                <div class="bg-slate-950/90 border border-slate-900 rounded-[14px] px-6 py-4.5 flex justify-between items-center shadow-[0_10px_30px_rgba(0,0,0,0.65)] relative overflow-hidden backdrop-blur-md">
                    <div class="absolute inset-0 bg-grid-tiny opacity-10 pointer-events-none"></div>

                    {{-- Left score (Cyan) --}}
                    <span class="font-hud text-4xl font-black leading-none text-slate-300 w-16 text-left {{ $isLead1 ? 'text-glow-cyan' : '' }}">
                        {{ sprintf($comp['format'], $comp['val1']) }}
                    </span>

                    {{-- Center title (minimum font size is text-sm for superior legibility) --}}
                    <span class="text-sm font-black tracking-[0.25em] font-hud text-slate-200 text-center uppercase flex-1 leading-none">
                        {{ $comp['title'] }}
                    </span>

                    {{-- Right score (Gold) --}}
                    <span class="font-hud text-4xl font-black leading-none text-slate-300 w-16 text-right {{ $isLead2 ? 'text-glow-gold' : '' }}">
                        {{ sprintf($comp['format'], $comp['val2']) }}
                    </span>
                </div>
                @endforeach
            </div>

            {{-- ── RIGHT FOREGROUND COLUMN: TEAM B (slides from right) ── --}}
            <div class="slide-right flex flex-col items-center justify-center z-10 w-95 mr-[4%] relative" style="animation-delay:0.08s;">
                
                {{-- Rotating Technical Target Crosshair Scope behind the crest logo --}}
                <div class="absolute inset-0 flex items-center justify-center opacity-30 z-0 pointer-events-none -top-16">
                    <svg class="w-64 h-64 text-yellow-500/50 rotate-cw" viewBox="0 0 100 100">
                        <circle cx="50" cy="50" r="44" stroke="currentColor" stroke-width="0.8" stroke-dasharray="10 15" fill="none" />
                        <line x1="50" y1="0" x2="50" y2="100" stroke="currentColor" stroke-width="0.5" stroke-dasharray="5 5" />
                        <line x1="0" y1="50" x2="100" y2="50" stroke="currentColor" stroke-width="0.5" stroke-dasharray="5 5" />
                    </svg>
                </div>

                {{-- Borderless crest logo --}}
                <img src="{{ $stat2 && $stat2->tournamentTeam->logo_image ? asset('storage/'.$stat2->tournamentTeam->logo_image) : asset('img/defult_team_logo.png') }}" onerror="this.src='{{ asset('img/defult_team_logo.png') }}';" class="w-36 h-36 object-contain logo-reveal filter drop-shadow-[0_10px_20px_rgba(250,204,21,0.3)] relative z-10">
                
                {{-- Team nameplate --}}
                <h2 class="text-5xl font-black uppercase text-center text-white leading-none tracking-wide text-shadow-gold font-esports max-w-full truncate mt-6 relative z-10">
                    {{ $stat2 ? $stat2->tournamentTeam->name : 'TEAM BETA' }}
                </h2>
                
                <span class="text-yellow-400 font-bold uppercase tracking-[0.25em] text-xs mt-3.5 bg-yellow-950/50 border border-yellow-900/40 rounded px-4 py-1 shadow-inner leading-none relative z-10">
                    {{ $stat2 ? $stat2->tournamentTeam->short_name : 'T_B' }}
                </span>

                <div class="text-[8px] font-hud text-yellow-500/60 mt-3 tracking-[0.3em] font-black uppercase relative z-10 leading-none">
                    LOC_COORD // B.912
                </div>
            </div>

        </div>

        {{-- ─── FOOTER (slides up) ─────────────────────────── --}}
        <div class="slide-up-footer tactical-footer relative z-10 flex items-center justify-between px-10 py-2.5 font-body text-[9px] font-bold text-slate-500 tracking-[0.4em] uppercase">
            <span>SYS_LOC // 0x2A190D</span>
            <div class="h-px w-36 shrink-0" style="background:linear-gradient(to right,transparent,rgba(6,182,212,0.2),transparent);"></div>
            <span class="text-cyan-400/60">Official Broadcast Analytical Comparison</span>
            <div class="h-px w-36 shrink-0" style="background:linear-gradient(to right,transparent,rgba(234,179,8,0.2),transparent);"></div>
            <span>SYS_VER_3.5.2</span>
        </div>
    </div>

    <script type="module">
        document.addEventListener("DOMContentLoaded", function () {
            const wrapper = document.getElementById('screen-wrapper');
            const grid = document.getElementById('bg-grid');
            const sweep = document.getElementById('bg-sweep');
            const video = document.getElementById('bg-video');
            const videoOverlay = document.getElementById('bg-video-overlay');

            const splitLeft = document.getElementById('split-bg-left');
            const splitRight = document.getElementById('split-bg-right');
            const watermarkLeft = document.getElementById('bg-watermark-left');
            const watermarkRight = document.getElementById('bg-watermark-right');

            function applyBackground(bgType, customVideoUrl) {
                if (!wrapper) return;
                
                if (bgType === 'animated') {
                    if (splitLeft) splitLeft.classList.remove('hidden');
                    if (splitRight) splitRight.classList.remove('hidden');
                    if (watermarkLeft) watermarkLeft.classList.remove('hidden');
                    if (watermarkRight) watermarkRight.classList.remove('hidden');
                    
                    if (grid) grid.classList.remove('hidden');
                    if (sweep) sweep.classList.remove('hidden');
                    if (video) video.classList.add('hidden');
                    if (videoOverlay) videoOverlay.classList.add('hidden');
                } else if (bgType === 'custom') {
                    // Show translucent splits on top of video for cinematic grading!
                    if (splitLeft) splitLeft.classList.remove('hidden');
                    if (splitRight) splitRight.classList.remove('hidden');
                    if (watermarkLeft) watermarkLeft.classList.remove('hidden');
                    if (watermarkRight) watermarkRight.classList.remove('hidden');

                    if (grid) grid.classList.add('hidden');
                    if (sweep) sweep.classList.add('hidden');
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
                    if (splitLeft) splitLeft.classList.add('hidden');
                    if (splitRight) splitRight.classList.add('hidden');
                    if (watermarkLeft) watermarkLeft.classList.add('hidden');
                    if (watermarkRight) watermarkRight.classList.add('hidden');

                    if (grid) grid.classList.add('hidden');
                    if (sweep) sweep.classList.add('hidden');
                    if (video) video.classList.add('hidden');
                    if (videoOverlay) videoOverlay.classList.add('hidden');
                }
            }

            // Apply active background state initially
            applyBackground("{{ $bgType }}", "{{ $customVideo ? asset('storage/' . $customVideo) : '' }}");

            // Echo channel events for real-time background changes
            Echo.channel('user-screens.{{ $user->id }}')
                .listen('.BackgroundChanged', (e) => {
                    console.log('BackgroundChanged received:', e);
                    applyBackground(e.bgType, e.customVideoUrl);
                });

            // Prevent reload listeners from executing inside the parent OBS Master View context
            if (!window.isObsMaster) {
                Echo.channel('user-screens.{{ $user->id }}')
                    .listen('.RefreshScreens', (e) => { window.location.reload(); })
                    .listen('.ObsViewSwitched', (e) => {
                        if (e.viewName === 'refresh') window.location.reload();
                    });
                @if($activeMatch)
                Echo.channel('active-match.{{ $activeMatch->id }}')
                    .listen('.MatchStatsUpdated', (e) => { window.location.reload(); });
                @endif
            }
        });
    </script>
</x-base>
