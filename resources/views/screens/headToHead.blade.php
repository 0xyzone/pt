<x-base title="HEAD TO HEAD MATCHUP">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;900&family=Rajdhani:wght@600;700;900&family=Orbitron:wght@700;800;900&family=Share+Tech+Mono&display=swap" rel="stylesheet">

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

        .font-mono-tech {
            font-family: 'Share Tech Mono', monospace;
        }

        /* ══════════════════════════════════════════════════════
           ENTRANCE ANIMATIONS — GPU-accelerated, expo-out
           ══════════════════════════════════════════════════════ */

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-50px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .slide-down {
            animation: slideDown 0.8s cubic-bezier(0.16, 1, 0.3, 1) both;
            will-change: transform, opacity;
        }

        @keyframes slideLeft {
            from { opacity: 0; transform: translateX(-80px) scale(0.95); filter: blur(10px); }
            to   { opacity: 1; transform: translateX(0) scale(1); filter: blur(0); }
        }
        .slide-left {
            animation: slideLeft 1s cubic-bezier(0.16, 1, 0.3, 1) both;
            will-change: transform, opacity;
        }

        @keyframes slideRight {
            from { opacity: 0; transform: translateX(80px) scale(0.95); filter: blur(10px); }
            to   { opacity: 1; transform: translateX(0) scale(1); filter: blur(0); }
        }
        .slide-right {
            animation: slideRight 1s cubic-bezier(0.16, 1, 0.3, 1) both;
            will-change: transform, opacity;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(50px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .slide-up {
            animation: slideUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) both;
            will-change: transform, opacity;
        }

        @keyframes slideUpFooter {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .slide-up-footer {
            animation: slideUpFooter 0.8s cubic-bezier(0.16, 1, 0.3, 1) both;
            animation-delay: 0.9s;
            will-change: transform, opacity;
        }

        @keyframes logoReveal {
            from {
                opacity: 0;
                transform: scale(0.6) rotate(-15deg);
                filter: brightness(2) blur(8px);
            }
            to {
                opacity: 1;
                transform: scale(1) rotate(0deg);
                filter: brightness(1) blur(0);
            }
        }

        .logo-reveal {
            animation: logoReveal 0.9s cubic-bezier(0.16, 1, 0.3, 1) both;
            will-change: transform, opacity;
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
            background: linear-gradient(to bottom, transparent 48%, rgba(16, 185, 129, 0.05) 50%, transparent 52%);
            animation: scanline 12s linear infinite;
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
                linear-gradient(to right, rgba(255, 255, 255, 0.01) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(255, 255, 255, 0.01) 1px, transparent 1px);
            animation: gridScroll 25s linear infinite;
            pointer-events: none;
        }

        @keyframes orbFloat {
            0%, 100% { transform: translateY(0) scale(1); opacity: 0.3; }
            50% { transform: translateY(-20px) scale(1.08); opacity: 0.5; }
        }

        .orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(110px);
            pointer-events: none;
            animation: orbFloat 12s ease-in-out infinite;
        }

        /* Center VS neon indicator pulsing */
        @keyframes versusPulse {
            0%, 100% { text-shadow: 0 0 10px rgba(16, 185, 129, 0.85), 0 0 20px rgba(16, 185, 129, 0.45); filter: drop-shadow(0 0 4px rgba(16, 185, 129, 0.2)); }
            50% { text-shadow: 0 0 25px rgba(16, 185, 129, 1), 0 0 40px rgba(16, 185, 129, 0.6); filter: drop-shadow(0 0 12px rgba(16, 185, 129, 0.5)); }
        }

        .versus-glow {
            animation: versusPulse 2s ease-in-out infinite;
            color: #10b981 !important;
        }

        @keyframes slideUpCapsule {
            from { opacity: 0; transform: translateY(50px) scale(0.95); filter: blur(8px); }
            to   { opacity: 1; transform: translateY(0) scale(1); filter: blur(0); }
        }
        .slide-up-capsule {
            animation: slideUpCapsule 0.9s cubic-bezier(0.16, 1, 0.3, 1) both;
            will-change: transform, opacity;
        }

        /* High-contrast neon text shadows */
        .text-glow-cyan {
            text-shadow: 0 0 15px rgba(6, 182, 212, 0.8), 0 0 25px rgba(6, 182, 212, 0.4);
            color: #22d3ee !important;
        }

        .text-glow-gold {
            text-shadow: 0 0 15px rgba(250, 204, 21, 0.8), 0 0 25px rgba(250, 204, 21, 0.4);
            color: #fde047 !important;
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
            animation: rotateClockwise 35s linear infinite;
        }

        .rotate-slow {
            animation: rotateClockwise 120s linear infinite;
        }

        /* ══════════════════════════════════════════════════════
           PREMIUM GLASS CYBER CARDS
           ══════════════════════════════════════════════════════ */
        .team-card-cyber {
            position: relative;
            background: linear-gradient(180deg, rgba(15, 23, 42, 0.94) 0%, rgba(2, 6, 23, 0.98) 100%);
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            overflow: hidden;
            box-shadow: 0 30px 60px -15px rgba(0, 0, 0, 0.85);
        }

        .team-card-cyber::before {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: inherit;
            padding: 1.5px;
            background: linear-gradient(to bottom, rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.02));
            -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            -webkit-mask-composite: xor;
            mask-composite: exclude;
            pointer-events: none;
        }

        .team-card-cyber.cyan-theme {
            border: 1px solid rgba(6, 182, 212, 0.25);
            box-shadow: 0 30px 60px -15px rgba(0, 0, 0, 0.85), 
                        0 0 40px rgba(6, 182, 212, 0.05),
                        inset 0 1px 0 rgba(6, 182, 212, 0.1);
        }
        .team-card-cyber.cyan-theme:hover {
            border-color: rgba(6, 182, 212, 0.6);
            box-shadow: 0 35px 70px -15px rgba(0, 0, 0, 0.95), 
                        0 0 50px rgba(6, 182, 212, 0.2),
                        inset 0 1px 0 rgba(6, 182, 212, 0.2);
            transform: translateY(-5px) scale(1.01);
        }

        .team-card-cyber.amber-theme {
            border: 1px solid rgba(250, 204, 21, 0.2);
            box-shadow: 0 30px 60px -15px rgba(0, 0, 0, 0.85), 
                        0 0 40px rgba(250, 204, 21, 0.04),
                        inset 0 1px 0 rgba(250, 204, 21, 0.08);
        }
        .team-card-cyber.amber-theme:hover {
            border-color: rgba(250, 204, 21, 0.5);
            box-shadow: 0 35px 70px -15px rgba(0, 0, 0, 0.95), 
                        0 0 50px rgba(250, 204, 21, 0.15),
                        inset 0 1px 0 rgba(250, 204, 21, 0.15);
            transform: translateY(-5px) scale(1.01);
        }

        /* ══════════════════════════════════════════════════════
           LASER SCANNER EFFECT
           ══════════════════════════════════════════════════════ */
        @keyframes sweepVertical {
            0% { transform: translateY(-100%); opacity: 0; }
            10%, 90% { opacity: 0.6; }
            100% { transform: translateY(220%); opacity: 0; }
        }
        .laser-sweep {
            position: absolute;
            left: 0;
            right: 0;
            height: 2px;
            pointer-events: none;
            z-index: 2;
            animation: sweepVertical 6.5s cubic-bezier(0.25, 0.46, 0.45, 0.94) infinite;
        }
        .laser-cyan {
            background: linear-gradient(to right, transparent, rgba(6, 182, 212, 0.8), transparent);
            box-shadow: 0 0 12px rgba(6, 182, 212, 0.8), 0 0 4px rgba(6, 182, 212, 0.5);
        }
        .laser-amber {
            background: linear-gradient(to right, transparent, rgba(250, 204, 21, 0.8), transparent);
            box-shadow: 0 0 12px rgba(250, 204, 21, 0.8), 0 0 4px rgba(250, 204, 21, 0.5);
            animation-delay: 3.2s;
        }

        /* ══════════════════════════════════════════════════════
           DYNAMIC GROWING PROGRESS BARS
           ══════════════════════════════════════════════════════ */
        @keyframes fillBarLeft {
            from { width: 0%; }
            to { width: var(--fill-width-left); }
        }
        @keyframes fillBarRight {
            from { width: 0%; }
            to { width: var(--fill-width-right); }
        }
        .fill-bar-left {
            width: 0%;
            animation: fillBarLeft 1.5s cubic-bezier(0.075, 0.82, 0.165, 1) both;
        }
        .fill-bar-right {
            width: 0%;
            animation: fillBarRight 1.5s cubic-bezier(0.075, 0.82, 0.165, 1) both;
        }

        /* Glitch animations */
        @keyframes techBlink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.4; }
        }
        .tech-blink {
            animation: techBlink 1.5s ease-in-out infinite;
        }

        .stat-card-glow-cyan {
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.75),
                        inset 0 1px 0 rgba(255, 255, 255, 0.05),
                        0 0 20px rgba(6, 182, 212, 0.06);
        }
        .stat-card-glow-amber {
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.75),
                        inset 0 1px 0 rgba(255, 255, 255, 0.05),
                        0 0 20px rgba(250, 204, 21, 0.06);
        }
    </style>

    {{-- Main screen wrapper — id used for live bg switching --}}
    <div id="screen-wrapper" class="w-full h-full min-h-screen bg-transparent relative flex flex-col justify-between overflow-hidden text-slate-100 font-esports select-none">

        {{-- ─── TACTICAL SPLIT-SCREEN BACKGROUND PANELS ────────── --}}
        {{-- Left Blue/Cyan Arena --}}
        <div id="split-bg-left" class="absolute inset-0 w-full h-full split-bg-left z-0 transition-all duration-300 {{ $bgType === 'transparent' ? 'hidden' : '' }}"></div>
        
        {{-- Right Gold/Amber Arena (Diagonally Clipped) --}}
        <div id="split-bg-right" class="absolute inset-0 left-[45%] w-[55%] h-full split-bg-right z-0 transition-all duration-300 {{ $bgType === 'transparent' ? 'hidden' : '' }}" style="clip-path: polygon(15% 0, 100% 0, 100% 100%, 0 100%); border-left: 2.5px solid rgba(250, 204, 21, 0.25);"></div>

        {{-- Ambient glows --}}
        <div class="orb z-1" style="width:500px;height:500px;background:rgba(6,182,212,0.06);top:15%;left:5%;"></div>
        <div class="orb z-1" style="width:550px;height:550px;background:rgba(250,204,21,0.05);bottom:15%;right:5%;"></div>

        {{-- Counter-rotating center HUD scopes --}}
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] opacity-10 pointer-events-none z-0">
            <svg class="w-full h-full text-emerald-500/40 rotate-cw" viewBox="0 0 100 100" style="animation-duration: 45s;">
                <circle cx="50" cy="50" r="48" stroke="currentColor" stroke-width="0.3" stroke-dasharray="1 3" fill="none" />
                <circle cx="50" cy="50" r="40" stroke="currentColor" stroke-width="0.5" stroke-dasharray="8 4" fill="none" />
                <circle cx="50" cy="50" r="28" stroke="currentColor" stroke-width="0.2" fill="none" />
                <path d="M 50 2 L 50 12 M 50 88 L 50 98 M 2 50 L 12 50 M 88 50 L 98 50" stroke="currentColor" stroke-width="0.8" />
            </svg>
        </div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[520px] h-[520px] opacity-5 pointer-events-none z-0">
            <svg class="w-full h-full text-cyan-500/30" viewBox="0 0 100 100" style="animation: rotateClockwise 28s linear infinite reverse;">
                <circle cx="50" cy="50" r="45" stroke="currentColor" stroke-width="0.4" stroke-dasharray="15 5" fill="none" />
                <circle cx="50" cy="50" r="35" stroke="currentColor" stroke-width="0.3" stroke-dasharray="2 10" fill="none" />
            </svg>
        </div>

        {{-- Enormous background watermarked crests with organic rotation --}}
        <img id="bg-watermark-left" src="{{ $stat1 && $stat1->tournamentTeam->logo_image ? asset('storage/'.$stat1->tournamentTeam->logo_image) : asset('img/defult_team_logo.png') }}" onerror="this.src='{{ asset('img/defult_team_logo.png') }}';" class="absolute left-[3%] top-[25%] w-110 h-110 opacity-[0.04] pointer-events-none z-0 object-contain rotate-slow {{ $bgType === 'transparent' ? 'hidden' : '' }}">
        <img id="bg-watermark-right" src="{{ $stat2 && $stat2->tournamentTeam->logo_image ? asset('storage/'.$stat2->tournamentTeam->logo_image) : asset('img/defult_team_logo.png') }}" onerror="this.src='{{ asset('img/defult_team_logo.png') }}';" class="absolute right-[3%] top-[25%] w-110 h-110 opacity-[0.04] pointer-events-none z-0 object-contain rotate-slow {{ $bgType === 'transparent' ? 'hidden' : '' }}" style="animation-direction: reverse;">

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

        {{-- ─── HEADER (slides down) ────────────────────────── --}}
        <div class="slide-down tactical-header relative z-10 flex justify-between items-center px-10 py-4 border-b border-slate-900 bg-slate-950/80 backdrop-blur-md">
            <div class="flex items-center gap-5">
                <div class="p-2 rounded-xl bg-slate-950 border border-slate-800 shadow-inner">
                    <img src="{{ $tournament->logo_image ? asset('storage/'.$tournament->logo_image) : asset('img/defult_team_logo.png') }}" onerror="this.src='{{ asset('img/defult_team_logo.png') }}';" class="w-12 h-12 object-contain">
                </div>
                <div>
                    <div class="text-cyan-400 text-[10px] font-black uppercase tracking-[0.4em] font-body leading-none">HEAD-TO-HEAD SHOWDOWN</div>
                    <h1 class="text-3xl font-black uppercase tracking-tight leading-none text-white mt-2">{{ $tournament->name }}</h1>
                </div>
            </div>
            <div class="flex items-center gap-6">
                <div class="px-5 py-2.5 rounded-xl text-center bg-slate-950/90 border border-emerald-500/30 shadow-[0_0_15px_rgba(16,185,129,0.05)]">
                    <div class="text-emerald-400 text-[9px] font-black uppercase tracking-[0.3em] font-body leading-none">ACTIVE ROUND</div>
                    <div class="text-white text-lg font-black uppercase leading-none mt-1.5">{{ $activeMatch->name }}</div>
                </div>
                <div class="text-right">
                    <div class="text-slate-500 text-[9px] font-mono-tech tracking-[0.25em] leading-none">SYS // NET_DATA_VS</div>
                    <div class="text-2xl font-black uppercase tracking-wider text-emerald-400 mt-1.5 versus-glow">VERSUS OVERLAY</div>
                </div>
            </div>
        </div>

        {{-- ─── MAIN CONTENT (CINEMATIC ARENA LAYOUT) ────────── --}}
        <div class="relative z-10 flex-1 flex px-12 py-6 min-h-0 items-center justify-between">

            {{-- ── LEFT FOREGROUND COLUMN: TEAM A (slides from left) ── --}}
            <div class="slide-left team-card-cyber cyan-theme rounded-3xl p-8 flex flex-col items-center justify-center z-10 w-96 ml-[4%] relative" style="animation-delay:0.05s;">
                <div class="laser-sweep laser-cyan"></div>
                
                {{-- Corner tech elements --}}
                <div class="absolute top-0 left-0 w-8 h-8 border-t-2 border-l-2 border-cyan-400 rounded-tl-3xl opacity-70"></div>
                <div class="absolute bottom-0 right-0 w-8 h-8 border-b-2 border-r-2 border-cyan-400 rounded-br-3xl opacity-70"></div>
                <div class="absolute top-0 right-0 w-2 h-2 border-t-2 border-r-2 border-slate-700"></div>
                <div class="absolute bottom-0 left-0 w-2 h-2 border-b-2 border-l-2 border-slate-700"></div>

                {{-- Rotating Scope behind the logo --}}
                <div class="absolute inset-0 flex items-center justify-center opacity-25 z-0 pointer-events-none -top-16">
                    <svg class="w-64 h-64 text-cyan-500 rotate-cw" viewBox="0 0 100 100" style="animation-duration: 22s;">
                        <circle cx="50" cy="50" r="44" stroke="currentColor" stroke-width="0.8" stroke-dasharray="8 12" fill="none" />
                        <line x1="50" y1="0" x2="50" y2="100" stroke="currentColor" stroke-width="0.5" stroke-dasharray="3 3" />
                        <line x1="0" y1="50" x2="100" y2="50" stroke="currentColor" stroke-width="0.5" stroke-dasharray="3 3" />
                    </svg>
                </div>

                {{-- Team logo with cyber shield border --}}
                <div class="relative w-40 h-40 flex items-center justify-center p-3 rounded-2xl bg-slate-900/60 border border-cyan-500/20 shadow-[0_0_30px_rgba(6,182,212,0.1)] z-10">
                    <img src="{{ $stat1 && $stat1->tournamentTeam->logo_image ? asset('storage/'.$stat1->tournamentTeam->logo_image) : asset('img/defult_team_logo.png') }}" onerror="this.src='{{ asset('img/defult_team_logo.png') }}';" class="w-32 h-32 object-contain logo-reveal filter drop-shadow-[0_8px_16px_rgba(6,182,212,0.35)]">
                </div>
                
                {{-- Team nameplate --}}
                <h2 class="text-4xl font-black uppercase text-center text-white leading-none tracking-wide text-glow-cyan font-esports max-w-full truncate mt-6 relative z-10">
                    {{ $stat1 ? $stat1->tournamentTeam->name : 'TEAM ALPHA' }}
                </h2>
                
                <span class="text-cyan-400 font-black uppercase tracking-[0.25em] text-xs mt-3 bg-cyan-950/70 border border-cyan-500/30 rounded-full px-5 py-1.5 shadow-inner leading-none relative z-10">
                    {{ $stat1 ? $stat1->tournamentTeam->short_name : 'T_A' }}
                </span>

                {{-- Tactical Telemetry Specs Sheet --}}
                <div class="w-full mt-6 pt-5 border-t border-slate-800/80 text-[10px] font-mono-tech text-slate-400 flex flex-col gap-2 relative z-10">
                    <div class="flex justify-between items-center bg-slate-900/40 px-3 py-1.5 rounded border border-slate-800/50">
                        <span>SYS_INTEGRITY</span>
                        <span class="text-emerald-400 flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                            SECURED_100%
                        </span>
                    </div>
                    <div class="flex justify-between items-center bg-slate-900/40 px-3 py-1.5 rounded border border-slate-800/50">
                        <span>SYS_CHANNEL_ID</span>
                        <span class="text-cyan-400">#{{ $stat1 ? $stat1->tournamentTeam->short_name : 'ALPHA' }}_N_A409</span>
                    </div>
                    <div class="flex justify-between items-center bg-slate-900/40 px-3 py-1.5 rounded border border-slate-800/50">
                        <span>ACTIVE_NODE</span>
                        <span class="text-slate-300">LOC_COORD // A.{{ rand(100, 999) }}</span>
                    </div>
                </div>
            </div>

            {{-- ── CENTER COLUMN: FLOATING TACTICAL HUD TOWER ── --}}
            <div class="w-144 flex flex-col gap-4.5 absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-20">
                
                {{-- Holographic green versus active badge --}}
                <div class="bg-slate-950/95 border border-emerald-500/40 py-2 px-5 rounded-full flex items-center justify-center gap-2.5 mx-auto shadow-[0_0_20px_rgba(16,185,129,0.15)] shrink-0 mb-1 leading-none">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span class="font-hud text-[10px] font-black text-emerald-400 tracking-[0.3em] uppercase">TELEMETRY COMPARISON ACTIVE</span>
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
                    $totalComp = $comp['val1'] + $comp['val2'];
                    $pct1 = $totalComp > 0 ? ($comp['val1'] / $totalComp) * 100 : 50;
                    $pct2 = $totalComp > 0 ? ($comp['val2'] / $totalComp) * 100 : 50;
                    $capsuleDelay = 0.12 * ($idx + 1);
                    $diff = abs($comp['val1'] - $comp['val2']);
                @endphp
                
                {{-- Floating Glass Stats Capsule with Staggered Entrance and Outward Segmented Progress Bars --}}
                <div class="slide-up-capsule bg-slate-950/94 border {{ $isLead1 ? 'border-cyan-500/30 stat-card-glow-cyan' : ($isLead2 ? 'border-yellow-500/25 stat-card-glow-amber' : 'border-slate-800/80') }} rounded-2xl px-6 py-4 flex flex-col justify-center shadow-[0_15px_40px_rgba(0,0,0,0.8)] relative overflow-hidden backdrop-blur-md group transition-all duration-300 hover:scale-[1.03] hover:border-slate-700"
                     style="animation-delay: {{ $capsuleDelay }}s;">
                    <div class="absolute inset-0 bg-grid-tiny opacity-[0.08] pointer-events-none"></div>
                    <div class="absolute inset-0 bg-gradient-to-r {{ $isLead1 ? 'from-cyan-500/[0.04] via-transparent to-transparent' : ($isLead2 ? 'from-transparent via-transparent to-yellow-500/[0.03]' : 'from-transparent to-transparent') }} pointer-events-none"></div>

                    {{-- Visor layout for comparative score reading --}}
                    <div class="flex items-center justify-between w-full z-10 pb-1.5">
                        
                        {{-- Left score (Cyan) --}}
                        <div class="flex items-center gap-2.5 w-36 justify-start">
                            <span class="font-hud text-3xl font-black leading-none text-slate-400 transition-all duration-300 group-hover:scale-105 {{ $isLead1 ? 'text-glow-cyan text-cyan-400' : '' }}">
                                {{ sprintf($comp['format'], $comp['val1']) }}
                            </span>
                            @if($isLead1 && $diff > 0)
                            <span class="text-[9px] font-black font-hud text-cyan-400 bg-cyan-950/80 border border-cyan-500/40 px-2 py-0.5 rounded shadow-[0_0_8px_rgba(6,182,212,0.35)] tech-blink shrink-0">
                                +{{ $diff }}
                            </span>
                            @endif
                        </div>

                        {{-- Center category title pill --}}
                        <div class="flex-1 flex justify-center">
                            <span class="text-[9.5px] font-black tracking-[0.25em] font-hud text-slate-300 bg-slate-900/90 px-4 py-1.5 rounded-lg border border-slate-800/80 text-center uppercase leading-none shadow-inner group-hover:border-slate-700/60 transition-colors">
                                {{ $comp['title'] }}
                            </span>
                        </div>

                        {{-- Right score (Gold) --}}
                        <div class="flex items-center gap-2.5 w-36 justify-end">
                            @if($isLead2 && $diff > 0)
                            <span class="text-[9px] font-black font-hud text-yellow-400 bg-yellow-950/80 border border-yellow-500/40 px-2 py-0.5 rounded shadow-[0_0_8px_rgba(250,204,21,0.35)] tech-blink shrink-0">
                                +{{ $diff }}
                            </span>
                            @endif
                            <span class="font-hud text-3xl font-black leading-none text-slate-400 transition-all duration-300 group-hover:scale-105 {{ $isLead2 ? 'text-glow-gold text-yellow-400' : '' }}">
                                {{ sprintf($comp['format'], $comp['val2']) }}
                            </span>
                        </div>

                    </div>

                    {{-- Premium Outward Segmented Visual Progress Bar --}}
                    <div class="absolute bottom-0 left-0 right-0 h-[6px] flex justify-between items-center z-10 opacity-90 overflow-hidden bg-slate-900">
                        {{-- Left Bar Segment --}}
                        <div class="w-1/2 h-full flex justify-end">
                            <div class="h-full bg-gradient-to-l from-cyan-400 to-cyan-600 shadow-[0_0_10px_#06b6d4] fill-bar-left rounded-l-full" 
                                 style="--fill-width-left: {{ $pct1 }}%; animation-delay: {{ $capsuleDelay + 0.3 }}s;"></div>
                        </div>
                        {{-- Neon Green Center Target Node --}}
                        <div class="w-1.5 h-full bg-emerald-500 z-20 shadow-[0_0_5px_#10b981]"></div>
                        {{-- Right Bar Segment --}}
                        <div class="w-1/2 h-full flex justify-start">
                            <div class="h-full bg-gradient-to-r from-yellow-400 to-amber-600 shadow-[0_0_10px_#facc15] fill-bar-right rounded-r-full" 
                                 style="--fill-width-right: {{ $pct2 }}%; animation-delay: {{ $capsuleDelay + 0.3 }}s;"></div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- ── RIGHT FOREGROUND COLUMN: TEAM B (slides from right) ── --}}
            <div class="slide-right team-card-cyber amber-theme rounded-3xl p-8 flex flex-col items-center justify-center z-10 w-96 mr-[4%] relative" style="animation-delay:0.05s;">
                <div class="laser-sweep laser-amber"></div>
                
                {{-- Corner tech elements --}}
                <div class="absolute top-0 right-0 w-8 h-8 border-t-2 border-r-2 border-yellow-400 rounded-tr-3xl opacity-70"></div>
                <div class="absolute bottom-0 left-0 w-8 h-8 border-b-2 border-l-2 border-yellow-400 rounded-bl-3xl opacity-70"></div>
                <div class="absolute top-0 left-0 w-2 h-2 border-t-2 border-l-2 border-slate-700"></div>
                <div class="absolute bottom-0 right-0 w-2 h-2 border-b-2 border-r-2 border-slate-700"></div>

                {{-- Rotating Scope behind the logo --}}
                <div class="absolute inset-0 flex items-center justify-center opacity-25 z-0 pointer-events-none -top-16">
                    <svg class="w-64 h-64 text-yellow-500 rotate-cw" viewBox="0 0 100 100" style="animation-duration: 22s; animation-direction: reverse;">
                        <circle cx="50" cy="50" r="44" stroke="currentColor" stroke-width="0.8" stroke-dasharray="8 12" fill="none" />
                        <line x1="50" y1="0" x2="50" y2="100" stroke="currentColor" stroke-width="0.5" stroke-dasharray="3 3" />
                        <line x1="0" y1="50" x2="100" y2="50" stroke="currentColor" stroke-width="0.5" stroke-dasharray="3 3" />
                    </svg>
                </div>

                {{-- Team logo with cyber shield border --}}
                <div class="relative w-40 h-40 flex items-center justify-center p-3 rounded-2xl bg-slate-900/60 border border-yellow-500/20 shadow-[0_0_30px_rgba(250,204,21,0.08)] z-10">
                    <img src="{{ $stat2 && $stat2->tournamentTeam->logo_image ? asset('storage/'.$stat2->tournamentTeam->logo_image) : asset('img/defult_team_logo.png') }}" onerror="this.src='{{ asset('img/defult_team_logo.png') }}';" class="w-32 h-32 object-contain logo-reveal filter drop-shadow-[0_8px_16px_rgba(250,204,21,0.35)]">
                </div>
                
                {{-- Team nameplate --}}
                <h2 class="text-4xl font-black uppercase text-center text-white leading-none tracking-wide text-glow-gold font-esports max-w-full truncate mt-6 relative z-10">
                    {{ $stat2 ? $stat2->tournamentTeam->name : 'TEAM BETA' }}
                </h2>
                
                <span class="text-yellow-400 font-black uppercase tracking-[0.25em] text-xs mt-3 bg-yellow-950/70 border border-yellow-500/30 rounded-full px-5 py-1.5 shadow-inner leading-none relative z-10">
                    {{ $stat2 ? $stat2->tournamentTeam->short_name : 'T_B' }}
                </span>

                {{-- Tactical Telemetry Specs Sheet --}}
                <div class="w-full mt-6 pt-5 border-t border-slate-800/80 text-[10px] font-mono-tech text-slate-400 flex flex-col gap-2 relative z-10">
                    <div class="flex justify-between items-center bg-slate-900/40 px-3 py-1.5 rounded border border-slate-800/50">
                        <span>SYS_INTEGRITY</span>
                        <span class="text-emerald-400 flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                            SECURED_100%
                        </span>
                    </div>
                    <div class="flex justify-between items-center bg-slate-900/40 px-3 py-1.5 rounded border border-slate-800/50">
                        <span>SYS_CHANNEL_ID</span>
                        <span class="text-yellow-400">#{{ $stat2 ? $stat2->tournamentTeam->short_name : 'BETA' }}_N_B912</span>
                    </div>
                    <div class="flex justify-between items-center bg-slate-900/40 px-3 py-1.5 rounded border border-slate-800/50">
                        <span>ACTIVE_NODE</span>
                        <span class="text-slate-300">LOC_COORD // B.{{ rand(100, 999) }}</span>
                    </div>
                </div>
            </div>

        </div>

        {{-- ─── FOOTER (slides up) ─────────────────────────── --}}
        <div class="slide-up-footer tactical-footer relative z-10 flex items-center justify-between px-10 py-3.5 border-t border-slate-900 bg-slate-950/90 font-mono-tech text-[10px] text-slate-500 tracking-[0.3em] uppercase">
            <span>SYS_LOC // 0x2A190D</span>
            <div class="h-px w-44 shrink-0" style="background:linear-gradient(to right,transparent,rgba(6,182,212,0.25),transparent);"></div>
            <span class="text-emerald-400/80 font-hud text-[9px] tracking-[0.3em]">Official Broadcast Analytical Comparison</span>
            <div class="h-px w-44 shrink-0" style="background:linear-gradient(to right,transparent,rgba(250,204,21,0.2),transparent);"></div>
            <span>SYS_VER_3.6.0</span>
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
