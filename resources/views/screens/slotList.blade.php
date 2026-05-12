<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Slot List — {{ $tournament ? $tournament->name : 'Tournament' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', system-ui, sans-serif; }
        
        .slot-row {
            transition: all 0.2s ease;
        }
        .slot-row:hover {
            transform: translateX(4px);
            background: rgba(255, 255, 255, 0.05);
        }
    </style>
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen p-4 md:p-8">

    <div class="max-w-4xl mx-auto">
        {{-- Header --}}
        <div class="mb-10 text-center">
            @if($tournament && $tournament->logo_image)
                <img src="{{ asset('storage/' . $tournament->logo_image) }}" alt="Tournament Logo" class="w-24 h-24 mx-auto mb-4 object-contain">
            @endif
            <h1 class="text-4xl md:text-5xl font-black italic tracking-tighter uppercase text-transparent bg-clip-text bg-gradient-to-r from-yellow-400 to-amber-500">
                Slot List
            </h1>
            <p class="text-slate-400 text-lg font-bold uppercase tracking-widest mt-2">
                {{ $tournament ? $tournament->name : 'No Active Tournament' }}
            </p>
        </div>

        @if($tournament && $teams->count() > 0)
            <div class="bg-slate-900/80 rounded-2xl border border-slate-800 overflow-hidden shadow-2xl">
                {{-- Table Header --}}
                <div class="grid grid-cols-[80px_1fr] md:grid-cols-[100px_1fr] items-center gap-4 px-6 py-4 bg-slate-800/80 border-b border-slate-700 text-sm font-black uppercase tracking-[0.15em] text-slate-400">
                    <span class="text-center text-yellow-500">Slot #</span>
                    <span>Team Name</span>
                </div>

                {{-- Teams List --}}
                <div class="divide-y divide-slate-800/50">
                    @foreach($teams as $index => $team)
                    <div class="slot-row grid grid-cols-[80px_1fr] md:grid-cols-[100px_1fr] items-center gap-4 px-6 py-4">
                        {{-- Slot Number starts from 2 --}}
                        <div class="text-center font-black text-2xl text-slate-300 tabular-nums">
                            {{ $index + 2 }}
                        </div>
                        
                        {{-- Team Info --}}
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-lg bg-slate-800 border border-slate-700 flex items-center justify-center p-1 overflow-hidden shadow-inner flex-shrink-0">
                                <img src="{{ $team->logo_image ? asset('storage/' . $team->logo_image) : asset('img/defult_team_logo.png') }}"
                                     alt="{{ $team->name }}"
                                     class="w-full h-full object-contain">
                            </div>
                            <div class="flex flex-col">
                                <span class="font-black text-lg md:text-xl uppercase tracking-wide leading-tight text-white">{{ $team->name }}</span>
                                @if($team->short_name)
                                    <span class="text-xs text-yellow-500 font-bold uppercase tracking-widest">{{ $team->short_name }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        @else
            <div class="text-center py-20 bg-slate-900/50 rounded-2xl border border-slate-800 border-dashed">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-16 h-16 mx-auto text-slate-600 mb-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                </svg>
                <h3 class="text-xl font-bold text-slate-400">No Teams Found</h3>
                <p class="text-slate-500 mt-2">There are currently no teams assigned to this tournament.</p>
            </div>
        @endif
        
        <div class="mt-8 text-center text-xs font-bold uppercase tracking-widest text-slate-600">
            Powered by Tournament Manager
        </div>
    </div>

    <script type="module">
        document.addEventListener("DOMContentLoaded", function () {
            // Use a fallback user ID if tournament isn't present, but usually it should be.
            const userId = '{{ $tournament ? $tournament->user_id : "" }}' || '{{ auth()->id() }}';
            if (userId) {
                Echo.channel('user-screens.' + userId)
                    .listen('.RefreshScreens', (e) => {
                        window.location.reload();
                    })
                    .listen('.TournamentMatchUpdated', (e) => {
                        window.location.reload();
                    });
            }
        });
    </script>
</body>
</html>
