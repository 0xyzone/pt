<x-filament-widgets::widget>
    <x-filament::section>
        <style>
            .api-copy-wrapper {
                display: flex;
                flex-direction: column;
                gap: 8px;
            }

            .api-label {
                font-size: 0.875rem;
                font-weight: 600;
                color: #46e5a5;
                /* lime-500 */
            }

            .copy-container {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 12px;
                cursor: pointer;
                padding: 12px 16px;
                border-radius: 12px;
                background-color: rgba(70, 229, 165, 0.05);
                /* Very light lime */
                border: 1px solid rgba(70, 229, 165, 0.1);
                transition: all 0.2s ease;
            }

            .copy-container:hover {
                background-color: rgba(70, 229, 165, 0.1);
                border-color: rgba(70, 229, 165, 0.3);
                transform: translateY(-1px);
            }

            .url-display {
                color: #46e5a5;
                font-family: 'JetBrains Mono', 'Fira Code', monospace;
                font-size: 0.95rem;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
                max-width: 80%;
            }

            .status-wrapper {
                display: flex;
                align-items: center;
                gap: 6px;
            }

            .copy-icon {
                color: #46e5a5;
                width: 18px;
                height: 18px;
            }

            .success-badge {
                font-size: 0.75rem;
                font-weight: bold;
                background: #10b981;
                color: white;
                padding: 4px 10px;
                border-radius: 20px;
                box-shadow: 0 2px 4px rgba(16, 185, 129, 0.2);
            }

            .subText {
                font-size: 0.75rem;
                color: #9ca3af;
                /* gray-400 */
            }

        </style>

        <div class="api-copy-wrapper" x-data="{ 
                copied: false,
                url: '{{ url('/api') }}/' + '{{ $this->userId }}',
                copyToClipboard() {
                    if (navigator.clipboard && window.isSecureContext) {
                        navigator.clipboard.writeText(this.url).then(() => this.showSuccess());
                    } else {
                        let textArea = document.createElement('textarea');
                        textArea.value = this.url;
                        textArea.style.position = 'fixed';
                        textArea.style.left = '-9999px';
                        document.body.appendChild(textArea);
                        textArea.focus();
                        textArea.select();
                        try {
                            document.execCommand('copy');
                            this.showSuccess();
                        } catch (err) {
                            console.error('Copy failed', err);
                        }
                        textArea.remove();
                    }
                },
                showSuccess() {
                    this.copied = true;
                    setTimeout(() => { this.copied = false }, 2000);
                }
            }">

            <p class="api-label">User API Endpoint <span class="subText">(click to copy)</span></p>

            <div class="copy-container" @click="copyToClipboard()">
                <span class="url-display" x-text="url"></span>

                <div class="status-wrapper">
                    <template x-if="!copied">
                        <svg class="copy-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.666 3.888A2.25 2.25 0 0 0 13.5 2.25h-3c-1.03 0-1.9.693-2.166 1.638m7.332 0c.055.194.084.4.084.612v0c0 .414-.336.75-.75.75H9a.75.75 0 0 1-.75-.75v0c0-.212.03-.418.084-.612m7.332 0c.646.049 1.288.11 1.927.184 1.1.128 1.907 1.077 1.907 2.185V19.5a2.25 2.25 0 0 1-2.25 2.25H6.75A2.25 2.25 0 0 1 4.5 19.5V6.257c0-1.108.806-2.057 1.907-2.185a48.208 48.208 0 0 1 1.927-.184" />
                        </svg>
                    </template>

                    <template x-if="copied">
                        <span class="success-badge" x-transition>COPIED</span>
                    </template>
                </div>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
