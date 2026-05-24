<x-base title="OVERALL STANDINGS">
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

        @keyframes slideFromLeft {
            from { opacity: 0; transform: translateX(-50px); }
            to   { opacity: 1; transform: translateX(0); }
        }
        .slide-from-left {
            animation: slideFromLeft 0.72s cubic-bezier(0.22, 1, 0.36, 1) both;
            will-change: transform, opacity;
            backface-visibility: hidden;
        }

        @keyframes slideFromRight {
            from { opacity: 0; transform: translateX(50px); }
            to   { opacity: 1; transform: translateX(0); }
        }
        .slide-from-right {
            animation: slideFromRight 0.72s cubic-bezier(0.22, 1, 0.36, 1) both;
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

        @keyframes slideUpFooter {
            from { opacity: 0; transform: translateY(32px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .slide-up-footer {
            animation: slideUpFooter 0.62s cubic-bezier(0.22, 1, 0.36, 1) both;
            animation-delay: 0.85s;
            will-change: transform, opacity;
        }

        @keyframes badgePop {
            from { opacity: 0; transform: scale(0.65); }
            65%  { transform: scale(1.12); }
            to   { opacity: 1; transform: scale(1); }
        }
        .badge-pop {
            animation: badgePop 0.68s cubic-bezier(0.34, 1.56, 0.64, 1) both;
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
        .orb { position: absolute; border-radius: 50%; filter: blur(90px); pointer-events: none; animation: orbFloat 9s ease-in-out infinite; }

        @keyframes rank1Pulse {
            0%, 100% { box-shadow: 0 0 0 0 rgba(250,204,21,0.45), inset 0 0 30px rgba(250,204,21,0.06); }
            50%       { box-shadow: 0 0 0 8px rgba(250,204,21,0.0), inset 0 0 60px rgba(250,204,21,0.13); }
        }
        .rank1-pulse { animation: rank1Pulse 2.6s ease-in-out infinite; }

        @keyframes numGlow {
            0%, 100% { text-shadow: 0 0 6px currentColor; }
            50%       { text-shadow: 0 0 22px currentColor, 0 0 44px currentColor; }
        }
        .num-glow { animation: numGlow 3s ease-in-out infinite; }

        @keyframes badgeShimmer {
            from { background-position: -200% center; }
            to   { background-position: 200% center; }
        }
        .badge-shimmer {
            background: linear-gradient(90deg,#f59e0b 0%,#fde68a 30%,#f59e0b 50%,#fb923c 70%,#f59e0b 100%);
            background-size: 200% auto;
            animation: badgeShimmer 3.5s linear infinite;
            -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
        }

        /* ══════════════════════════════════════════════════════
           CARD STYLES
           ══════════════════════════════════════════════════════ */

        .glass-row {
            background: rgba(8, 11, 22, 0.88);
            border: 1px solid rgba(255,255,255,0.07);
            box-shadow: 0 2px 14px rgba(0,0,0,0.6);
            backdrop-filter: blur(14px);
        }
        .rank1-card {
            background: linear-gradient(to right, rgba(28,20,4,0.96), rgba(8,11,22,0.96));
            border: 1px solid rgba(250,204,21,0.35);
            border-left: 3px solid #facc15;
            box-shadow: 0 0 35px rgba(250,204,21,0.13), 0 4px 20px rgba(0,0,0,0.7);
            backdrop-filter: blur(16px);
        }
        .rank2-card {
            background: rgba(8, 11, 22, 0.88);
            border: 1px solid rgba(148,163,184,0.18);
            border-left: 3px solid rgba(148,163,184,0.65);
            backdrop-filter: blur(14px);
        }
        .rank3-card {
            background: rgba(8, 11, 22, 0.88);
            border: 1px solid rgba(234,88,12,0.18);
            border-left: 3px solid rgba(234,88,12,0.65);
            backdrop-filter: blur(14px);
        }

        .tbl-header {
            background: rgba(2, 4, 16, 0.92);
            border-bottom: 2px solid rgba(250,204,21,0.55);
            backdrop-filter: blur(12px);
        }

        .header-bar {
            background: rgba(2, 4, 16, 0.85);
            border-bottom: 1px solid rgba(250,204,21,0.25);
            backdrop-filter: blur(16px);
        }

        .footer-bar {
            background: rgba(2, 4, 16, 0.82);
            border-top: 1px solid rgba(255,255,255,0.05);
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

        {{-- Ambient orbs --}}
        <div class="orb z-0" style="width:400px;height:400px;background:rgba(245,158,11,0.07);top:4%;left:6%;animation-delay:0s;"></div>
        <div class="orb z-0" style="width:480px;height:480px;background:rgba(234,88,12,0.05);bottom:4%;right:5%;animation-delay:4s;"></div>

        {{-- ─── HEADER (slides down) ────────────────────────── --}}
        <div class="slide-down header-bar relative z-10 flex justify-between items-center px-10 py-4" style="animation-delay:0s;">
            <div class="flex items-center gap-5">
                <div class="p-2 rounded-lg" style="background:rgba(250,204,21,0.1);border:1px solid rgba(250,204,21,0.4);">
                    <img src="{{ $tournament->logo_image ? asset('storage/'.$tournament->logo_image) : asset('img/defult_team_logo.png') }}" class="w-12 h-12 object-contain">
                </div>
                <div>
                    <div class="text-yellow-400 text-xs font-black uppercase tracking-[0.4em] font-body leading-none">
                        @if($currentRound) {{ $currentRound->name }} STANDINGS @else OFFICIAL LEADERBOARD @endif
                    </div>
                    <h1 class="text-3xl font-black uppercase tracking-tight leading-none text-white mt-1.5">{{ $tournament->name }}</h1>
                </div>
            </div>
            <div class="text-right">
                <div class="text-slate-500 text-[9px] font-black uppercase tracking-[0.35em] font-body leading-none">SYS // STANDINGS_OVERALL</div>
                <div class="text-3xl font-black uppercase tracking-wide mt-1.5">
                    <span class="badge-shimmer">Overall Rankings</span>
                </div>
            </div>
        </div>

        @php
            $leftColumn  = $rankings->take(8);
            $rightColumn = $rankings->slice(8)->take(8);
        @endphp

        {{-- ─── CONTENT ────────────────────────────────────── --}}
        <div class="relative z-10 flex-1 grid grid-cols-2 gap-6 px-8 py-4 min-h-0">

            @php
                $cols = [
                    ['data' => $leftColumn,  'startRank' => 1, 'class' => 'slide-from-left',  'delay' => 0.15],
                    ['data' => $rightColumn, 'startRank' => 9, 'class' => 'slide-from-right', 'delay' => 0.25],
                ];
            @endphp

            @foreach($cols as $colIdx => $col)
            <div class="{{ $col['class'] }} flex flex-col gap-1.5" style="animation-delay:{{ $col['delay'] }}s;">

                {{-- Column header --}}
                <div class="tbl-header px-3 py-2 flex items-center gap-2 rounded-t-md">
                    <span class="w-10 text-center text-[9px] font-black uppercase tracking-widest text-slate-500 font-body">#</span>
                    <span class="w-8 shrink-0"></span>
                    <span class="flex-1 text-[9px] font-black uppercase tracking-widest text-slate-300 font-body">Team</span>
                    <span class="w-12 text-center text-[9px] font-black uppercase tracking-widest text-slate-400 font-body">Played</span>
                    <span class="w-12 text-center text-[9px] font-black uppercase tracking-widest text-yellow-400 font-body">WWCD</span>
                    <span class="w-12 text-center text-[9px] font-black uppercase tracking-widest text-slate-400 font-body">Elims</span>
                    <span class="w-14 text-center text-[9px] font-black uppercase tracking-widest text-slate-400 font-body">Pl.Pts</span>
                    <span class="w-16 text-right text-[9px] font-black uppercase tracking-widest text-orange-400 font-body">Total</span>
                </div>

                {{-- Rows — slide up with stagger --}}
                @foreach($col['data'] as $item)
                    @php $rank = $loop->iteration + $col['startRank'] - 1; @endphp

                    <div class="slide-up {{ $rank === 1 ? 'rank1-card rank1-pulse' : ($rank === 2 ? 'rank2-card' : ($rank === 3 ? 'rank3-card' : 'glass-row')) }} px-3 py-2.5 flex items-center gap-2 rounded-md transition-all duration-200 hover:scale-[1.006] hover:brightness-110"
                         style="animation-delay:{{ $col['delay'] + 0.1 + $loop->index * 0.07 }}s;">

                        {{-- Rank --}}
                        <div class="w-10 flex justify-center items-center shrink-0">
                            @if($rank === 1)
                                <span class="badge-pop num-glow font-hud text-2xl font-black" style="color:#facc15;animation-delay:0.6s;">01</span>
                            @elseif($rank === 2)
                                <span class="font-hud text-xl font-black" style="color:#cbd5e1;text-shadow:0 0 8px rgba(148,163,184,0.7);">02</span>
                            @elseif($rank === 3)
                                <span class="font-hud text-xl font-black" style="color:#f97316;text-shadow:0 0 8px rgba(249,115,22,0.7);">03</span>
                            @else
                                <span class="font-body text-xs font-bold text-slate-500">{{ str_pad($rank, 2, '0', STR_PAD_LEFT) }}</span>
                            @endif
                        </div>

                        {{-- Logo --}}
                        <div class="w-14 h-14 rounded shrink-0 flex items-center justify-center" style="background:rgba(2,6,23,0.88);border:1px solid {{ $rank===1 ? 'rgba(250,204,21,0.5)' : 'rgba(255,255,255,0.12)' }};">
                            <img src="{{ $item['team']->logo_image ? asset('storage/'.$item['team']->logo_image) : asset('img/defult_team_logo.png') }}" class="w-11 h-11 object-contain">
                        </div>

                        {{-- Team Name --}}
                        <div class="flex-1 min-w-0 flex flex-col justify-center">
                            <div class="font-black uppercase leading-tight text-xl {{ $rank === 1 ? 'text-yellow-300' : 'text-white' }} truncate max-w-37.5" style="font-family:'Rajdhani',sans-serif;{{ $rank===1 ? 'text-shadow:0 0 14px rgba(250,204,21,0.35);' : '' }}">
                                {{ $item['team']->name }}
                            </div>
                            <div class="text-sm uppercase tracking-wider font-body leading-none {{ $rank===1 ? 'text-yellow-400/70' : 'text-slate-400' }} truncate max-w-37.5 mt-0.5">
                                {{ $item['team']->short_name }}
                            </div>
                        </div>

                        {{-- Played --}}
                        <div class="w-12 text-center">
                            <span class="font-body text-sm font-bold text-slate-400">{{ $item['matches_played'] }}</span>
                        </div>

                        {{-- WWCD --}}
                        <div class="w-12 text-center">
                            @if($item['total_wins'] > 0)
                                <span class="num-glow font-hud text-base font-black" style="color:#facc15;">{{ $item['total_wins'] }}</span>
                            @else
                                <span class="font-hud text-sm font-bold text-slate-600">0</span>
                            @endif
                        </div>

                        {{-- Elims --}}
                        <div class="w-12 text-center">
                            <span class="font-hud text-base font-black text-slate-200">{{ $item['total_kills'] }}</span>
                        </div>

                        {{-- Place Pts --}}
                        <div class="w-14 text-center">
                            <span class="font-hud text-sm font-bold text-slate-400">{{ $item['total_placement_points'] }}</span>
                        </div>

                        {{-- Total Pts --}}
                        <div class="w-16 text-right">
                            @if($rank === 1)
                                <span class="num-glow font-hud text-2xl font-black" style="color:#facc15;">{{ $item['total_points'] }}</span>
                            @else
                                <span class="font-hud text-xl font-black" style="color:#fb923c;text-shadow:0 0 8px rgba(251,146,60,0.4);">{{ $item['total_points'] }}</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
            @endforeach
        </div>

        {{-- ─── FOOTER (slides up) ─────────────────────────── --}}
        <div class="slide-up-footer footer-bar relative z-10 flex items-center justify-between px-10 py-2.5 font-body text-[9px] font-bold text-slate-500 tracking-[0.4em] uppercase">
            <span>SYS_LOC // 0x48FA90</span>
            <div class="h-px w-36 shrink-0" style="background:linear-gradient(to right,transparent,rgba(250,204,21,0.2),transparent);"></div>
            <span class="text-yellow-400/60">Official Standings Stream Overlay</span>
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
            }
        });
    </script>
</x-base>
