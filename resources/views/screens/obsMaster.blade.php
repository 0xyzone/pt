<x-base>
    <!-- Master overlay container -->
    <div id="obs-container" class="w-full h-full relative font-sans text-slate-100 bg-transparent transition-opacity duration-500">
        <!-- Intentionally empty initial view -->
    </div>

    <!-- Fade Overlay for smooth transitions -->
    <div id="obs-fade-layer" class="fixed inset-0 bg-slate-950 pointer-events-none opacity-0 transition-opacity duration-300 z-50"></div>

    <script type="module">
        let currentViewName = 'empty';
        
        function loadView(viewType) {
            const container = document.getElementById('obs-container');
            const fader = document.getElementById('obs-fade-layer');
            
            // If the view type is changing, trigger a fade animation
            if(viewType !== currentViewName) {
                fader.style.opacity = '1';
            }

            setTimeout(() => {
                currentViewName = viewType;
                
                if (viewType === 'empty') {
                    // Empty state logic: totally clear the contents.
                    container.innerHTML = '';
                    fader.style.opacity = '0';
                    return;
                }
                
                // Construct the routing URL based on the requested view string
                let url = '';
                if (viewType === 'postmatch') {
                    url = '{{ route("screens.postmatch", ["user_id" => $user->id]) }}';
                } else if (viewType === 'overallranking') {
                    url = '{{ route("screens.overallranking", ["user_id" => $user->id]) }}';
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
                            
                            // Restore visibility
                            fader.style.opacity = '0';
                        })
                        .catch(() => {
                            // Recover from failed loads
                            fader.style.opacity = '0';
                        });
                } else {
                    fader.style.opacity = '0';
                }
            }, viewType !== currentViewName ? 350 : 0); // Wait for fade out if changing views
        }

        document.addEventListener("DOMContentLoaded", function () {
            // Listen to the Director's Control Panel Actions
            Echo.channel('user-screens.{{ $user->id }}')
                .listen('ObsViewSwitched', (e) => {
                    loadView(e.viewName);
                });

            // Listen to Match Stats dynamically updating in the background!
            if ('{{ $activeMatch->id ?? "" }}') {
                Echo.channel('active-match.{{ $activeMatch->id }}')
                    .listen('MatchStatsUpdated', (e) => {
                        // If we are currently showing a stats view, transparently re-fetch it identically
                        if(currentViewName !== 'empty') {
                            loadView(currentViewName);
                        }
                    });
            }
        });
    </script>
</x-base>
