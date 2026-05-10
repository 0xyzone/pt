<x-base>
    <div class="p-4 md:p-8 font-sans text-slate-100 selection:bg-cyan-500/30">
        <div class="relative overflow-hidden mb-8 rounded-2xl bg-slate-900/80 border border-slate-800 shadow-2xl">
            <div class="absolute -top-24 -right-24 w-96 h-96 bg-cyan-600/10 blur-[100px] rounded-full"></div>

            <div class="relative p-6 md:p-10 flex flex-col md:flex-row md:items-end justify-between gap-6">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-cyan-500/10 border border-cyan-500/20 text-cyan-400 text-xs font-bold tracking-widest uppercase mb-4">
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-cyan-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-cyan-500"></span>
                        </span>
                        Post Match Results
                    </div>
                    <h1 class="text-4xl md:text-6xl font-black italic tracking-tighter uppercase leading-none">
                        {{ $activeMatch->tournament->name }}
                        <span class="block text-transparent bg-clip-text bg-linear-to-r from-cyan-400 to-blue-600">
                            {{ $activeMatch->name }}
                        </span>
                    </h1>
                </div>

                <div class="flex items-center gap-4 text-right">
                    <div class="px-4 py-2 bg-slate-800/50 backdrop-blur-md border border-slate-700 rounded-xl">
                        <p class="text-[10px] uppercase tracking-[0.2em] text-slate-400 font-bold">Current Map</p>
                        <p class="text-xl font-black uppercase italic text-amber-400">{{ $activeMatch->map }}</p>
                    </div>
                </div>
            </div>
        </div>

        @php
            $allStats = $activeMatch->matchStats->sortByDesc('placement')->values();
            $leftColumn = $allStats->take(8);
            $rightColumn = $allStats->slice(8);
        @endphp

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            {{-- Left Side (1-10) --}}
            <div class="overflow-hidden rounded-2xl border border-slate-800 bg-slate-900/50 backdrop-blur-xl shadow-2xl">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-800/50 text-slate-400 border-b border-slate-800">
                            <th class="px-4 py-4 text-xs font-bold uppercase tracking-widest">Rank</th>
                            <th class="px-4 py-4 text-xs font-bold uppercase tracking-widest">Team Name</th>
                            <th class="px-4 py-4 text-xs font-bold uppercase tracking-widest text-center text-cyan-400">Total Pts</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/50">
                        @foreach ($leftColumn as $match)
                            @php $rank = $loop->iteration; @endphp
                            <tr class="group transition-all duration-300 hover:bg-slate-800/40 {{ $rank === 1 ? 'bg-amber-500/3' : '' }}">
                                <td class="px-4 py-4 font-black italic text-xl {{ $rank === 1 ? 'text-amber-500 text-4xl! font-bold' : 'text-slate-500' }}">#{{ $rank }}</td>
                                <td class="px-4 py-4">
                                    <div class="flex items-center gap-3">
                                        <img src="{{ $match->tournamentTeam->logo_image ? asset('storage/' . $match->tournamentTeam->logo_image) : asset('img/defult_team_logo.png') }}" class="w-12 aspect-square">
                                        <span class="font-black uppercase tracking-tight {{ $rank === 1 ? 'text-amber-400 text-2xl font-bold' : 'text-slate-100' }}">{{ $match->tournamentTeam->name }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-4 text-center font-black text-xl {{ $rank === 1 ? 'text-amber-400 text-4xl! font-bold' : 'text-cyan-400' }}">{{ $match->points }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Right Side (11+) --}}
            <div class="overflow-hidden rounded-2xl border border-slate-800 bg-slate-900/50 backdrop-blur-xl shadow-2xl">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-800/50 text-slate-400 border-b border-slate-800">
                            <th class="px-4 py-4 text-xs font-bold uppercase tracking-widest">Rank</th>
                            <th class="px-4 py-4 text-xs font-bold uppercase tracking-widest">Team Name</th>
                            <th class="px-4 py-4 text-xs font-bold uppercase tracking-widest text-center">Status</th>
                            <th class="px-4 py-4 text-xs font-bold uppercase tracking-widest text-right text-cyan-400">Total Pts</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/50">
                        @foreach ($rightColumn as $match)
                            @php $rank = $loop->iteration + 10; @endphp
                            <tr class="group transition-all duration-300 hover:bg-slate-800/40">
                                <td class="px-4 py-4 font-black italic text-xl text-slate-500">#{{ $rank }}</td>
                                <td class="px-4 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="h-8 w-8 rounded-lg bg-slate-800 border border-slate-700 flex items-center justify-center text-xs font-bold group-hover:border-cyan-500/50 transition-colors text-slate-300">
                                            {{ substr($match->tournamentTeam->name, 0, 1) }}
                                        </div>
                                        <span class="font-black uppercase tracking-tight text-slate-100 text-sm">{{ $match->tournamentTeam->name }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-4 text-center">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase border {{ $match->alive ? 'bg-green-500/10 text-green-500 border-green-500/20' : 'bg-rose-500/10 text-rose-500 border-rose-500/20 opacity-60' }}">
                                        {{ $match->alive ? 'Alive' : 'Out' }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 text-right font-black text-xl text-cyan-400">{{ $match->points }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-8 flex justify-between items-center opacity-30 px-2">
            <div class="h-px flex-1 bg-linear-to-r from-transparent via-slate-500 to-transparent"></div>
            <div class="px-4 text-[10px] font-bold uppercase tracking-[0.4em]">Official Match Report</div>
            <div class="h-px flex-1 bg-linear-to-r from-transparent via-slate-500 to-transparent"></div>
        </div>
    </div>
</x-base>