
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Screen</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-transparent">

    <!-- Background styling for control panel specifically, distinct from OBS overlay -->
    <div class="min-h-screen bg-slate-950 p-6 md:p-12 font-sans text-slate-100 flex flex-col items-center w-full">
        
        <div class="text-center w-full mb-10">
            <h1 class="text-4xl md:text-5xl font-black italic tracking-tighter uppercase drop-shadow-md pb-2 text-transparent bg-clip-text bg-linear-to-r from-red-500 to-rose-600">
                OBS Director Console
            </h1>
            <p class="text-slate-400 font-bold uppercase tracking-[0.2em] text-sm mt-3 border border-slate-800 bg-slate-900 rounded-lg py-2 inline-block px-4">
                Active User Environment: <span class="text-slate-100">{{ $user->name }}</span>
            </p>
        </div>
        
        @if(session('status'))
            <div class="w-full bg-emerald-500/10 text-emerald-400 p-4 rounded-xl mb-8 border border-emerald-500/20 text-center font-bold tracking-wide shadow-[0_0_15px_rgba(16,185,129,0.15)] animate-pulse">
                {{ session('status') }}
            </div>
        @endif

        <div class="w-full grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- POST MATCH BTN -->
            <form action="{{ route('screens.switchview', ['user_id' => $user->id]) }}" method="POST" class="h-full">
                @csrf
                <input type="hidden" name="view" value="postmatch">
                <button type="submit" class="w-full h-40 flex flex-col items-center justify-center gap-3 bg-slate-900/80 hover:bg-yellow-900/40 hover:border-yellow-500 border border-slate-800 rounded-2xl text-yellow-500 transition-all shadow-[0_4px_20px_rgba(0,0,0,0.5)] hover:shadow-[0_0_30px_rgba(234,179,8,0.2)] active:scale-95 group">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-10 h-10 group-hover:scale-110 transition-transform">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                    </svg>
                    <span class="text-xl font-black uppercase tracking-widest text-center">Post Match</span>
                </button>
            </form>
            
            <!-- OVERALL RANKING BTN -->
            <form action="{{ route('screens.switchview', ['user_id' => $user->id]) }}" method="POST" class="h-full">
                @csrf
                <input type="hidden" name="view" value="overallranking">
                <button type="submit" class="w-full h-40 flex flex-col items-center justify-center gap-3 bg-slate-900/80 hover:bg-orange-900/40 hover:border-orange-500 border border-slate-800 rounded-2xl text-orange-400 transition-all shadow-[0_4px_20px_rgba(0,0,0,0.5)] hover:shadow-[0_0_30px_rgba(249,115,22,0.2)] active:scale-95 group">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-10 h-10 group-hover:scale-110 transition-transform">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z" />
                    </svg>
                    <span class="text-xl font-black uppercase tracking-widest text-center">Overall Rank</span>
                </button>
            </form>

            <!-- CLEAR SCREEN BTN -->
            <form action="{{ route('screens.switchview', ['user_id' => $user->id]) }}" method="POST" class="h-full">
                @csrf
                <input type="hidden" name="view" value="empty">
                <button type="submit" class="w-full h-40 flex flex-col items-center justify-center gap-3 bg-slate-900/80 hover:bg-rose-900/40 hover:border-rose-500 border border-slate-800 rounded-2xl text-slate-300 hover:text-rose-400 transition-all shadow-[0_4px_20px_rgba(0,0,0,0.5)] hover:shadow-[0_0_30px_rgba(244,63,94,0.2)] active:scale-95 group">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-10 h-10 group-hover:scale-110 transition-transform">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                    </svg>
                    <span class="text-xl font-black uppercase tracking-widest text-center">Empty Screen</span>
                </button>
            </form>
        </div>

        <!-- VISIBILITY TOGGLES -->
        <div class="w-full grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
            <form action="{{ route('screens.togglevisibility', ['user_id' => $user->id]) }}" method="POST">
                @csrf
                <input type="hidden" name="visible" value="1">
                <button type="submit" class="w-full py-6 flex items-center justify-center gap-3 bg-emerald-900/20 hover:bg-emerald-900/40 border border-emerald-500/30 hover:border-emerald-500 rounded-2xl text-emerald-400 transition-all font-black uppercase tracking-widest active:scale-95">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Show Active Match
                </button>
            </form>

            <form action="{{ route('screens.togglevisibility', ['user_id' => $user->id]) }}" method="POST">
                @csrf
                <input type="hidden" name="visible" value="0">
                <button type="submit" class="w-full py-6 flex items-center justify-center gap-3 bg-rose-900/20 hover:bg-rose-900/40 border border-rose-500/30 hover:border-rose-500 rounded-2xl text-rose-400 transition-all font-black uppercase tracking-widest active:scale-95">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                    </svg>
                    Hide Active Match
                </button>
            </form>
        </div>

        <!-- LIVE STATS CONTROL LINK -->
        <a href="{{ route('screens.statscontrol', ['user_id' => $user->id]) }}" 
           class="w-full mt-6 py-5 flex items-center justify-center gap-3 bg-amber-900/20 hover:bg-amber-900/40 border-2 border-amber-500/30 hover:border-amber-500 rounded-2xl text-amber-400 transition-all font-black uppercase tracking-widest active:scale-95 text-lg shadow-[0_0_30px_rgba(245,158,11,0.1)] hover:shadow-[0_0_40px_rgba(245,158,11,0.2)]">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-7 h-7">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-9.75 0h9.75"/>
            </svg>
            Open Live Stats Control
        </a>

        <div class="mt-12 bg-slate-900/60 p-6 rounded-2xl border border-slate-800/50 w-full shadow-lg">
            <h3 class="text-slate-400 text-xs font-bold uppercase tracking-widest mb-2 border-b border-slate-800 pb-2">Director Instructions</h3>
            <ul class="text-slate-500 text-sm list-disc pl-4 space-y-2">
                <li><strong class="text-slate-300 uppercase text-[10px] tracking-wider">Main Master:</strong> Load <code class="text-rose-400 bg-slate-950 px-2 py-0.5 rounded">{{ route('screens.obsmaster', ['user_id' => $user->id]) }}</code> for Post-Match & Standings.</li>
                <li><strong class="text-slate-300 uppercase text-[10px] tracking-wider">Elimination HUD:</strong> Load <code class="text-red-400 bg-slate-950 px-2 py-0.5 rounded">{{ route('screens.teamelimination', ['user_id' => $user->id]) }}</code> for top-center squad notifications.</li>
                <li><strong class="text-slate-300 uppercase text-[10px] tracking-wider">Live Ranking HUD:</strong> Load <code class="text-yellow-400 bg-slate-950 px-2 py-0.5 rounded">{{ route('screens.activematch', ['user_id' => $user->id]) }}</code> for the left-side scrolling leaderboard.</li>
                <li><strong class="text-slate-300 uppercase text-[10px] tracking-wider">Map Screen:</strong> Load <code class="text-blue-400 bg-slate-950 px-2 py-0.5 rounded">{{ route('screens.mapscreen', ['user_id' => $user->id]) }}</code> for the 1080x1080 map view with teams.</li>
                <li>Set the Browser Source dimensions accurately. All views have transparent layers so your game source will shine through.</li>
            </ul>
        </div>
    </div>
</body>
</html>
