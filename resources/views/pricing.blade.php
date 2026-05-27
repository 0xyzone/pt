<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pricing Plans — BroadKaster</title>
    <meta name="description" content="Choose a BroadKaster plan that fits your esports production needs. From daily passes to annual subscriptions — flexible pricing for every tournament organizer.">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="Pricing Plans — BroadKaster">
    <meta property="og:description" content="Choose a BroadKaster plan that fits your esports production needs. From daily passes to annual subscriptions — flexible pricing for every tournament organizer.">
    <meta property="og:image" content="{{ asset('img/symbol.png') }}">

    <!-- Twitter -->
    <meta name="twitter:card" content="summary">
    <meta name="twitter:url" content="{{ url()->current() }}">
    <meta name="twitter:title" content="Pricing Plans — BroadKaster">
    <meta name="twitter:description" content="Choose a BroadKaster plan that fits your esports production needs. From daily passes to annual subscriptions — flexible pricing for every tournament organizer.">
    <meta name="twitter:image" content="{{ asset('img/symbol.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700;900&display=swap" rel="stylesheet">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('img/symbol.png') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-['Inter'] bg-[#0a0b0e] text-slate-200 min-h-screen overflow-x-hidden antialiased relative">
    <!-- Cinematic Preloader -->
    <div id="preloader" class="fixed inset-0 z-[99999] pointer-events-none flex flex-col">
        <div id="preloader-top" class="flex-1 bg-[#050508] transition-transform duration-1000 ease-[cubic-bezier(0.85,0,0.15,1)] border-b border-orange-500/20"></div>
        <div id="preloader-bottom" class="flex-1 bg-[#050508] transition-transform duration-1000 ease-[cubic-bezier(0.85,0,0.15,1)] border-t border-orange-500/20"></div>
        
        <!-- The glowing line in the center -->
        <div id="preloader-line" class="absolute top-1/2 left-1/2 -translate-y-1/2 -translate-x-1/2 h-[2px] bg-orange-500 shadow-[0_0_20px_rgba(249,115,22,1)] w-0 transition-all duration-700 ease-in-out z-10"></div>
        
        <!-- Tech text -->
        <div id="preloader-text" class="absolute top-[48%] left-1/2 -translate-x-1/2 -translate-y-1/2 text-orange-500 font-mono text-[10px] md:text-[12px] uppercase tracking-[0.3em] md:tracking-[0.5em] opacity-0 transition-opacity duration-300 z-20 whitespace-nowrap">
            System Initializing...
        </div>
    </div>

    <!-- Background overlay -->
    <div class="fixed inset-0 opacity-[0.025] pointer-events-none z-0" style="background-image: url(&quot;data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)'/%3E%3C/svg%3E&quot;);"></div>

    <!-- Atmospheric Orbs -->
    <div class="orb w-[700px] h-[700px] -top-[15%] -right-[10%] bg-[radial-gradient(circle,rgba(249,115,22,0.15),transparent_70%)] [animation-duration:22s]" aria-hidden="true"></div>
    <div class="orb w-[600px] h-[600px] -bottom-[20%] -left-[12%] bg-[radial-gradient(circle,rgba(245,158,11,0.1),transparent_70%)] [animation-duration:28s] [animation-delay:-8s]" aria-hidden="true"></div>

    <!-- ─── NAVBAR ─── -->
    <nav class="sticky top-0 z-[100] bg-[#0a0b0e]/85 backdrop-blur-[24px] saturate-150 border-b border-white/5 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 md:px-8 h-[72px] flex justify-between items-center">
            <a href="/" class="flex items-center gap-3 decoration-0">
                <img src="{{ asset('img/logo.png') }}" alt="BroadKaster" class="h-[36px]">
            </a>
            
            <div class="hidden md:flex items-center gap-3">
                <a href="/" class="px-[18px] py-[8px] text-[11px] font-bold tracking-[0.12em] uppercase text-slate-300 hover:text-white border border-white/10 hover:border-orange-500/40 hover:bg-orange-500/10 transition-all duration-200 rounded-lg">Home</a>
                <a href="/#contact" class="px-[18px] py-[8px] text-[11px] font-bold tracking-[0.12em] uppercase text-slate-300 hover:text-white border border-white/10 hover:border-orange-500/40 hover:bg-orange-500/10 transition-all duration-200 rounded-lg">Contact</a>
                @auth
                    <a href="{{ url('/maidan') }}" class="px-[22px] py-[9px] text-[11px] font-bold tracking-[0.12em] uppercase text-white bg-gradient-to-br from-orange-500 to-orange-600 rounded-lg shadow-[0_0_20px_rgba(249,115,22,0.3)] hover:shadow-[0_0_35px_rgba(249,115,22,0.5)] hover:-translate-y-[1px] transition-all duration-200 border border-transparent">Dashboard</a>
                @else
                    <a href="{{ url('/maidan/login') }}" class="px-[22px] py-[9px] text-[11px] font-bold tracking-[0.12em] uppercase text-white bg-gradient-to-br from-orange-500 to-orange-600 rounded-lg shadow-[0_0_20px_rgba(249,115,22,0.3)] hover:shadow-[0_0_35px_rgba(249,115,22,0.5)] hover:-translate-y-[1px] transition-all duration-200 border border-transparent">Get Started</a>
                @endauth
            </div>
            
            <button class="md:hidden flex items-center justify-center p-2 text-white bg-transparent cursor-pointer" id="mobile-menu-btn">
                <svg width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>
        
        <!-- Mobile Menu -->
        <div class="hidden flex-col gap-3 p-4 bg-[#0f1117] border-b border-white/5 absolute top-[72px] left-0 right-0 shadow-[0_10px_30px_rgba(0,0,0,0.5)]" id="mobile-menu">
            <a href="/" class="block px-4 py-3 font-semibold text-slate-100 bg-white/5 rounded-lg text-center decoration-0">Home</a>
            <a href="/#contact" class="block px-4 py-3 font-semibold text-slate-100 bg-white/5 rounded-lg text-center decoration-0">Contact</a>
            @auth
                <a href="{{ url('/maidan') }}" class="block px-4 py-3 font-semibold text-slate-100 bg-white/5 rounded-lg text-center decoration-0">Dashboard</a>
            @else
                <a href="{{ url('/maidan/login') }}" class="block px-4 py-3 font-semibold text-white bg-gradient-to-br from-orange-500 to-orange-600 rounded-lg text-center decoration-0">Get Started</a>
            @endauth
        </div>
    </nav>

    <!-- ─── PAGE HEADER ─── -->
    <div class="relative z-10 text-center pt-[80px] px-8 pb-[60px]">
        <div class="inline-flex items-center gap-2 px-4 py-[7px] mb-7 bg-orange-500/10 border border-orange-500/20 rounded-full text-[10px] font-bold uppercase tracking-[0.22em] text-orange-500 reveal-on-scroll">
            <span class="w-1.5 h-1.5 rounded-full bg-orange-500 animate-blink"></span>
            Transparent Pricing
        </div>
        <h1 class="font-['Rajdhani'] text-[clamp(36px,6vw,64px)] font-bold text-slate-100 leading-[1.05] mb-5 tracking-[-0.02em] reveal-on-scroll delay-200">
            Plans for Every<br>
            <span class="bg-gradient-to-br from-orange-500 to-amber-500 text-transparent bg-clip-text">Tournament Scale</span>
        </h1>
        <p class="text-[16px] text-slate-400 max-w-[520px] mx-auto leading-[1.7] reveal-on-scroll delay-300">
            From a single day event to a full esports season — choose the plan that fits your production needs. All plans include OBS overlays and real-time sync.
        </p>
    </div>

    <!-- ─── PRICING GRID ─── -->
    <section class="relative z-10 px-8 pb-[100px]">
        <div class="max-w-[1200px] mx-auto grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @php
                $popular = ['1-month-plan', '3-month-plan'];
            @endphp

            @foreach($plans as $index => $plan)
            @php
                $isFeatured = in_array($plan->slug, $popular);
                $features = $plan->features ?? [];
                $maxTournaments = $features['max_tournaments'] ?? 0;
                $maxTeams = $features['max_teams'] ?? 0;
                $maxMatches = $features['max_matches'] ?? 0;
                $isFree = !$plan->price || $plan->price <= 0;
                $durationDisplay = $plan->duration_value ? ucfirst($plan->duration_period === 'days' ? $plan->duration_value . ' Day(s)' : ($plan->duration_value . ' ' . ucfirst(rtrim($plan->duration_period, 's')))) : 'Lifetime';
                $durationLabel = match($plan->duration_period) {
                    'days' => $plan->duration_value . ' ' . ($plan->duration_value > 1 ? 'Days' : 'Day') . ' Access',
                    'weeks' => $plan->duration_value . ' ' . ($plan->duration_value > 1 ? 'Weeks' : 'Week') . ' Access',
                    'months' => $plan->duration_value . ' ' . ($plan->duration_value > 1 ? 'Months' : 'Month') . ' Access',
                    'years' => $plan->duration_value . ' ' . ($plan->duration_value > 1 ? 'Years' : 'Year') . ' Access',
                    default => 'Lifetime Access',
                };
            @endphp
            <div class="flex flex-col relative overflow-hidden transition-all duration-300 rounded-[20px] p-8 border {{ $isFeatured ? 'bg-[linear-gradient(145deg,rgba(249,115,22,0.07),#0f1117_60%)] border-orange-500/35 hover:border-orange-500/25 hover:-translate-y-1 hover:shadow-[0_24px_60px_rgba(0,0,0,0.5),0_0_0_1px_rgba(249,115,22,0.1)]' : 'bg-[#0f1117] border-white/5 hover:border-orange-500/25 hover:-translate-y-1 hover:shadow-[0_24px_60px_rgba(0,0,0,0.5),0_0_0_1px_rgba(249,115,22,0.1)]' }} animate-[cardIn_0.5s_cubic-bezier(0.22,1,0.36,1)_both]" style="animation-delay: {{ $index * 0.07 }}s;">
                
                @if($isFeatured)
                    <!-- Top gradient line -->
                    <div class="absolute top-0 left-0 right-0 h-[2px] bg-gradient-to-r from-transparent via-orange-500 to-transparent"></div>
                    <!-- Badge -->
                    <div class="absolute top-5 right-5 text-[9px] font-bold tracking-[0.15em] uppercase text-[#0a0b0e] bg-gradient-to-br from-orange-500 to-amber-500 px-3 py-1.5 rounded-full">Most Popular</div>
                @endif

                <div class="mb-6">
                    <div class="font-['Rajdhani'] text-[22px] font-bold text-slate-100 tracking-[0.02em] mb-1.5">{{ $plan->name }}</div>
                    <div class="text-[12.5px] text-slate-400 leading-[1.6]">{{ $plan->description }}</div>
                </div>

                <div class="mb-6 pb-6 border-b border-white/5">
                    <div class="font-['Rajdhani'] text-[38px] font-bold leading-none mb-1 {{ $isFree ? 'text-green-500' : 'text-orange-500' }}">
                        {{ $plan->price_display }}
                    </div>
                    <div class="text-[11px] font-semibold tracking-[0.1em] uppercase text-slate-400">{{ $durationLabel }}</div>
                </div>

                <div class="flex-1 flex flex-col gap-2.5 mb-7">
                    <!-- Tournaments -->
                    <div class="flex items-center gap-2.5 text-[13px] text-slate-300">
                        <span class="shrink-0 w-[18px] h-[18px] rounded-full flex items-center justify-center bg-green-500/10 border border-green-500/25">
                            <svg class="w-2.5 h-2.5 text-green-500" fill="none" viewBox="0 0 12 12" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2 6l3 3 5-5"/></svg>
                        </span>
                        <span>Tournaments</span>
                        <span class="ml-auto text-[11px] font-bold font-['Rajdhani'] text-orange-500 bg-orange-500/10 border border-orange-500/15 px-2 py-0.5 rounded-full">{{ $maxTournaments === -1 ? '∞' : $maxTournaments }}</span>
                    </div>
                    <!-- Teams -->
                    <div class="flex items-center gap-2.5 text-[13px] text-slate-300">
                        <span class="shrink-0 w-[18px] h-[18px] rounded-full flex items-center justify-center bg-green-500/10 border border-green-500/25">
                            <svg class="w-2.5 h-2.5 text-green-500" fill="none" viewBox="0 0 12 12" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2 6l3 3 5-5"/></svg>
                        </span>
                        <span>Teams per Tournament</span>
                        <span class="ml-auto text-[11px] font-bold font-['Rajdhani'] text-orange-500 bg-orange-500/10 border border-orange-500/15 px-2 py-0.5 rounded-full">{{ $maxTeams === -1 ? '∞' : $maxTeams }}</span>
                    </div>
                    <!-- Matches -->
                    <div class="flex items-center gap-2.5 text-[13px] text-slate-300">
                        <span class="shrink-0 w-[18px] h-[18px] rounded-full flex items-center justify-center bg-green-500/10 border border-green-500/25">
                            <svg class="w-2.5 h-2.5 text-green-500" fill="none" viewBox="0 0 12 12" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2 6l3 3 5-5"/></svg>
                        </span>
                        <span>Matches per Tournament</span>
                        <span class="ml-auto text-[11px] font-bold font-['Rajdhani'] text-orange-500 bg-orange-500/10 border border-orange-500/15 px-2 py-0.5 rounded-full">{{ ($features['max_matches'] ?? 0) === -1 ? '∞' : ($features['max_matches'] ?? 0) }}</span>
                    </div>
                    <!-- OBS Overlays -->
                    <div class="flex items-center gap-2.5 text-[13px] {{ ($features['obs_overlays'] ?? false) ? 'text-slate-300' : 'text-slate-500/40 line-through' }}">
                        <span class="shrink-0 w-[18px] h-[18px] rounded-full flex items-center justify-center {{ ($features['obs_overlays'] ?? false) ? 'bg-green-500/10 border border-green-500/25' : 'bg-red-500/10 border border-red-500/15' }}">
                            @if($features['obs_overlays'] ?? false)
                                <svg class="w-2.5 h-2.5 text-green-500" fill="none" viewBox="0 0 12 12" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2 6l3 3 5-5"/></svg>
                            @else
                                <svg class="w-2.5 h-2.5 text-red-500/60" fill="none" viewBox="0 0 12 12" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3l6 6M9 3l-6 6"/></svg>
                            @endif
                        </span>
                        <span>OBS Overlays</span>
                    </div>
                    <!-- WebSocket Sync -->
                    <div class="flex items-center gap-2.5 text-[13px] {{ ($features['websocket_sync'] ?? false) ? 'text-slate-300' : 'text-slate-500/40 line-through' }}">
                        <span class="shrink-0 w-[18px] h-[18px] rounded-full flex items-center justify-center {{ ($features['websocket_sync'] ?? false) ? 'bg-green-500/10 border border-green-500/25' : 'bg-red-500/10 border border-red-500/15' }}">
                            @if($features['websocket_sync'] ?? false)
                                <svg class="w-2.5 h-2.5 text-green-500" fill="none" viewBox="0 0 12 12" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2 6l3 3 5-5"/></svg>
                            @else
                                <svg class="w-2.5 h-2.5 text-red-500/60" fill="none" viewBox="0 0 12 12" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3l6 6M9 3l-6 6"/></svg>
                            @endif
                        </span>
                        <span>Real-Time WebSocket Sync</span>
                    </div>
                    <!-- Roadmap Overlay -->
                    <div class="flex items-center gap-2.5 text-[13px] {{ ($features['roadmap_overlay'] ?? false) ? 'text-slate-300' : 'text-slate-500/40 line-through' }}">
                        <span class="shrink-0 w-[18px] h-[18px] rounded-full flex items-center justify-center {{ ($features['roadmap_overlay'] ?? false) ? 'bg-green-500/10 border border-green-500/25' : 'bg-red-500/10 border border-red-500/15' }}">
                            @if($features['roadmap_overlay'] ?? false)
                                <svg class="w-2.5 h-2.5 text-green-500" fill="none" viewBox="0 0 12 12" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2 6l3 3 5-5"/></svg>
                            @else
                                <svg class="w-2.5 h-2.5 text-red-500/60" fill="none" viewBox="0 0 12 12" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3l6 6M9 3l-6 6"/></svg>
                            @endif
                        </span>
                        <span>Roadmap Overlay</span>
                    </div>
                    <!-- Casters Management -->
                    <div class="flex items-center gap-2.5 text-[13px] {{ ($features['casters_management'] ?? false) ? 'text-slate-300' : 'text-slate-500/40 line-through' }}">
                        <span class="shrink-0 w-[18px] h-[18px] rounded-full flex items-center justify-center {{ ($features['casters_management'] ?? false) ? 'bg-green-500/10 border border-green-500/25' : 'bg-red-500/10 border border-red-500/15' }}">
                            @if($features['casters_management'] ?? false)
                                <svg class="w-2.5 h-2.5 text-green-500" fill="none" viewBox="0 0 12 12" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2 6l3 3 5-5"/></svg>
                            @else
                                <svg class="w-2.5 h-2.5 text-red-500/60" fill="none" viewBox="0 0 12 12" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3l6 6M9 3l-6 6"/></svg>
                            @endif
                        </span>
                        <span>Casters Management</span>
                    </div>
                    <!-- Player Management -->
                    <div class="flex items-center gap-2.5 text-[13px] {{ ($features['player_management'] ?? false) ? 'text-slate-300' : 'text-slate-500/40 line-through' }}">
                        <span class="shrink-0 w-[18px] h-[18px] rounded-full flex items-center justify-center {{ ($features['player_management'] ?? false) ? 'bg-green-500/10 border border-green-500/25' : 'bg-red-500/10 border border-red-500/15' }}">
                            @if($features['player_management'] ?? false)
                                <svg class="w-2.5 h-2.5 text-green-500" fill="none" viewBox="0 0 12 12" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2 6l3 3 5-5"/></svg>
                            @else
                                <svg class="w-2.5 h-2.5 text-red-500/60" fill="none" viewBox="0 0 12 12" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3l6 6M9 3l-6 6"/></svg>
                            @endif
                        </span>
                        <span>Player Management</span>
                    </div>
                    <!-- Custom Branding -->
                    <div class="flex items-center gap-2.5 text-[13px] {{ ($features['custom_branding'] ?? false) ? 'text-slate-300' : 'text-slate-500/40 line-through' }}">
                        <span class="shrink-0 w-[18px] h-[18px] rounded-full flex items-center justify-center {{ ($features['custom_branding'] ?? false) ? 'bg-green-500/10 border border-green-500/25' : 'bg-red-500/10 border border-red-500/15' }}">
                            @if($features['custom_branding'] ?? false)
                                <svg class="w-2.5 h-2.5 text-green-500" fill="none" viewBox="0 0 12 12" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2 6l3 3 5-5"/></svg>
                            @else
                                <svg class="w-2.5 h-2.5 text-red-500/60" fill="none" viewBox="0 0 12 12" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3l6 6M9 3l-6 6"/></svg>
                            @endif
                        </span>
                        <span>Custom Branding</span>
                    </div>
                </div>

                <a href="/maidan" class="block text-center px-6 py-[13px] rounded-[10px] text-[12px] font-bold tracking-[0.12em] uppercase transition-all duration-250 {{ $isFeatured ? 'bg-gradient-to-br from-orange-500 to-orange-600 text-white shadow-[0_0_24px_rgba(249,115,22,0.3)] hover:-translate-y-0.5 hover:shadow-[0_0_40px_rgba(249,115,22,0.5)]' : 'bg-white/5 border border-white/10 text-slate-300 hover:bg-orange-500/10 hover:border-orange-500/30 hover:text-white' }}">
                    Get Started
                </a>
            </div>
            @endforeach
        </div>
    </section>

    <!-- ─── NOTE SECTION ─── -->
    <div class="relative z-10 text-center px-8 pb-[80px]">
        <div class="max-w-[700px] mx-auto bg-orange-500/5 border border-orange-500/10 rounded-[16px] px-9 py-7 reveal-on-scroll delay-300">
            <p class="text-[14px] text-slate-400 leading-[1.7]">
                All plans are activated manually by our team after verifying your payment. Once logged in, head to your dashboard and submit a subscription request with your transaction screenshot.
                Have questions? <a href="/#contact" class="text-orange-500 font-semibold no-underline hover:underline">Contact us</a> and we'll help you choose the right plan.
            </p>
        </div>
    </div>

    <!-- ─── FOOTER ─── -->
    <footer class="relative z-10 border-t border-white/5 px-8 py-8 text-center animate-fade-in">
        <p class="text-[12px] text-slate-500 tracking-[0.05em]">&copy; {{ date('Y') }} BroadKaster &mdash; All rights reserved.</p>
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
