<x-base>
    <!-- ELIMINATION TOASTER OVERLAY (Standalone Page) -->
    <div id="elimination-toaster" class="fixed top-[-150px] left-1/2 transform -translate-x-1/2 z-[100] transition-all duration-700 ease-in-out opacity-0 flex items-center justify-center p-4 bg-red-950/95 border-b-4 border-red-600 rounded-b-2xl shadow-[0_15px_40px_rgba(220,38,38,0.7)] backdrop-blur-xl min-w-[420px]">
        <div class="w-20 h-20 mr-6 bg-slate-900/50 rounded-lg p-2 border border-red-500/30 flex-shrink-0">
            <img id="toaster-logo" src="" class="w-full h-full object-contain filter drop-shadow-md">
        </div>
        <div class="flex flex-col text-left">
            <div class="flex items-center gap-2 mb-1">
                <div class="w-2 h-2 rounded-full bg-red-500 animate-pulse"></div>
                <span class="text-red-400 text-xs font-black tracking-[0.4em] uppercase drop-shadow-[0_0_5px_rgba(220,38,38,0.8)]">Squad Eliminated</span>
            </div>
            <span id="toaster-team-name" class="text-4xl font-black italic uppercase text-slate-100 tracking-wider drop-shadow-lg"></span>
        </div>
    </div>

    <script type="module">
        document.addEventListener("DOMContentLoaded", function () {
            const toaster = document.getElementById('elimination-toaster');
            const teamNameSpan = document.getElementById('toaster-team-name');
            const logoImg = document.getElementById('toaster-logo');
            
            let eliminationQueue = [];
            let isProcessing = false;

            function processQueue() {
                if (eliminationQueue.length === 0 || isProcessing) return;

                isProcessing = true;
                const matchData = eliminationQueue.shift();

                // Set content
                teamNameSpan.innerText = matchData.teamName;
                let logoUrl = "{{ asset('img/defult_team_logo.png') }}";
                if (matchData.teamLogo) {
                    logoUrl = "/storage/" + matchData.teamLogo;
                }
                logoImg.src = logoUrl;

                // Slide in
                toaster.style.top = '40px';
                toaster.style.opacity = '1';

                // Wait for the full display duration (5.5s) plus some buffer for the slide-out
                setTimeout(() => {
                    // Slide out
                    toaster.style.top = '-150px';
                    toaster.style.opacity = '0';

                    // Wait for the slide-out animation to finish before starting the next one
                    setTimeout(() => {
                        isProcessing = false;
                        processQueue();
                    }, 800); // Buffer for slide-out transition
                }, 5500);
            }

            // Global listeners for refresh and match activation
            Echo.channel('user-screens.{{ $user->id }}')
                .listen('.RefreshScreens', (e) => {
                    console.log('Force refresh received...');
                    window.location.reload();
                })
                .listen('.TournamentMatchUpdated', (e) => {
                    console.log('Match updated, reloading for channel sync...');
                    window.location.reload();
                });

            if ('{{ $activeMatch->id ?? "" }}') {
                Echo.channel('active-match.{{ $activeMatch->id }}')
                    .listen('.TeamEliminated', (e) => {
                        eliminationQueue.push(e);
                        processQueue();
                    });
            }
        });
    </script>
</x-base>
