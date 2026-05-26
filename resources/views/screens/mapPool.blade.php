<x-base title="MAP POOL RESULTS">
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
           ENTRANCE & EXIT ANIMATIONS — Full obs-master compatibility
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
            backface-visibility: hidden;
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

        .card-reveal {
            animation-duration: 1.4s;
            animation-timing-function: cubic-bezier(0.075, 0.82, 0.165, 1);
            /* ultra-smooth cinematic ease-out */
            animation-fill-mode: both;
            will-change: transform, opacity;
            backface-visibility: hidden;
        }

        .card-container> :nth-child(odd) {
            animation-name: cardRevealLeft;
        }

        .card-container> :nth-child(even) {
            animation-name: cardRevealRight;
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

        @keyframes logoReveal {
            from {
                opacity: 0;
                transform: scale(0.6) rotate(-8deg);
            }

            to {
                opacity: 1;
                transform: scale(1) rotate(0deg);
            }
        }

        .logo-reveal {
            animation: logoReveal 0.75s cubic-bezier(0.34, 1.56, 0.64, 1) both;
            will-change: transform, opacity;
        }

        /* ══════════════════════════════════════════════════════
           MICRO-ROTATORS & RADARS (TACTICAL GRAPHICS)
        ══════════════════════════════════════════════════════ */

        /* ══════════════════════════════════════════════════════
           BACKGROUND EFFECTS & STYLES (NO INFINITE LOOPS)
           ══════════════════════════════════════════════════════ */

        .cyber-bg {
            background-color: #030712;
            background-image:
                radial-gradient(at 15% 15%, rgba(6, 182, 212, 0.08) 0px, transparent 60%),
                radial-gradient(at 85% 85%, rgba(234, 179, 8, 0.06) 0px, transparent 60%);
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

        /* ══════════════════════════════════════════════════════
            TACTICAL INTERFACE STYLES (ROWS LAYOUT REDESIGN)
        ══════════════════════════════════════════════════════ */

        .glass-header {
            background: rgba(2, 4, 16, 0.92);
            border-bottom: 1.5px solid rgba(250, 204, 21, 0.3);
            backdrop-filter: blur(16px);
        }

        .glass-footer {
            background: rgba(2, 4, 16, 0.9);
            border-top: 1px solid rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(16px);
        }

        .map-row {
            background: linear-gradient(90deg, rgba(10, 15, 30, 0.88) 0%, rgba(6, 8, 16, 0.96) 100%);
            border: 1.5px solid rgba(6, 182, 212, 0.25);
            border-left: 6px solid rgba(6, 182, 212, 0.6) !important;
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            backdrop-filter: blur(20px);
        }

        .map-row:hover {
            transform: translateY(-4px) scale(1.008);
            border-color: rgba(6, 182, 212, 0.55);
            border-left-color: rgba(6, 182, 212, 0.95) !important;
            box-shadow: 0 15px 35px rgba(6, 182, 212, 0.1), 0 0 25px rgba(6, 182, 212, 0.05);
        }

        .completed-row-active {
            background: linear-gradient(90deg, rgba(20, 18, 12, 0.92) 0%, rgba(8, 7, 5, 0.97) 100%) !important;
            border: 1.5px solid rgba(250, 204, 21, 0.35) !important;
            border-left: 7px solid #facc15 !important;
        }

        .completed-row-active:hover {
            border-color: rgba(250, 204, 21, 0.75) !important;
            border-left-color: #facc15 !important;
            box-shadow: 0 15px 35px rgba(250, 204, 21, 0.12), 0 0 25px rgba(250, 204, 21, 0.06);
        }

        .live-row-active {
            background: linear-gradient(90deg, rgba(6, 20, 14, 0.92) 0%, rgba(3, 10, 6, 0.97) 100%) !important;
            border: 1.5px solid rgba(16, 185, 129, 0.35) !important;
            border-left: 7px solid #10b981 !important;
        }

        .live-row-active:hover {
            border-color: rgba(16, 185, 129, 0.75) !important;
            border-left-color: #10b981 !important;
            box-shadow: 0 15px 35px rgba(16, 185, 129, 0.12), 0 0 25px rgba(16, 185, 129, 0.06);
        }

        /* Holographic micro-grid backdrop */
        .bg-grid-tiny {
            background-size: 8px 8px;
            background-image:
                linear-gradient(to right, rgba(255, 255, 255, 0.02) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(255, 255, 255, 0.02) 1px, transparent 1px);
        }

        /* Neon text glow */
        .text-glow-yellow {
            text-shadow: 0 0 10px rgba(250, 204, 21, 0.55), 0 0 20px rgba(250, 204, 21, 0.2);
        }

        .text-glow-cyan {
            text-shadow: 0 0 10px rgba(6, 182, 212, 0.55), 0 0 20px rgba(6, 182, 212, 0.2);
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
                <div class="p-2 bg-slate-900 border border-yellow-400/40 rounded-xl shadow-lg shrink-0">
                    <img src="{{ $activeMatch->tournament->logo_image ? asset('storage/'.$activeMatch->tournament->logo_image) : asset('img/defult_team_logo.png') }}" class="w-11 h-11 object-contain">
                </div>
                <div>
                    <div class="text-yellow-400 text-xs font-black uppercase tracking-[0.4em] font-body leading-none">MAP POOL HUB</div>
                    <h1 class="text-3xl font-black uppercase tracking-wider leading-none text-white mt-1.5">{{ $activeMatch->tournament->name }}</h1>
                </div>
            </div>
            <div class="text-right">
                <div class="text-slate-500 text-[10px] font-black uppercase tracking-[0.3em] font-body leading-none">ROUND ACTIVE SCHED</div>
                <div class="text-2xl font-black uppercase tracking-widest mt-1.5 text-yellow-400 leader-text-pulse">
                    {{ $currentRound ? $currentRound->name : 'Tournament Map Pool' }}
                </div>
            </div>
        </div>

        {{-- ─── MAIN CONTENT: TACTICAL MAP TIMELINE (DYN GRID/ROWS) ─── --}}
        <div class="relative z-10 flex-1 flex px-12 py-6 min-h-0 items-center justify-center">

            @php
            $count = $matches->count();
            // 2-column fallback for rounds containing more than 5 maps
            $useGrid = $count > 5;

            $containerClass = $useGrid
            ? 'grid grid-cols-2 gap-5 w-full max-w-7xl my-auto'
            : 'flex flex-col gap-4.5 w-full max-w-7xl my-auto';

            $rowHeightPx = 145;
            $rightWidthPx = 340;

            if ($useGrid) {
            $rowHeightPx = 115;
            $rightWidthPx = 220;
            } elseif ($count >= 5) {
            $rowHeightPx = 115;
            $rightWidthPx = 220;
            } elseif ($count >= 4) {
            $rowHeightPx = 130;
            $rightWidthPx = 300;
            }
            @endphp

            {{-- Dynamic container stack --}}
            <div class="card-container {{ $containerClass }}">
                @foreach($matches as $index => $match)
                @php
                $mapNameLower = strtolower($match->map);
                $mapImage = match ($mapNameLower) {
                'erangle', 'erangel' => asset('/img/erangel_thumb.jpg'),
                'miramar' => asset('/img/miramar_thumb.jpg'),
                'sanhok' => asset('/img/sanhok_thumb.jpg'),
                'rondo' => asset('/img/rondo_thumb.jpg'),
                'vikendi' => asset('/img/vikendi_thumb.png'),
                'taego' => asset('/img/taego_thumb.png'),
                default => asset('/img/erangel_thumb.jpg'),
                };
                $winner = $match->matchStats->firstWhere('is_winner', true)?->tournamentTeam;
                $delay = 0.15 * ($index + 1);
                @endphp
                {{-- Horizontal Row Container — Reuses card-reveal for full exit-transition compatibility --}}
                <div class="card-reveal map-row rounded-2xl flex items-center justify-between px-8 py-3 relative overflow-hidden {{ $match->is_completed ? 'completed-row-active' : ($match->is_active ? 'live-row-active' : '') }}" style="height: {{ $rowHeightPx }}px; animation-delay: {{ $delay }}s;">

                    {{-- VERY SLIGHT BLURRED BACKGROUND THEME OF THE MAP --}}
                    <img src="{{ $mapImage }}" class="absolute inset-0 w-full h-full object-cover z-0 pointer-events-none" style="opacity: 0.05; filter: blur(5px);">

                    {{-- Sleek high-contrast dark cyber overlays --}}
                    <div class="absolute inset-0 bg-linear-to-r from-slate-950/96 via-slate-950/88 to-slate-950/96 z-5 pointer-events-none"></div>

                    {{-- Technical dashed borders inside --}}
                    <div class="absolute inset-1.5 border border-dashed {{ $match->is_completed ? 'border-yellow-500/18' : ($match->is_active ? 'border-emerald-500/22' : 'border-cyan-500/18') }} rounded-xl pointer-events-none z-10"></div>
                    <div class="absolute inset-0 bg-grid-tiny opacity-10 pointer-events-none z-0"></div>

                    {{-- LEFT COLUMN: Gorgeous Widescreen Map Thumbnail Card & Details --}}
                    <div class="flex items-center gap-5 z-10 relative pl-2 shrink-0">
                        {{-- Dedicated Widescreen Map Showcase Card --}}
                        <div class="relative overflow-hidden rounded-xl border-2 {{ $match->is_completed ? 'border-yellow-500/50 shadow-[0_0_15px_rgba(250,204,21,0.2)]' : ($match->is_active ? 'border-emerald-500/60 shadow-[0_0_18px_rgba(16,185,129,0.25)]' : 'border-cyan-500/40 shadow-[0_0_12px_rgba(6,182,212,0.1)]') }} shrink-0 group" style="width: {{ $useGrid ? 140 : 210 }}px; height: {{ $useGrid ? 85 : 110 }}px;">
                            <img src="{{ $mapImage }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110 pointer-events-none">

                            {{-- High-tech grid & scanning overlay inside thumbnail --}}
                            <div class="absolute inset-0 bg-linear-to-t from-slate-950/80 via-slate-950/20 to-transparent z-5"></div>
                            <div class="absolute inset-0 bg-grid-tiny opacity-20 z-5"></div>

                            {{-- Internal corner ticks --}}
                            <div class="absolute inset-1.5 border border-dashed {{ $match->is_completed ? 'border-yellow-400/30' : ($match->is_active ? 'border-emerald-450/40' : 'border-cyan-500/30') }} rounded-lg z-5"></div>
                        </div>

                        {{-- Map Name Metadata --}}
                        <div class="flex flex-col justify-center">
                            <span class="text-slate-400/60 font-black text-[8px] tracking-[0.3em] font-hud leading-none uppercase mb-1.5">MAP_REF_0{{ $loop->iteration }}</span>
                            <h3 class="text-glow-yellow {{ $useGrid ? 'text-2xl' : 'text-3.5xl' }} font-black uppercase italic tracking-wider leading-none font-esports {{ $match->is_completed ? 'text-yellow-400' : ($match->is_active ? 'text-emerald-400' : 'text-cyan-400') }}">
                                {{ $match->map }}
                            </h3>
                            <div class="w-12 h-0.5 bg-yellow-400 mt-2 shadow-[0_0_10px_#facc15] {{ $match->is_completed ? '' : ($match->is_active ? 'bg-emerald-500 shadow-[0_0_10px_#10b981]' : 'bg-cyan-500 shadow-[0_0_10px_#06b6d4] opacity-35') }}"></div>
                        </div>
                    </div>

                    {{-- CENTER COLUMN: Futuristic Visor with Match Title --}}
                    <div class="flex flex-col items-center justify-center text-center z-10 relative px-4">
                        <span class="text-slate-400/50 font-black text-[8px] tracking-[0.3em] font-hud uppercase leading-none mb-2">STAGE // PHASE</span>
                        <div class="px-5.5 py-2.5 rounded-xl {{ $match->is_completed ? 'border-yellow-500/40 shadow-[0_0_15px_rgba(250,204,21,0.06)]' : ($match->is_active ? 'border-emerald-500/45 shadow-[0_0_15px_rgba(16,185,129,0.1)]' : 'border-cyan-500/25 shadow-[0_0_12px_rgba(6,182,212,0.05)]') }} flex flex-col items-center justify-center relative overflow-hidden">
                            <div class="absolute inset-0 bg-grid-tiny opacity-10"></div>
                            <span class="text-lg font-black tracking-widest uppercase {{ $match->is_completed ? 'text-yellow-400' : ($match->is_active ? 'text-emerald-400 font-bold' : 'text-slate-200') }} font-hud whitespace-nowrap">
                                {{ $match->name }}
                            </span>
                            <span class="text-[7.5px] font-black uppercase tracking-[0.25em] {{ $match->is_completed ? 'text-yellow-500/50' : ($match->is_active ? 'text-emerald-500/60' : 'text-cyan-400/80') }} font-body mt-0.5">
                                {{ $match->is_completed ? 'ROUND COMPLETED' : ($match->is_active ? 'ENGAGED IN COMBAT' : 'STANDBY MODE') }}
                            </span>
                        </div>
                    </div>

                    {{-- RIGHT COLUMN: Completed WWCD Spotlight OR Active live reticle OR Scheduled item --}}
                    <div class="flex justify-end items-center z-10 relative pr-2" style="width: {{ $rightWidthPx }}px;">
                        @if($match->is_completed && $winner)
                        {{-- WWCD WINNER SPOTLIGHT --}}
                        <div class="w-full flex items-center gap-4 bg-slate-950/95 border border-yellow-400/40 rounded-xl p-2.5 shadow-lg relative overflow-hidden">
                            <div class="absolute inset-0 bg-grid-tiny opacity-15"></div>
                            <div class="absolute inset-0 bg-linear-to-r from-yellow-500/10 via-transparent to-transparent"></div>

                            @if(!$useGrid)
                            <div class="absolute right-2 w-28 h-28 flex items-center justify-center opacity-20 pointer-events-none">
                                <svg class="w-full h-full text-yellow-500/70" viewBox="0 0 100 100">
                                    <circle cx="50" cy="50" r="45" stroke="currentColor" stroke-width="0.5" stroke-dasharray="6 8" fill="none" />
                                </svg>
                            </div>
                            @endif

                            {{-- Gold Winner Logo Frame --}}
                            <div class="w-14 h-14 bg-linear-to-br from-yellow-400 to-yellow-600 border border-yellow-300 rounded-xl flex items-center justify-center p-1 shrink-0 relative shadow-[0_0_15px_rgba(250,204,21,0.25)]">
                                <div class="w-full h-full bg-slate-950 rounded-lg flex items-center justify-center p-1.5 shadow-inner">
                                    <img src="{{ $winner->logo_image ? asset('storage/' . $winner->logo_image) : asset('img/defult_team_logo.png') }}" class="logo-shine w-9 h-9 object-contain">
                                </div>

                                {{-- Mini WWCD Badge --}}
                                <div class="wwcd-glow absolute -top-2.5 -right-2 bg-linear-to-r from-yellow-400 to-amber-500 border border-white/30 px-1 py-0.5 rounded text-[7px] font-black text-slate-950 tracking-wider shadow-md font-hud leading-none">
                                    WWCD
                                </div>
                            </div>

                            {{-- Winner Names --}}
                            <div class="flex-1 min-w-0 flex flex-col justify-center">
                                <span class="text-yellow-400 font-black text-[8px] tracking-[0.25em] font-hud uppercase leading-none mb-1 text-glow-yellow">VICTORY ROUND</span>
                                <h4 class="text-glow-yellow text-base font-black uppercase tracking-wide text-white truncate leading-tight">
                                    {{ $winner->name }}
                                </h4>
                                <span class="text-yellow-400/70 font-black text-[9px] uppercase tracking-wider font-body mt-0.5 leading-none">
                                    {{ $winner->short_name }}
                                </span>
                            </div>
                        </div>
                        @elseif($match->is_active)
                        {{-- ACTIVE PULSING GREEN TARGET LOCK RETICLE --}}
                        <div class="w-full flex items-center justify-between bg-emerald-950/20 border border-emerald-500/40 rounded-xl p-2.5 shadow-lg relative overflow-hidden">
                            <div class="absolute inset-0 bg-grid-tiny opacity-15"></div>
                            <div class="absolute inset-0 bg-linear-to-r from-emerald-500/10 via-transparent to-transparent"></div>

                            @if(!$useGrid)
                            <div class="absolute right-3 w-24 h-24 flex items-center justify-center opacity-40 pointer-events-none">
                                <svg class="w-full h-full text-emerald-500/70" viewBox="0 0 100 100">
                                    <circle cx="50" cy="50" r="40" stroke="currentColor" stroke-width="0.8" stroke-dasharray="6 8" fill="none" />
                                    <line x1="50" y1="10" x2="50" y2="90" stroke="currentColor" stroke-width="0.5" stroke-dasharray="2 2" />
                                    <line x1="10" y1="50" x2="90" y2="50" stroke="currentColor" stroke-width="0.5" stroke-dasharray="2 2" />
                                </svg>
                            </div>
                            @endif

                            <div class="flex items-center gap-3 z-10">
                                <div class="live-visor bg-linear-to-r from-emerald-600 to-green-700 border border-white/20 px-2.5 py-1.5 shadow-[0_0_15px_rgba(16,185,129,0.35)] rounded-lg relative shrink-0">
                                    <span class="block text-white font-black uppercase text-[9px] tracking-widest leading-none font-hud">
                                        🟢 LIVE
                                    </span>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-emerald-400 font-black text-[11px] tracking-wider uppercase font-hud leading-none">ACTIVE TELEMETRY</span>
                                    <span class="text-emerald-500/60 font-hud text-[7px] tracking-widest uppercase mt-1.5 animate-pulse">LOCK_ON_STREAM</span>
                                </div>
                            </div>
                        </div>
                        @else
                        {{-- SCHEDULED TACTICAL INTERFACE --}}
                        <div class="w-full flex items-center justify-between bg-slate-950 border border-slate-800/80 rounded-xl p-2.5 shadow-md">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-lg border border-slate-800 bg-slate-950 flex items-center justify-center shrink-0 shadow-md relative overflow-hidden">
                                    <svg class="w-5 h-5 text-cyan-400/80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <circle cx="12" cy="12" r="10" stroke-dasharray="4 4" />
                                        <line x1="12" y1="2" x2="12" y2="22" stroke-width="0.5" />
                                        <line x1="2" y1="12" x2="22" y2="12" stroke-width="0.5" />
                                    </svg>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-slate-300 font-black text-[10px] tracking-wider uppercase font-hud leading-none">STANDBY STATE</span>
                                    <span class="text-cyan-400/70 font-hud text-[7px] tracking-widest uppercase mt-1.5">SYSTEM // READY</span>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>

                </div>
                @endforeach
            </div>

        </div>

        {{-- ─── FOOTER ─────────────────────────────────────── --}}
        <div class="slide-up-footer glass-footer relative z-10 flex items-center justify-between px-12 py-3.5 font-body text-[10px] font-bold text-slate-500 tracking-[0.4em] uppercase">
            <span>SYS_LOC // 0x58BF12</span>
            <div class="h-px w-48 shrink-0 bg-linear-to-r from-transparent via-yellow-400/20 to-transparent"></div>
            <span class="text-yellow-400/60 font-black">Official Map Pool & Schedule</span>
            <div class="h-px w-48 shrink-0 bg-linear-to-r from-transparent via-yellow-400/20 to-transparent"></div>
            <span>SYS_VER_3.5.2</span>
        </div>

    </div>

    {{-- Live updates listener --}}
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
                    if (sweep) sweep.classList.remove('hidden');
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
                
                @if($activeMatch)
                Echo.channel('active-match.{{ $activeMatch->id }}')
                    .listen('.MatchStatsUpdated', (e) => { window.location.reload(); });
                @endif
            }
        });
    </script>
</x-base>
