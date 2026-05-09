<x-filament-widgets::widget>
    <x-filament::section>
        <style>
            .api-list {
                display: flex;
                flex-direction: column;
                gap: 12px;
            }
            .api-item {
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 10px 14px;
                background: #f8fafc; /* slate-50 */
                border: 1px solid #e2e8f0; /* slate-200 */
                border-radius: 8px;
                transition: all 0.2s;
            }
            .api-item:hover {
                border-color: #6366f1; /* indigo-500 */
                background: #f5f3ff; /* indigo-50 ghost */
            }
            .api-url {
                font-family: 'JetBrains Mono', monospace;
                font-size: 0.85rem;
                color: #475569; /* slate-600 */
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: nowrap;
                margin-right: 10px;
            }
            .copy-btn {
                flex-shrink: 0;
                display: flex;
                align-items: center;
                gap: 4px;
                padding: 4px 10px;
                font-size: 0.75rem;
                font-weight: 600;
                color: #6366f1;
                border: 1px solid #e0e7ff;
                border-radius: 6px;
                background: white;
                cursor: pointer;
                transition: 0.2s;
            }
            .copy-btn:hover {
                background: #6366f1;
                color: white;
            }
            .copy-btn.success {
                background: #10b981;
                color: white;
                border-color: #10b981;
            }
        </style>

        <div class="api-list" x-data="{ 
            copy(url, id) {
                if (navigator.clipboard && window.isSecureContext) {
                    navigator.clipboard.writeText(url).then(() => this.confirm(id));
                } else {
                    let textArea = document.createElement('textarea');
                    textArea.value = url;
                    textArea.style.position = 'fixed';
                    textArea.style.left = '-9999px';
                    document.body.appendChild(textArea);
                    textArea.focus();
                    textArea.select();
                    document.execCommand('copy');
                    textArea.remove();
                    this.confirm(id);
                }
            },
            activeId: null,
            confirm(id) {
                this.activeId = id;
                setTimeout(() => { this.activeId = null }, 2000);
            }
        }">
            <h3 class="text-sm font-semibold text-gray-500 mb-2">Available API Endpoints</h3>
            
            @foreach($this->getApiRoutes() as $index => $url)
                <div class="api-item">
                    <span class="api-url">{{ $url }}</span>
                    
                    <button 
                        type="button"
                        class="copy-btn"
                        :class="activeId === {{ $index }} ? 'success' : ''"
                        @click="copy('{{ $url }}', {{ $index }})"
                    >
                        <template x-if="activeId !== {{ $index }}">
                            <div class="flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2" />
                                </svg>
                                <span>Copy</span>
                            </div>
                        </template>
                        
                        <template x-if="activeId === {{ $index }}">
                            <span>Copied!</span>
                        </template>
                    </button>
                </div>
            @endforeach
        </div>
    </x-filament::section>
</x-filament-widgets::widget>