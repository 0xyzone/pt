<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BroadKaster — Live Tournament Broadcasting System</title>
    <meta name="description" content="BroadKaster is a professional real-time tournament broadcasting platform with OBS-ready overlays, live stats, and dynamic rankings for PUBG Mobile esports events.">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="BroadKaster — Live Tournament Broadcasting System">
    <meta property="og:description" content="BroadKaster is a professional real-time tournament broadcasting platform with OBS-ready overlays, live stats, and dynamic rankings for PUBG Mobile esports events.">
    <meta property="og:image" content="{{ asset('img/symbol-preview.jpg') }}">
    <meta property="og:image:secure_url" content="{{ asset('img/symbol-preview.jpg') }}">

    <!-- Twitter -->
    <meta name="twitter:card" content="summary">
    <meta name="twitter:url" content="{{ url()->current() }}">
    <meta name="twitter:title" content="BroadKaster — Live Tournament Broadcasting System">
    <meta name="twitter:description" content="BroadKaster is a professional real-time tournament broadcasting platform with OBS-ready overlays, live stats, and dynamic rankings for PUBG Mobile esports events.">
    <meta name="twitter:image" content="{{ asset('img/symbol-preview.jpg') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700;900&display=swap" rel="stylesheet">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('img/symbol.png') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-['Inter'] bg-[#0a0b0e] text-slate-200 min-h-screen overflow-x-hidden antialiased">
    <!-- Cinematic Preloader -->
    <div id="preloader" class="fixed inset-0 z-99999 pointer-events-none flex flex-col">
        <div id="preloader-top" class="flex-1 bg-[#050508] transition-transform duration-1000 ease-[cubic-bezier(0.85,0,0.15,1)] border-b border-orange-500/20"></div>
        <div id="preloader-bottom" class="flex-1 bg-[#050508] transition-transform duration-1000 ease-[cubic-bezier(0.85,0,0.15,1)] border-t border-orange-500/20"></div>
        
        <!-- The glowing line in the center -->
        <div id="preloader-line" class="absolute top-1/2 left-1/2 -translate-y-1/2 -translate-x-1/2 h-0.5 bg-orange-500 shadow-[0_0_20px_rgba(249,115,22,1)] w-0 transition-all duration-700 ease-in-out z-10"></div>
        
        <!-- Tech text -->
        <div id="preloader-text" class="absolute top-[48%] left-1/2 -translate-x-1/2 -translate-y-1/2 text-orange-500 font-mono text-[10px] md:text-[12px] uppercase tracking-[0.3em] md:tracking-[0.5em] opacity-0 transition-opacity duration-300 z-20 whitespace-nowrap">
            System Initializing...
        </div>
    </div>

    <!-- Atmospheric Orbs -->
    <div class="orb w-175 h-175 top-[-15%] right-[-10%] bg-[radial-gradient(circle,rgba(249,115,22,0.18),transparent_70%)] [animation-duration:22s]" aria-hidden="true"></div>
    <div class="orb w-150 h-150 bottom-[-20%] left-[-12%] bg-[radial-gradient(circle,rgba(245,158,11,0.12),transparent_70%)] [animation-duration:28s] [animation-delay:-8s]" aria-hidden="true"></div>
    <div class="orb w-100 h-100 top-[40%] left-[35%] bg-[radial-gradient(circle,rgba(234,88,12,0.08),transparent_70%)] [animation-duration:35s] [animation-delay:-15s]" aria-hidden="true"></div>

    <!-- ─── NAVBAR ─── -->
    <nav class="fixed top-0 left-0 right-0 z-50 bg-[#0a0b0e]/80 backdrop-blur-md border-b border-white/5 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 md:px-8 h-18 flex justify-between items-center">
            <a href="/" class="flex items-center gap-3 decoration-0">
                <img src="{{ asset('img/logo.png') }}" alt="BroadKaster" class="h-7 drop-shadow-[0_0_12px_rgba(249,115,22,0.4)]">
            </a>
            
            <div class="hidden md:flex items-center gap-2">
                <a href="#contact" class="px-5 py-2.5 text-[13px] font-semibold tracking-wider uppercase text-slate-400 hover:text-white transition-colors duration-200 rounded-lg">Contact</a>
                <a href="{{ route('pricing') }}" class="px-5 py-2.5 text-[13px] font-semibold tracking-wider uppercase text-slate-400 hover:text-white transition-colors duration-200 rounded-lg">Pricing</a>
                @auth
                    <a href="{{ url('/maidan') }}" class="px-5 py-2.5 text-[13px] font-semibold tracking-wider uppercase text-slate-400 hover:text-white transition-colors duration-200 rounded-lg">Dashboard</a>
                @else
                    <a href="#request-demo" class="px-5 py-2.5 text-[13px] font-semibold tracking-wider uppercase text-slate-400 hover:text-white transition-colors duration-200 rounded-lg">Request a Demo</a>
                    <a href="{{ url('/maidan/login') }}" class="ml-2 px-6 py-2.5 text-[13px] font-bold tracking-wider uppercase text-white bg-linear-to-br from-orange-500 to-orange-600 rounded-lg shadow-[0_0_0_1px_rgba(249,115,22,0.4),0_6px_20px_rgba(249,115,22,0.3)] hover:shadow-[0_0_0_1px_rgba(249,115,22,0.6),0_8px_25px_rgba(249,115,22,0.45)] transition-all duration-250">Sign In</a>
                @endauth
            </div>
            
            <button class="md:hidden flex items-center justify-center w-10 h-10 text-slate-300 bg-white/5 border border-white/10 rounded-lg cursor-pointer transition-colors duration-200 hover:bg-white/10 hover:text-white" id="mobile-menu-btn">
                <svg width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>
        
        <!-- Mobile Menu -->
        <div class="hidden flex-col gap-2 p-4 bg-[#0f1117] border-b border-white/5" id="mobile-menu">
            <a href="#contact" class="block px-4 py-3 text-[14px] font-semibold tracking-wide uppercase text-slate-300 bg-white/5 rounded-lg text-center decoration-0 hover:bg-white/10">Contact</a>
            <a href="{{ route('pricing') }}" class="block px-4 py-3 text-[14px] font-semibold tracking-wide uppercase text-slate-300 bg-white/5 rounded-lg text-center decoration-0 hover:bg-white/10">Pricing</a>
            @auth
                <a href="{{ url('/maidan') }}" class="block px-4 py-3 text-[14px] font-semibold tracking-wide uppercase text-slate-300 bg-white/5 rounded-lg text-center decoration-0 hover:bg-white/10">Dashboard</a>
            @else
                <a href="#request-demo" class="block px-4 py-3 text-[14px] font-semibold tracking-wide uppercase text-slate-300 bg-white/5 rounded-lg text-center decoration-0 hover:bg-white/10">Request a Demo</a>
                <a href="{{ url('/maidan/login') }}" class="block px-4 py-3 text-[14px] font-bold tracking-wide uppercase text-white bg-linear-to-br from-orange-500 to-orange-600 rounded-lg text-center decoration-0 shadow-[0_4px_14px_rgba(249,115,22,0.3)]">Sign In</a>
            @endauth
        </div>
    </nav>

    <!-- ─── HERO ─── -->
    <section class="relative pt-32 pb-24 md:pt-45 md:pb-35 px-4 text-center flex flex-col items-center justify-center min-h-[90vh]">
        <div class="inline-flex items-center gap-2 px-4 py-2 mb-8 bg-orange-500/10 border border-orange-500/30 rounded-full text-[11px] font-bold uppercase tracking-widest text-orange-400 backdrop-blur-sm shadow-[0_0_20px_rgba(249,115,22,0.15)] animate-fade-in-up">
            <span class="w-2 h-2 rounded-full bg-orange-500 animate-blink shadow-[0_0_8px_rgba(249,115,22,0.8)]"></span>
            Professional PUBG Mobile Tournament Broadcast System
        </div>

        <div class="mb-10 animate-fade-in-up delay-200">
            <img class="h-17.5 md:h-27.5 animate-breathe object-scale-down" src="{{ asset('img/logo.png') }}" alt="BroadKaster">
        </div>

        <p class="max-w-175 mx-auto text-lg md:text-[22px] font-light leading-relaxed text-slate-300 mb-12 animate-fade-in-up delay-300">
            Command every match moment. Real-time OBS overlays, live rankings, and seamless
            director controls — all in one powerful broadcasting engine.
        </p>

        <div class="flex flex-col md:flex-row gap-4 mb-16 w-full md:w-auto px-4 reveal-on-scroll delay-400">
            <a href="{{ url('/maidan') }}" class="flex items-center justify-center gap-2 px-8 py-4 text-[14px] md:text-[15px] font-bold uppercase tracking-widest text-white bg-linear-to-br from-orange-500 to-orange-600 rounded-xl shadow-[0_0_0_1px_rgba(249,115,22,0.5),0_10px_30px_rgba(249,115,22,0.4)] hover:shadow-[0_0_0_1px_rgba(249,115,22,0.7),0_12px_40px_rgba(249,115,22,0.55)] hover:-translate-y-0.5 transition-all duration-300">
                <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5.25 5.653c0-.856.917-1.398 1.667-.986l11.54 6.347a1.125 1.125 0 0 1 0 1.972l-11.54 6.347a1.125 1.125 0 0 1-1.667-.986V5.653Z" />
                </svg>
                Launch App Panel
            </a>
            <a href="{{ url('/admin') }}" class="flex items-center justify-center gap-2 px-8 py-4 text-[14px] md:text-[15px] font-bold uppercase tracking-widest text-slate-200 bg-[#161923]/80 backdrop-blur-md border border-white/10 rounded-xl shadow-[0_10px_30px_rgba(0,0,0,0.3)] hover:bg-[#1e2535] hover:text-white hover:border-white/20 hover:-translate-y-0.5 transition-all duration-300">
                <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" />
                </svg>
                Mukhiyas Panel
            </a>
        </div>

        <!-- Stats bar -->
        <div class="flex flex-wrap justify-center gap-3 md:gap-4 max-w-200 w-full px-4 reveal-on-scroll delay-500">
            <div class="flex-1 min-w-35 px-4 py-3 bg-[#0f1117]/60 backdrop-blur-md border border-white/5 rounded-xl flex flex-col justify-center transition-transform hover:-translate-y-1">
                <span class="block font-['Rajdhani'] text-[24px] md:text-[32px] font-bold text-white mb-0.5">16+</span>
                <span class="block text-[10px] md:text-[11px] font-bold uppercase tracking-widest text-slate-400">OBS Overlays</span>
            </div>
            <div class="flex-1 min-w-35 px-4 py-3 bg-[#0f1117]/60 backdrop-blur-md border border-white/5 rounded-xl flex flex-col justify-center transition-transform hover:-translate-y-1">
                <span class="block font-['Rajdhani'] text-[24px] md:text-[32px] font-bold text-white mb-0.5">Real-Time</span>
                <span class="block text-[10px] md:text-[11px] font-bold uppercase tracking-widest text-orange-400 drop-shadow-[0_0_8px_rgba(249,115,22,0.4)]">WebSocket Sync</span>
            </div>
            <div class="flex-1 min-w-35 px-4 py-3 bg-[#0f1117]/60 backdrop-blur-md border border-white/5 rounded-xl flex flex-col justify-center transition-transform hover:-translate-y-1">
                <span class="block font-['Rajdhani'] text-[24px] md:text-[32px] font-bold text-white mb-0.5">1080p</span>
                <span class="block text-[10px] md:text-[11px] font-bold uppercase tracking-widest text-slate-400">Overlay Quality</span>
            </div>
            <div class="flex-1 min-w-35 px-4 py-3 bg-[#0f1117]/60 backdrop-blur-md border border-white/5 rounded-xl flex flex-col justify-center transition-transform hover:-translate-y-1">
                <span class="block font-['Rajdhani'] text-[24px] md:text-[32px] font-bold text-white mb-0.5">v{{ $systemVersion ?? '1.1.117' }}</span>
                <span class="block text-[10px] md:text-[11px] font-bold uppercase tracking-widest text-slate-400">Build</span>
            </div>
        </div>
    </section>

    <!-- ─── FEATURES GRID ─── -->
    <section class="py-24 px-4 relative z-10">
        <div class="text-center mb-16 reveal-on-scroll delay-200">
            <p class="text-[12px] font-bold uppercase tracking-[0.2em] text-orange-500 mb-3 drop-shadow-[0_0_10px_rgba(249,115,22,0.4)]">Core Capabilities</p>
            <h2 class="font-['Rajdhani'] text-[36px] md:text-[46px] font-bold text-white mb-4">Everything your broadcast needs</h2>
            <p class="text-slate-400 text-[15px] max-w-150 mx-auto">From the first whistle to the final kill, BroadKaster keeps your stream production-ready at all times.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 max-w-300 mx-auto reveal-on-scroll delay-400">
            <!-- Card 1 -->
            <div class="group p-8 rounded-2xl bg-[#0f1117]/80 backdrop-blur-md border border-orange-500/20 shadow-[0_15px_35px_rgba(0,0,0,0.5),inset_0_1px_0_rgba(255,255,255,0.05)] transition-all duration-400 hover:-translate-y-2 hover:bg-[#161923] hover:border-orange-500/40 relative overflow-hidden">
                <div class="absolute -top-1/2 -right-1/2 w-full h-full bg-[radial-gradient(circle,rgba(249,115,22,0.1)_0%,transparent_70%)] opacity-0 group-hover:opacity-100 transition-opacity duration-700"></div>
                <div class="w-14 h-14 rounded-xl bg-orange-500/10 border border-orange-500/20 flex items-center justify-center text-orange-500 mb-6 group-hover:bg-orange-500 group-hover:text-white group-hover:shadow-[0_0_20px_rgba(249,115,22,0.4)] transition-all duration-300">
                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />
                    </svg>
                </div>
                <div class="font-['Rajdhani'] text-[24px] font-bold text-white mb-3">Real-Time Sync Engine</div>
                <p class="text-[14px] text-slate-400 leading-relaxed mb-6">WebSocket-powered live data bridge instantly pushes match stat changes, score updates, and phase transitions to all connected OBS sources in under 100ms.</p>
                <span class="inline-block px-3 py-1 bg-orange-500/10 border border-orange-500/20 rounded-md text-[11px] font-bold uppercase tracking-wider text-orange-400">⚡ WebSockets</span>
            </div>

            <!-- Card 2 -->
            <div class="group p-8 rounded-2xl bg-[#0f1117]/80 backdrop-blur-md border border-white/5 shadow-[0_15px_35px_rgba(0,0,0,0.5),inset_0_1px_0_rgba(255,255,255,0.05)] transition-all duration-400 hover:-translate-y-2 hover:bg-[#161923] hover:border-white/10 relative overflow-hidden">
                <div class="w-14 h-14 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center text-slate-300 mb-6 group-hover:bg-white/10 group-hover:text-white transition-all duration-300">
                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5l4.72-4.72a.75.75 0 011.28.53v11.38a.75.75 0 01-1.28.53l-4.72-4.72M4.5 18.75h9a2.25 2.25 0 002.25-2.25v-9a2.25 2.25 0 00-2.25-2.25h-9A2.25 2.25 0 002.25 7.5v9a2.25 2.25 0 002.25 2.25z" />
                    </svg>
                </div>
                <div class="font-['Rajdhani'] text-[24px] font-bold text-white mb-3">OBS Director Console</div>
                <p class="text-[14px] text-slate-400 leading-relaxed mb-6">Centralised control panel to switch between all 16+ live overlay screens with a single click. Pre-match and post-match zones keep the director workflow crystal clear.</p>
                <span class="inline-block px-3 py-1 bg-white/5 border border-white/10 rounded-md text-[11px] font-bold uppercase tracking-wider text-slate-300">🎬 OBS Ready</span>
            </div>

            <!-- Card 3 -->
            <div class="group p-8 rounded-2xl bg-[#0f1117]/80 backdrop-blur-md border border-white/5 shadow-[0_15px_35px_rgba(0,0,0,0.5),inset_0_1px_0_rgba(255,255,255,0.05)] transition-all duration-400 hover:-translate-y-2 hover:bg-[#161923] hover:border-white/10 relative overflow-hidden">
                <div class="w-14 h-14 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center text-slate-300 mb-6 group-hover:bg-white/10 group-hover:text-white transition-all duration-300">
                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                    </svg>
                </div>
                <div class="font-['Rajdhani'] text-[24px] font-bold text-white mb-3">Smart Rankings</div>
                <p class="text-[14px] text-slate-400 leading-relaxed mb-6">Automated kill-point and placement scoring aggregates overall tournament leaderboards in real-time. Head-to-head comparisons and Top Fraggers overlays included.</p>
                <span class="inline-block px-3 py-1 bg-white/5 border border-white/10 rounded-md text-[11px] font-bold uppercase tracking-wider text-slate-300">📊 Auto-Calculated</span>
            </div>

            <!-- Card 4 -->
            <div class="group p-8 rounded-2xl bg-[#0f1117]/80 backdrop-blur-md border border-white/5 shadow-[0_15px_35px_rgba(0,0,0,0.5),inset_0_1px_0_rgba(255,255,255,0.05)] transition-all duration-400 hover:-translate-y-2 hover:bg-[#161923] hover:border-white/10 relative overflow-hidden">
                <div class="w-14 h-14 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center text-slate-300 mb-6 group-hover:bg-white/10 group-hover:text-white transition-all duration-300">
                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 6.75V15m6-6v8.25m.503 3.498l4.875-2.437c.381-.19.622-.58.622-1.006V4.82c0-.836-.88-1.38-1.628-1.006l-3.869 1.934c-.317.159-.69.159-1.006 0L9.503 3.252a1.125 1.125 0 00-1.006 0L3.622 5.689C3.24 5.88 3 6.27 3 6.695V19.18c0 .836.88 1.38 1.628 1.006l3.869-1.934c.317-.159.69-.159 1.006 0l4.994 2.497c.317.158.69.158 1.006 0z" />
                    </svg>
                </div>
                <div class="font-['Rajdhani'] text-[24px] font-bold text-white mb-3">Tournament Roadmap</div>
                <p class="text-[14px] text-slate-400 leading-relaxed mb-6">Visual phase-by-phase tournament progression overlay with cinematic staggered entry animations. Directors update stages in Filament — the overlay refreshes automatically.</p>
                <span class="inline-block px-3 py-1 bg-white/5 border border-white/10 rounded-md text-[11px] font-bold uppercase tracking-wider text-slate-300">🗺️ Live Updates</span>
            </div>

            <!-- Card 5 -->
            <div class="group p-8 rounded-2xl bg-[#0f1117]/80 backdrop-blur-md border border-orange-500/20 shadow-[0_15px_35px_rgba(0,0,0,0.5),inset_0_1px_0_rgba(255,255,255,0.05)] transition-all duration-400 hover:-translate-y-2 hover:bg-[#161923] hover:border-orange-500/40 relative overflow-hidden">
                <div class="absolute -top-1/2 -right-1/2 w-full h-full bg-[radial-gradient(circle,rgba(249,115,22,0.1)_0%,transparent_70%)] opacity-0 group-hover:opacity-100 transition-opacity duration-700"></div>
                <div class="w-14 h-14 rounded-xl bg-orange-500/10 border border-orange-500/20 flex items-center justify-center text-orange-500 mb-6 group-hover:bg-orange-500 group-hover:text-white group-hover:shadow-[0_0_20px_rgba(249,115,22,0.4)] transition-all duration-300">
                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                    </svg>
                </div>
                <div class="font-['Rajdhani'] text-[24px] font-bold text-white mb-3">Cinematic Overlays</div>
                <p class="text-[14px] text-slate-400 leading-relaxed mb-6">16+ professionally animated, 1920×1080 browser-source overlays — Map Pool, Point System, Post Match, Overall Ranking, Head to Head, Top Fraggers, and more.</p>
                <span class="inline-block px-3 py-1 bg-orange-500/10 border border-orange-500/20 rounded-md text-[11px] font-bold uppercase tracking-wider text-orange-400">🎨 Cinematic Quality</span>
            </div>

            <!-- Card 6 -->
            <div class="group p-8 rounded-2xl bg-[#0f1117]/80 backdrop-blur-md border border-white/5 shadow-[0_15px_35px_rgba(0,0,0,0.5),inset_0_1px_0_rgba(255,255,255,0.05)] transition-all duration-400 hover:-translate-y-2 hover:bg-[#161923] hover:border-white/10 relative overflow-hidden">
                <div class="w-14 h-14 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center text-slate-300 mb-6 group-hover:bg-white/10 group-hover:text-white transition-all duration-300">
                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                    </svg>
                </div>
                <div class="font-['Rajdhani'] text-[24px] font-bold text-white mb-3">Team & Player Management</div>
                <p class="text-[14px] text-slate-400 leading-relaxed mb-6">Full Filament-powered admin panel to manage teams, slots, match rosters, casters, and tournament schedules — with a dedicated operator panel for tournament organizers.</p>
                <span class="inline-block px-3 py-1 bg-white/5 border border-white/10 rounded-md text-[11px] font-bold uppercase tracking-wider text-slate-300">👥 Full Control</span>
            </div>
        </div>
    </section>

    <!-- ─── HOW IT WORKS ─── -->
    <section class="py-24 px-4 bg-[#08090b] border-y border-white/5 relative z-10">
        <div class="max-w-300 mx-auto grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            <div class="reveal-on-scroll delay-200">
                <p class="text-[12px] font-bold uppercase tracking-[0.2em] text-orange-500 mb-3 drop-shadow-[0_0_10px_rgba(249,115,22,0.4)]">Workflow</p>
                <h2 class="font-['Rajdhani'] text-[36px] md:text-[46px] font-bold text-white mb-4 leading-tight">Set up in minutes,<br>broadcast all day</h2>
                <p class="text-slate-400 text-[15px] mb-10">The entire pipeline from match setup to live broadcast is designed to be intuitive and lightning-fast.</p>

                <div class="space-y-6">
                    <div class="flex items-start gap-5">
                        <div class="shrink-0 w-9 h-9 rounded-full bg-[#1e2535] border border-white/10 flex items-center justify-center font-['Rajdhani'] text-[18px] font-bold text-white shadow-[0_4px_10px_rgba(0,0,0,0.3)]">1</div>
                        <div>
                            <div class="font-['Rajdhani'] text-[20px] font-bold text-white mb-1">Configure your tournament</div>
                            <p class="text-[14px] text-slate-400 leading-relaxed">Set up teams, maps, point rules, and the roadmap phases in the Maidan operator panel.</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-5">
                        <div class="shrink-0 w-9 h-9 rounded-full bg-[#1e2535] border border-white/10 flex items-center justify-center font-['Rajdhani'] text-[18px] font-bold text-white shadow-[0_4px_10px_rgba(0,0,0,0.3)]">2</div>
                        <div>
                            <div class="font-['Rajdhani'] text-[20px] font-bold text-white mb-1">Add overlay URLs to OBS</div>
                            <p class="text-[14px] text-slate-400 leading-relaxed">Copy the unique browser-source URLs for each overlay from the OBS Hub widget and drop them into OBS Studio as Browser Sources.</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-5">
                        <div class="shrink-0 w-9 h-9 rounded-full bg-[#1e2535] border border-white/10 flex items-center justify-center font-['Rajdhani'] text-[18px] font-bold text-white shadow-[0_4px_10px_rgba(0,0,0,0.3)]">3</div>
                        <div>
                            <div class="font-['Rajdhani'] text-[20px] font-bold text-white mb-1">Director controls the show</div>
                            <p class="text-[14px] text-slate-400 leading-relaxed">Use the OBS Director Console to switch active overlays in real-time. Stats, kills, and placements update live on air.</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-5">
                        <div class="shrink-0 w-9 h-9 rounded-full bg-[#1e2535] border border-white/10 flex items-center justify-center font-['Rajdhani'] text-[18px] font-bold text-white shadow-[0_4px_10px_rgba(0,0,0,0.3)]">4</div>
                        <div>
                            <div class="font-['Rajdhani'] text-[20px] font-bold text-white mb-1">Post-match in one click</div>
                            <p class="text-[14px] text-slate-400 leading-relaxed">Mark the match complete — the system auto-calculates rankings, fraggers, and leaderboards instantly.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dashboard Mockup -->
            <div class="bg-[#161923] border border-white/10 rounded-2xl shadow-[0_20px_50px_rgba(0,0,0,0.6)] overflow-hidden transform lg:-rotate-2 transition-transform duration-500 hover:rotate-0 reveal-on-scroll delay-400">
                <div class="flex items-center px-4 py-3 bg-[#0a0b0e] border-b border-white/5">
                    <div class="flex gap-2 mr-4">
                        <div class="w-3 h-3 rounded-full bg-[#ff5f57]"></div>
                        <div class="w-3 h-3 rounded-full bg-[#febc2e]"></div>
                        <div class="w-3 h-3 rounded-full bg-[#28c840]"></div>
                    </div>
                    <span class="flex-1 text-center text-[11px] font-bold text-slate-500 tracking-wider uppercase">OBS Director Console — Match 3 of 6</span>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-3 gap-4 mb-6">
                        <div class="bg-[#1e2535] border border-white/5 rounded-xl p-4 text-center">
                            <div class="text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-1">Active Match</div>
                            <div class="font-['Rajdhani'] text-[20px] font-bold text-white">Match 3</div>
                        </div>
                        <div class="bg-[#1e2535] border border-white/5 rounded-xl p-4 text-center">
                            <div class="text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-1">Live Teams</div>
                            <div class="font-['Rajdhani'] text-[20px] font-bold text-white">12</div>
                        </div>
                        <div class="bg-[#1e2535] border border-white/5 rounded-xl p-4 text-center">
                            <div class="text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-1">Total Kills</div>
                            <div class="font-['Rajdhani'] text-[20px] font-bold text-white">47</div>
                        </div>
                    </div>
                    <div class="text-[10px] font-bold uppercase tracking-[0.12em] text-slate-500 mb-2 mt-4">Overall Standings</div>
                    @php
                    $teams = [
                    ['rank' => 1, 'name' => 'Skyline Wolves', 'pts' => 148, 'pct' => 100],
                    ['rank' => 2, 'name' => 'Iron Ghosts', 'pts' => 132, 'pct' => 89],
                    ['rank' => 3, 'name' => 'Storm Riders', 'pts' => 117, 'pct' => 79],
                    ['rank' => 4, 'name' => 'Neon Vipers', 'pts' => 103, 'pct' => 70],
                    ['rank' => 5, 'name' => 'Apex Hunters', 'pts' => 89, 'pct' => 60],
                    ];
                    @endphp
                    @foreach ($teams as $t)
                    <div class="flex items-center py-2.5 border-b border-white/5 last:border-0">
                        <div class="w-8 font-['Rajdhani'] text-[15px] font-bold text-slate-400">#{{ $t['rank'] }}</div>
                        <div class="flex-1 font-semibold text-[13px] {{ $t['rank'] == 1 ? 'text-orange-500' : 'text-slate-200' }}">{{ $t['name'] }}</div>
                        <div class="w-16 text-right font-['Rajdhani'] text-[15px] font-bold text-white">{{ $t['pts'] }} pts</div>
                        <div class="w-17.5 ml-4">
                            <div class="h-1.5 w-full bg-[#0a0b0e] rounded-full overflow-hidden">
                                <div class="h-full rounded-full {{ $t['rank'] > 2 ? 'bg-linear-to-r from-slate-600 to-slate-500' : 'bg-linear-to-r from-orange-500 to-orange-400' }}" style="width:{{ $t['pct'] }}%;"></div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <!-- ─── REQUEST A DEMO ─── -->
    <section class="py-24 px-4 bg-[#0a0b0e] relative z-10" id="request-demo">
        <div class="max-w-275 mx-auto text-center mb-12 reveal-on-scroll delay-200">
            <p class="text-[12px] font-bold uppercase tracking-[0.2em] text-orange-500 mb-3 drop-shadow-[0_0_10px_rgba(249,115,22,0.4)]">Get Started</p>
            <h2 class="font-['Rajdhani'] text-[36px] md:text-[46px] font-bold text-white mb-4">Request a Demo</h2>
            <p class="text-slate-400 text-[15px] max-w-150 mx-auto">Fill in the form and we'll reach out to set you up with a BroadKaster account tailored to your tournament needs.</p>
        </div>

        <div class="max-w-275 mx-auto bg-[#0f1117] border border-white/5 rounded-2xl shadow-[0_20px_50px_rgba(0,0,0,0.5)] overflow-hidden grid grid-cols-1 lg:grid-cols-2 reveal-on-scroll delay-400">
            <!-- Left: Value props -->
            <div class="p-10 lg:p-12 border-b lg:border-b-0 lg:border-r border-white/5 bg-[#12151d]">
                <img src="{{ asset('img/symbol.png') }}" alt="BroadKaster" class="w-16 h-16 object-contain mb-6 drop-shadow-[0_0_20px_rgba(249,115,22,0.3)]">
                <h3 class="font-['Rajdhani'] text-[28px] font-bold text-white mb-3">What you get with BroadKaster</h3>
                <p class="text-[14px] text-slate-400 leading-relaxed mb-8">
                    No self-registration. We personally onboard every client to ensure a smooth production experience from day one.
                </p>
                <ul class="space-y-4">
                    @php
                        $perks = [
                            'Real-time OBS overlay system — 16+ animated screens',
                            'WebSocket-powered live stat updates in under 100ms',
                            'Director console to switch screens without touching OBS',
                            'Automated kill-point & placement ranking engine',
                            'Tournament Roadmap, Head-to-Head, Top Fraggers overlays',
                            'Full team & player management with logo support',
                            'Dedicated operator panel for tournament organizers',
                        ];
                    @endphp
                    @foreach ($perks as $perk)
                    <li class="flex items-start gap-3 text-[14px] text-slate-300 font-medium leading-snug">
                        <span class="shrink-0 w-4.5 h-4.5 rounded-full bg-green-500/10 flex items-center justify-center text-green-500 mt-0.5">
                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                            </svg>
                        </span>
                        {{ $perk }}
                    </li>
                    @endforeach
                </ul>
            </div>

            <!-- Right: Form -->
            <div class="p-10 lg:p-12 flex flex-col justify-center">
                @if (session('demo_success'))
                    <div class="bg-green-500/10 border border-green-500/20 rounded-xl p-8 text-center">
                        <div class="w-12 h-12 rounded-full bg-green-500/20 flex items-center justify-center mx-auto mb-4">
                            <svg class="w-6 h-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                            </svg>
                        </div>
                        <div class="font-['Rajdhani'] text-[22px] font-bold text-white mb-2">Request Received!</div>
                        <p class="text-[13px] text-slate-400 leading-relaxed">Thank you! We've received your demo request and will get back to you shortly.</p>
                    </div>
                @else
                    <form action="{{ route('demo.request') }}" method="POST" class="space-y-5">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label for="demo_name" class="block text-[11px] font-bold uppercase tracking-widest text-slate-400 mb-2">Full Name <span class="text-orange-500">*</span></label>
                                <input id="demo_name" type="text" name="name" class="w-full bg-[#0a0b0e] border {{ $errors->has('name') ? 'border-red-500/60' : 'border-white/10' }} rounded-lg px-4 py-3 text-[13.5px] text-white placeholder-slate-600 focus:outline-none focus:border-orange-500/50 focus:bg-orange-500/5 transition-all" placeholder="Sumin Shrestha" value="{{ old('name') }}" required>
                                @error('name') <span class="text-[11px] text-red-400 mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="demo_email" class="block text-[11px] font-bold uppercase tracking-widest text-slate-400 mb-2">Email Address <span class="text-orange-500">*</span></label>
                                <input id="demo_email" type="email" name="email" class="w-full bg-[#0a0b0e] border {{ $errors->has('email') ? 'border-red-500/60' : 'border-white/10' }} rounded-lg px-4 py-3 text-[13.5px] text-white placeholder-slate-600 focus:outline-none focus:border-orange-500/50 focus:bg-orange-500/5 transition-all" placeholder="you@esports.com" value="{{ old('email') }}" required>
                                @error('email') <span class="text-[11px] text-red-400 mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label for="demo_org" class="block text-[11px] font-bold uppercase tracking-widest text-slate-400 mb-2">Organization</label>
                                <input id="demo_org" type="text" name="organization" class="w-full bg-[#0a0b0e] border border-white/10 rounded-lg px-4 py-3 text-[13.5px] text-white placeholder-slate-600 focus:outline-none focus:border-orange-500/50 focus:bg-orange-500/5 transition-all" placeholder="Esports Nepal (optional)" value="{{ old('organization') }}">
                            </div>
                            <div>
                                <label for="demo_phone" class="block text-[11px] font-bold uppercase tracking-widest text-slate-400 mb-2">Phone</label>
                                <input id="demo_phone" type="tel" name="phone" class="w-full bg-[#0a0b0e] border border-white/10 rounded-lg px-4 py-3 text-[13.5px] text-white placeholder-slate-600 focus:outline-none focus:border-orange-500/50 focus:bg-orange-500/5 transition-all" placeholder="+977 98XXXXXXXX (optional)" value="{{ old('phone') }}">
                            </div>
                        </div>
                        <div>
                            <label for="demo_message" class="block text-[11px] font-bold uppercase tracking-widest text-slate-400 mb-2">What are you looking to broadcast?</label>
                            <textarea id="demo_message" name="message" class="w-full min-h-22.5 bg-[#0a0b0e] border border-white/10 rounded-lg px-4 py-3 text-[13.5px] text-white placeholder-slate-600 focus:outline-none focus:border-orange-500/50 focus:bg-orange-500/5 transition-all resize-y" placeholder="Tell us about your tournament — game title, team count, event scale...">{{ old('message') }}</textarea>
                        </div>
                        <button type="submit" class="w-full flex items-center justify-center gap-2 px-7 py-3.5 bg-linear-to-br from-orange-500 to-orange-600 text-white font-['Rajdhani'] font-bold text-[15px] uppercase tracking-widest rounded-lg border-none cursor-pointer shadow-[0_0_0_1px_rgba(249,115,22,0.4),0_6px_24px_rgba(249,115,22,0.3)] hover:shadow-[0_0_0_1px_rgba(249,115,22,0.6),0_8px_32px_rgba(249,115,22,0.45)] transition-all duration-250">
                            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5" />
                            </svg>
                            Send Demo Request
                        </button>
                        <p class="text-[11px] text-slate-500 text-center">We typically respond within 1–2 business days.</p>
                    </form>
                @endif
            </div>
        </div>
    </section>

    <!-- ─── CONTACT SECTION ─── -->
    <section class="pt-24 pb-12 px-4 relative z-10 overflow-hidden" id="contact">
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-200 h-200 bg-[radial-gradient(circle,rgba(249,115,22,0.08)_0%,transparent_60%)] rounded-full pointer-events-none -z-10 animate-pulse-glow"></div>
        <div class="text-center mb-12 reveal-on-scroll delay-200">
            <h2 class="font-['Rajdhani'] text-[42px] font-bold text-white mb-3">Let's <span class="bg-linear-to-br from-orange-500 to-amber-500 text-transparent bg-clip-text">Connect</span></h2>
            <p class="text-[16px] text-slate-400 max-w-125 mx-auto">Have questions or need a custom setup? Reach out to OxyZone directly via any platform below.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 max-w-250 mx-auto reveal-on-scroll delay-400">
            <!-- Name/Profile -->
            <div class="group flex flex-col items-center p-8 bg-[#0f1117]/60 backdrop-blur-md border border-white/5 rounded-2xl text-center relative overflow-hidden transition-all duration-400 hover:-translate-y-2.5 hover:border-orange-500/30 hover:shadow-[0_20px_40px_rgba(0,0,0,0.6),0_0_20px_rgba(249,115,22,0.15)] hover:bg-[#0f1117]/90 cursor-default">
                <div class="absolute top-0 left-0 right-0 h-0.5 bg-linear-to-r from-transparent via-orange-500 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-400"></div>
                <div class="w-14 h-14 bg-orange-500/10 border border-orange-500/20 rounded-xl flex items-center justify-center text-orange-500 mb-5 transition-all duration-400 relative group-hover:bg-orange-500 group-hover:text-white group-hover:scale-110 group-hover:rotate-6 group-hover:shadow-[0_0_20px_rgba(249,115,22,0.4)]">
                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <div class="text-[11px] text-slate-400 uppercase tracking-[0.15em] font-bold mb-1.5 transition-colors duration-300 group-hover:text-slate-300">Founder</div>
                <div class="text-[16px] font-semibold text-white transition-colors duration-300 group-hover:text-orange-500">Sumin Shrestha</div>
                <div class="text-[12px] text-slate-400 mt-1">(A.k.a OxyZone)</div>
            </div>

            <!-- Phone -->
            <a href="tel:+9779802350986" class="group flex flex-col items-center p-8 bg-[#0f1117]/60 backdrop-blur-md border border-white/5 rounded-2xl text-center relative overflow-hidden transition-all duration-400 hover:-translate-y-2.5 hover:border-orange-500/30 hover:shadow-[0_20px_40px_rgba(0,0,0,0.6),0_0_20px_rgba(249,115,22,0.15)] hover:bg-[#0f1117]/90 decoration-0">
                <div class="absolute top-0 left-0 right-0 h-0.5 bg-linear-to-r from-transparent via-orange-500 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-400"></div>
                <div class="w-14 h-14 bg-orange-500/10 border border-orange-500/20 rounded-xl flex items-center justify-center text-orange-500 mb-5 transition-all duration-400 relative group-hover:bg-orange-500 group-hover:text-white group-hover:scale-110 group-hover:-rotate-6 group-hover:shadow-[0_0_20px_rgba(249,115,22,0.4)]">
                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                    </svg>
                </div>
                <div class="text-[11px] text-slate-400 uppercase tracking-[0.15em] font-bold mb-1.5 transition-colors duration-300 group-hover:text-slate-300">Phone</div>
                <div class="text-[16px] font-semibold text-white transition-colors duration-300 group-hover:text-orange-500">9802350986</div>
            </a>

            <!-- Email -->
            <a href="mailto:sumnsth@gmail.com" class="group flex flex-col items-center p-8 bg-[#0f1117]/60 backdrop-blur-md border border-white/5 rounded-2xl text-center relative overflow-hidden transition-all duration-400 hover:-translate-y-2.5 hover:border-orange-500/30 hover:shadow-[0_20px_40px_rgba(0,0,0,0.6),0_0_20px_rgba(249,115,22,0.15)] hover:bg-[#0f1117]/90 decoration-0">
                <div class="absolute top-0 left-0 right-0 h-0.5 bg-linear-to-r from-transparent via-orange-500 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-400"></div>
                <div class="w-14 h-14 bg-orange-500/10 border border-orange-500/20 rounded-xl flex items-center justify-center text-orange-500 mb-5 transition-all duration-400 relative group-hover:bg-orange-500 group-hover:text-white group-hover:scale-110 group-hover:rotate-6 group-hover:shadow-[0_0_20px_rgba(249,115,22,0.4)]">
                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
                <div class="text-[11px] text-slate-400 uppercase tracking-[0.15em] font-bold mb-1.5 transition-colors duration-300 group-hover:text-slate-300">Email</div>
                <div class="text-[16px] font-semibold text-white transition-colors duration-300 group-hover:text-orange-500 truncate w-full">sumnsth@gmail.com</div>
            </a>

            <!-- Discord -->
            <a href="#" class="group flex flex-col items-center p-8 bg-[#0f1117]/60 backdrop-blur-md border border-white/5 rounded-2xl text-center relative overflow-hidden transition-all duration-400 hover:-translate-y-2.5 hover:border-orange-500/30 hover:shadow-[0_20px_40px_rgba(0,0,0,0.6),0_0_20px_rgba(249,115,22,0.15)] hover:bg-[#0f1117]/90 decoration-0" onclick="navigator.clipboard.writeText('oxyzone'); alert('Discord username copied to clipboard!'); return false;">
                <div class="absolute top-0 left-0 right-0 h-0.5 bg-linear-to-r from-transparent via-orange-500 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-400"></div>
                <div class="w-14 h-14 bg-orange-500/10 border border-orange-500/20 rounded-xl flex items-center justify-center text-orange-500 mb-5 transition-all duration-400 relative group-hover:bg-orange-500 group-hover:text-white group-hover:scale-110 group-hover:-rotate-6 group-hover:shadow-[0_0_20px_rgba(249,115,22,0.4)]">
                    <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M20.317 4.3698a19.7913 19.7913 0 00-4.8851-1.5152.0741.0741 0 00-.0785.0371c-.211.3753-.4447.8648-.6083 1.2495-1.8447-.2762-3.68-.2762-5.4868 0-.1636-.3933-.4058-.8742-.6177-1.2495a.077.077 0 00-.0785-.037 19.7363 19.7363 0 00-4.8852 1.515.0699.0699 0 00-.0321.0277C.5334 9.0458-.319 13.5799.0992 18.0578a.0824.0824 0 00.0312.0561c2.0528 1.5076 4.0413 2.4228 5.9929 3.0294a.0777.0777 0 00.0842-.0276c.4616-.6304.8731-1.2952 1.226-1.9942a.076.076 0 00-.0416-.1057c-.6528-.2476-1.2743-.5495-1.8722-.8923a.077.077 0 01-.0076-.1277c.1258-.0943.2517-.1923.3718-.2914a.0743.0743 0 01.0776-.0105c3.9278 1.7933 8.18 1.7933 12.0614 0a.0739.0739 0 01.0785.0095c.1202.099.246.1981.3728.2924a.077.077 0 01-.0066.1276 12.2986 12.2986 0 01-1.873.8914.0766.0766 0 00-.0407.1067c.3604.698.7719 1.3628 1.225 1.9932a.076.076 0 00.0842.0286c1.961-.6067 3.9495-1.5219 6.0023-3.0294a.077.077 0 00.0313-.0552c.5004-5.177-.8382-9.6739-3.5485-13.6604a.061.061 0 00-.0312-.0286zM8.02 15.3312c-1.1825 0-2.1569-1.0857-2.1569-2.419 0-1.3332.9555-2.4189 2.157-2.4189 1.2108 0 2.1757 1.0952 2.1568 2.419 0 1.3332-.9555 2.4189-2.1569 2.4189zm7.9748 0c-1.1825 0-2.1569-1.0857-2.1569-2.419 0-1.3332.9554-2.4189 2.1569-2.4189 1.2108 0 2.1757 1.0952 2.1568 2.419 0 1.3332-.946 2.4189-2.1568 2.4189Z"/>
                    </svg>
                </div>
                <div class="text-[11px] text-slate-400 uppercase tracking-[0.15em] font-bold mb-1.5 transition-colors duration-300 group-hover:text-slate-300">Discord</div>
                <div class="text-[16px] font-semibold text-white transition-colors duration-300 group-hover:text-orange-500">oxyzone</div>
            </a>

            <!-- Instagram -->
            <a href="https://instagram.com/0xyzone" target="_blank" class="group flex flex-col items-center p-8 bg-[#0f1117]/60 backdrop-blur-md border border-white/5 rounded-2xl text-center relative overflow-hidden transition-all duration-400 hover:-translate-y-2.5 hover:border-orange-500/30 hover:shadow-[0_20px_40px_rgba(0,0,0,0.6),0_0_20px_rgba(249,115,22,0.15)] hover:bg-[#0f1117]/90 decoration-0">
                <div class="absolute top-0 left-0 right-0 h-0.5 bg-linear-to-r from-transparent via-orange-500 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-400"></div>
                <div class="w-14 h-14 bg-orange-500/10 border border-orange-500/20 rounded-xl flex items-center justify-center text-orange-500 mb-5 transition-all duration-400 relative group-hover:bg-orange-500 group-hover:text-white group-hover:scale-110 group-hover:rotate-6 group-hover:shadow-[0_0_20px_rgba(249,115,22,0.4)]">
                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect>
                        <path d="M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37z"></path>
                        <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line>
                    </svg>
                </div>
                <div class="text-[11px] text-slate-400 uppercase tracking-[0.15em] font-bold mb-1.5 transition-colors duration-300 group-hover:text-slate-300">Instagram</div>
                <div class="text-[16px] font-semibold text-white transition-colors duration-300 group-hover:text-orange-500">0xyzone</div>
            </a>
        </div>
    </section>

    <!-- ─── FOOTER ─── -->
    <footer class="relative z-10 border-t border-white/5 bg-[#0a0b0e]/90 animate-fade-in">
        <div class="max-w-7xl mx-auto p-8 flex justify-between items-center flex-wrap gap-4">
            <div class="flex items-center gap-2.5">
                <img src="{{ asset('img/logo.png') }}" alt="BroadKaster" class="h-5 opacity-60">
            </div>
            <div class="text-[11px] text-slate-600 font-medium">&copy; {{ date('Y') }} BroadKaster. Built with ❤️ by <a href="https://suminshrestha.com.np" class="text-orange-500 decoration-0">OxyZone</a></div>
            <div class="font-mono text-[10px] text-slate-800 tracking-[0.08em]">SYS_BUILD: v{{ $systemVersion ?? '1.1.117' }}</div>
        </div>
    </footer>

    <!-- Mouse Trail Container -->
    <div id="mouse-trail-container" class="fixed inset-0 pointer-events-none z-50 overflow-hidden hidden md:block mix-blend-screen"></div>

    <script>
        // Cinematic Preloader Logic
        window.addEventListener('load', () => {
            const preloader = document.getElementById('preloader');
            const line = document.getElementById('preloader-line');
            const top = document.getElementById('preloader-top');
            const bottom = document.getElementById('preloader-bottom');
            const text = document.getElementById('preloader-text');
            
            if(preloader && line && top && bottom && text) {
                // Step 1: Expand line and fade in text
                setTimeout(() => {
                    text.style.opacity = '1';
                    line.style.width = '100vw';
                }, 100);
                
                // Step 2: Open shutter
                setTimeout(() => {
                    text.style.opacity = '0';
                    line.style.opacity = '0';
                    top.style.transform = 'translateY(-100%)';
                    bottom.style.transform = 'translateY(100%)';
                }, 1000);
                
                // Step 3: Remove from DOM completely
                setTimeout(() => {
                    preloader.remove();
                }, 2200);
            }
        });

        // Scroll Reveal Logic
        const revealElements = document.querySelectorAll('.reveal-on-scroll');
        const revealObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                }
            });
        }, { threshold: 0.1, rootMargin: "0px 0px -50px 0px" });

        revealElements.forEach(el => revealObserver.observe(el));

        document.getElementById('mobile-menu-btn').addEventListener('click', function() {
            const menu = document.getElementById('mobile-menu');
            if(menu.classList.contains('hidden')){
                menu.classList.remove('hidden');
                menu.classList.add('flex');
            } else {
                menu.classList.add('hidden');
                menu.classList.remove('flex');
            }
        });

        // Snake Mouse Trail logic
        if (window.matchMedia("(pointer: fine)").matches) {
            const container = document.getElementById('mouse-trail-container');
            const dots = [];
            const numDots = 15;
            
            for(let i=0; i<numDots; i++) {
                let dot = document.createElement('div');
                dot.className = "absolute w-4 h-4 rounded-full bg-orange-500 will-change-transform";
                dot.style.opacity = Math.max(0.1, 1 - (i / numDots));
                dot.style.transform = `scale(${1 - (i / numDots)})`;
                dot.style.filter = `blur(${i * 0.4}px)`;
                container.appendChild(dot);
                dots.push({ el: dot, x: window.innerWidth / 2, y: window.innerHeight / 2 });
            }
            
            let mouseX = window.innerWidth / 2;
            let mouseY = window.innerHeight / 2;
            
            document.addEventListener('mousemove', (e) => {
                mouseX = e.clientX;
                mouseY = e.clientY;
            });
            
            function animateTrail() {
                let x = mouseX;
                let y = mouseY;
                
                dots.forEach((dot, index) => {
                    const nextDot = dots[index + 1] || dots[0];
                    dot.x = x;
                    dot.y = y;
                    dot.el.style.left = (x - 8) + 'px';
                    dot.el.style.top = (y - 8) + 'px';
                    
                    x += (nextDot.x - x) * 0.4;
                    y += (nextDot.y - y) * 0.4;
                });
                
                requestAnimationFrame(animateTrail);
            }
            animateTrail();
        }
    </script>
</body>
</html>
