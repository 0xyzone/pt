<x-base>
    <table class="table-auto border-collapse border border-neutral-300">
        <thead>
            <!-- Title Row -->
            <tr class="bg-gray-500">
                <th colspan="3" class="text-2xl font-bold text-center uppercase text-white">
                    <p>{{ $activeMatch->name }}</p>
                </th>
            </tr>
            <!-- Header Row -->
            <tr class="bg-black text-white font-bold text-center">
                <th class="text-right pr-3 w-4/12 py-2">Team</th>
                <th class="w-1/6 py-2">Alive</th>
                <th class="w-1/12 py-2">Points</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($activeMatch->matchStats->sortByDesc(['points']) as $match)
            @php
            $alive = $match->alive;
            if ($alive === 0) {
            $bgColor = "color-mix(in oklab, var(--color-red-600) /* oklch(57.7% 0.245 27.325) */ 40%";
            } else {
            $bgColor = "white";
            }
            @endphp
            <tr class="text-center" style="background-color: '{{ $bgColor }}';">
                <td class="uppercase pr-3 font-bold border bg-red-600/40 border-neutral-300 py-1.5 relative overflow-hidden">
                    @if ($match->tournamentTeam->logo_image)
                    <img src="{{ asset('storage/' . $match->tournamentTeam->logo_image) }}" alt="{{ $activeMatch->tournamentTeam?->name . ' logo' }}" class="w-32 rotate-4 -top-10 -left-10 object-cover absolute mask-r-from-20% mask-r-to-80%">
                    @else
                    <img src="{{ asset('img/defult_team_logo.png') }}" alt="" class="w-32 rotate-4 -top-10 -left-10 object-cover absolute mask-r-from-20% mask-r-to-80%">
                    @endif
                    <div class="flex gap-2 items-center justify-end">
                        <p class="text-2xl">{{ $match->tournamentTeam->short_name }}</p>
                        @if ($match->tournamentTeam->logo_image)
                        <img src="{{ asset('storage/' . $match->tournamentTeam->logo_image) }}" alt="{{ $activeMatch->tournamentTeam?->name . ' logo' }}" class="w-6 h-6 object-cover">
                        @else
                        <img src="{{ asset('img/defult_team_logo.png') }}" alt="" class="w-6 h-6 object-cover">
                        @endif
                    </div>
                </td>
                <td class="align-middle border border-neutral-300">
                    <div class="flex items-center justify-center">
                        @for($i = 0; $i < $match->alive; $i++)
                            <span class="inline-block w-2 h-6 bg-green-500 rounded-sm mr-1"></span>
                            @endfor
                            @for($i = 0; $i < 4 - $match->alive; $i++)
                                <span class="inline-block w-2 h-6 bg-gray-400 rounded-sm mr-1"></span>
                                @endfor
                    </div>
                </td>
                <td class="font-bold border border-neutral-300 text-2xl">
                    {{ $match->points }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</x-base>
