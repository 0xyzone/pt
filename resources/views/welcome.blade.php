<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Maidan Tournament System') }}</title>
        
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,600,800,900&display=swap" rel="stylesheet" />

        <!-- Tailwind CSS (Vite Configuration) -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <!-- Fallback Script for Dev just in case -->
            <script src="https://cdn.tailwindcss.com"></script>
        @endif
        
        <style>
            body { font-family: 'Inter', sans-serif; }
            .bg-glow {
                animation: floatGlow 10s ease-in-out infinite alternate;
            }
            @keyframes floatGlow {
                0% { transform: scale(1) translate(0, 0); opacity: 0.3; }
                50% { transform: scale(1.2) translate(10px, -20px); opacity: 0.6; }
                100% { transform: scale(1) translate(-20px, 10px); opacity: 0.3; }
            }
        </style>
    </head>
    <body class="bg-slate-950 text-slate-100 min-h-screen flex flex-col relative overflow-x-hidden w-full">
        
        <!-- Premium Animated Glass Background Elements -->
        <div class="fixed inset-0 z-0 pointer-events-none overflow-hidden w-full h-full">
            <div class="absolute -top-40 -right-40 w-[600px] h-[600px] bg-cyan-600/20 blur-[130px] rounded-full bg-glow"></div>
            <div class="absolute -bottom-60 -left-40 w-[700px] h-[700px] bg-purple-600/15 blur-[150px] rounded-full bg-glow" style="animation-duration: 14s; animation-delay: 2s;"></div>
            <div class="absolute top-[20%] left-[30%] w-[400px] h-[400px] bg-rose-600/10 blur-[120px] rounded-full bg-glow" style="animation-duration: 18s;"></div>
        </div>

        <!-- Navigation Bar -->
        <nav class="relative z-10 w-full p-6 lg:p-8 flex justify-between items-center max-w-7xl mx-auto">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-gradient-to-br from-cyan-400 to-purple-600 rounded-lg flex items-center justify-center font-black text-slate-100 text-2xl shadow-[0_0_20px_rgba(6,182,212,0.4)]">
                    M
                </div>
                <span class="text-3xl font-black italic tracking-tighter uppercase text-slate-100 drop-shadow-md">
                    Maidan
                </span>
            </div>
            
            <div class="flex items-center gap-4">
                @auth
                    <a href="{{ url('/maidan') }}" class="px-6 py-2.5 bg-slate-900 hover:bg-gradient-to-r hover:from-cyan-600 hover:to-purple-600 border border-slate-700 hover:border-transparent text-slate-200 font-black uppercase tracking-widest text-xs transition-all duration-300 rounded-lg shadow-lg hover:shadow-[0_0_25px_rgba(6,182,212,0.5)]">
                        Dashboard
                    </a>
                @else
                    <a href="{{ url('/maidan/login') }}" class="px-6 py-2.5 bg-slate-900 hover:bg-gradient-to-r hover:from-cyan-600 hover:to-purple-600 border border-slate-700 hover:border-transparent text-slate-200 font-black uppercase tracking-widest text-xs transition-all duration-300 rounded-lg shadow-lg hover:shadow-[0_0_25px_rgba(6,182,212,0.5)]">
                        Admin Login
                    </a>
                @endauth
            </div>
        </nav>

        <!-- Hero Section -->
        <main class="relative z-10 flex-1 flex flex-col justify-center items-center px-6 w-full max-w-7xl mx-auto pt-10 pb-20">
            
            <div class="text-center mb-16">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-slate-900/80 border border-cyan-500/30 text-cyan-400 font-bold uppercase tracking-[0.2em] text-xs mb-8 shadow-[0_0_15px_rgba(6,182,212,0.15)]">
                    <span class="w-2 h-2 rounded-full bg-cyan-400 animate-pulse"></span>
                    Next-Gen Esports Integration
                </div>
                
                <h1 class="text-6xl md:text-8xl font-black italic tracking-tighter uppercase pb-2 mb-6 leading-tight">
                    <span class="text-slate-100 drop-shadow-lg">Tournament</span> <br class="hidden md:block" />
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 via-purple-400 to-rose-400 drop-shadow-[0_0_15px_rgba(168,85,247,0.3)]">Ecosystem</span>
                </h1>
                
                <p class="text-slate-400 text-lg md:text-xl max-w-2xl mx-auto leading-relaxed font-medium">
                    Elevate your broadcast with real-time match statistics, live OBS overlays, and dynamic visual standings powered by the robust Maidan backend panel.
                </p>
                
                <div class="mt-10 flex justify-center gap-6">
                    <a href="{{ url('/maidan') }}" class="px-8 py-4 bg-gradient-to-r from-cyan-500 to-purple-600 text-white font-black uppercase tracking-widest text-sm rounded-xl shadow-[0_0_30px_rgba(6,182,212,0.4)] hover:shadow-[0_0_40px_rgba(168,85,247,0.6)] hover:scale-105 transition-all duration-300">
                        Launch Panel
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 w-full">
                <!-- Feature 1 -->
                <div class="bg-slate-900/60 backdrop-blur-md border border-slate-700/50 p-8 rounded-2xl hover:bg-slate-800/80 hover:border-cyan-500/70 transition-all duration-300 group shadow-[0_4px_20px_rgba(0,0,0,0.5)]">
                    <div class="text-cyan-400 mb-5 group-hover:scale-110 transition-transform origin-left">
                        <svg class="w-10 h-10 drop-shadow-[0_0_10px_rgba(6,182,212,0.5)]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-black text-slate-100 uppercase tracking-widest mb-3 italic">Real-Time Sync</h3>
                    <p class="text-slate-400 font-medium leading-relaxed">Instantaneous broadcast bridging via WebSockets keeps your live stream flawlessly updated as matches happen.</p>
                </div>
                
                <!-- Feature 2 -->
                <div class="bg-slate-900/60 backdrop-blur-md border border-slate-700/50 p-8 rounded-2xl hover:bg-slate-800/80 hover:border-purple-500/70 transition-all duration-300 group shadow-[0_4px_20px_rgba(0,0,0,0.5)] md:-translate-y-6">
                    <div class="text-purple-400 mb-5 group-hover:scale-110 transition-transform origin-left">
                        <svg class="w-10 h-10 drop-shadow-[0_0_10px_rgba(168,85,247,0.5)]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-black text-slate-100 uppercase tracking-widest mb-3 italic">OBS Ready</h3>
                    <p class="text-slate-400 font-medium leading-relaxed">Transparent Director-controlled master overlays drop straight into your streaming software completely friction-free.</p>
                </div>

                <!-- Feature 3 -->
                <div class="bg-slate-900/60 backdrop-blur-md border border-slate-700/50 p-8 rounded-2xl hover:bg-slate-800/80 hover:border-rose-500/70 transition-all duration-300 group shadow-[0_4px_20px_rgba(0,0,0,0.5)]">
                    <div class="text-rose-400 mb-5 group-hover:scale-110 transition-transform origin-left">
                        <svg class="w-10 h-10 drop-shadow-[0_0_10px_rgba(244,63,94,0.5)]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-black text-slate-100 uppercase tracking-widest mb-3 italic">Smart Rankings</h3>
                    <p class="text-slate-400 font-medium leading-relaxed">Advanced algorithmic stat aggregation instantly calculates overall leaderboards matching the active tournament rules.</p>
                </div>
            </div>

        </main>
        
        <!-- Footer -->
        <footer class="relative z-10 w-full text-center py-10 bg-slate-950/80 backdrop-blur-sm border-t border-slate-900">
            <p class="text-slate-600 text-[10px] sm:text-xs tracking-[0.3em] uppercase font-bold">
                &copy; {{ date('Y') }} Maidan Tournament Architecture. Built on Laravel.
            </p>
        </footer>

    </body>
</html>
