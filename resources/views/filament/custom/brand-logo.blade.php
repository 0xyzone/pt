<div class="flex items-center gap-2.5">
    @php
        $logoExists = file_exists(public_path('img/logo.png'));
    @endphp
    @if ($logoExists)
        <img src="{{ asset('img/logo.png') }}" alt="{{ config('app.name') }}" class="h-9 w-auto select-none dark:brightness-100" style="max-height: 2.25rem;">
        <span class="text-[9.5px] font-bold font-mono px-1.5 py-0.5 rounded-md bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-500/20 leading-none select-none tracking-wider">
            v{{ $systemVersion }}
        </span>
    @else
        <span class="font-extrabold text-xl tracking-tight text-gray-900 dark:text-white select-none">
            {{ config('app.name', 'BroadKaster') }}
        </span>
        <span class="text-[9.5px] font-bold font-mono px-1.5 py-0.5 rounded-md bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-500/20 leading-none select-none tracking-wider">
            v{{ $systemVersion }}
        </span>
    @endif
</div>
