<x-base title="POST MATCH RESULTS">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700;900&family=Rajdhani:wght@600;700;900&family=Orbitron:wght@700;800;900&display=swap" rel="stylesheet">

    <style>
        * { box-sizing: border-box; }
        body, html { margin: 0; padding: 0; width: 100%; height: 100%; overflow: hidden; background: transparent; }

        .font-esports { font-family: 'Rajdhani', sans-serif; }
        .font-body    { font-family: 'Inter', sans-serif; }
        .font-hud     { font-family: 'Orbitron', sans-serif; }

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
            animation: slideLeft 0.76s cubic-bezier(0.22, 1, 0.36, 1) both;
            will-change: transform, opacity;
            backface-visibility: hidden;
        }

        @keyframes slideRight {
            from { opacity: 0; transform: translateX(50px); }
            to   { opacity: 1; transform: translateX(0); }
        }
        .slide-right {
            animation: slideRight 0.68s cubic-bezier(0.22, 1, 0.36, 1) both;
            will-change: transform, opacity;
            backface-visibility: hidden;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(18px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .slide-up {
            animation: slideUp 0.5s cubic-bezier(0.22, 1, 0.36, 1) both;
            will-change: transform, opacity;
        }

        @keyframes slideUpFast {
            from { opacity: 0; transform: translateY(32px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .slide-up-footer {
            animation: slideUpFast 0.62s cubic-bezier(0.22, 1, 0.36, 1) both;
            animation-delay: 0.85s;
            will-change: transform, opacity;
        }

        @keyframes badgeDrop {
            from { opacity: 0; transform: translateY(-24px) scale(0.82); }
            62%  { transform: translateY(4px) scale(1.03); }
            to   { opacity: 1; transform: translateY(0) scale(1); }
        }
        .badge-drop {
            animation: badgeDrop 0.78s cubic-bezier(0.34, 1.56, 0.64, 1) both;
            will-change: transform, opacity;
        }

        @keyframes logoReveal {
            from { opacity: 0; transform: scale(0.55) rotate(-6deg); }
            to   { opacity: 1; transform: scale(1) rotate(0deg); }
        }
        .logo-reveal {
            animation: logoReveal 0.82s cubic-bezier(0.34, 1.56, 0.64, 1) both;
            will-change: transform, opacity;
        }

        @keyframes statReveal {
            from { opacity: 0; transform: translateY(16px) scale(0.85); }
            to   { opacity: 1; transform: translateY(0) scale(1); }
        }
        .stat-reveal {
            animation: statReveal 0.5s cubic-bezier(0.34, 1.56, 0.64, 1) both;
            will-change: transform, opacity;
        }

        /* ══════════════════════════════════════════════════════
           DYNAMIC INTERMISSION BACKGROUND SYSTEM
        ══════════════════════════════════════════════════════ */

        .cyber-bg {
            background-color: #030712;
            background-image:
                radial-gradient(at 15% 15%, rgba(6, 182, 212, 0.08) 0px, transparent 60%),
                radial-gradient(at 85% 85%, rgba(234, 179, 8, 0.06) 0px, transparent 60%);
        }

        @keyframes scanline {
            from { transform: translateY(-100%); }
            to   { transform: translateY(200%); }
        }
        .scan-sweep {
            position: absolute; inset: 0; z-index: 0;
            background: linear-gradient(to bottom, transparent 48%, rgba(6, 182, 212, 0.04) 50%, transparent 52%);
            animation: scanline 12s linear infinite;
            pointer-events: none;
        }

        @keyframes gridScroll {
            from { background-position: 0 0; }
            to   { background-position: 0 40px; }
        }

        .grid-bg {
            position: absolute;
            inset: 0;
            z-index: 0;
            background-size: 50px 50px;
            background-image:
                linear-gradient(to right, rgba(255, 255, 255, 0.012) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(255, 255, 255, 0.012) 1px, transparent 1px);
            animation: gridScroll 20s linear infinite;
            pointer-events: none;
        }

        @keyframes orbFloat {
            0%, 100% { transform: translateY(0) scale(1); }
            50%       { transform: translateY(-18px) scale(1.04); }
        }
        .orb {
            position: absolute; border-radius: 50%;
            filter: blur(80px); pointer-events: none;
            animation: orbFloat 9s ease-in-out infinite;
        }

        @keyframes pulseRing {
            0%   { box-shadow: 0 0 0 0 rgba(250,204,21,0.55), 0 0 30px rgba(250,204,21,0.2); }
            70%  { box-shadow: 0 0 0 15px rgba(250,204,21,0), 0 0 50px rgba(250,204,21,0.4); }
            100% { box-shadow: 0 0 0 0 rgba(250,204,21,0), 0 0 30px rgba(250,204,21,0.2); }
        }
        .pulse-ring { animation: pulseRing 2.4s ease-in-out infinite; }

        @keyframes logoShine {
            0%, 100% { filter: drop-shadow(0 0 8px rgba(250,204,21,0.4)) brightness(1); }
            50%       { filter: drop-shadow(0 0 24px rgba(250,204,21,0.9)) brightness(1.15); }
        }
        .logo-shine { animation: logoShine 3s ease-in-out infinite; }

        @keyframes numFlash {
            0%, 100% { text-shadow: 0 0 8px currentColor; }
            50%       { text-shadow: 0 0 24px currentColor, 0 0 45px currentColor; }
        }
        .num-flash { animation: numFlash 2.6s ease-in-out infinite; }

        @keyframes tickerGlow {
            0%, 100% { box-shadow: 0 0 12px rgba(250,204,21,0.4); }
            50%       { box-shadow: 0 0 35px rgba(250,204,21,0.85), 0 0 60px rgba(250,204,21,0.25); }
        }
        .winner-badge { animation: tickerGlow 2.2s ease-in-out infinite; }

        @keyframes borderFlicker {
            0%, 100% { border-color: rgba(250,204,21,0.35); }
            50%       { border-color: rgba(250,204,21,0.7); }
        }
        .winner-panel { animation: borderFlicker 3s ease-in-out infinite; }

        /* ══════════════════════════════════════════════════════
           CARD & PANEL STYLES
           ══════════════════════════════════════════════════════ */

        .glass-card {
            background: rgba(8, 11, 22, 0.88);
            border: 1px solid rgba(255,255,255,0.08);
            box-shadow: 0 4px 20px rgba(0,0,0,0.6), inset 0 1px 0 rgba(255,255,255,0.04);
            backdrop-filter: blur(14px);
        }
        .glass-card-rank2 {
            background: rgba(8, 11, 22, 0.88);
            border: 1px solid rgba(148,163,184,0.2);
            border-left: 3px solid rgba(148,163,184,0.75);
            box-shadow: 0 4px 20px rgba(0,0,0,0.6);
            backdrop-filter: blur(14px);
        }
        .glass-card-rank3 {
            background: rgba(8, 11, 22, 0.88);
            border: 1px solid rgba(234,88,12,0.2);
            border-left: 3px solid rgba(234,88,12,0.75);
            box-shadow: 0 4px 20px rgba(0,0,0,0.6);
            backdrop-filter: blur(14px);
        }

        .tbl-header {
            background: rgba(2, 4, 16, 0.92);
            border-bottom: 2px solid rgba(250,204,21,0.55);
            backdrop-filter: blur(10px);
        }

        .winner-panel-bg {
            background: linear-gradient(160deg, rgba(6,9,20,0.95) 0%, rgba(18,14,3,0.95) 100%);
            border: 1px solid rgba(250,204,21,0.35);
            border-top: 4px solid #facc15;
            box-shadow: 0 0 80px rgba(250,204,21,0.12), 0 30px 80px rgba(0,0,0,0.75);
            backdrop-filter: blur(22px);
        }

        .gold-divider {
            height: 1px;
            background: linear-gradient(to right, transparent, rgba(250,204,21,0.6), transparent);
        }

        .header-bar {
            background: rgba(2, 4, 16, 0.85);
            border-bottom: 1px solid rgba(250,204,21,0.25);
            backdrop-filter: blur(16px);
        }

        .footer-bar {
            background: rgba(2, 4, 16, 0.82);
            border-top: 1px solid rgba(255,255,255,0.06);
            backdrop-filter: blur(16px);
        }
    </style>

    {{-- Main screen wrapper — id used for live bg switching --}}
    <div id="screen-wrapper" class="w-full h-full min-h-screen {{ $bgType === 'animated' ? 'cyber-bg' : 'bg-transparent' }} relative flex flex-col justify-between overflow-hidden text-slate-100 font-esports select-none">

        {{-- Custom background video layer --}}
        <video id="bg-video" autoplay loop muted playsinline class="absolute inset-0 w-full h-full object-cover z-0 {{ ($bgType === 'custom' && $customVideo) ? '' : 'hidden' }}">
            @if($bgType === 'custom' && $customVideo)
            <source src="{{ asset('storage/' . $customVideo) }}" type="video/mp4">
            @endif
        </video>
        <div id="bg-video-overlay" class="absolute inset-0 bg-slate-950/85 z-0 backdrop-blur-[1px] {{ ($bgType === 'custom' && $customVideo) ? '' : 'hidden' }}"></div>

        {{-- Interactive Scanning laser overlays --}}
        <div id="bg-grid" class="grid-bg z-0 opacity-30 {{ $bgType === 'transparent' ? 'hidden' : '' }}"></div>
        <div id="bg-sweep" class="scan-sweep z-0 {{ $bgType === 'transparent' ? 'hidden' : '' }}"></div>

        {{-- Ambient orbs (subtle, not solid bg) --}}
        <div class="orb z-0" style="width:380px;height:380px;background:rgba(245,158,11,0.07);top:4%;left:6%;animation-delay:0s;"></div>
        <div class="orb z-0" style="width:460px;height:460px;background:rgba(234,88,12,0.05);bottom:4%;right:5%;animation-delay:3.5s;"></div>
        <div class="orb z-0" style="width:260px;height:260px;background:rgba(250,204,21,0.04);top:45%;left:38%;animation-delay:1.8s;"></div>

        {{-- ─── HEADER ──────────────────────────────────────── --}}
        <div class="slide-down header-bar relative z-10 flex justify-between items-center px-10 py-4" style="animation-delay:0s;">
            <div class="flex items-center gap-5">
                <div class="p-2 rounded-lg" style="background:rgba(250,204,21,0.12);border:1px solid rgba(250,204,21,0.4);">
                    <img src="{{ $activeMatch->tournament->logo_image ? asset('storage/'.$activeMatch->tournament->logo_image) : asset('img/defult_team_logo.png') }}" class="w-12 h-12 object-contain">
                </div>
                <div>
                    <div class="text-yellow-400 text-xs font-black uppercase tracking-[0.4em] font-body leading-none">MATCH COMPLETED</div>
                    <h1 class="text-3xl font-black uppercase tracking-tight leading-none text-white mt-1.5">{{ $activeMatch->tournament->name }}</h1>
                </div>
            </div>
            <div class="flex items-center gap-6">
                <div class="px-5 py-2 rounded-lg text-center" style="background:rgba(250,204,21,0.1);border:1px solid rgba(250,204,21,0.35);">
                    <div class="text-yellow-400/70 text-[10px] font-black uppercase tracking-[0.3em] font-body leading-none">MAP</div>
                    <div class="text-yellow-400 text-xl font-black uppercase leading-none mt-1.5">{{ $activeMatch->map }}</div>
                </div>
                <div class="text-right">
                    <div class="text-slate-500 text-[9px] font-black uppercase tracking-[0.3em] font-body leading-none">RESULT SUMMARY</div>
                    <div class="text-2xl font-black uppercase tracking-wide mt-1.5" style="background:linear-gradient(to right,#facc15,#fb923c);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">{{ $activeMatch->name }}</div>
                </div>
            </div>
        </div>

        @php
            $allStats = $activeMatch->matchStats->sortBy(function($stat) {
                return $stat->placement == 0 ? 999 : $stat->placement;
            })->values();
            $winner       = $allStats->firstWhere('placement', 1) ?? $allStats->first();
            $remaining    = $allStats->reject(fn($s) => $s->id === ($winner->id ?? null))->values();
            $leftColumn   = $remaining->take(8);
            $rightColumn  = $remaining->slice(8)->take(8);
        @endphp

        {{-- ─── MAIN CONTENT ────────────────────────────────── --}}
        <div class="relative z-10 flex-1 flex gap-6 px-8 py-4 min-h-0">

            {{-- ── WINNER SPOTLIGHT (slides from LEFT) ──────── --}}
            <div class="slide-left w-72 shrink-0" style="animation-delay:0.15s;">
                <div class="winner-panel-bg winner-panel rounded-2xl h-full flex flex-col items-center justify-between py-7 px-5 relative overflow-hidden">

                    {{-- Corner brackets --}}
                    <div class="absolute top-0 left-0 w-9 h-9 border-t-2 border-l-2 border-yellow-400/80 rounded-tl-2xl pointer-events-none"></div>
                    <div class="absolute top-0 right-0 w-9 h-9 border-t-2 border-r-2 border-yellow-400/80 rounded-tr-2xl pointer-events-none"></div>
                    <div class="absolute bottom-0 left-0 w-9 h-9 border-b-2 border-l-2 border-yellow-400/80 rounded-bl-2xl pointer-events-none"></div>
                    <div class="absolute bottom-0 right-0 w-9 h-9 border-b-2 border-r-2 border-yellow-400/80 rounded-br-2xl pointer-events-none"></div>

                    {{-- 🏆 Badge (drops in) --}}
                    <div class="badge-drop winner-badge rounded-md px-6 py-2 text-center mt-2" style="animation-delay:0.5s;background:linear-gradient(to right,#d97706,#facc15,#d97706);">
                        <span class="text-black font-black text-lg uppercase tracking-[0.2em] block leading-none">🏆 CHICKEN DINNER</span>
                    </div>

                    <div class="text-yellow-400/50 text-[10px] font-black tracking-[0.5em] uppercase font-body mt-1 leading-none">MATCH WINNER</div>

                    {{-- Logo (scales up) --}}
                    <div class="logo-reveal pulse-ring w-36 h-36 rounded-full flex items-center justify-center my-4" style="animation-delay:0.6s;background:rgba(250,204,21,0.08);border:2px solid rgba(250,204,21,0.4);">
                        <img src="{{ $winner && $winner->tournamentTeam->logo_image ? asset('storage/'.$winner->tournamentTeam->logo_image) : asset('img/defult_team_logo.png') }}" class="logo-shine w-24 h-24 object-contain">
                    </div>

                    {{-- Team Name (slides up) --}}
                    <div class="text-center slide-up" style="animation-delay:0.75s;">
                        <div class="text-4xl font-black uppercase leading-none text-white max-w-60 truncate" style="text-shadow:0 0 30px rgba(250,204,21,0.45);">
                            {{ $winner ? $winner->tournamentTeam->name : 'No Winner' }}
                        </div>
                        <div class="text-yellow-400/60 text-sm font-bold tracking-[0.3em] uppercase font-body mt-2.5">
                            {{ $winner ? $winner->tournamentTeam->short_name : '' }}
                        </div>
                    </div>

                    <div class="gold-divider w-full my-3"></div>

                    {{-- Stats --}}
                    <div class="flex w-full justify-around text-center">
                        <div class="stat-reveal" style="animation-delay:0.85s;">
                            <div class="text-yellow-400 text-[10px] font-black uppercase tracking-widest font-body">ELIMS</div>
                            <div class="num-flash text-4xl font-black font-hud mt-1.5" style="color:#fde68a;">{{ $winner ? $winner->kills : 0 }}</div>
                        </div>
                        <div class="w-px" style="background:rgba(250,204,21,0.2); height: 40px; align-self: center;"></div>
                        <div class="stat-reveal" style="animation-delay:0.95s;">
                            <div class="text-yellow-400 text-[10px] font-black uppercase tracking-widest font-body">PLACE PTS</div>
                            <div class="text-3xl font-black text-slate-200 font-hud mt-1.5">{{ $winner ? ($winner->points - $winner->kills) : 0 }}</div>
                        </div>
                        <div class="w-px" style="background:rgba(250,204,21,0.2); height: 40px; align-self: center;"></div>
                        <div class="stat-reveal" style="animation-delay:1.05s;">
                            <div class="text-yellow-400 text-[10px] font-black uppercase tracking-widest font-body">TOTAL</div>
                            <div class="num-flash text-4xl font-black font-hud mt-1.5" style="color:#fb923c;">{{ $winner ? $winner->points : 0 }}</div>
                        </div>
                    </div>

                    <div class="text-slate-700 text-[9px] font-mono tracking-widest mt-2">SYS // MATCH_WINNER_ID_0xE8FA</div>
                </div>
            </div>

            {{-- ── LEADERBOARD (slides from RIGHT) ────────────── --}}
            <div class="flex-1 grid grid-cols-2 gap-5 min-h-0">

                @php
                    $columns = [
                        ['data' => $leftColumn,  'startRank' => 2,  'delay' => 0.25],
                        ['data' => $rightColumn, 'startRank' => 10, 'delay' => 0.35],
                    ];
                @endphp

                @foreach($columns as $colIdx => $col)
                <div class="slide-right flex flex-col gap-1.5" style="animation-delay:{{ $col['delay'] }}s;">

                    {{-- Table header --}}
                    <div class="tbl-header px-3 py-2 flex items-center gap-2 rounded-t-md">
                        <span class="w-9 text-center text-[10px] font-black uppercase tracking-widest text-slate-500 font-body">#</span>
                        <span class="w-7 shrink-0"></span>
                        <span class="flex-1 text-[10px] font-black uppercase tracking-widest text-slate-300 font-body">Team</span>
                        <span class="w-11 text-center text-[10px] font-black uppercase tracking-widest text-slate-400 font-body">Elims</span>
                        <span class="w-14 text-center text-[10px] font-black uppercase tracking-widest text-slate-400 font-body">Pl.Pts</span>
                        <span class="w-14 text-right text-[10px] font-black uppercase tracking-widest text-orange-400 font-body">Total</span>
                    </div>

                    {{-- Rows — each slides up with stagger --}}
                    @foreach($col['data'] as $match)
                        @php $rank = $loop->iteration + $col['startRank'] - 1; @endphp
                        <div class="slide-up {{ $rank === 2 ? 'glass-card-rank2' : ($rank === 3 ? 'glass-card-rank3' : 'glass-card') }} px-3 py-2.5 flex items-center gap-2 rounded-md transition-all duration-200"
                             style="animation-delay:{{ ($col['delay'] + 0.1 + $loop->index * 0.07) }}s;">

                            {{-- Rank --}}
                            <div class="w-9 flex justify-center shrink-0">
                                @if($rank === 2)
                                    <span class="font-hud text-xl font-black" style="color:#cbd5e1;text-shadow:0 0 10px rgba(148,163,184,0.7);">02</span>
                                @elseif($rank === 3)
                                    <span class="font-hud text-xl font-black" style="color:#f97316;text-shadow:0 0 10px rgba(249,115,22,0.7);">03</span>
                                @else
                                    <span class="font-body text-sm font-bold text-slate-500">{{ str_pad($rank, 2, '0', STR_PAD_LEFT) }}</span>
                                @endif
                            </div>

                            {{-- Logo --}}
                            <div class="w-13 h-13 rounded shrink-0 flex items-center justify-center" style="background:rgba(2,6,23,0.85);border:1px solid rgba(255,255,255,0.12);">
                                <img src="{{ $match->tournamentTeam->logo_image ? asset('storage/'.$match->tournamentTeam->logo_image) : asset('img/defult_team_logo.png') }}" class="w-11 h-11 object-contain">
                            </div>

                            {{-- Name --}}
                            <div class="flex-1 min-w-0">
                                <div class="font-black uppercase text-xl leading-tight text-white truncate max-w-35" style="font-family:'Rajdhani',sans-serif;">{{ $match->tournamentTeam->name }}</div>
                                <div class="text-sm text-slate-400 uppercase tracking-wider font-body leading-none truncate max-w-35 mt-0.5">{{ $match->tournamentTeam->short_name }}</div>
                            </div>

                            {{-- Elims --}}
                            <div class="w-11 text-center">
                                <span class="font-hud text-base font-black text-slate-200">{{ $match->kills }}</span>
                            </div>

                            {{-- Place Pts --}}
                            <div class="w-14 text-center">
                                <span class="font-hud text-sm font-bold text-slate-400">{{ $match->points - $match->kills }}</span>
                            </div>

                            {{-- Total --}}
                            <div class="w-14 text-right">
                                <span class="font-hud text-xl font-black" style="color:#fb923c;text-shadow:0 0 8px rgba(251,146,60,0.55);">{{ $match->points }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
                @endforeach
            </div>
        </div>

        {{-- ─── FOOTER ─────────────────────────────────────── --}}
        <div class="slide-up-footer footer-bar relative z-10 flex items-center justify-between px-10 py-2.5 font-body text-[9px] font-bold text-slate-500 tracking-[0.4em] uppercase">
            <span>SYS_LOC // 0x58BF12</span>
            <div class="h-px w-36 shrink-0" style="background:linear-gradient(to right,transparent,rgba(250,204,21,0.2),transparent);"></div>
            <span class="text-yellow-400/60">Official Match Summary Overlay</span>
            <div class="h-px w-36 shrink-0" style="background:linear-gradient(to right,transparent,rgba(250,204,21,0.2),transparent);"></div>
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

            // Echo channel events
            Echo.channel('user-screens.{{ $activeMatch->tournament->user_id }}')
                .listen('.BackgroundChanged', (e) => {
                    console.log('BackgroundChanged received:', e);
                    applyBackground(e.bgType, e.customVideoUrl);
                });

            // Prevent reload listeners from executing inside the parent OBS Master View context
            if (!window.isObsMaster) {
                Echo.channel('user-screens.{{ $activeMatch->tournament->user_id }}')
                    .listen('.RefreshScreens', (e) => { window.location.reload(); })
                    .listen('.TournamentMatchUpdated', (e) => { window.location.reload(); });
                
                Echo.channel('active-match.{{ $activeMatch->id }}')
                    .listen('.MatchStatsUpdated', (e) => { window.location.reload(); });
            }
        });
    </script>
</x-base>