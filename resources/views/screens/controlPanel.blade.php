<x-base>
    <!-- Background styling for control panel specifically, distinct from OBS overlay -->
    <div class="min-h-screen bg-slate-950 p-6 md:p-12 font-sans text-slate-100 flex flex-col items-center max-w-4xl mx-auto">
        
        <div class="text-center w-full mb-10">
            <h1 class="text-4xl md:text-5xl font-black italic tracking-tighter uppercase drop-shadow-md pb-2 text-transparent bg-clip-text bg-gradient-to-r from-red-500 to-rose-600">
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
                <button type="submit" class="w-full h-40 flex flex-col items-center justify-center gap-3 bg-slate-900/80 hover:bg-cyan-900/40 hover:border-cyan-500 border border-slate-800 rounded-2xl text-cyan-400 transition-all shadow-[0_4px_20px_rgba(0,0,0,0.5)] hover:shadow-[0_0_30px_rgba(6,182,212,0.2)] active:scale-95 group">
                    <svg xmlns="http://www.w3.org/-2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-10 h-10 group-hover:scale-110 transition-transform">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                    </svg>
                    <span class="text-xl font-black uppercase tracking-widest text-center">Post Match</span>
                </button>
            </form>
            
            <!-- OVERALL RANKING BTN -->
            <form action="{{ route('screens.switchview', ['user_id' => $user->id]) }}" method="POST" class="h-full">
                @csrf
                <input type="hidden" name="view" value="overallranking">
                <button type="submit" class="w-full h-40 flex flex-col items-center justify-center gap-3 bg-slate-900/80 hover:bg-purple-900/40 hover:border-purple-500 border border-slate-800 rounded-2xl text-purple-400 transition-all shadow-[0_4px_20px_rgba(0,0,0,0.5)] hover:shadow-[0_0_30px_rgba(168,85,247,0.2)] active:scale-95 group">
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

        <div class="mt-12 bg-slate-900/60 p-6 rounded-2xl border border-slate-800/50 w-full shadow-lg">
            <h3 class="text-slate-400 text-xs font-bold uppercase tracking-widest mb-2 border-b border-slate-800 pb-2">Director Instructions</h3>
            <ul class="text-slate-500 text-sm list-disc pl-4 space-y-1">
                <li>Load <code class="text-rose-400 bg-slate-950 px-2 py-0.5 rounded">/{{$user->id}}/screens/obs-master</code> directly into OBS as a Browser Source.</li>
                <li>Set the Browser Source dimensions accurately, and use the Control Panel above to switch layouts live.</li>
                <li>Because the views have transparent layers, your OBS background source (below the web browser source) will shine through.</li>
                <li>Live calculations dynamically update instantly even while selected via WebSockets.</li>
            </ul>
        </div>
    </div>
</x-base>
