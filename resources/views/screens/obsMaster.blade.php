<x-base>
    <!-- Master overlay container -->
    <div id="obs-container" class="w-full h-full relative font-sans text-slate-100 bg-transparent transition-opacity duration-500 opacity-100 z-10">
        <!-- Intentionally empty initial view -->
    </div>

    <script type="module">
        let currentViewName = 'empty';
        
        function loadView(viewType) {
            const container = document.getElementById('obs-container');
            
            // If the view type is changing, trigger a fade animation on the container
            if(viewType !== currentViewName) {
                container.style.opacity = '0';
            }

            setTimeout(() => {
                currentViewName = viewType;
                
                if (viewType === 'empty') {
                    // Empty state logic: totally clear the contents.
                    container.innerHTML = '';
                    setTimeout(() => container.style.opacity = '1', 50);
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
                            setTimeout(() => container.style.opacity = '1', 50);
                        })
                        .catch(() => {
                            // Recover from failed loads
                            container.style.opacity = '1';
                        });
                } else {
                    container.style.opacity = '1';
                }
            }, viewType !== currentViewName ? 500 : 0); // Wait for fade out if changing views
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
