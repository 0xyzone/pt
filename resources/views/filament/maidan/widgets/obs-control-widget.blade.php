<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            <div class="flex items-center gap-2">
                <x-filament::icon
                     icon="heroicon-m-video-camera"
                     class="h-5 w-5 text-primary-500"
                />
                <span class="text-xl font-bold tracking-tight">OBS Broadcast Hub</span>
            </div>
        </x-slot>

        {{--
            Fully inline Alpine.js x-data scope. This eliminates all script tag timing
            and Livewire re-render issues, and uses single quotes exclusively to prevent
            HTML attribute quote collisions.
        --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mt-2"
             x-data="{
                copyLink(url, button) {
                    const doCopy = () => {
                        if (navigator.clipboard && window.isSecureContext) {
                            return navigator.clipboard.writeText(url);
                        }
                        return new Promise((resolve, reject) => {
                            const ta = document.createElement('textarea');
                            ta.value = url;
                            ta.style.cssText = 'position:fixed;top:0;left:0;opacity:0;pointer-events:none;';
                            document.body.appendChild(ta);
                            ta.focus();
                            ta.select();
                            try {
                                document.execCommand('copy') ? resolve() : reject(new Error('failed'));
                            } catch (e) {
                                reject(e);
                            } finally {
                                document.body.removeChild(ta);
                            }
                        });
                    };

                    doCopy().then(() => {
                        const label = button.querySelector('.obs-copy-label');
                        if (label) label.textContent = 'Copied!';
                        button.classList.add('!bg-emerald-500', '!text-white', '!border-transparent');

                        setTimeout(() => {
                            if (label) label.textContent = 'Copy OBS Link';
                            button.classList.remove('!bg-emerald-500', '!text-white', '!border-transparent');
                        }, 1600);

                        this.showTooltip(button, 'Link Copied!');
                    }).catch(err => {
                        console.error('OBS widget copy failed:', err);
                        this.showTooltip(button, 'Copy Failed!');
                    });
                },

                showTooltip(element, text) {
                    const tip = document.createElement('div');
                    tip.textContent = text;

                    Object.assign(tip.style, {
                        position:      'fixed',
                        background:    '#10b981',
                        color:         '#fff',
                        padding:       '6px 14px',
                        borderRadius:  '8px',
                        fontSize:      '12px',
                        fontWeight:    '700',
                        letterSpacing: '0.03em',
                        pointerEvents: 'none',
                        whiteSpace:    'nowrap',
                        zIndex:        '2147483647',
                        boxShadow:     '0 4px 16px rgba(0,0,0,0.35)',
                        opacity:       '0',
                        transform:     'translateY(8px) scale(0.92)',
                        transition:    'opacity 0.22s ease, transform 0.32s cubic-bezier(0.22,1,0.36,1)'
                    });

                    document.body.appendChild(tip);

                    const rect = element.getBoundingClientRect();
                    const tipRect = tip.getBoundingClientRect();
                    tip.style.left = (rect.left + rect.width / 2 - tipRect.width / 2) + 'px';
                    tip.style.top = (rect.top - tipRect.height - 8) + 'px';

                    requestAnimationFrame(() => {
                        tip.style.opacity = '1';
                        tip.style.transform = 'translateY(0) scale(1)';
                    });

                    setTimeout(() => {
                        tip.style.opacity = '0';
                        tip.style.transform = 'translateY(-10px) scale(0.95)';
                        setTimeout(() => tip.parentNode && tip.parentNode.removeChild(tip), 350);
                    }, 1400);
                }
             }">

            @php
                $links = $this->getLinks();
                $controls = array_filter($links, fn($l) => $l['category'] === 'control');
                $preMatch = array_filter($links, fn($l) => $l['category'] === 'pre_match');
                $postMatch = array_filter($links, fn($l) => $l['category'] === 'post_match');
            @endphp

            <div class="col-span-full grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Group 1: Consoles & Control -->
                <div class="bg-red-50/40 dark:bg-red-950/10 p-4 rounded-xl border border-red-100 dark:border-red-900/30 flex flex-col gap-3.5 shadow-sm">
                    <h3 class="text-xs font-black uppercase tracking-wider text-red-600 dark:text-red-400 flex items-center gap-2 pb-2 border-b border-red-100 dark:border-red-900/20">
                        <x-filament::icon icon="heroicon-m-adjustments-horizontal" class="h-4.5 w-4.5 text-red-500" />
                        Consoles & Master
                    </h3>
                    <div class="flex flex-col gap-2.5">
                        @foreach($controls as $link)
                            <div class="group flex items-center justify-between p-2.5 rounded-lg bg-white dark:bg-gray-900 border border-gray-150 dark:border-gray-800/60 hover:border-primary-500 dark:hover:border-primary-500 hover:shadow-xs transition-all duration-150 gap-3"
                                 title="{{ $link['description'] }}">
                                <div class="flex items-center gap-2.5 min-w-0">
                                    <div @class([
                                        'p-1.5 rounded-md shrink-0',
                                        match($link['color']) {
                                            'danger'  => 'bg-danger-500/10 text-danger-600 dark:text-danger-400',
                                            'primary' => 'bg-primary-500/10 text-primary-600 dark:text-primary-400',
                                            'warning' => 'bg-warning-500/10 text-warning-600 dark:text-warning-400',
                                            'success' => 'bg-success-500/10 text-success-600 dark:text-success-400',
                                            'info'    => 'bg-info-500/10 text-info-600 dark:text-info-400',
                                            default   => 'bg-gray-500/10 text-gray-600 dark:text-gray-400',
                                        }
                                    ])>
                                        <x-filament::icon icon="{{ $link['icon'] }}" class="h-4.5 w-4.5" />
                                    </div>
                                    <div class="min-w-0">
                                        <div class="flex items-center gap-1.5">
                                            <span class="text-xs font-bold text-gray-900 dark:text-white truncate block group-hover:text-primary-500 transition-colors">
                                                {{ $link['name'] }}
                                            </span>
                                            @if($link['is_main'] ?? false)
                                                <span class="flex h-1.5 w-1.5 relative shrink-0">
                                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-danger-400 opacity-75"></span>
                                                    <span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-danger-500"></span>
                                                </span>
                                            @endif
                                        </div>
                                        <span class="text-[9.5px] text-gray-500 dark:text-gray-400 block truncate mt-0.5 max-w-[180px]">
                                            {{ $link['description'] }}
                                        </span>
                                    </div>
                                </div>

                                <div class="flex items-center gap-1.5 shrink-0">
                                    @if(!($link['is_main'] ?? false))
                                        <button
                                            type="button"
                                            x-on:click.stop.prevent="copyLink('{{ $link['url'] }}', $el)"
                                            class="obs-copy-btn p-1.5 rounded bg-gray-50 dark:bg-gray-800 hover:bg-emerald-500 dark:hover:bg-emerald-600 text-gray-500 dark:text-gray-400 hover:text-white border border-gray-200 dark:border-gray-700/60 hover:border-transparent transition-all duration-150 flex items-center justify-center shrink-0"
                                            title="Copy OBS Link"
                                        >
                                            <x-filament::icon icon="heroicon-o-clipboard" class="h-3.5 w-3.5 shrink-0" />
                                        </button>
                                    @endif
                                    <a
                                        href="{{ $link['url'] }}"
                                        target="_blank"
                                        class="p-1.5 rounded bg-gray-50 dark:bg-gray-800 hover:bg-primary-500 dark:hover:bg-primary-600 text-gray-500 dark:text-gray-400 hover:text-white border border-gray-200 dark:border-gray-700/60 hover:border-transparent transition-all duration-150"
                                        title="Open Overlay"
                                    >
                                        <x-filament::icon icon="heroicon-m-arrow-top-right-on-square" class="h-3.5 w-3.5" />
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Group 2: Pre-Match Setup -->
                <div class="bg-emerald-50/40 dark:bg-emerald-950/10 p-4 rounded-xl border border-emerald-100 dark:border-emerald-900/30 flex flex-col gap-3.5 shadow-sm">
                    <h3 class="text-xs font-black uppercase tracking-wider text-emerald-600 dark:text-emerald-400 flex items-center gap-2 pb-2 border-b border-emerald-100 dark:border-emerald-900/20">
                        <x-filament::icon icon="heroicon-m-calendar" class="h-4.5 w-4.5 text-emerald-500" />
                        Pre-Match & Setup
                    </h3>
                    <div class="flex flex-col gap-2.5">
                        @foreach($preMatch as $link)
                            <div class="group flex items-center justify-between p-2.5 rounded-lg bg-white dark:bg-gray-900 border border-gray-150 dark:border-gray-800/60 hover:border-primary-500 dark:hover:border-primary-500 hover:shadow-xs transition-all duration-150 gap-3"
                                 title="{{ $link['description'] }}">
                                <div class="flex items-center gap-2.5 min-w-0">
                                    <div @class([
                                        'p-1.5 rounded-md shrink-0',
                                        match($link['color']) {
                                            'danger'  => 'bg-danger-500/10 text-danger-600 dark:text-danger-400',
                                            'primary' => 'bg-primary-500/10 text-primary-600 dark:text-primary-400',
                                            'warning' => 'bg-warning-500/10 text-warning-600 dark:text-warning-400',
                                            'success' => 'bg-success-500/10 text-success-600 dark:text-success-400',
                                            'info'    => 'bg-info-500/10 text-info-600 dark:text-info-400',
                                            default   => 'bg-gray-500/10 text-gray-600 dark:text-gray-400',
                                        }
                                    ])>
                                        <x-filament::icon icon="{{ $link['icon'] }}" class="h-4.5 w-4.5" />
                                    </div>
                                    <div class="min-w-0">
                                        <span class="text-xs font-bold text-gray-900 dark:text-white truncate block group-hover:text-primary-500 transition-colors">
                                            {{ $link['name'] }}
                                        </span>
                                        <span class="text-[9.5px] text-gray-500 dark:text-gray-400 block truncate mt-0.5 max-w-[180px]">
                                            {{ $link['description'] }}
                                        </span>
                                    </div>
                                </div>

                                <div class="flex items-center gap-1.5 shrink-0">
                                    @if(!($link['is_main'] ?? false))
                                        <button
                                            type="button"
                                            x-on:click.stop.prevent="copyLink('{{ $link['url'] }}', $el)"
                                            class="obs-copy-btn p-1.5 rounded bg-gray-50 dark:bg-gray-800 hover:bg-emerald-500 dark:hover:bg-emerald-600 text-gray-500 dark:text-gray-400 hover:text-white border border-gray-200 dark:border-gray-700/60 hover:border-transparent transition-all duration-150 flex items-center justify-center shrink-0"
                                            title="Copy OBS Link"
                                        >
                                            <x-filament::icon icon="heroicon-o-clipboard" class="h-3.5 w-3.5 shrink-0" />
                                        </button>
                                    @endif
                                    <a
                                        href="{{ $link['url'] }}"
                                        target="_blank"
                                        class="p-1.5 rounded bg-gray-50 dark:bg-gray-800 hover:bg-primary-500 dark:hover:bg-primary-600 text-gray-500 dark:text-gray-400 hover:text-white border border-gray-200 dark:border-gray-700/60 hover:border-transparent transition-all duration-150"
                                        title="Open Overlay"
                                    >
                                        <x-filament::icon icon="heroicon-m-arrow-top-right-on-square" class="h-3.5 w-3.5" />
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Group 3: Post-Match & Results -->
                <div class="bg-amber-50/40 dark:bg-amber-950/10 p-4 rounded-xl border border-amber-100 dark:border-amber-900/30 flex flex-col gap-3.5 shadow-sm">
                    <h3 class="text-xs font-black uppercase tracking-wider text-amber-600 dark:text-amber-400 flex items-center gap-2 pb-2 border-b border-amber-100 dark:border-amber-900/20">
                        <x-filament::icon icon="heroicon-m-trophy" class="h-4.5 w-4.5 text-amber-500" />
                        Post-Match & Results
                    </h3>
                    <div class="flex flex-col gap-2.5">
                        @foreach($postMatch as $link)
                            <div class="group flex items-center justify-between p-2.5 rounded-lg bg-white dark:bg-gray-900 border border-gray-150 dark:border-gray-800/60 hover:border-primary-500 dark:hover:border-primary-500 hover:shadow-xs transition-all duration-150 gap-3"
                                 title="{{ $link['description'] }}">
                                <div class="flex items-center gap-2.5 min-w-0">
                                    <div @class([
                                        'p-1.5 rounded-md shrink-0',
                                        match($link['color']) {
                                            'danger'  => 'bg-danger-500/10 text-danger-600 dark:text-danger-400',
                                            'rose'    => 'bg-danger-500/10 text-danger-600 dark:text-danger-400',
                                            'primary' => 'bg-primary-500/10 text-primary-600 dark:text-primary-400',
                                            'warning' => 'bg-warning-500/10 text-warning-600 dark:text-warning-400',
                                            'success' => 'bg-success-500/10 text-success-600 dark:text-success-400',
                                            'info'    => 'bg-info-500/10 text-info-600 dark:text-info-400',
                                            default   => 'bg-gray-500/10 text-gray-600 dark:text-gray-400',
                                        }
                                    ])>
                                        <x-filament::icon icon="{{ $link['icon'] }}" class="h-4.5 w-4.5" />
                                    </div>
                                    <div class="min-w-0">
                                        <span class="text-xs font-bold text-gray-900 dark:text-white truncate block group-hover:text-primary-500 transition-colors">
                                            {{ $link['name'] }}
                                        </span>
                                        <span class="text-[9.5px] text-gray-500 dark:text-gray-400 block truncate mt-0.5 max-w-[180px]">
                                            {{ $link['description'] }}
                                        </span>
                                    </div>
                                </div>

                                <div class="flex items-center gap-1.5 shrink-0">
                                    @if(!($link['is_main'] ?? false))
                                        <button
                                            type="button"
                                            x-on:click.stop.prevent="copyLink('{{ $link['url'] }}', $el)"
                                            class="obs-copy-btn p-1.5 rounded bg-gray-50 dark:bg-gray-800 hover:bg-emerald-500 dark:hover:bg-emerald-600 text-gray-500 dark:text-gray-400 hover:text-white border border-gray-200 dark:border-gray-700/60 hover:border-transparent transition-all duration-150 flex items-center justify-center shrink-0"
                                            title="Copy OBS Link"
                                        >
                                            <x-filament::icon icon="heroicon-o-clipboard" class="h-3.5 w-3.5 shrink-0" />
                                        </button>
                                    @endif
                                    <a
                                        href="{{ $link['url'] }}"
                                        target="_blank"
                                        class="p-1.5 rounded bg-gray-50 dark:bg-gray-800 hover:bg-primary-500 dark:hover:bg-primary-600 text-gray-500 dark:text-gray-400 hover:text-white border border-gray-200 dark:border-gray-700/60 hover:border-transparent transition-all duration-150"
                                        title="Open Overlay"
                                    >
                                        <x-filament::icon icon="heroicon-m-arrow-top-right-on-square" class="h-3.5 w-3.5" />
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <x-slot name="footer">
            <div class="text-xs text-gray-500 dark:text-gray-400 text-center italic">
                Tip: Copy the OBS link and paste it into OBS Studio as a Browser Source set to 1920x1080 resolution.
            </div>
        </x-slot>
    </x-filament::section>
</x-filament-widgets::widget>
