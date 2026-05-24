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

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mt-2">
            @foreach($this->getLinks() as $link)
                <a 
                    href="{{ $link['url'] }}" 
                    target="_blank"
                    @class([
                        'relative group flex flex-col gap-3 p-4 rounded-xl border transition-all duration-200 justify-between',
                        'bg-white dark:bg-gray-900 border-gray-200 dark:border-gray-800 hover:border-primary-500 dark:hover:border-primary-500 hover:shadow-lg',
                        'col-span-full md:col-span-1 lg:col-span-2 ring-2 ring-danger-500/20' => $link['is_main'] ?? false,
                    ])
                >
                    <div class="flex flex-col gap-2">
                        <div class="flex items-center justify-between">
                            <div @class([
                                'p-2 rounded-lg',
                                match($link['color']) {
                                    'danger' => 'bg-danger-55/10 dark:bg-danger-900/20 text-danger-600 dark:text-danger-400',
                                    'primary' => 'bg-primary-55/10 dark:bg-primary-900/20 text-primary-600 dark:text-primary-400',
                                    'warning' => 'bg-warning-55/10 dark:bg-warning-900/20 text-warning-600 dark:text-warning-400',
                                    'success' => 'bg-success-55/10 dark:bg-success-900/20 text-success-600 dark:text-success-400',
                                    'info' => 'bg-info-55/10 dark:bg-info-900/20 text-info-600 dark:text-info-400',
                                    default => 'bg-gray-55/10 dark:bg-gray-900/20 text-gray-600 dark:text-gray-400',
                                }
                            ])>
                                <x-filament::icon
                                    icon="{{ $link['icon'] }}"
                                    class="h-6 w-6"
                                />
                            </div>
                            
                            <x-filament::icon
                                icon="heroicon-m-arrow-top-right-on-square"
                                class="h-4 w-4 text-gray-400 group-hover:text-primary-500 transition-colors"
                            />
                        </div>

                        <div>
                            <h4 class="font-bold text-gray-900 dark:text-white group-hover:text-primary-500 transition-colors">
                                {{ $link['name'] }}
                            </h4>
                            <p class="text-xs text-gray-500 dark:text-gray-400 line-clamp-2 mt-1">
                                {{ $link['description'] }}
                            </p>
                        </div>
                    </div>

                    @if(!($link['is_main'] ?? false))
                        {{-- Copy OBS Link Button --}}
                        <div class="mt-2 pt-3 border-t border-gray-150 dark:border-gray-800/80 flex items-center justify-between gap-2">
                            <span class="text-[9px] font-black uppercase tracking-wider text-gray-400 dark:text-gray-500">OBS Overlay</span>
                            <button 
                                type="button"
                                onclick="event.preventDefault(); event.stopPropagation(); copyObsLink('{{ $link['url'] }}', this, event);"
                                class="flex items-center gap-1.5 px-2.5 py-1 rounded bg-gray-50 dark:bg-gray-800 hover:bg-emerald-500 dark:hover:bg-emerald-600 hover:text-white text-[11px] font-bold text-gray-650 dark:text-gray-300 border border-gray-200 dark:border-gray-700/60 hover:border-transparent transition-all duration-150"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor" class="w-3.5 h-3.5">
                                  <path stroke-linecap="round" stroke-linejoin="round" d="M15.666 3.888A2.25 2.25 0 0 0 13.5 2.25h-3c-1.03 0-1.9.693-2.166 1.638m7.332 0c.055.194.084.4.084.612v0a.75.75 0 0 1-.75.75H9a.75.75 0 0 1-.75-.75v0c0-.212.03-.418.084-.612m7.332 0c.346.102.637.318.806.622.196.353.312.76.312 1.193v12.25a2.25 2.25 0 0 1-2.25 2.25H9a2.25 2.25 0 0 1-2.25-2.25V5.5c0-.433.116-.84.312-1.193.17-.304.46-.52.806-.622" />
                                </svg>
                                <span>Copy OBS Link</span>
                            </button>
                        </div>
                    @endif

                    @if($link['is_main'] ?? false)
                        <div class="absolute top-2 right-2">
                            <span class="flex h-2 w-2">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-danger-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-danger-500"></span>
                            </span>
                        </div>
                    @endif
                </a>
            @endforeach
        </div>

        <x-slot name="footer">
            <div class="text-xs text-gray-500 dark:text-gray-400 text-center italic">
                Tip: Copy the OBS link and paste it into OBS Studio as a Browser Source set to 1920x1080 resolution.
            </div>
        </x-slot>
    </x-filament::section>

    <script>
        function copyObsLink(url, button, event) {
            function doCopy() {
                if (navigator.clipboard && window.isSecureContext) {
                    return navigator.clipboard.writeText(url);
                } else {
                    return new Promise((resolve, reject) => {
                        const textArea = document.createElement("textarea");
                        textArea.value = url;
                        textArea.style.top = "0";
                        textArea.style.left = "0";
                        textArea.style.position = "fixed";
                        document.body.appendChild(textArea);
                        textArea.focus();
                        textArea.select();
                        try {
                            const successful = document.execCommand('copy');
                            if (successful) resolve();
                            else reject(new Error('Copy command failed'));
                        } catch (err) {
                            reject(err);
                        }
                        document.body.removeChild(textArea);
                    });
                }
            }

            doCopy().then(() => {
                // Success styling feedback on button
                const textSpan = button.querySelector('span');
                const svgNode = button.querySelector('svg');
                const originalText = textSpan.innerText;
                const originalSvg = svgNode.innerHTML;
                
                textSpan.innerText = 'Copied!';
                svgNode.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />';
                
                button.classList.remove('bg-gray-50', 'dark:bg-gray-800', 'text-gray-650', 'dark:text-gray-300', 'border-gray-200', 'dark:border-gray-700/60');
                button.classList.add('bg-emerald-500', 'text-white', 'border-transparent');
                
                setTimeout(() => {
                    textSpan.innerText = originalText;
                    svgNode.innerHTML = originalSvg;
                    button.classList.add('bg-gray-50', 'dark:bg-gray-800', 'text-gray-650', 'dark:text-gray-300', 'border-gray-200', 'dark:border-gray-700/60');
                    button.classList.remove('bg-emerald-500', 'text-white', 'border-transparent');
                }, 1500);

                // Spawn cursor tooltip
                showCursorTooltip(event, 'Link Copied!');
            }).catch(err => {
                console.error('Failed to copy link: ', err);
                showCursorTooltip(event, 'Copy Failed!');
            });
        }

        function showCursorTooltip(event, text) {
            const tooltip = document.createElement('div');
            tooltip.innerText = text;
            tooltip.style.position = 'absolute';
            tooltip.style.background = '#10b981'; // Emerald
            tooltip.style.color = '#ffffff';
            tooltip.style.padding = '6px 12px';
            tooltip.style.borderRadius = '6px';
            tooltip.style.fontSize = '12px';
            tooltip.style.fontWeight = 'bold';
            tooltip.style.pointerEvents = 'none';
            tooltip.style.zIndex = '99999';
            tooltip.style.boxShadow = '0 4px 10px rgba(0, 0, 0, 0.3)';
            tooltip.style.transition = 'all 0.4s cubic-bezier(0.16, 1, 0.3, 1)';
            
            // Adjust position slightly above cursor
            const scrollLeft = window.pageXOffset || document.documentElement.scrollLeft;
            const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
            tooltip.style.left = (event.clientX + scrollLeft - 40) + 'px';
            tooltip.style.top = (event.clientY + scrollTop - 35) + 'px';
            tooltip.style.opacity = '0';
            tooltip.style.transform = 'translateY(8px)';
            
            document.body.appendChild(tooltip);
            
            // Force reflow
            tooltip.offsetHeight;
            
            // Animate in
            tooltip.style.opacity = '1';
            tooltip.style.transform = 'translateY(0)';
            
            // Fade out and remove
            setTimeout(() => {
                tooltip.style.opacity = '0';
                tooltip.style.transform = 'translateY(-12px)';
                setTimeout(() => {
                    if (tooltip.parentNode) {
                        tooltip.parentNode.removeChild(tooltip);
                    }
                }, 400);
            }, 1000);
        }
    </script>
</x-filament-widgets::widget>
