<x-base title="OBS MASTER VIEW">
    <style>
        /* ══════════════════════════════════════════════════════
           GLOBAL EXIT TRANSITIONS OVERRIDES
           ══════════════════════════════════════════════════════ */
        
        .exit-active .slide-down {
            animation: slideDownExit 0.5s cubic-bezier(0.16, 1, 0.3, 1) both !important;
            animation-delay: 0s !important;
        }

        .exit-active .slide-left,
        .exit-active .slide-from-left {
            animation: slideLeftExit 0.5s cubic-bezier(0.16, 1, 0.3, 1) both !important;
            animation-delay: 0s !important;
        }

        .exit-active .slide-right,
        .exit-active .slide-from-right {
            animation: slideRightExit 0.5s cubic-bezier(0.16, 1, 0.3, 1) both !important;
            animation-delay: 0s !important;
        }

        .exit-active .slide-up {
            animation: slideUpExit 0.45s cubic-bezier(0.16, 1, 0.3, 1) both !important;
            animation-delay: 0s !important;
        }

        .exit-active .slide-up-footer {
            animation: slideUpFooterExit 0.4s cubic-bezier(0.16, 1, 0.3, 1) both !important;
            animation-delay: 0s !important;
        }

        .exit-active .card-reveal {
            animation: cardRevealExit 0.5s cubic-bezier(0.16, 1, 0.3, 1) both !important;
            animation-delay: 0s !important;
        }

        .exit-active .logo-reveal {
            animation: logoRevealExit 0.5s cubic-bezier(0.16, 1, 0.3, 1) both !important;
            animation-delay: 0s !important;
        }

        .exit-active .badge-drop {
            animation: badgeDropExit 0.5s cubic-bezier(0.16, 1, 0.3, 1) both !important;
            animation-delay: 0s !important;
        }

        .exit-active .stat-reveal {
            animation: statRevealExit 0.4s cubic-bezier(0.16, 1, 0.3, 1) both !important;
            animation-delay: 0s !important;
        }

        .exit-active .badge-pop {
            animation: badgePopExit 0.4s cubic-bezier(0.16, 1, 0.3, 1) both !important;
            animation-delay: 0s !important;
        }

        /* ══════════════════════════════════════════════════════
           EXIT KEYFRAMES
           ══════════════════════════════════════════════════════ */
        
        @keyframes slideDownExit {
            from { opacity: 1; transform: translateY(0); }
            to { opacity: 0; transform: translateY(-80px); }
        }

        @keyframes slideLeftExit {
            from { opacity: 1; transform: translateX(0) scale(1); }
            to { opacity: 0; transform: translateX(-100px) scale(0.94); }
        }

        @keyframes slideRightExit {
            from { opacity: 1; transform: translateX(0); }
            to { opacity: 0; transform: translateX(100px); }
        }

        @keyframes slideUpExit {
            from { opacity: 1; transform: translateY(0); }
            to { opacity: 0; transform: translateY(40px); }
        }

        @keyframes slideUpFooterExit {
            from { opacity: 1; transform: translateY(0); }
            to { opacity: 0; transform: translateY(60px); }
        }

        @keyframes cardRevealExit {
            from { opacity: 1; transform: translateY(0) scale(1); }
            to { opacity: 0; transform: translateY(110px) scale(0.93); }
        }

        @keyframes logoRevealExit {
            from { opacity: 1; transform: scale(1) rotate(0deg); }
            to { opacity: 0; transform: scale(0.4) rotate(-15deg); }
        }

        @keyframes badgeDropExit {
            from { opacity: 1; transform: translateY(0) scale(1); }
            to { opacity: 0; transform: translateY(-40px) scale(0.7); }
        }

        @keyframes statRevealExit {
            from { opacity: 1; transform: translateY(0) scale(1); }
            to { opacity: 0; transform: translateY(30px) scale(0.7); }
        }

        @keyframes badgePopExit {
            from { opacity: 1; transform: scale(1); }
            to { opacity: 0; transform: scale(0.5); }
        }
    </style>

    <!-- Master overlay container -->
    <div id="obs-container" class="w-full h-full relative font-sans text-slate-100 bg-transparent transition-opacity duration-300 opacity-100 z-10">
        <!-- Intentionally empty initial view -->
    </div>

    <script type="module">
        let currentViewName = 'empty';
        let isTransitioning = false;
        
        function loadView(viewType) {
            if (isTransitioning) return;
            const container = document.getElementById('obs-container');
            
            // If switching to the same view, just update in-place without transitions
            if (viewType === currentViewName) {
                fetchAndRender(viewType);
                return;
            }
            
            isTransitioning = true;
            
            // Trigger synchronized exit animations across all child elements
            container.classList.add('exit-active');
            
            // Wait for exit keyframes to complete (600ms) before loading new route
            setTimeout(() => {
                fetchAndRender(viewType, () => {
                    // Remove exit active class to allow standard entrance animations
                    container.classList.remove('exit-active');
                    isTransitioning = false;
                });
            }, 600);
        }

        function fetchAndRender(viewType, callback) {
            const container = document.getElementById('obs-container');
            currentViewName = viewType;
            
            if (viewType === 'empty') {
                container.innerHTML = '';
                if (callback) callback();
                return;
            }
            
            // Construct the routing URL based on the requested view string
            let url = '';
            if (viewType === 'postmatch') {
                url = '{{ route("screens.postmatch", ["user_id" => $user->id]) }}';
            } else if (viewType === 'overallranking') {
                url = '{{ route("screens.overallranking", ["user_id" => $user->id]) }}';
            } else if (viewType === 'headtohead') {
                url = '{{ route("screens.headtohead", ["user_id" => $user->id]) }}';
            } else if (viewType === 'topfraggers') {
                url = '{{ route("screens.topfraggers", ["user_id" => $user->id]) }}';
            }
            
            if (url) {
                // Cache busting headers to guarantee latest stats
                fetch(url, { cache: 'no-store', headers: {'Cache-Control': 'no-cache'} })
                    .then(response => response.text())
                    .then(html => {
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');
                        
                        // Load the parsed body contents directly into our container
                        const newBody = doc.querySelector('body');
                        if (newBody) {
                            container.innerHTML = newBody.innerHTML;
                        }
                        if (callback) callback();
                    })
                    .catch(() => {
                        // Recover from failed loads
                        if (callback) callback();
                    });
            } else {
                if (callback) callback();
            }
        }

        document.addEventListener("DOMContentLoaded", function () {
            // Listen to the Director's Control Panel Actions
            Echo.channel('user-screens.{{ $user->id }}')
                .listen('.ObsViewSwitched', (e) => {
                    console.log('ObsViewSwitched received:', e);
                    if (e.viewName === 'refresh') {
                        loadView(currentViewName);
                    } else {
                        loadView(e.viewName);
                    }
                })
                .listen('.MatchStatsUpdated', (e) => {
                    console.log('MatchStatsUpdated received (User Channel):', e);
                    // Seamless background stats/data update (in-place render)
                    fetchAndRender(currentViewName);
                })
                .listen('.TournamentMatchUpdated', (e) => {
                    console.log('TournamentMatchUpdated received:', e);
                    // Seamless background stats/data update (in-place render)
                    fetchAndRender(currentViewName);
                })
                .listen('.RefreshScreens', (e) => {
                    console.log('Force refresh received...');
                    window.location.reload();
                });

            // Listen to Match Stats dynamically updating in the background!
            if ('{{ $activeMatch->id ?? "" }}') {
                Echo.channel('active-match.{{ $activeMatch->id }}')
                    .listen('.MatchStatsUpdated', (e) => {
                        console.log('MatchStatsUpdated received (Active Match Channel):', e);
                        // Seamless background stats/data update (in-place render)
                        if (currentViewName !== 'empty') {
                            fetchAndRender(currentViewName);
                        }
                    });
            }
        });
    </script>
</x-base>
