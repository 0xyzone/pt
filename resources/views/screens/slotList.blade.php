<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Slot List — {{ $tournament ? $tournament->name : 'Tournament' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{-- High-End Typography & Theme Imports --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700;900&family=Rajdhani:wght@600;700;900&display=swap" rel="stylesheet">
    
    <style>
        .font-esports {
            font-family: 'Rajdhani', sans-serif;
        }
        .font-body-esports {
            font-family: 'Inter', sans-serif;
        }

        .pubg-skew {
            transform: none;
        }
        .pubg-unskew {
            transform: none;
        }

        .glass-panel {
            background: rgba(8, 12, 24, 0.85);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(250, 204, 21, 0.25);
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.8);
        }

        .slot-row {
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            border-left: 4px solid transparent;
        }
        .slot-row:hover {
            transform: translateX(6px);
            background: rgba(250, 204, 21, 0.05);
            border-left-color: #f59e0b;
        }

        /* Tech corner brackets */
        .bracket-corner::before,
        .bracket-corner::after {
            content: '';
            position: absolute;
            width: 12px;
            height: 12px;
            border-color: #f59e0b;
            border-style: solid;
            pointer-events: none;
        }
        .bracket-corner::before { top: -2px; left: -2px; border-width: 2.5px 0 0 2.5px; }
        .bracket-corner::after { top: -2px; right: -2px; border-width: 2.5px 2.5px 0 0; }
    </style>
</head>
<body class="bg-transparent text-slate-100 min-h-screen p-8 overflow-hidden font-esports select-none" style="width: 1920px; height: 1080px; position: relative;">

    {{-- High-tech background grid --}}
    <div class="absolute inset-0 z-0 pointer-events-none opacity-20">
        <div class="absolute inset-0 bg-[linear-gradient(rgba(255,255,255,0.015)_1px,transparent_1px),linear-gradient(90deg,rgba(255,255,255,0.015)_1px,transparent_1px)] bg-size-[45px_45px]"></div>
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_center,transparent_30%,#020617_90%)]"></div>
        <div class="absolute top-[20%] left-[30%] w-125 h-125 bg-amber-500/5 blur-[120px] rounded-full"></div>
    </div>

    <div class="max-w-4xl mx-auto relative z-10 flex flex-col h-full justify-between py-12">
        
        {{-- Header --}}
        <div class="text-center mb-8 relative">
            <div class="inline-flex items-center gap-3 px-4 py-1 rounded bg-amber-500/10 border border-amber-500/30 text-amber-400 text-sm font-bold tracking-[0.25em] uppercase mb-4 pubg-skew">
                <span class="pubg-unskew font-bold">Lobby Slots</span>
            </div>
            
            <h1 class="text-5xl md:text-6xl font-black italic tracking-tighter uppercase leading-none drop-shadow-2xl text-slate-100">
                Lobby Slot List
            </h1>
            <p class="text-slate-400 text-xl font-bold uppercase tracking-widest mt-2 block">
                {{ $tournament ? $tournament->name : 'No Active Tournament' }}
            </p>
        </div>

        @if($tournament && $teams->count() > 0)
            <div class="glass-panel rounded-xl overflow-hidden shadow-2xl flex-1 flex flex-col justify-start relative">
                
                {{-- Table Header --}}
                <div class="pubg-skew bg-slate-950/95 border-b border-yellow-400/40 px-8 py-3 flex items-center gap-6 text-sm font-black uppercase tracking-widest text-slate-400 shadow-md">
                    <div class="pubg-unskew flex items-center w-full gap-6">
                        <span class="w-16 text-center text-yellow-500 font-mono">Slot</span>
                        <span class="flex-1 text-left text-slate-200">Participating Team</span>
                    </div>
                </div>

                {{-- Teams List --}}
                <div class="divide-y divide-slate-800/40 overflow-y-auto flex-1 max-h-155 pr-2">
                    @foreach($teams as $index => $team)
                    <div class="slot-row flex items-center gap-6 px-8 py-3">
                        {{-- Slot Number starts from 2 --}}
                        <div class="w-16 text-center font-black text-3xl text-yellow-500 font-mono italic">
                            {{ str_pad($index + 2, 2, '0', STR_PAD_LEFT) }}
                        </div>
                        
                        {{-- Team Info --}}
                        <div class="flex items-center gap-5">
                            <div class="w-11 h-11 bg-slate-950/80 p-1 border border-slate-800 rounded shrink-0 flex items-center justify-center">
                                <img src="{{ $team->logo_image ? asset('storage/' . $team->logo_image) : asset('img/defult_team_logo.png') }}"
                                     alt="{{ $team->name }}"
                                     class="w-full h-full object-contain filter drop-shadow-md">
                            </div>
                            <div class="flex flex-col text-left">
                                <span class="font-black text-2xl uppercase tracking-tight leading-none text-white">{{ $team->name }}</span>
                                @if($team->short_name)
                                    <span class="text-xs text-slate-500 font-semibold tracking-wider font-body-esports mt-1 uppercase leading-none">{{ $team->short_name }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        @else
            <div class="text-center py-20 glass-panel rounded-xl border border-slate-800 border-dashed">
                <h3 class="text-2xl font-black text-slate-400 uppercase tracking-widest">No Teams Registered</h3>
                <p class="text-slate-500 mt-2 font-body-esports">Assign teams under the Tournament dashboard to populate slots.</p>
            </div>
        @endif
        
        <div class="mt-8 text-center text-xs font-bold uppercase tracking-[0.4em] text-slate-650 pt-4 border-t border-slate-900/60">
            Official Tournament Stream System // lobby_slot_hud
        </div>
    </div>

    <script type="module">
        document.addEventListener("DOMContentLoaded", function () {
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
