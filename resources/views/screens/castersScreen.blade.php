<x-base title="CASTERS OVERLAY">
    {{-- High-End Esports & Sci-Fi Typography --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700;900&family=Rajdhani:wght@600;700;900&family=Orbitron:wght@500;700;900&display=swap" rel="stylesheet">

    @php
    // Robust VDO.Ninja URL parsing helper
    if (!function_exists('formatVdoNinjaUrl')) {
    function formatVdoNinjaUrl($url) {
    if (empty($url)) return '';

    $url = trim($url);

    // If it is just a room name or stream ID (doesn't contain http)
    if (!str_starts_with($url, 'http://') && !str_starts_with($url, 'https://')) {
    $url = "https://vdo.ninja/?view=" . urlencode($url);
    }

    // Convert accidental push/invite link to view URL
    $url = str_replace('push=', 'view=', $url);

    // Append transparent parameters
    $separator = str_contains($url, '?') ? '&' : '?';

    $params = [];
    if (!str_contains($url, 'transparent=')) $params[] = 'transparent=1';
    if (!str_contains($url, 'cleanoutput=')) $params[] = 'cleanoutput=1';
    if (!str_contains($url, 'autoplay=')) $params[] = 'autoplay=1';
    if (!str_contains($url, 'bgopacity=')) $params[] = 'bgopacity=0';

    if (!empty($params)) {
    $url .= $separator . implode('&', $params);
    }

    return $url;
    }
    }
    @endphp

    <style>
        .font-esports {
            font-family: 'Rajdhani', sans-serif;
        }

        .font-body-esports {
            font-family: 'Inter', sans-serif;
        }

        .font-orbitron {
            font-family: 'Orbitron', sans-serif;
        }

        /* Sci-fi Moving Grid Background */
        .cyber-grid {
            background-size: 50px 50px;
            background-image:
                linear-gradient(to right, rgba(0, 240, 255, 0.015) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(0, 240, 255, 0.015) 1px, transparent 1px);
            animation: gridMove 20s linear infinite;
        }

        @keyframes gridMove {
            0% {
                background-position: 0 0;
            }

            100% {
                background-position: 50px 50px;
            }
        }

        /* 3D Virtual Studio Curved Framing */
        .virtual-set-overlay {
            background:
                radial-gradient(ellipse at top, rgba(16, 185, 129, 0.01) 0%, transparent 60%),
                radial-gradient(ellipse at bottom, rgba(0, 240, 255, 0.03) 0%, transparent 70%);
        }

        /* Neon Laser Sweep */
        .laser-sweep {
            position: absolute;
            inset: 0;
            background: linear-gradient(to bottom, transparent 40%, rgba(0, 240, 255, 0.03) 50%, transparent 60%);
            animation: sweep 12s infinite linear;
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

        /* Sci-Fi Spinning Objects */
        .animate-spin-slow {
            animation: spin 12s linear infinite;
        }

        .animate-spin-reverse {
            animation: spin-back 16s linear infinite;
        }

        @keyframes spin-back {
            from {
                transform: rotate(360deg);
            }

            to {
                transform: rotate(0deg);
            }
        }

        /* Glowing chassis borders */
        .neon-glow-cyan {
            box-shadow: 0 0 25px rgba(0, 240, 255, 0.15), inset 0 0 15px rgba(0, 240, 255, 0.05);
            border: 1px solid rgba(0, 240, 255, 0.3);
        }

        .neon-glow-gold {
            box-shadow: 0 0 25px rgba(234, 179, 8, 0.15), inset 0 0 15px rgba(234, 179, 8, 0.05);
            border: 1px solid rgba(234, 179, 8, 0.3);
        }

        /* Tactical Brackets and Sci-Fi Corners */
        .bracket-corner::before,
        .bracket-corner::after,
        .bracket-inner::before,
        .bracket-inner::after {
            content: '';
            position: absolute;
            width: 18px;
            height: 18px;
            border-color: #00f0ff;
            border-style: solid;
            pointer-events: none;
            z-index: 25;
        }

        .bracket-corner::before {
            top: -2px;
            left: -2px;
            border-width: 3px 0 0 3px;
        }

        .bracket-corner::after {
            top: -2px;
            right: -2px;
            border-width: 3px 3px 0 0;
        }

        .bracket-inner::before {
            bottom: -2px;
            left: -2px;
            border-width: 0 0 3px 3px;
        }

        .bracket-inner::after {
            bottom: -2px;
            right: -2px;
            border-width: 0 3px 3px 0;
        }

        /* Gold highlight bracket for active styling */
        .bracket-gold::before,
        .bracket-gold::after,
        .bracket-gold-inner::before,
        .bracket-gold-inner::after {
            border-color: #eab308;
        }

        /* Holographic flickering text */
        .holo-text {
            animation: holoFlicker 3s infinite;
        }

        @keyframes holoFlicker {

            0%,
            19.999%,
            22%,
            62.999%,
            64%,
            64.999%,
            70%,
            100% {
                opacity: 0.99;
                filter: hue-rotate(0deg);
            }

            20%,
            21.999%,
            63%,
            63.999%,
            65%,
            69.999% {
                opacity: 0.4;
                filter: hue-rotate(90deg);
            }
        }

        /* Pulse light indicator */
        .pulse-light {
            animation: pulseGlow 2.5s infinite ease-in-out;
        }

        @keyframes pulseGlow {

            0%,
            100% {
                opacity: 0.3;
            }

            50% {
                opacity: 0.85;
            }
        }

        /* Center virtual monitor desk support shape */
        .desk-base {
            clip-path: polygon(15% 0%, 85% 0%, 100% 100%, 0% 100%);
            background: linear-gradient(to bottom, #111827, #030712);
            border-top: 2px solid rgba(0, 240, 255, 0.2);
        }

    </style>

    {{-- Main screen wrapper — id used for live bg switching --}}
    <div id="screen-wrapper" class="w-full h-full min-h-screen {{ $bgType === 'animated' ? 'cyber-bg' : 'bg-transparent' }} relative flex flex-col justify-between items-center py-10 px-12 overflow-hidden text-white font-esports select-none virtual-set-overlay">

        {{-- Custom background video layer --}}
        <video id="bg-video" autoplay loop muted playsinline class="absolute inset-0 w-full h-full object-cover z-0 {{ ($bgType === 'custom' && $customVideo) ? '' : 'hidden' }}">
            @if($bgType === 'custom' && $customVideo)
            <source src="{{ asset('storage/' . $customVideo) }}" type="video/mp4">
            @endif
        </video>
        <div id="bg-video-overlay" class="absolute inset-0 bg-slate-950/85 z-0 backdrop-blur-[1px] {{ ($bgType === 'custom' && $customVideo) ? '' : 'hidden' }}"></div>

        {{-- Interactive scanning overlays --}}
        <div id="bg-grid" class="absolute inset-0 cyber-grid z-0 opacity-25 {{ $bgType === 'transparent' ? 'hidden' : '' }}"></div>
        <div id="bg-sweep" class="laser-sweep z-0 {{ $bgType === 'transparent' ? 'hidden' : '' }}"></div>

        {{-- BACKGROUND ANIMATED OBJECTS: Left & Right Rotating Ventilator Fans --}}
        <div class="absolute top-10 left-10 w-24 h-24 z-10 opacity-20 pointer-events-none select-none">
            <svg class="w-full h-full text-cyan-400 animate-spin-slow" viewBox="0 0 100 100" fill="currentColor">
                <circle cx="50" cy="50" r="14" fill="none" stroke="currentColor" stroke-width="4" />
                <path d="M50 15 L56 38 A4 4 0 0 1 44 38 Z" />
                <path d="M50 85 L44 62 A4 4 0 0 1 56 62 Z" />
                <path d="M15 50 L38 44 A4 4 0 0 1 38 56 Z" />
                <path d="M85 50 L62 56 A4 4 0 0 1 62 44 Z" />
                <path d="M25 25 L41 41 A4 4 0 0 1 34 48 Z" />
                <path d="M75 75 L59 59 A4 4 0 0 1 66 52 Z" />
                <path d="M75 25 L59 41 A4 4 0 0 1 52 34 Z" />
                <path d="M25 75 L41 59 A4 4 0 0 1 48 66 Z" />
            </svg>
        </div>
        <div class="absolute top-10 right-10 w-24 h-24 z-10 opacity-20 pointer-events-none select-none">
            <svg class="w-full h-full text-cyan-400 animate-spin-reverse" viewBox="0 0 100 100" fill="currentColor">
                <circle cx="50" cy="50" r="14" fill="none" stroke="currentColor" stroke-width="4" />
                <path d="M50 15 L56 38 A4 4 0 0 1 44 38 Z" />
                <path d="M50 85 L44 62 A4 4 0 0 1 56 62 Z" />
                <path d="M15 50 L38 44 A4 4 0 0 1 38 56 Z" />
                <path d="M85 50 L62 56 A4 4 0 0 1 62 44 Z" />
                <path d="M25 25 L41 41 A4 4 0 0 1 34 48 Z" />
                <path d="M75 75 L59 59 A4 4 0 0 1 66 52 Z" />
                <path d="M75 25 L59 41 A4 4 0 0 1 52 34 Z" />
                <path d="M25 75 L41 59 A4 4 0 0 1 48 66 Z" />
            </svg>
        </div>

        {{-- BACKGROUND TELEMETRY CHART GRAPHICS --}}
        <div class="absolute bottom-20 left-12 z-10 opacity-15 pointer-events-none select-none hidden lg:flex flex-col gap-2 font-mono text-[9px] text-cyan-400">
            <span class="tracking-wider">SYS_LINK_FLOW</span>
            <div class="flex items-end gap-1 h-12 w-32 border-b border-l border-cyan-500/35 p-1">
                <div class="w-2.5 bg-cyan-400 animate-pulse" style="height: 40%"></div>
                <div class="w-2.5 bg-cyan-400" style="height: 65%"></div>
                <div class="w-2.5 bg-cyan-400 animate-pulse" style="height: 25%"></div>
                <div class="w-2.5 bg-cyan-400" style="height: 85%"></div>
                <div class="w-2.5 bg-cyan-400 animate-pulse" style="height: 50%"></div>
                <div class="w-2.5 bg-cyan-400" style="height: 70%"></div>
                <div class="w-2.5 bg-cyan-400 animate-pulse" style="height: 95%"></div>
            </div>
        </div>

        <div class="absolute bottom-20 right-12 z-10 opacity-15 pointer-events-none select-none hidden lg:flex flex-col gap-2 font-mono text-[9px] text-yellow-400">
            <span class="tracking-wider text-right">BROADCAST_STATUS_TELEMETRY</span>
            <div class="flex flex-col gap-1 w-36 border border-yellow-500/25 p-2 rounded-lg bg-slate-900/40">
                <div class="flex justify-between"><span>BITRATE:</span><span id="tel-bitrate">6450 kbps</span></div>
                <div class="flex justify-between"><span>LATENCY:</span><span id="tel-latency">12ms</span></div>
                <div class="flex justify-between"><span>FPS:</span><span>60.00</span></div>
            </div>
        </div>

        {{-- TOP: Tournament Branding HUD --}}
        <div class="w-full max-w-430 flex justify-between items-center z-20">
            {{-- Telemetry Info (Left) --}}
            <div class="flex flex-col gap-1 text-left">
                <span class="telemetry-text font-orbitron">SYSTEM // DESK_LIVE</span>
                <div class="flex items-center gap-2">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-red-500"></span>
                    </span>
                    <span class="text-xs text-slate-400 font-bold tracking-widest uppercase font-orbitron">FEED_ONLINE</span>
                </div>
            </div>

            {{-- Center Title bar for 1 or 3+ casters layouts --}}
            @if($casters->count() != 2)
            <div class="flex items-center gap-4 bg-slate-900/60 border border-cyan-400/25 px-6 py-2.5 rounded-xl backdrop-blur-md shadow-lg">
                <img src="{{ $tournament && $tournament->logo_image ? asset('storage/' . $tournament->logo_image) : asset('img/defult_team_logo.png') }}" class="w-10 h-10 object-contain" alt="Tournament Logo">
                <div class="flex flex-col text-left">
                    <span class="text-[10px] font-bold text-cyan-400 tracking-[0.3em] uppercase leading-none font-orbitron">CASTERS ANALYST DESK</span>
                    <span class="text-lg font-black uppercase text-slate-100 tracking-wider mt-1 leading-none font-esports">
                        {{ $tournament ? $tournament->name : 'MAIDAN CHAMPIONSHIP' }}
                    </span>
                </div>
            </div>
            @endif

            {{-- Telemetry Info (Right) --}}
            <div class="flex flex-col gap-1 text-right">
                <span class="telemetry-text font-orbitron">ACTIVE_PHASE</span>
                <span class="text-lg font-black uppercase text-cyan-400 tracking-widest leading-none font-esports">
                    {{ $activeMatch ? $activeMatch->name : 'LIVE DESK' }}
                </span>
            </div>
        </div>

        {{-- MIDDLE: Dynamic Caster Layouts --}}
        <div class="w-full max-w-430 flex justify-center items-center my-auto z-20">

            {{-- CASE 1: 1 CASTER --}}
            @if($casters->count() == 1)
            @php $caster = $casters->first(); @endphp
            <div class="flex flex-col items-center justify-center gap-6">
                <div class="relative w-215 h-[483.75px] bg-slate-950/80 border border-cyan-400/25 rounded-lg overflow-hidden neon-glow-cyan bracket-corner bracket-inner flex items-center justify-center">

                    {{-- Static Background Image Fallback --}}
                    <div class="absolute inset-0 w-full h-full z-0 overflow-hidden">
                        @if ($caster->image)
                        <img src="{{ asset('storage/' . $caster->image) }}" class="w-full h-full object-cover" alt="{{ $caster->display_name }}">
                        @else
                        <div class="w-full h-full bg-slate-900 flex flex-col items-center justify-center">
                            <svg class="w-24 h-24 text-slate-700 pulse-light mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <span class="text-xs text-slate-500 font-bold uppercase tracking-widest font-orbitron">NO_IMAGE</span>
                        </div>
                        @endif
                    </div>

                    {{-- VDO.Ninja Frame --}}
                    @if ($caster->vdoninja_link)
                    <iframe src="{{ formatVdoNinjaUrl($caster->vdoninja_link) }}" class="absolute inset-0 w-full h-full border-0 z-10" allow="autoplay;camera;microphone;fullscreen;picture-in-picture;display-capture" allowtransparency="true">
                    </iframe>
                    @endif

                    {{-- Nameplate overlay --}}
                    <div class="absolute bottom-0 inset-x-0 h-20 bg-linear-to-t from-slate-950 via-slate-950/90 to-transparent z-20 flex flex-col justify-end px-8 pb-5">
                        <div class="flex justify-between items-end">
                            <div class="flex flex-col text-left">
                                <span class="text-3xl font-black uppercase text-cyan-400 tracking-wider leading-none font-esports">
                                    {{ $caster->display_name }}
                                </span>
                                <span class="text-sm text-slate-400 font-bold mt-1.5 leading-none font-orbitron">
                                    {{ $caster->display_handle ?: '@caster' }}
                                </span>
                            </div>
                            <div class="flex items-center gap-2 bg-slate-900/80 border border-cyan-400/30 px-4 py-1.5 rounded-lg text-xs tracking-wider uppercase font-orbitron text-cyan-400 font-semibold shadow-inner">
                                <span class="relative flex h-2 w-2">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-cyan-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-2 w-2 bg-cyan-500"></span>
                                </span>
                                {{ $caster->vdoninja_link ? 'VDO_FEED' : 'STATIC_PIC' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- CASE 2: 2 CASTERS (With Center Infinix-style Virtual Monitor) --}}
            @elseif($casters->count() == 2 || $casters->count() == 0)
            @php
            $caster1 = $casters->get(0);
            $caster2 = $casters->get(1);
            @endphp
            <div class="flex items-center justify-between w-full gap-8">

                {{-- Caster 1 (Left Screen) --}}
                <div class="relative w-155 h-[348.75px] bg-slate-950/80 border border-cyan-400/25 rounded-lg overflow-hidden neon-glow-cyan bracket-corner bracket-inner flex items-center justify-center">
                    @if ($caster1)
                    <div class="absolute inset-0 w-full h-full z-0 overflow-hidden">
                        @if ($caster1->image)
                        <img src="{{ asset('storage/' . $caster1->image) }}" class="w-full h-full object-cover" alt="{{ $caster1->display_name }}">
                        @else
                        <div class="w-full h-full bg-slate-900 flex flex-col items-center justify-center">
                            <svg class="w-16 h-16 text-slate-700 pulse-light mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <span class="text-[10px] text-slate-500 font-bold uppercase tracking-widest font-orbitron">NO_IMAGE</span>
                        </div>
                        @endif
                    </div>

                    @if ($caster1->vdoninja_link)
                    <iframe src="{{ formatVdoNinjaUrl($caster1->vdoninja_link) }}" class="absolute inset-0 w-full h-full border-0 z-10" allow="autoplay;camera;microphone;fullscreen;picture-in-picture;display-capture" allowtransparency="true">
                    </iframe>
                    @endif

                    <div class="absolute bottom-0 inset-x-0 h-16 bg-linear-to-t from-slate-950 via-slate-950/90 to-transparent z-20 flex flex-col justify-end px-5 pb-3">
                        <div class="flex justify-between items-end">
                            <div class="flex flex-col text-left">
                                <span class="text-xl font-black uppercase text-cyan-400 tracking-wider leading-none font-esports">
                                    {{ $caster1->display_name }}
                                </span>
                                <span class="text-[10px] text-slate-400 font-bold mt-1 leading-none font-orbitron">
                                    {{ $caster1->display_handle ?: '@caster1' }}
                                </span>
                            </div>
                            <div class="flex items-center gap-1.5 bg-slate-900/80 border border-cyan-400/30 px-3 py-1 rounded-md text-[9px] tracking-wider uppercase font-orbitron text-cyan-400 font-semibold shadow-inner">
                                <span class="relative flex h-1.5 w-1.5">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-cyan-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-cyan-500"></span>
                                </span>
                                {{ $caster1->vdoninja_link ? 'VDO_FEED' : 'STATIC_PIC' }}
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="w-full h-full bg-slate-950/70 flex flex-col items-center justify-center p-6 z-10 text-center">
                        <div class="w-12 h-12 border border-dashed border-slate-700 rounded-full flex items-center justify-center mb-3 pulse-light">
                            <span class="text-slate-500 font-orbitron text-xs">C1</span>
                        </div>
                        <span class="text-xs font-bold text-slate-500 tracking-[0.2em] uppercase font-orbitron">CASTER_SLOT_1</span>
                        <span class="text-[9px] text-cyan-400/35 font-bold uppercase tracking-widest mt-1 font-orbitron">AWAITING_CONNECTION</span>
                    </div>
                    @endif
                </div>

                {{-- CENTER: Virtual Desk Monitor Chassis (Exactly matches the Infinix block style) --}}
                <div class="flex flex-col items-center shrink-0 w-100">
                    {{-- Futuristic Bezel / Outer shell --}}
                    <div class="w-full h-62.75 bg-slate-900 border-2 border-slate-800 rounded-2xl p-4 shadow-2xl relative overflow-hidden neon-glow-cyan">
                        <div class="absolute inset-0 bg-linear-to-b from-slate-950 to-slate-900 opacity-40 z-0"></div>

                        {{-- Scanning overlays inside screen --}}
                        <div class="absolute inset-0 cyber-grid opacity-15 z-0"></div>

                        {{-- Central screen content --}}
                        <div class="w-full h-full flex flex-col justify-between items-center relative z-10 py-1 text-center select-none font-esports">
                            {{-- Infinix Brand Badge --}}
                            <div class="bg-slate-950 border border-slate-850 px-5 py-0.5 rounded-full text-[9px] font-black uppercase text-slate-400 tracking-[0.2em] font-orbitron mb-2 shadow-md">
                                {{ $tournament ? 'MAIDAN VIRTUAL STUDIO' : 'INFINIX BROADCAST' }}
                            </div>

                            {{-- Large Tournament Logo --}}
                            <img src="{{ $tournament && $tournament->logo_image ? asset('storage/' . $tournament->logo_image) : asset('img/defult_team_logo.png') }}" class="w-16 h-16 object-contain filter drop-shadow-[0_0_10px_rgba(0,240,255,0.4)] my-1 hover:scale-105 transition-transform" alt="Tournament Logo">

                            {{-- Tournament Name --}}
                            <div class="flex flex-col">
                                <span class="text-lg font-black uppercase tracking-wider text-slate-100 leading-none truncate max-w-85">
                                    {{ $tournament ? $tournament->name : 'PUBG CHAMPIONSHIP' }}
                                </span>
                                <span class="text-[10px] text-cyan-400 font-extrabold uppercase mt-1 tracking-widest font-orbitron leading-none">
                                    {{ $activeMatch ? $activeMatch->name : 'ANALYST DISCUSSION' }}
                                </span>
                            </div>

                            {{-- Map Badge / Location --}}
                            <div class="bg-cyan-500/10 border border-cyan-500/35 px-4 py-1.5 rounded-md mt-2 flex items-center justify-center gap-1.5 shadow-inner">
                                <span class="w-1.5 h-1.5 bg-cyan-400 rounded-full animate-ping"></span>
                                <span class="text-xs font-black uppercase text-cyan-400 tracking-wider font-orbitron leading-none">
                                    {{ $activeMatch ? $activeMatch->map : 'LIVE STREAM' }}
                                </span>
                            </div>
                        </div>

                        {{-- High-tech telemetry overlays inside bezel --}}
                        <div class="absolute bottom-2 left-3 text-[7px] font-mono text-slate-600">SYS_V_STUDIO_V1</div>
                        <div class="absolute bottom-2 right-3 text-[7px] font-mono text-slate-600">60FPS // 1080P</div>
                    </div>

                    {{-- Curved stand support under screen --}}
                    <div class="w-32 h-6 desk-base mt-0.5"></div>
                </div>

                {{-- Caster 2 (Right Screen) --}}
                <div class="relative w-155 h-[348.75px] bg-slate-950/80 border border-cyan-400/25 rounded-lg overflow-hidden neon-glow-cyan bracket-corner bracket-inner flex items-center justify-center">
                    @if ($caster2)
                    <div class="absolute inset-0 w-full h-full z-0 overflow-hidden">
                        @if ($caster2->image)
                        <img src="{{ asset('storage/' . $caster2->image) }}" class="w-full h-full object-cover" alt="{{ $caster2->display_name }}">
                        @else
                        <div class="w-full h-full bg-slate-900 flex flex-col items-center justify-center">
                            <svg class="w-16 h-16 text-slate-700 pulse-light mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <span class="text-[10px] text-slate-500 font-bold uppercase tracking-widest font-orbitron">NO_IMAGE</span>
                        </div>
                        @endif
                    </div>

                    @if ($caster2->vdoninja_link)
                    <iframe src="{{ formatVdoNinjaUrl($caster2->vdoninja_link) }}" class="absolute inset-0 w-full h-full border-0 z-10" allow="autoplay;camera;microphone;fullscreen;picture-in-picture;display-capture" allowtransparency="true">
                    </iframe>
                    @endif

                    <div class="absolute bottom-0 inset-x-0 h-16 bg-linear-to-t from-slate-950 via-slate-950/90 to-transparent z-20 flex flex-col justify-end px-5 pb-3">
                        <div class="flex justify-between items-end">
                            <div class="flex flex-col text-left">
                                <span class="text-xl font-black uppercase text-cyan-400 tracking-wider leading-none font-esports">
                                    {{ $caster2->display_name }}
                                </span>
                                <span class="text-[10px] text-slate-400 font-bold mt-1 leading-none font-orbitron">
                                    {{ $caster2->display_handle ?: '@caster2' }}
                                </span>
                            </div>
                            <div class="flex items-center gap-1.5 bg-slate-900/80 border border-cyan-400/30 px-3 py-1 rounded-md text-[9px] tracking-wider uppercase font-orbitron text-cyan-400 font-semibold shadow-inner">
                                <span class="relative flex h-1.5 w-1.5">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-cyan-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-cyan-500"></span>
                                </span>
                                {{ $caster2->vdoninja_link ? 'VDO_FEED' : 'STATIC_PIC' }}
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="w-full h-full bg-slate-950/70 flex flex-col items-center justify-center p-6 z-10 text-center">
                        <div class="w-12 h-12 border border-dashed border-slate-700 rounded-full flex items-center justify-center mb-3 pulse-light">
                            <span class="text-slate-500 font-orbitron text-xs">C2</span>
                        </div>
                        <span class="text-xs font-bold text-slate-500 tracking-[0.2em] uppercase font-orbitron">CASTER_SLOT_2</span>
                        <span class="text-[9px] text-cyan-400/35 font-bold uppercase tracking-widest mt-1 font-orbitron">AWAITING_CONNECTION</span>
                    </div>
                    @endif
                </div>

            </div>

            {{-- CASE 3: 3 CASTERS (Three 16:9 side-by-side cards) --}}
            @elseif($casters->count() == 3)
            <div class="grid grid-cols-3 gap-6 w-full max-w-430">
                @foreach($casters as $caster)
                <div class="relative w-full h-[292.5px] bg-slate-950/80 border border-cyan-400/25 rounded-lg overflow-hidden neon-glow-cyan bracket-corner bracket-inner flex items-center justify-center">
                    <div class="absolute inset-0 w-full h-full z-0 overflow-hidden">
                        @if ($caster->image)
                        <img src="{{ asset('storage/' . $caster->image) }}" class="w-full h-full object-cover" alt="{{ $caster->display_name }}">
                        @else
                        <div class="w-full h-full bg-slate-900 flex flex-col items-center justify-center">
                            <svg class="w-14 h-14 text-slate-750 pulse-light mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <span class="text-[9px] text-slate-500 font-bold uppercase tracking-widest font-orbitron">NO_IMAGE</span>
                        </div>
                        @endif
                    </div>

                    @if ($caster->vdoninja_link)
                    <iframe src="{{ formatVdoNinjaUrl($caster->vdoninja_link) }}" class="absolute inset-0 w-full h-full border-0 z-10" allow="autoplay;camera;microphone;fullscreen;picture-in-picture;display-capture" allowtransparency="true">
                    </iframe>
                    @endif

                    <div class="absolute bottom-0 inset-x-0 h-14 bg-linear-to-t from-slate-950 via-slate-950/90 to-transparent z-20 flex flex-col justify-end px-5 pb-3">
                        <div class="flex justify-between items-end">
                            <div class="flex flex-col text-left">
                                <span class="text-lg font-black uppercase text-cyan-400 tracking-wider leading-none font-esports">
                                    {{ $caster->display_name }}
                                </span>
                                <span class="text-[9px] text-slate-400 font-bold mt-1 leading-none font-orbitron">
                                    {{ $caster->display_handle ?: '@caster' }}
                                </span>
                            </div>
                            <div class="flex items-center gap-1 bg-slate-900/80 border border-cyan-400/20 px-2.5 py-0.5 rounded text-[8px] tracking-wider uppercase font-orbitron text-cyan-400 font-semibold shadow-inner">
                                {{ $caster->vdoninja_link ? 'VDO' : 'STATIC' }}
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- CASE 4: 4 OR MORE CASTERS (2x2 Grid) --}}
            @else
            <div class="grid grid-cols-2 gap-6 w-full max-w-310">
                @foreach($casters->take(4) as $caster)
                <div class="relative w-full h-[281.25px] bg-slate-950/80 border border-cyan-400/25 rounded-lg overflow-hidden neon-glow-cyan bracket-corner bracket-inner flex items-center justify-center">
                    <div class="absolute inset-0 w-full h-full z-0 overflow-hidden">
                        @if ($caster->image)
                        <img src="{{ asset('storage/' . $caster->image) }}" class="w-full h-full object-cover" alt="{{ $caster->display_name }}">
                        @else
                        <div class="w-full h-full bg-slate-900 flex flex-col items-center justify-center">
                            <svg class="w-14 h-14 text-slate-750 pulse-light mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <span class="text-[9px] text-slate-500 font-bold uppercase tracking-widest font-orbitron">NO_IMAGE</span>
                        </div>
                        @endif
                    </div>

                    @if ($caster->vdoninja_link)
                    <iframe src="{{ formatVdoNinjaUrl($caster->vdoninja_link) }}" class="absolute inset-0 w-full h-full border-0 z-10" allow="autoplay;camera;microphone;fullscreen;picture-in-picture;display-capture" allowtransparency="true">
                    </iframe>
                    @endif

                    <div class="absolute bottom-0 inset-x-0 h-14 bg-linear-to-t from-slate-950 via-slate-950/90 to-transparent z-20 flex flex-col justify-end px-5 pb-3">
                        <div class="flex justify-between items-end">
                            <div class="flex flex-col text-left">
                                <span class="text-lg font-black uppercase text-cyan-400 tracking-wider leading-none font-esports">
                                    {{ $caster->display_name }}
                                </span>
                                <span class="text-[9px] text-slate-400 font-bold mt-1 leading-none font-orbitron">
                                    {{ $caster->display_handle ?: '@caster' }}
                                </span>
                            </div>
                            <div class="flex items-center gap-1 bg-slate-900/80 border border-cyan-400/20 px-2.5 py-0.5 rounded text-[8px] tracking-wider uppercase font-orbitron text-cyan-400 font-semibold shadow-inner">
                                {{ $caster->vdoninja_link ? 'VDO' : 'STATIC' }}
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @endif

        </div>

        {{-- BOTTOM: Cyber Technical Footer --}}
        <div class="w-full max-w-430 flex justify-between items-center z-20 border-t border-slate-800/40 pt-4 telemetry-text font-orbitron">
            <span>INDEX // ANALYST_DESK_ROOM_01</span>
            <span class="pulse-light font-bold">PT_PUBG_DESK_OVERLAY_v2.0.0</span>
            <span>SECURE_STAGE_LINK</span>
        </div>

    </div>

    {{-- WebSocket listeners & state updates --}}
    <script type="module">
        document.addEventListener("DOMContentLoaded", function () {
            // Apply background switcher
            function applyBackground(bgType, customVideoUrl) {
                const wrapper = document.getElementById('screen-wrapper');
                const video = document.getElementById('bg-video');
                const videoOverlay = document.getElementById('bg-video-overlay');
                const grid = document.getElementById('bg-grid');
                const sweep = document.getElementById('bg-sweep');

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
                    wrapper.classList.add('bg-transparent');
                    if (grid) grid.classList.add('hidden');
                    if (sweep) sweep.classList.add('hidden');
                    if (video) video.classList.add('hidden');
                    if (videoOverlay) videoOverlay.classList.add('hidden');
                }
            }

            // Fluctuate telemetry values dynamically for high realism
            const bitrateEl = document.getElementById('tel-bitrate');
            const latencyEl = document.getElementById('tel-latency');

            setInterval(() => {
                if (bitrateEl) {
                    const bitrate = 6000 + Math.floor(Math.random() * 800);
                    bitrateEl.innerText = `${bitrate} kbps`;
                }
                if (latencyEl) {
                    const latency = 9 + Math.floor(Math.random() * 7);
                    latencyEl.innerText = `${latency}ms`;
                }
            }, 3000);

            // Echo channels
            Echo.channel('user-screens.{{ $user->id }}')
                .listen('.BackgroundChanged', (e) => {
                    console.log('BackgroundChanged event received:', e);
                    applyBackground(e.bgType, e.customVideoUrl);
                })
                .listen('.ObsViewSwitched', (e) => {
                    if (e.viewName === 'refresh') {
                        window.location.reload();
                    }
                })
                .listen('.RefreshScreens', (e) => {
                    window.location.reload();
                });
        });
    </script>
</x-base>
