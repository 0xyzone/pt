<x-base title="TOURNAMENT ROADMAP">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700;800;900&family=Rajdhani:wght@600;700;800;900&family=Orbitron:wght@700;800;900&display=swap" rel="stylesheet">

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
            font-weight: 800;
        }

        .font-body {
            font-family: 'Inter', sans-serif;
        }

        .font-hud {
            font-family: 'Orbitron', sans-serif;
            font-weight: 800;
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

        @keyframes cyberReveal {
            0% {
                opacity: 0;
                transform: translateY(60px) scale(0.92) rotateX(-15deg);
                filter: blur(10px);
            }
            100% {
                opacity: 1;
                transform: translateY(0) scale(1) rotateX(0deg);
                filter: blur(0);
            }
        }

        .card-reveal-staggered {
            animation: cyberReveal 1.1s cubic-bezier(0.16, 1, 0.3, 1) both;
            transform-origin: bottom center;
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
            animation-delay: 0.95s;
            will-change: transform, opacity;
        }

        /* ══════════════════════════════════════════════════════
           BACKGROUND & CYBER HUD EFFECTS
           ══════════════════════════════════════════════════════ */
        .cyber-bg {
            background-color: #02040a;
            background-image:
                radial-gradient(at 10% 10%, rgba(16, 185, 129, 0.1) 0px, transparent 65%),
                radial-gradient(at 90% 90%, rgba(245, 158, 11, 0.08) 0px, transparent 65%);
        }

        .grid-bg {
            position: absolute;
            inset: 0;
            z-index: 0;
            background-size: 50px 50px;
            background-image:
                linear-gradient(to right, rgba(255, 255, 255, 0.015) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(255, 255, 255, 0.015) 1px, transparent 1px);
            pointer-events: none;
        }

        .glass-header {
            background: rgba(1, 2, 8, 0.95);
            border-bottom: 2px solid rgba(16, 185, 129, 0.45);
            backdrop-filter: blur(20px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.8);
        }

        .glass-footer {
            background: rgba(1, 2, 8, 0.95);
            border-top: 1.5px solid rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            box-shadow: 0 -10px 30px rgba(0, 0, 0, 0.8);
        }

        /* ══════════════════════════════════════════════════════
           ROADMAP PIPELINE FLOW & FLEX CARDS
           ══════════════════════════════════════════════════════ */
        .roadmap-main {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            flex: 1;
            width: 100%;
            padding: 0 48px;
            box-sizing: border-box;
            position: relative;
        }

        /* Ultra Premium Widescreen Description Box */
        .roadmap-desc-box {
            width: 100%;
            max-width: 900px;
            background: linear-gradient(90deg, rgba(8, 14, 28, 0) 0%, rgba(10, 19, 38, 0.9) 20%, rgba(10, 19, 38, 0.9) 80%, rgba(8, 14, 28, 0) 100%);
            border-left: 4px solid #f59e0b;
            border-right: 4px solid #f59e0b;
            padding: 14px 36px;
            margin-bottom: 28px;
            text-align: center;
            font-size: 13px;
            font-weight: 600;
            color: #cbd5e1;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.6);
            border-radius: 4px;
        }

        .timeline-container {
            width: 100%;
            max-width: 1600px;
            position: relative;
            margin-top: 5px;
        }

        /* Winding horizontal progress line track */
        .timeline-track {
            position: absolute;
            top: 26px; /* matches center of 56px timeline node */
            left: 12.5%;
            right: 12.5%;
            height: 6px;
            background: rgba(255, 255, 255, 0.05);
            z-index: 1;
            border-radius: 3px;
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.9);
        }

        .timeline-track-progress {
            height: 100%;
            background: linear-gradient(90deg, #10b981 0%, #f59e0b 50%, #0ea5e9 100%);
            width: 0%; /* dynamically injected */
            box-shadow: 0 0 20px #f59e0b, 0 0 35px #10b981;
            border-radius: 3px;
            transition: width 2s cubic-bezier(0.22, 1, 0.36, 1);
        }

        /* Robust horizontal flex layout to prevent vertical stacking */
        .timeline-row {
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            align-items: stretch; /* equal height for all columns */
            width: 100%;
            position: relative;
            z-index: 2;
            gap: 28px;
        }

        .timeline-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            flex: 1;
            min-width: 260px;
            position: relative;
        }

        .timeline-node {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            z-index: 5;
            margin-bottom: 24px;
            background: #060a12;
            border: 2.5px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.9), inset 0 2px 5px rgba(255, 255, 255, 0.05);
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .timeline-node-active {
            border-color: #f59e0b;
            background: #251806;
            box-shadow: 0 0 30px rgba(245, 158, 11, 0.7), inset 0 0 10px rgba(245, 158, 11, 0.3);
        }

        .timeline-node-completed {
            border-color: #10b981;
            background: #03140e;
            box-shadow: 0 0 25px rgba(16, 185, 129, 0.5);
        }

        .timeline-node-upcoming {
            border-color: #0ea5e9;
            background: #030e14;
            box-shadow: 0 0 20px rgba(14, 165, 233, 0.3);
        }

        /* Ultra Premium Widescreen Cards */
        .timeline-card {
            background: linear-gradient(135deg, rgba(10, 18, 36, 0.96) 0%, rgba(4, 7, 16, 0.99) 100%);
            border: 2px solid rgba(255, 255, 255, 0.06);
            border-radius: 20px;
            padding: 26px 24px;
            width: 100%;
            flex: 1; /* stretches card body dynamically */
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.7), inset 0 1px 1px rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            overflow: hidden; /* prevents inner content/animations from leaking outside the rounded corners */
            box-sizing: border-box;
        }

        /* Background large index watermark */
        .card-watermark {
            position: absolute;
            top: 24px;
            right: 20px;
            font-family: 'Orbitron', sans-serif;
            font-size: 72px;
            font-weight: 900;
            line-height: none;
            user-select: none;
            pointer-events: none;
            z-index: 0;
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes activePulse {
            0%, 100% {
                box-shadow: 0 0 20px rgba(245, 158, 11, 0.08), 0 25px 50px rgba(0, 0, 0, 0.7);
                border-color: rgba(245, 158, 11, 0.45);
            }
            50% {
                box-shadow: 0 0 35px rgba(245, 158, 11, 0.22), 0 25px 50px rgba(0, 0, 0, 0.8);
                border-color: rgba(245, 158, 11, 0.65);
            }
        }

        @keyframes completedPulse {
            0%, 100% {
                box-shadow: 0 0 15px rgba(16, 185, 129, 0.04), 0 25px 50px rgba(0, 0, 0, 0.7);
                border-color: rgba(16, 185, 129, 0.35);
            }
            50% {
                box-shadow: 0 0 25px rgba(16, 185, 129, 0.15), 0 25px 50px rgba(0, 0, 0, 0.8);
                border-color: rgba(16, 185, 129, 0.55);
            }
        }

        @keyframes upcomingPulse {
            0%, 100% {
                box-shadow: 0 0 15px rgba(14, 165, 233, 0.04), 0 25px 50px rgba(0, 0, 0, 0.7);
                border-color: rgba(14, 165, 233, 0.25);
            }
            50% {
                box-shadow: 0 0 25px rgba(14, 165, 233, 0.15), 0 25px 50px rgba(0, 0, 0, 0.8);
                border-color: rgba(14, 165, 233, 0.45);
            }
        }

        @keyframes watermarkBreath {
            0%, 100% { opacity: 0.05; transform: scale(1); }
            50% { opacity: 0.10; transform: scale(1.04) translate(-2px, -2px); }
        }

        .card-watermark {
            animation: watermarkBreath 8s infinite ease-in-out;
        }

        /* Status Custom Themes (High-contrast glows) */
        .timeline-card-active {
            border-left: 6px solid #f59e0b !important;
            border-color: rgba(245, 158, 11, 0.45) !important;
            box-shadow: 0 0 20px rgba(245, 158, 11, 0.08), 0 25px 50px rgba(0, 0, 0, 0.7);
        }
        .timeline-card-active .card-watermark {
            color: rgba(245, 158, 11, 0.08);
        }

        .timeline-card-completed {
            border-left: 6px solid #10b981 !important;
            border-color: rgba(16, 185, 129, 0.35) !important;
            box-shadow: 0 0 15px rgba(16, 185, 129, 0.04), 0 25px 50px rgba(0, 0, 0, 0.7);
        }
        .timeline-card-completed .card-watermark {
            color: rgba(16, 185, 129, 0.08);
        }

        .timeline-card-upcoming {
            border-left: 6px solid #0ea5e9 !important;
            border-color: rgba(14, 165, 233, 0.25) !important;
            box-shadow: 0 0 15px rgba(14, 165, 233, 0.04), 0 25px 50px rgba(0, 0, 0, 0.7);
        }
        .timeline-card-upcoming .card-watermark {
            color: rgba(14, 165, 233, 0.07);
        }

        /* High-contrast highlighted date badge */
        .timeline-date-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(0, 0, 0, 0.45);
            border: 1.5px solid rgba(255, 255, 255, 0.1);
            padding: 6px 14px;
            border-radius: 30px;
            color: #ffffff;
            font-size: 11px;
            font-weight: 850;
            letter-spacing: 0.12em;
            font-family: 'Orbitron', sans-serif;
            box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.6);
        }
        .timeline-card-active .timeline-date-badge {
            border-color: rgba(245, 158, 11, 0.45);
            color: #fef08a;
        }
        .timeline-card-completed .timeline-date-badge {
            border-color: rgba(16, 185, 129, 0.4);
            color: #a7f3d0;
        }
        .timeline-card-upcoming .timeline-date-badge {
            border-color: rgba(14, 165, 233, 0.4);
            color: #bae6fd;
        }

        /* Diagonal laser sweep scan */
        @keyframes cyberSweep {
            0% {
                transform: translate(-100%, -100%) rotate(45deg);
            }
            100% {
                transform: translate(200%, 200%) rotate(45deg);
            }
        }
        
        .timeline-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, transparent, rgba(255,255,255,0.01) 40%, rgba(255,255,255,0.06) 50%, rgba(255,255,255,0.01) 60%, transparent);
            transform: translate(-100%, -100%) rotate(45deg);
            pointer-events: none;
            animation: cyberSweep 7s infinite ease-in-out;
        }

        /* Scanline sweeps inside HUD */
        @keyframes scanline {
            0% { transform: translateY(-100%); }
            100% { transform: translateY(100%); }
        }

        .scanline-sweep::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(to bottom, transparent, rgba(255, 255, 255, 0.02), transparent);
            animation: scanline 4.5s linear infinite;
            pointer-events: none;
        }

        .bg-grid-tiny {
            background-size: 8px 8px;
            background-image:
                linear-gradient(to right, rgba(255, 255, 255, 0.02) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(255, 255, 255, 0.02) 1px, transparent 1px);
        }

        /* Radar pulse for live nodes */
        @keyframes radarPulse {
            0% {
                transform: scale(0.9);
                opacity: 1;
            }
            100% {
                transform: scale(2.2);
                opacity: 0;
            }
        }

        .radar-glow {
            position: absolute;
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background-color: rgba(245, 158, 11, 0.6);
            animation: radarPulse 1.8s infinite ease-out;
        }

        /* Esports corner brackets */
        .card-corner-brackets {
            position: absolute;
            inset: 0;
            pointer-events: none;
            z-index: 2;
        }

        .card-corner-brackets > div {
            position: absolute;
            width: 12px;
            height: 12px;
            border-style: solid;
            border-color: rgba(255, 255, 255, 0.12);
            pointer-events: none;
            transition: border-color 0.4s cubic-bezier(0.16, 1, 0.3, 1), filter 0.4s ease;
        }

        .bracket-tl {
            top: 14px;
            left: 14px;
            border-width: 2px 0 0 2px;
        }

        .bracket-tr {
            top: 14px;
            right: 14px;
            border-width: 2px 2px 0 0;
        }

        .bracket-bl {
            bottom: 14px;
            left: 14px;
            border-width: 0 0 2px 2px;
        }

        .bracket-br {
            bottom: 14px;
            right: 14px;
            border-width: 0 2px 2px 0;
        }

        .timeline-card-active .card-corner-brackets > div {
            border-color: rgba(245, 158, 11, 0.55);
            filter: drop-shadow(0 0 3px rgba(245, 158, 11, 0.4));
        }

        .timeline-card-completed .card-corner-brackets > div {
            border-color: rgba(16, 185, 129, 0.45);
            filter: drop-shadow(0 0 3px rgba(16, 185, 129, 0.35));
        }

        .timeline-card-upcoming .card-corner-brackets > div {
            border-color: rgba(14, 165, 233, 0.45);
            filter: drop-shadow(0 0 3px rgba(14, 165, 233, 0.35));
        }

        .text-glow-emerald {
            text-shadow: 0 0 10px rgba(16, 185, 129, 0.5), 0 0 20px rgba(16, 185, 129, 0.2);
        }

        .text-glow-yellow {
            text-shadow: 0 0 10px rgba(245, 158, 11, 0.5), 0 0 20px rgba(245, 158, 11, 0.2);
        }

        .text-glow-cyan {
            text-shadow: 0 0 10px rgba(14, 165, 233, 0.5), 0 0 20px rgba(14, 165, 233, 0.2);
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
                    <div class="text-emerald-400 text-xs font-black uppercase tracking-[0.4em] font-body leading-none">OFFICIAL TIMELINE</div>
                    <h1 class="text-3xl font-black uppercase tracking-wider leading-none text-white mt-1.5">{{ $tournament ? $tournament->name : 'PUBG Mobile Championship' }}</h1>
                </div>
            </div>
            <div class="text-right">
                <div class="text-slate-500 text-[10px] font-black uppercase tracking-[0.3em] font-body leading-none">ROAD TO GLORY</div>
                <div class="text-2xl font-black uppercase tracking-widest mt-1.5 text-yellow-400 leader-text-pulse">
                    {{ $roadmap ? $roadmap->title : 'TOURNAMENT ROADMAP' }}
                </div>
            </div>
        </div>

        {{-- ─── MAIN CONTENT ────────────────────────────────── --}}
        <div class="roadmap-main relative z-10 flex-1 flex flex-col justify-center items-center py-6 min-h-0 select-none">

            @if(!$roadmap || empty($roadmap->steps))
            {{-- Visual stunning empty fallback --}}
            <div class="hud-card scanline-sweep p-12 rounded-3xl border border-slate-800/80 flex flex-col justify-center items-center text-center max-w-lg card-reveal-staggered" style="animation-delay: 0.15s;">
                <div class="w-20 h-20 rounded-2xl bg-slate-950 border border-slate-800 flex items-center justify-center mb-6">
                    <svg class="w-10 h-10 text-slate-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 6.75L12 3m0 0l3 3m-3-3v12m-9-2.25h18" />
                    </svg>
                </div>
                <h3 class="text-slate-300 font-hud text-lg font-black uppercase tracking-widest">ROADMAP NOT YET DEFINED</h3>
                <p class="text-slate-500 font-body text-xs mt-2 uppercase tracking-widest leading-relaxed">Please configure the milestones & stages inside the Tournament Roadmap Control Panel page first.</p>
            </div>
            @else

            {{-- General roadmap description if exists --}}
            @if($roadmap->description)
            <div class="card-reveal-staggered roadmap-desc-box" style="animation-delay: 0.1s;">
                {{ $roadmap->description }}
            </div>
            @endif

            {{-- ─── HORIZONTAL TIMELINE WIDGET ───────────────────── --}}
            <div class="timeline-container">
                @php
                    $totalSteps = count($roadmap->steps);
                    $activeStepIndex = 0;
                    foreach($roadmap->steps as $index => $step) {
                        if (strtolower($step['status'] ?? '') === 'active') {
                            $activeStepIndex = $index;
                            break;
                        }
                    }
                    if ($activeStepIndex === 0) {
                        $completedCount = collect($roadmap->steps)->filter(fn($s) => strtolower($s['status'] ?? '') === 'completed')->count();
                        $progressPercent = $totalSteps > 1 ? ($completedCount / ($totalSteps - 1)) * 100 : 0;
                    } else {
                        $progressPercent = $totalSteps > 1 ? ($activeStepIndex / ($totalSteps - 1)) * 100 : 0;
                    }
                    $progressPercent = min(100, max(0, $progressPercent));
                @endphp

                {{-- Glowing timeline track --}}
                <div class="timeline-track">
                    <div class="timeline-track-progress" id="timeline-progress-bar" style="width: {{ $progressPercent }}%;"></div>
                </div>

                {{-- Horizontal milestone cards flex row --}}
                <div class="timeline-row">
                    @foreach($roadmap->steps as $step)
                        @php
                            $status = strtolower($step['status'] ?? 'upcoming');
                            $isCompleted = $status === 'completed';
                            $isActive = $status === 'active';
                            $isUpcoming = $status === 'upcoming' || $status === 'cancelled';

                            // Aesthetic classifications
                            if ($isActive) {
                                $cardClass = 'timeline-card-active';
                                $textGlow = 'text-yellow-400 text-glow-yellow';
                                $nodeClass = 'timeline-node-active';
                            } elseif ($isCompleted) {
                                $cardClass = 'timeline-card-completed';
                                $textGlow = 'text-emerald-400 text-glow-emerald';
                                $nodeClass = 'timeline-node-completed';
                            } else {
                                $cardClass = 'timeline-card-upcoming';
                                $textGlow = 'text-cyan-400 text-glow-cyan';
                                $nodeClass = 'timeline-node-upcoming';
                            }

                            $delay = 0.12 + (0.12 * $loop->index);
                        @endphp
                        
                        <div class="timeline-item">
                            {{-- Timeline Node Circle --}}
                            <div class="card-reveal-staggered timeline-node {{ $nodeClass }}" style="animation-delay: {{ $delay }}s;">
                                @if($isActive)
                                    <div class="radar-glow"></div>
                                    <div class="w-3 h-3 bg-yellow-400 rounded-full z-10" style="width:12px; height:12px;"></div>
                                @elseif($isCompleted)
                                    <svg class="w-6 h-6 text-emerald-400 z-10" fill="none" stroke="currentColor" stroke-width="3.5" viewBox="0 0 24 24" style="width:24px; height:24px;">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                    </svg>
                                @else
                                    <div class="w-3 h-3 bg-cyan-400 rounded-full z-10" style="width:12px; height:12px; box-shadow: 0 0 10px rgba(14, 165, 233, 0.8);"></div>
                                @endif
                            </div>

                            {{-- Phase Card (placed below the node) --}}
                            <div class="card-reveal-staggered timeline-card scanline-sweep {{ $cardClass }}" style="animation-delay: {{ $delay + 0.12 }}s; height: 215px;">
                                <div class="absolute inset-0 bg-grid-tiny opacity-10 pointer-events-none"></div>
                                <div class="card-corner-brackets">
                                    <div class="bracket-tl"></div>
                                    <div class="bracket-tr"></div>
                                    <div class="bracket-bl"></div>
                                    <div class="bracket-br"></div>
                                </div>
                                <div class="card-watermark">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</div>
                                
                                {{-- Card Header: Phase badge & Status --}}
                                <div style="display:flex; justify-content:space-between; align-items:center; width:100%; border-bottom: 1.5px solid rgba(255,255,255,0.08); padding-bottom: 10px; position:relative; z-index:1;">
                                    <span class="font-hud tracking-[0.2em] uppercase" style="font-size:11px; color:#94a3b8;">
                                        PHASE {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}
                                    </span>
                                    <span class="font-hud uppercase" style="font-size:9px; font-weight:900; letter-spacing:0.12em; border-width:1.5px; border-style:solid; padding:3px 10px; border-radius:5px;
                                        {{ $isActive ? 'color:#000000; border-color:#f59e0b; bg-color:#f59e0b; background:#f59e0b; font-weight:900;' : ($isCompleted ? 'color:#10b981; border-color:rgba(16,185,129,0.4); background:rgba(16,185,129,0.15);' : 'color:#0ea5e9; border-color:rgba(14,165,233,0.4); background:rgba(14,165,233,0.15);') }}">
                                        {{ $isActive ? 'LIVE NOW' : ($isCompleted ? 'COMPLETED' : strtoupper($status)) }}
                                    </span>
                                </div>

                                {{-- Card Body: Name & Dates --}}
                                <div style="margin-top:14px; margin-bottom:14px; position:relative; z-index:1; display:flex; flex-direction:column; gap:8px;">
                                    <h3 class="font-esports uppercase tracking-wide {{ $textGlow }}" style="font-size:24px; font-weight:900; margin:0; line-height:1.1; letter-spacing:0.02em; color:#ffffff;">
                                        {{ $step['name'] }}
                                    </h3>
                                    
                                    <div>
                                        <div class="timeline-date-badge">
                                            <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" style="width:13px; height:13px;">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5m-9-6h.008v.008H12v-.008zM12 15h.008v.008H12V15zm0 2.25h.008v.008H12v-.008zM9.75 15h.008v.008H9.75V15zm0 2.25h.008v.008H9.75v-.008zM7.5 15h.008v.008H7.5V15zm0 2.25h.008v.008H7.5v-.008zm6.75-4.5h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V15zm0 2.25h.008v.008h-.008v-.008zm2.25-4.5h.008v.008H16.5v-.008zm0 2.25h.008v.008H16.5V15z" />
                                            </svg>
                                            @if(!empty($step['start_date']) || !empty($step['end_date']))
                                                @if(!empty($step['start_date']))
                                                    <span>{{ \Carbon\Carbon::parse($step['start_date'])->format('M d') }}</span>
                                                @endif
                                                @if(!empty($step['start_date']) && !empty($step['end_date']))
                                                    <span style="color:rgba(255,255,255,0.4);">-</span>
                                                @endif
                                                @if(!empty($step['end_date']))
                                                    <span>{{ \Carbon\Carbon::parse($step['end_date'])->format('M d') }}</span>
                                                @endif
                                            @else
                                                <span>SCHEDULE TBD</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                {{-- Card Footer: Details / Goals --}}
                                <div class="font-body font-medium" style="border-top: 1.5px solid rgba(255,255,255,0.06); padding-top: 10px; font-size:11.5px; color:#e2e8f0; line-height:1.55; position:relative; z-index:1;">
                                    {{ $step['description'] ?: 'Milestones configuration pending for this stage.' }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

        </div>

        {{-- ─── FOOTER ─────────────────────────────────────── --}}
        <div class="slide-up-footer glass-footer relative z-10 flex items-center justify-between px-12 py-3.5 font-body text-[10px] font-bold text-slate-500 tracking-[0.4em] uppercase">
            <span>SYS_LOC // 0x58BF34</span>
            <div class="h-px w-48 shrink-0" style="height:1.5px; width:192px; background: linear-gradient(90deg, transparent, rgba(245,158,11,0.35), transparent);"></div>
            <span class="text-yellow-400/60 font-black">Official Tournament Schedule & Milestones</span>
            <div class="h-px w-48 shrink-0" style="height:1.5px; width:192px; background: linear-gradient(90deg, transparent, rgba(245,158,11,0.35), transparent);"></div>
            <span>SYS_VER_{{ $systemVersion }}</span>
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
                    .listen('.TournamentRoadmapUpdated', (e) => { window.location.reload(); });
            }
        });
    </script>
</x-base>
