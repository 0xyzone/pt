<div class="flex items-center select-none py-1">
    <div class="inline-flex items-center gap-2 px-2.5 py-1 rounded-full bg-gray-500/5 dark:bg-white/5 border border-gray-200 dark:border-gray-800 backdrop-blur-sm transition-all duration-300 hover:scale-[1.02] hover:border-primary-500/30 dark:hover:border-primary-400/30 hover:bg-gray-500/10 dark:hover:bg-white/10 shadow-xs">
        <div class="relative flex h-1.5 w-1.5 shrink-0">
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 dark:bg-emerald-300 opacity-75"></span>
            <span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-emerald-500 dark:bg-emerald-400"></span>
        </div>
        <span class="text-[9.5px] sm:text-[10px] font-bold font-mono tracking-wider text-gray-500 dark:text-gray-400 flex items-center gap-1 leading-none">
            <span class="text-primary-600 dark:text-primary-400 uppercase text-[8.5px] tracking-widest font-sans font-extrabold mr-0.5">Build</span>
            v{{ $systemVersion }}
        </span>
    </div>
</div>
