<x-base title="TOP 5 FRAGGERS">
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
            ENTRANCE ANIMATIONS — GPU-accelerated, expo-out easing
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

        @keyframes cardReveal {
            from { opacity: 0; transform: translateY(50px) scale(0.97); }
            to   { opacity: 1; transform: translateY(0) scale(1); }
        }
        .card-reveal {
            animation: cardReveal 0.78s cubic-bezier(0.22, 1, 0.36, 1) both;
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

        /* ══════════════════════════════════════════════════════
            STAGGERED CARD INNER ELEMENTS ANIMATIONS
        ══════════════════════════════════════════════════════ */
        @keyframes badgeDrop {
            from { opacity: 0; transform: translate(-50%, -24px) scaleY(0.7); }
            to   { opacity: 1; transform: translate(-50%, 0) scaleY(1); }
        }
        .badge-drop-anim {
            animation: badgeDrop 0.58s cubic-bezier(0.22, 1, 0.36, 1) both;
            will-change: transform, opacity;
        }

        @keyframes portraitReveal {
            from { opacity: 0; transform: scale(0.92) translateY(16px); }
            to   { opacity: 1; transform: scale(1) translateY(0); }
        }
        .portrait-anim {
            animation: portraitReveal 0.68s cubic-bezier(0.22, 1, 0.36, 1) both;
            will-change: transform, opacity;
        }

        @keyframes detailsReveal {
            from { opacity: 0; transform: translateY(18px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .details-anim {
            animation: detailsReveal 0.62s cubic-bezier(0.22, 1, 0.36, 1) both;
            will-change: transform, opacity;
        }

        @keyframes statsCoreReveal {
            from { opacity: 0; transform: scale(0.9) translateY(18px); }
            to   { opacity: 1; transform: scale(1) translateY(0); }
        }
        .stats-core-anim {
            animation: statsCoreReveal 0.65s cubic-bezier(0.22, 1, 0.36, 1) both;
            will-change: transform, opacity;
        }

        /* ══════════════════════════════════════════════════════
           BACKGROUND & ANIMATED EFFECTS
           ══════════════════════════════════════════════════════ */

        .cyber-bg {
            background-color: #030712;
            background-image:
                radial-gradient(at 15% 15%, rgba(6, 182, 212, 0.08) 0px, transparent 60%),
                radial-gradient(at 85% 85%, rgba(234, 179, 8, 0.06) 0px, transparent 60%);
        }

        .cyber-grid {
            position: absolute;
            inset: 0;
            z-index: 0;
            background-size: 50px 50px;
            background-image:
                linear-gradient(to right, rgba(255, 255, 255, 0.012) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(255, 255, 255, 0.012) 1px, transparent 1px);
            animation: gridMove 20s linear infinite;
            pointer-events: none;
        }

        @keyframes gridMove {
            0% {
                background-position: 0 0;
            }

            100% {
                background-position: 50px 50px;
            }
        }

        .laser-sweep {
            position: absolute;
            inset: 0;
            z-index: 0;
            background: linear-gradient(to bottom, transparent 40%, rgba(6, 182, 212, 0.03) 50%, transparent 60%);
            animation: sweep 8s infinite linear;
            pointer-events: none;
        }

        @keyframes sweep {
            0% {
                transform: translateY(-100%);
            }

            100% {
                transform: translateY(100%);
            }
        }

        @keyframes orbFloat {

            0%,
            100% {
                transform: translateY(0) scale(1);
            }

            50% {
                transform: translateY(-15px) scale(1.04);
            }
        }

        .orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(100px);
            pointer-events: none;
            animation: orbFloat 10s ease-in-out infinite;
        }

        /* Rotating Target Crosshairs */
        @keyframes rotateClockwise {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        @keyframes rotateCounterClockwise {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(-360deg);
            }
        }

        .rotate-cw {
            animation: rotateClockwise 18s linear infinite;
        }

        .rotate-ccw {
            animation: rotateCounterClockwise 24s linear infinite;
        }

        /* Vertical Laser Scanner Line */
        @keyframes scannerSweep {
            0% {
                top: 0%;
                opacity: 0;
            }

            10% {
                opacity: 0.8;
            }

            90% {
                opacity: 0.8;
            }

            100% {
                top: 100%;
                opacity: 0;
            }
        }

        .scanner-line {
            position: absolute;
            left: 0;
            width: 100%;
            height: 2.5px;
            z-index: 6;
            animation: scannerSweep 4s cubic-bezier(0.4, 0, 0.2, 1) infinite;
            pointer-events: none;
        }

        /* Blinking chevrons */
        @keyframes chevronBlink {

            0%,
            100% {
                opacity: 0.25;
            }

            50% {
                opacity: 1;
                filter: drop-shadow(0 0 4px currentColor);
            }
        }

        .chevron-pulse {
            animation: chevronBlink 1.2s infinite ease-in-out;
        }

        /* Leader border pulse */
        @keyframes pulseLeaderBorder {

            0%,
            100% {
                border-color: rgba(250, 204, 21, 0.35);
                box-shadow: 0 0 20px rgba(250, 204, 21, 0.08), inset 0 0 15px rgba(250, 204, 21, 0.03);
            }

            50% {
                border-color: rgba(250, 204, 21, 0.75);
                box-shadow: 0 0 35px rgba(250, 204, 21, 0.2), inset 0 0 25px rgba(250, 204, 21, 0.1);
            }
        }

        .leader-border-pulse {
            animation: pulseLeaderBorder 2.8s ease-in-out infinite;
        }

        @keyframes pulseLeaderText {

            0%,
            100% {
                text-shadow: 0 0 8px rgba(250, 204, 21, 0.6);
                color: #facc15;
            }

            50% {
                text-shadow: 0 0 20px rgba(250, 204, 21, 0.95), 0 0 35px rgba(250, 204, 21, 0.4);
                color: #ffffff;
            }
        }

        .leader-text-pulse {
            animation: pulseLeaderText 2.4s ease-in-out infinite;
        }

        /* Visor pulsing neon */
        @keyframes pulseVisor {

            0%,
            100% {
                opacity: 0.75;
            }

            50% {
                opacity: 1;
                filter: drop-shadow(0 0 6px currentColor);
            }
        }

        .visor-pulse {
            animation: pulseVisor 1.8s infinite ease-in-out;
        }

        /* ══════════════════════════════════════════════════════
           TACTICAL INTERFACE STYLES
           ══════════════════════════════════════════════════════ */

        .tactical-header {
            background: rgba(2, 4, 16, 0.92);
            border-bottom: 1.5px solid rgba(250, 204, 21, 0.3);
            backdrop-filter: blur(16px);
        }

        .tactical-footer {
            background: rgba(2, 4, 16, 0.9);
            border-top: 1px solid rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(16px);
        }

        /* Widescreen 5 Columns Deck Cards */
        @keyframes cardFloatFraggers {
            0%, 100% {
                transform: translateY(0);
                box-shadow: 0 15px 45px rgba(0, 0, 0, 0.7);
            }
            50% {
                transform: translateY(-5px);
                box-shadow: 0 20px 45px rgba(6, 182, 212, 0.08), 0 30px 80px rgba(0, 0, 0, 0.8);
            }
        }

        @keyframes cardFloatLeader {
            0%, 100% {
                transform: translateY(0);
                border-color: rgba(250, 204, 21, 0.3);
                box-shadow: 0 15px 45px rgba(250, 204, 21, 0.08), 0 25px 70px rgba(0, 0, 0, 0.8);
            }
            50% {
                transform: translateY(-8px);
                border-color: rgba(250, 204, 21, 0.65) !important;
                box-shadow: 0 25px 50px rgba(250, 204, 21, 0.18), 0 35px 90px rgba(0, 0, 0, 0.95);
            }
        }

        .fragger-card {
            background: linear-gradient(180deg, rgba(6, 10, 22, 0.94) 0%, rgba(3, 4, 8, 0.98) 100%);
            border: 1px solid rgba(6, 182, 212, 0.18);
            border-bottom: 3.5px solid rgba(6, 182, 212, 0.4) !important;
            box-shadow: 0 15px 45px rgba(0, 0, 0, 0.7), inset 0 1px 0 rgba(255, 255, 255, 0.02);
            backdrop-filter: blur(16px);
            /* Dynamic Height Calculation prevents any clipping */
            height: auto;
            min-height: 670px;
            animation: cardFloatFraggers 5.5s infinite ease-in-out;
        }

        .fragger-card:nth-child(even) {
            animation-delay: 1.5s;
        }

        .fragger-card:nth-child(3n) {
            animation-delay: 3s;
        }

        .leader-card-active {
            background: linear-gradient(180deg, rgba(30, 22, 6, 0.96) 0%, rgba(5, 6, 12, 0.99) 100%);
            border: 1px solid rgba(250, 204, 21, 0.3);
            border-bottom: 4.5px solid #facc15 !important;
            animation: cardFloatLeader 5s infinite ease-in-out !important;
        }

        /* Futuristic Angled Corner Cuts */
        .angled-corners {
            clip-path: polygon(15px 0%, 100% 0%, 100% calc(100% - 15px), calc(100% - 15px) 100%, 0% 100%, 0% 15px);
        }

        /* Tech corner brackets */
        .bracket-corner::before,
        .bracket-corner::after,
        .bracket-inner::before,
        .bracket-inner::after {
            content: '';
            position: absolute;
            width: 14px;
            height: 14px;
            border-color: rgba(6, 182, 212, 0.25);
            border-style: solid;
            pointer-events: none;
        }

        .bracket-corner::before {
            top: -2px;
            left: -2px;
            border-width: 2.5px 0 0 2.5px;
        }

        .bracket-corner::after {
            top: -2px;
            right: -2px;
            border-width: 2.5px 2.5px 0 0;
        }

        .bracket-inner::before {
            bottom: -2px;
            left: -2px;
            border-width: 0 0 2.5px 2.5px;
        }

        .bracket-inner::after {
            bottom: -2px;
            right: -2px;
            border-width: 0 2.5px 2.5px 0;
        }

        .leader-brackets::before,
        .leader-brackets::after,
        .leader-brackets-inner::before,
        .leader-brackets-inner::after {
            border-color: #facc15 !important;
        }

        /* Geometric Rank Badge */
        .rank-badge {
            clip-path: polygon(0 0, 100% 0, 80% 100%, 20% 100%);
            background: rgba(2, 6, 23, 0.96);
            border: 1px solid rgba(6, 182, 212, 0.2);
            border-top: none;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.45);
        }

        .leader-rank-badge {
            border-color: rgba(250, 204, 21, 0.35);
        }

        /* Holographic micro-grid backdrop */
        .bg-grid-tiny {
            background-size: 8px 8px;
            background-image:
                linear-gradient(to right, rgba(255, 255, 255, 0.02) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(255, 255, 255, 0.02) 1px, transparent 1px);
        }

        /* Neon glows */
        .text-shadow-cyan {
            text-shadow: 0 0 10px rgba(6, 182, 212, 0.6), 0 0 20px rgba(6, 182, 212, 0.2);
        }

        .text-shadow-gold {
            text-shadow: 0 0 10px rgba(250, 204, 21, 0.65), 0 0 20px rgba(250, 204, 21, 0.25);
        }

    </style>

    {{-- Main screen wrapper — id used for live bg switching --}}
    <div id="screen-wrapper" class="w-full h-full min-h-screen {{ $bgType === 'animated' ? 'cyber-bg' : 'bg-transparent' }} relative flex flex-col justify-between overflow-hidden text-slate-100 font-esports select-none">

        {{-- Custom background video layer (always in DOM, shown/hidden dynamically) --}}
        <video id="bg-video" autoplay loop muted playsinline class="absolute inset-0 w-full h-full object-cover z-0 {{ ($bgType === 'custom' && $customVideo) ? '' : 'hidden' }}">
            @if($bgType === 'custom' && $customVideo)
            <source src="{{ asset('storage/' . $customVideo) }}" type="video/mp4">
            @endif
        </video>
        <div id="bg-video-overlay" class="absolute inset-0 bg-slate-950/85 z-0 backdrop-blur-[1px] {{ ($bgType === 'custom' && $customVideo) ? '' : 'hidden' }}"></div>

        {{-- Interactive Scanning laser overlays --}}
        <div id="bg-grid" class="cyber-grid z-0 opacity-30 {{ $bgType === 'transparent' ? 'hidden' : '' }}"></div>
        <div id="bg-sweep" class="laser-sweep z-0 {{ $bgType === 'transparent' ? 'hidden' : '' }}"></div>

        {{-- Ambient glows --}}
        <div class="orb z-0" style="width:450px;height:450px;background:rgba(250,204,21,0.05);top:5%;left:10%;"></div>
        <div class="orb z-0" style="width:500px;height:500px;background:rgba(6,182,212,0.04);bottom:5%;right:10%;"></div>

        {{-- ─── HEADER (slides down) ────────────────────────── --}}
        <div class="slide-down tactical-header relative z-10 flex justify-between items-center px-10 py-3.5">
            <div class="flex items-center gap-5">
                <div class="p-2 rounded-lg bg-slate-950/95 border border-yellow-500/30 shadow-inner flex items-center justify-center">
                    <img src="{{ $activeMatch->tournament->logo_image ? asset('storage/'.$activeMatch->tournament->logo_image) : asset('img/defult_team_logo.png') }}" onerror="this.src='{{ asset('img/defult_team_logo.png') }}';" class="w-11 h-11 object-contain">
                </div>
                <div>
                    <div class="text-yellow-400 text-xs font-black uppercase tracking-[0.4em] font-body leading-none">FRAGGERS STATS</div>
                    <h1 class="text-3xl font-black uppercase tracking-tight leading-none text-white mt-1.5">{{ $activeMatch->tournament->name }}</h1>
                </div>
            </div>
            <div class="flex items-center gap-6">
                <div class="px-5 py-2 rounded-lg text-center bg-slate-950/85 border border-yellow-500/20 shadow-inner">
                    <div class="text-yellow-400 text-[10px] font-black uppercase tracking-[0.3em] font-body leading-none">ACTIVE MATCH</div>
                    <div class="text-white text-lg font-black uppercase leading-none mt-1.5">{{ $activeMatch->name }}</div>
                </div>
                <div class="text-right">
                    <div class="text-slate-500 text-[9px] font-black uppercase tracking-[0.35em] font-body leading-none">SYS // DATA_PHASE // ELIMS</div>
                    <div class="text-2xl font-black uppercase tracking-wider text-yellow-400 mt-1.5 leader-text-pulse">TOP 5 FRAGGERS</div>
                </div>
            </div>
        </div>

        {{-- ─── MAIN CONTENT ────────────────────────────────── --}}
        <div class="relative z-10 flex-1 flex px-10 py-5 min-h-0 items-center justify-center">

            @if($topFraggers->isEmpty())
            <div class="glass-panel rounded-2xl p-16 text-center text-slate-500 text-base font-black uppercase tracking-widest font-hud">
                No active match statistics recorded yet.
            </div>
            @else
            {{-- 5 Columns staggered cards deck --}}
            <div class="grid grid-cols-5 gap-6 w-full max-w-440 items-end justify-center">
                @foreach($topFraggers as $idx => $player)
                @php
                $rank = $idx + 1;
                $isLeader = ($rank === 1);
                @endphp

                <div class="card-reveal fragger-card angled-corners flex flex-col justify-between items-center rounded-2xl p-5 relative overflow-hidden {{ $isLeader ? 'leader-card-active leader-border-pulse' : '' }}" style="animation-delay:{{ 0.08 + $idx * 0.08 }}s;">

                    {{-- Corner tech details --}}
                    <div class="bracket-corner {{ $isLeader ? 'leader-brackets' : '' }} absolute inset-0 pointer-events-none"></div>
                    <div class="bracket-inner {{ $isLeader ? 'leader-brackets-inner' : '' }} absolute inset-0 pointer-events-none"></div>

                    {{-- Hexagonal/Polygon Rank Badge --}}
                    <div class="absolute top-0 left-1/2 -translate-x-1/2 rank-badge {{ $isLeader ? 'leader-rank-badge' : '' }} px-6 py-1 z-20 badge-drop-anim" style="animation-delay:{{ 0.08 + $idx * 0.08 + 0.18 }}s;">
                        <span class="font-hud text-base font-black tracking-wider leading-none {{ $isLeader ? 'text-yellow-400 animate-pulse' : 'text-slate-400' }}">
                            #0{{ $rank }}
                        </span>
                    </div>

                    {{-- PLAYER PORTRAIT BOX (STRICT 9:16 ASPECT RATIO) --}}
                    <div class="w-full aspect-9/16 shrink-0 rounded-xl overflow-hidden relative mt-2.5 flex items-center justify-center bg-slate-950/80 border border-slate-900 shadow-inner portrait-anim" style="animation-delay:{{ 0.08 + $idx * 0.08 + 0.12 }}s;">

                        {{-- Concentric Tech HUD Indicators --}}
                        <div class="absolute inset-0 z-10 pointer-events-none overflow-hidden">
                            <!-- Inner border coordinates outline -->
                            <div class="absolute inset-2 border border-dashed {{ $isLeader ? 'border-yellow-500/15' : 'border-cyan-500/15' }} rounded-lg"></div>

                            <!-- High-contrast hud corner arrows -->
                            <div class="absolute top-3 left-3 w-3 h-3 border-t-2 border-l-2 {{ $isLeader ? 'border-yellow-500/70' : 'border-cyan-500/70' }}"></div>
                            <div class="absolute top-3 right-3 w-3 h-3 border-t-2 border-r-2 {{ $isLeader ? 'border-yellow-500/70' : 'border-cyan-500/70' }}"></div>
                            <div class="absolute bottom-3 left-3 w-3 h-3 border-b-2 border-l-2 {{ $isLeader ? 'border-yellow-500/70' : 'border-cyan-500/70' }}"></div>
                            <div class="absolute bottom-3 right-3 w-3 h-3 border-b-2 border-r-2 {{ $isLeader ? 'border-yellow-500/70' : 'border-cyan-500/70' }}"></div>

                            <!-- Concentric scopes/target HUD rings (rotating) -->
                            <div class="absolute inset-0 flex items-center justify-center opacity-40">
                                <svg class="w-48 h-48 rotate-cw {{ $isLeader ? 'text-yellow-500/40' : 'text-cyan-500/40' }}" viewBox="0 0 100 100">
                                    <circle cx="50" cy="50" r="45" stroke="currentColor" stroke-width="0.75" stroke-dasharray="6 8" fill="none" />
                                    <circle cx="50" cy="50" r="35" stroke="currentColor" stroke-width="0.5" stroke-dasharray="30 15" fill="none" />
                                </svg>
                                <svg class="w-48 h-48 rotate-ccw absolute {{ $isLeader ? 'text-yellow-500/50' : 'text-cyan-500/50' }}" viewBox="0 0 100 100">
                                    <circle cx="50" cy="50" r="40" stroke="currentColor" stroke-width="1.2" stroke-dasharray="3 18" fill="none" />
                                    <circle cx="50" cy="50" r="30" stroke="currentColor" stroke-width="0.5" fill="none" opacity="0.3" />
                                </svg>
                            </div>

                            <!-- Tactical technical stats labels -->
                            <div class="absolute top-4 left-4 font-hud text-[6px] tracking-widest {{ $isLeader ? 'text-yellow-400/60' : 'text-cyan-400/60' }}">SYS_LOCK_ACQ // 0{{ $rank }}</div>
                            <div class="absolute top-4 right-4 font-hud text-[6px] tracking-widest {{ $isLeader ? 'text-yellow-400/60' : 'text-cyan-400/60' }}">POS_X.0{{ $rank }}9</div>
                            <div class="absolute bottom-8 right-4 font-hud text-[6px] tracking-widest {{ $isLeader ? 'text-yellow-400/60' : 'text-cyan-400/60' }}">TRGT_SCAN_ON</div>
                        </div>

                        {{-- Sweeping laser scanner line --}}
                        <div class="scanner-line {{ $isLeader ? 'bg-yellow-500 shadow-[0_0_10px_#facc15]' : 'bg-cyan-500 shadow-[0_0_10px_#38bdf8]' }}"></div>

                        @if($player['image'])
                        <img src="{{ asset('storage/'.$player['image']) }}" onerror="this.style.display='none'; document.getElementById('placeholder_{{ $idx }}').classList.remove('hidden');" class="w-full h-full object-cover object-top relative z-5" alt="{{ $player['ign'] }}">
                        @endif

                        <div id="placeholder_{{ $idx }}" class="w-full h-full relative z-2 {{ $player['image'] ? 'hidden' : '' }}">
                            {{-- Stenciled operator vector graphic --}}
                            <svg class="w-full h-full text-slate-500" viewBox="0 0 180 320" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <defs>
                                    <radialGradient id="avatarGlow_{{ $idx }}" cx="50%" cy="40%" r="60%">
                                        <stop offset="0%" stop-color="{{ $isLeader ? '#facc15' : '#06b6d4' }}" stop-opacity="0.35" />
                                        <stop offset="100%" stop-color="transparent" stop-opacity="0" />
                                    </radialGradient>
                                    <linearGradient id="stencilGrad_{{ $idx }}" x1="0%" y1="0%" x2="0%" y2="100%">
                                        <stop offset="0%" stop-color="{{ $isLeader ? '#facc15' : '#06b6d4' }}" stop-opacity="0.85" />
                                        <stop offset="60%" stop-color="{{ $isLeader ? '#d97706' : '#0891b2' }}" stop-opacity="0.3" />
                                        <stop offset="100%" stop-color="#020617" stop-opacity="0.95" />
                                    </linearGradient>
                                </defs>

                                <!-- Grid backplate -->
                                <rect width="180" height="320" fill="#020617" />
                                <circle cx="90" cy="120" r="80" fill="url(#avatarGlow_{{ $idx }})" />

                                <!-- Tech grids -->
                                <g opacity="0.12">
                                    <path d="M20 0 V320 M40 0 V320 M60 0 V320 M80 0 V320 M100 0 V320 M120 0 V320 M140 0 V320 M160 0 V320" stroke="currentColor" stroke-width="0.5" />
                                    <path d="M0 40 H180 M0 80 H180 M0 120 H180 M0 160 H180 M0 200 H180 M0 240 H180 M0 280 H180" stroke="currentColor" stroke-width="0.5" />
                                </g>

                                <!-- Soldier Silhouette -->
                                <!-- Helmet -->
                                <path d="M90 65 C80 65 76 71 76 81 C76 89 77 92 81 96 L82 101 H98 L99 96 C103 92 104 89 104 81 C104 71 100 65 90 65 Z" fill="url(#stencilGrad_{{ $idx }})" stroke="{{ $isLeader ? '#facc15' : '#06b6d4' }}" stroke-width="1.5" />

                                <!-- Glowing Visor -->
                                <path d="M80 80 H100 V84 H80 Z" fill="{{ $isLeader ? '#facc15' : '#00f0ff' }}" class="visor-pulse" style="filter: drop-shadow(0 0 4px {{ $isLeader ? '#facc15' : '#00f0ff' }});" />

                                <!-- Shoulders & Body -->
                                <path d="M82 101 L80 108 C68 114 55 125 50 145 L48 185 H132 L130 145 C125 125 112 114 100 108 L98 101 H82 Z" fill="url(#stencilGrad_{{ $idx }})" stroke="{{ $isLeader ? '#facc15' : '#06b6d4' }}" stroke-width="1.5" />

                                <!-- Vest coordinates details -->
                                <path d="M68 122 L90 140 L112 122" stroke="{{ $isLeader ? '#facc15' : '#06b6d4' }}" stroke-width="1" opacity="0.4" />
                                <path d="M78 108 V185 M102 108 V185" stroke="{{ $isLeader ? '#facc15' : '#06b6d4' }}" stroke-width="1" opacity="0.4" />
                            </svg>
                        </div>

                        {{-- Role Pill Badge Overlay --}}
                        <div class="absolute bottom-2 left-2 bg-slate-950/90 border border-slate-800/80 px-2 py-0.5 rounded text-[8px] tracking-wider uppercase font-hud text-slate-300 z-20 shadow-md">
                            {{ $player['role'] ?: 'Player' }}
                        </div>
                    </div>

                    {{-- PLAYER DETAILS --}}
                    <div class="text-center w-full flex flex-col items-center justify-center mt-3 details-anim" style="animation-delay:{{ 0.08 + $idx * 0.08 + 0.22 }}s;">
                        <h3 class="text-2xl font-black uppercase leading-none tracking-wider font-esports truncate max-w-full {{ $isLeader ? 'leader-text-pulse text-3xl' : 'text-white' }}">
                            {{ $player['ign'] }}
                        </h3>
                        @if($player['name'])
                        <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider font-body mt-1 block truncate max-w-full leading-none">
                            {{ $player['name'] }}
                        </span>
                        @endif

                        {{-- Semi-transparent angled pill badges for Team Name/Logo --}}
                        <div class="flex items-center gap-2 mt-3 justify-center bg-slate-950/80 border border-slate-900 rounded-md px-3 py-1 shadow-inner max-w-full">
                            <div class="w-5 h-5 bg-slate-950 border border-slate-900 p-0.5 rounded-full flex items-center justify-center shrink-0 shadow-inner">
                                <img src="{{ $player['team_logo'] ? asset('storage/'.$player['team_logo']) : asset('img/defult_team_logo.png') }}" onerror="this.src='{{ asset('img/defult_team_logo.png') }}';" class="w-3.5 h-3.5 object-contain">
                            </div>
                            <span class="text-slate-300 font-bold uppercase tracking-widest text-[9px] truncate max-w-32.5 font-body">
                                {{ $player['team_name'] }}
                            </span>
                        </div>
                    </div>

                    {{-- HOLOGRAPHIC STATS CORE (TOTAL ELIMS HIGHLIGHT) --}}
                    <div class="w-full mt-4.5 bg-slate-950/90 border {{ $isLeader ? 'border-yellow-500/50 shadow-[0_0_15px_rgba(234,179,8,0.15)]' : 'border-cyan-500/28 shadow-[0_0_15px_rgba(6,182,212,0.08)]' }} rounded-xl py-2.5 px-3 shadow-inner relative overflow-hidden stats-core-anim" style="animation-delay:{{ 0.08 + $idx * 0.08 + 0.28 }}s;">
                        <div class="absolute inset-0 bg-grid-tiny opacity-15 pointer-events-none"></div>
                        <div class="absolute inset-0 bg-linear-to-r {{ $isLeader ? 'from-yellow-500/5 to-transparent' : 'from-cyan-500/5 to-transparent' }} pointer-events-none"></div>

                        <div class="w-full flex flex-col items-center justify-center relative z-10">
                            <span class="text-[9px] font-black tracking-[0.3em] uppercase font-hud leading-none {{ $isLeader ? 'text-yellow-400' : 'text-cyan-400' }}">TOTAL ELIMS</span>
                            <div class="flex items-center justify-center gap-3.5 mt-1 leading-none">
                                <span class="font-hud text-base font-black chevron-pulse {{ $isLeader ? 'text-yellow-500' : 'text-cyan-500' }}">&gt;&gt;</span>
                                <div class="font-hud text-5xl font-black leading-none {{ $isLeader ? 'text-yellow-400 text-6xl leader-text-pulse text-shadow-gold' : 'text-slate-100 text-shadow-cyan' }}">
                                    {{ $player['kills'] }}
                                </div>
                                <span class="font-hud text-base font-black chevron-pulse {{ $isLeader ? 'text-yellow-500' : 'text-cyan-500' }}">&lt;&lt;</span>
                            </div>
                        </div>
                    </div>

                </div>
                @endforeach
            </div>
            @endif

        </div>

        {{-- ─── FOOTER (slides up) ─────────────────────────── --}}
        <div class="slide-up-footer tactical-footer relative z-10 flex items-center justify-between px-10 py-2.5 font-body text-[9px] font-bold text-slate-500 tracking-[0.4em] uppercase">
            <span>SYS_LOC // 0x3FCA09</span>
            <div class="h-px w-36 shrink-0" style="background:linear-gradient(to right,transparent,rgba(250,204,21,0.2),transparent);"></div>
            <span class="text-yellow-500/60">Official Match Kill Leaderboard Overlay</span>
            <div class="h-px w-36 shrink-0" style="background:linear-gradient(to right,transparent,rgba(250,204,21,0.2),transparent);"></div>
            <span>SYS_VER_{{ $systemVersion }}</span>
        </div>
    </div>

    <script type="module">
        document.addEventListener("DOMContentLoaded", function () {
            const wrapper = document.getElementById('screen-wrapper');
            const grid = document.getElementById('bg-grid');
            const sweep = document.getElementById('bg-sweep');
            const video = document.getElementById('bg-video');
            const videoOverlay = document.getElementById('bg-video-overlay');

            function applyBackground(bgType, customVideoUrl) {
                if (!wrapper) return;
                
                // Reset classes
                wrapper.classList.remove('cyber-bg', 'bg-transparent');
                
                if (bgType === 'animated') {
                    wrapper.classList.add('cyber-bg');
                    if (grid) grid.classList.remove('hidden');
                    if (sweep) sweep.classList.remove('hidden');
                    if (video) video.classList.add('hidden');
                    if (videoOverlay) videoOverlay.classList.add('hidden');
                } else if (bgType === 'custom') {
                    wrapper.classList.add('bg-transparent');
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
                    wrapper.classList.add('bg-transparent');
                    if (grid) grid.classList.add('hidden');
                    if (sweep) sweep.classList.add('hidden');
                    if (video) video.classList.add('hidden');
                    if (videoOverlay) videoOverlay.classList.add('hidden');
                }
            }

            // Apply active background state initially
            applyBackground("{{ $bgType }}", "{{ $customVideo ? asset('storage/' . $customVideo) : '' }}");

            // Echo channel events for real-time director background switching
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
