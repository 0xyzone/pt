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
                        'relative group flex flex-col gap-2 p-4 rounded-xl border transition-all duration-200',
                        'bg-white dark:bg-gray-900 border-gray-200 dark:border-gray-800 hover:border-primary-500 dark:hover:border-primary-500 hover:shadow-lg',
                        'col-span-full md:col-span-1 lg:col-span-2 ring-2 ring-danger-500/20' => $link['is_main'] ?? false,
                    ])
                >
                    <div class="flex items-center justify-between">
                        <div @class([
                            'p-2 rounded-lg',
                            match($link['color']) {
                                'danger' => 'bg-danger-50 dark:bg-danger-900/20 text-danger-600 dark:text-danger-400',
                                'primary' => 'bg-primary-50 dark:bg-primary-900/20 text-primary-600 dark:text-primary-400',
                                'warning' => 'bg-warning-50 dark:bg-warning-900/20 text-warning-600 dark:text-warning-400',
                                'success' => 'bg-success-50 dark:bg-success-900/20 text-success-600 dark:text-success-400',
                                'info' => 'bg-info-50 dark:bg-info-900/20 text-info-600 dark:text-info-400',
                                default => 'bg-gray-50 dark:bg-gray-900/20 text-gray-600 dark:text-gray-400',
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
                Tip: Right-click and copy link to paste into OBS Browser Source. All screens are 1920x1080 with transparency.
            </div>
        </x-slot>
    </x-filament::section>
</x-filament-widgets::widget>
