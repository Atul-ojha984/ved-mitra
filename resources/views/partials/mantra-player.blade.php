@php($audio = config('brand.audio.gayatri'))
<div class="fixed bottom-5 right-5 z-50 no-print" data-mantra-player>
    <audio data-mantra-audio loop preload="none" playsinline>
        <source src="{{ $audio }}" type="audio/ogg">
    </audio>
    <div class="rounded-2xl bg-[#2a0d09]/95 text-white shadow-2xl border border-amber-300/20 overflow-hidden">
        <button type="button" data-mantra-toggle class="w-full flex items-center gap-3 px-4 py-3 hover:bg-white/10 transition" aria-label="Play or pause Gayatri Mantra">
            <span class="h-10 w-10 rounded-full temple-gradient flex items-center justify-center divine-glow"><i data-mantra-icon class="fa-solid fa-volume-high"></i></span>
            <span class="text-left">
                <span class="block text-sm font-bold">Gayatri Mantra</span>
                <span data-mantra-status class="block text-xs text-orange-100/75">soft ambience</span>
            </span>
        </button>
        <div class="px-4 pb-3 flex items-center gap-3">
            <button type="button" data-mantra-mute class="h-8 w-8 rounded-full bg-white/10 hover:bg-white/20" aria-label="Mute mantra"><i class="fa-solid fa-volume-xmark text-xs"></i></button>
            <input data-mantra-volume type="range" min="0" max="0.45" step="0.01" value="0.18" class="w-28 accent-amber-400" aria-label="Mantra volume">
        </div>
    </div>
</div>
<script>
    (() => {
        const root = document.querySelector('[data-mantra-player]');
        if (!root) return;
        const audio = root.querySelector('[data-mantra-audio]');
        const toggle = root.querySelector('[data-mantra-toggle]');
        const mute = root.querySelector('[data-mantra-mute]');
        const volume = root.querySelector('[data-mantra-volume]');
        const status = root.querySelector('[data-mantra-status]');
        const icon = root.querySelector('[data-mantra-icon]');
        let desiredVolume = Number(localStorage.getItem('vedMantraVolume') || volume.value || 0.18);
        let muted = localStorage.getItem('vedMantraMuted') === '1';
        audio.volume = 0;
        audio.muted = muted;
        volume.value = desiredVolume;

        const paint = () => {
            const playing = !audio.paused;
            icon.className = playing && !audio.muted ? 'fa-solid fa-volume-high' : 'fa-solid fa-play';
            status.textContent = playing ? (audio.muted ? 'muted' : 'playing softly') : 'tap to play';
        };
        const fadeTo = (target) => {
            const step = () => {
                if (Math.abs(audio.volume - target) < 0.015) {
                    audio.volume = target;
                    return;
                }
                audio.volume += audio.volume < target ? 0.015 : -0.015;
                requestAnimationFrame(step);
            };
            step();
        };
        const play = async () => {
            try {
                audio.muted = muted;
                await audio.play();
                fadeTo(muted ? 0 : desiredVolume);
            } catch (error) {
                status.textContent = 'tap to play';
            }
            paint();
        };
        toggle.addEventListener('click', () => {
            if (audio.paused) {
                play();
            } else {
                audio.pause();
                paint();
            }
        });
        mute.addEventListener('click', () => {
            muted = !muted;
            audio.muted = muted;
            localStorage.setItem('vedMantraMuted', muted ? '1' : '0');
            paint();
        });
        volume.addEventListener('input', () => {
            desiredVolume = Number(volume.value);
            audio.volume = desiredVolume;
            audio.muted = false;
            muted = false;
            localStorage.setItem('vedMantraVolume', String(desiredVolume));
            localStorage.setItem('vedMantraMuted', '0');
            paint();
        });
        window.addEventListener('load', () => setTimeout(play, 650), { once: true });
        paint();
    })();
</script>
