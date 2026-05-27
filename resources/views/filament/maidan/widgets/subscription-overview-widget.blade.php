@php
    $latestSub = $this->getLatestSubscription();
    $activeSub = $this->getActiveSubscription();
    $tournaments = $this->getTournamentUsage();
    $plans = $this->getAvailablePlans();
    $user = auth()->user();
    $pendingRequests = $this->getPendingRequests();
@endphp

<div class="fi-wi-widget fi-wi-subscription-overview col-span-full">
    <div class="relative overflow-hidden rounded-3xl bg-linear-to-br from-slate-900 via-slate-950 to-slate-900 border border-slate-800 p-6 md:p-8 shadow-2xl">
        <!-- Abstract glowing circles in the background -->
        <div class="absolute -right-16 -top-16 w-64 h-64 rounded-full bg-amber-500/10 blur-3xl pointer-events-none"></div>
        <div class="absolute -left-16 -bottom-16 w-64 h-64 rounded-full bg-yellow-500/5 blur-3xl pointer-events-none"></div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-stretch relative z-10">
            <!-- Left Side: Subscription status & limits -->
            <div class="lg:col-span-7 flex flex-col justify-between space-y-6">
                <div>
                    <div class="flex items-center space-x-3 mb-3">
                        <div class="p-2 rounded-xl bg-amber-500/10 border border-amber-500/20 text-amber-500">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                            </svg>
                        </div>
                        <h2 class="text-xl md:text-2xl font-black text-white uppercase tracking-wider">Subscription Overview</h2>
                    </div>

                    @if($activeSub)
                        <div class="mb-4">
                            <span class="text-xs font-bold text-amber-500 uppercase tracking-widest block mb-1">Current Active Plan</span>
                            <div class="flex items-center space-x-3">
                                <span class="text-2xl md:text-3xl font-black text-white">{{ $activeSub->plan->name }}</span>
                                <span class="px-3 py-1 text-xs font-extrabold uppercase tracking-wider bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 rounded-full shadow-sm">
                                    Active
                                </span>
                            </div>
                        </div>

                        <!-- Subscription detail row -->
                        <div class="flex flex-wrap gap-x-8 gap-y-2 text-sm text-slate-400 font-medium">
                            <div>
                                <span class="text-xs text-slate-500 block">Assigned On</span>
                                <span class="text-white font-bold">{{ $activeSub->starts_at->format('d M Y') }}</span>
                            </div>
                            @if($activeSub->ends_at)
                                <div>
                                    <span class="text-xs text-slate-500 block">Expires On</span>
                                    <span class="text-white font-bold">{{ $activeSub->ends_at->format('d M Y') }}</span>
                                </div>
                                <div>
                                    <span class="text-xs text-slate-500 block">Time Left</span>
                                    @php
                                        $days = $activeSub->daysRemaining();
                                        $daysColor = $days <= 7 ? 'text-rose-400' : ($days <= 30 ? 'text-amber-400' : 'text-emerald-400');
                                    @endphp
                                    <span class="font-extrabold {{ $daysColor }}">
                                        {{ $days }} {{ Str::plural('day', $days) }} left
                                    </span>
                                </div>
                            @else
                                <div>
                                    <span class="text-xs text-slate-500 block">Expiry</span>
                                    <span class="text-emerald-400 font-bold">Lifetime / Custom</span>
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="mb-6 rounded-2xl bg-rose-500/10 border border-rose-500/20 p-4">
                            <div class="flex items-start space-x-3">
                                <svg class="w-5 h-5 text-rose-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                                <div>
                                    <h4 class="text-sm font-bold text-white uppercase tracking-wider">No Active Subscription</h4>
                                    <p class="text-xs text-slate-400 mt-1 leading-relaxed">
                                        You do not have an active subscription assigned. Tournament limits are restricted, and public OBS overlays are currently locked. Request a plan assignment to unlock full features.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Plan Usage & Progress -->
                <div class="space-y-4">
                    <div>
                        <div class="flex justify-between items-center mb-1.5 text-xs font-bold uppercase tracking-wider">
                            <span class="text-slate-400">Tournament Quota Usage</span>
                            <span class="text-white">{{ $tournaments['display'] }}</span>
                        </div>
                        <div class="w-full bg-slate-950 rounded-full h-3.5 p-0.5 border border-slate-800">
                            <div class="bg-linear-to-r from-amber-500 to-yellow-400 h-2 rounded-full transition-all duration-500" style="width: {{ $tournaments['percent'] }}%"></div>
                        </div>
                    </div>

                    <!-- Feature checkmarks -->
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 bg-slate-950/60 rounded-2xl border border-slate-800/80 p-4">
                        @php
                            $features = [
                                'obs_overlays' => 'OBS Overlays',
                                'websocket_sync' => 'Real-Time Sync',
                                'roadmap_overlay' => 'Roadmap Overlay',
                                'casters_management' => 'Casters HUD',
                                'player_management' => 'Rosters Mgmt',
                                'custom_branding' => 'Branding Control',
                            ];
                        @endphp
                        @foreach($features as $key => $label)
                            <div class="flex items-center space-x-2 text-xs font-semibold">
                                @if(\App\Services\SubscriptionService::can($user, $key))
                                    <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                    <span class="text-slate-200">{{ $label }}</span>
                                @else
                                    <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    <span class="text-slate-500 line-through">{{ $label }}</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Divider -->
            <div class="hidden lg:flex lg:col-span-1 justify-center items-center">
                <div class="w-px h-full bg-linear-to-b from-transparent via-slate-800 to-transparent"></div>
            </div>

            <!-- Right Side: Upgrade / Renew Request Form & Pending Requests -->
            <div class="lg:col-span-4 flex flex-col space-y-6 justify-center">

                @if($pendingRequests->isNotEmpty())
                    <!-- Pending Requests List -->
                    <div class="bg-amber-500/10 border border-amber-500/20 rounded-2xl p-5">
                        <div class="flex items-center space-x-2 mb-3">
                            <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <h3 class="text-sm font-black text-amber-500 uppercase tracking-wider">Pending Requests</h3>
                        </div>
                        <div class="space-y-3">
                            @foreach($pendingRequests as $req)
                                <div class="bg-slate-900 border border-amber-500/10 rounded-xl p-3 flex justify-between items-center">
                                    <div>
                                        <div class="text-white font-bold text-sm">{{ $req->plan->name }}</div>
                                        <div class="text-[10px] text-slate-400 font-medium">Applied: {{ $req->created_at->diffForHumans() }}</div>
                                    </div>
                                    <button wire:click="cancelRequest({{ $req->id }})" wire:confirm="Are you sure you want to cancel this request?" type="button" class="text-xs px-3 py-1.5 bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 font-bold rounded-lg transition-colors border border-rose-500/20">
                                        Cancel
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <form wire:submit.prevent="submitRequest" class="space-y-4 bg-slate-950/40 border border-slate-800/80 rounded-2xl p-5 md:p-6">
                    <div>
                        <h3 class="text-sm font-black text-white uppercase tracking-wider mb-1">Renew or Change Plan</h3>
                        <p class="text-xs text-slate-500">Submit a manual request to the platform administrator.</p>
                    </div>

                    <div class="space-y-3">
                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1" for="plan-select">Select Plan</label>
                            <select wire:model="selectedPlanSlug" id="plan-select" class="w-full bg-slate-950 border border-slate-700 text-white text-xs font-bold rounded-xl px-3 py-2.5 outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition-all">
                                @foreach($plans as $plan)
                                    <option value="{{ $plan->slug }}">{{ $plan->name }} ({{ $plan->price_display }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Transaction Screenshot</label>
                            <div class="relative border-2 border-dashed border-slate-700 hover:border-amber-500 rounded-xl p-4 bg-slate-950 text-center cursor-pointer transition-all duration-300 group">
                                <input type="file" wire:model="transactionScreenshot" id="screenshot-upload" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" accept="image/*" />
                                
                                <div class="flex flex-col items-center justify-center space-y-2">
                                    @if ($transactionScreenshot)
                                        <div class="relative w-full max-h-36 rounded-lg overflow-hidden border border-slate-800 bg-slate-900 flex justify-center items-center">
                                            <img src="{{ $transactionScreenshot->temporaryUrl() }}" class="object-contain max-h-36 w-auto rounded-lg" />
                                            <div class="absolute inset-0 bg-slate-950/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                                <span class="text-xs font-bold text-white bg-slate-900/80 px-2 py-1 rounded">Change Image</span>
                                            </div>
                                        </div>
                                    @else
                                        <div class="p-2.5 rounded-xl bg-slate-900 group-hover:bg-amber-500/10 border border-slate-800 group-hover:border-amber-500/30 text-slate-400 group-hover:text-amber-500 transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                        <div class="text-xs font-bold text-slate-300 group-hover:text-white transition-colors">Click or drag image to upload</div>
                                        <div class="text-[10px] text-slate-500">PNG, JPG or JPEG (Max 5MB)</div>
                                    @endif
                                </div>
                            </div>
                            
                            <div wire:loading wire:target="transactionScreenshot" class="text-xs text-amber-500 font-bold mt-1">
                                <span class="animate-pulse">Uploading screenshot...</span>
                            </div>

                            @error('transactionScreenshot')
                                <span class="text-xs text-rose-500 font-bold mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <button type="submit" class="w-full py-2.5 bg-linear-to-r from-amber-500 to-yellow-400 hover:from-amber-400 hover:to-yellow-300 text-slate-950 font-black uppercase tracking-wider text-xs rounded-xl shadow-lg shadow-amber-500/10 transition-all active:scale-[0.98]">
                        Send Upgrade Request
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
