{{--
    Effect 1 — Hero parallax + reflection.
    ONE parallax layer contains the image, reflection, and ALL gradients so nothing
    can drift out of sync on scroll (static overlay siblings caused the seam bug).
--}}
<section class="relative overflow-hidden bg-cowboy-950" data-hero>

    {{-- Spacer reserves room for reflection below the viewport content --}}
    @php $reflectionH = '14rem'; @endphp

    <div class="absolute inset-x-0 top-0 overflow-hidden pointer-events-none" aria-hidden="true"
         style="height: calc(100vh + {{ $reflectionH }} + 25vh);">
        <div
            class="absolute inset-x-0 top-0 will-change-transform"
            style="height: calc(100vh + {{ $reflectionH }}); transform: scale(1.35); transform-origin: top center;"
            data-hero-parallax
        >
            {{-- Main hero photo --}}
            <div
                class="absolute inset-x-0 top-0 bg-cover bg-center"
                style="height: 100vh; background-image: url('/assets/photos/hero-alaska-boat.jpg');"
            ></div>

            {{-- Mirrored reflection directly beneath the hero photo --}}
            <div
                class="absolute inset-x-0 bg-cover bg-center opacity-45"
                style="top: 100vh; height: {{ $reflectionH }}; background-image: url('/assets/photos/hero-alaska-boat.jpg'); background-position: center bottom; transform: scaleY(-1); transform-origin: top center;"
            ></div>

            {{-- Vignette: lives inside parallax layer so it tracks the photo --}}
            <div class="absolute inset-x-0 top-0 bg-gradient-to-b from-cowboy-950/25 via-transparent to-transparent pointer-events-none"
                 style="height: 100vh;"></div>

            {{-- Reflection fade into page background: also inside parallax layer --}}
            <div class="absolute inset-x-0 bg-gradient-to-b from-transparent via-cowboy-950/45 to-cowboy-950 pointer-events-none"
                 style="top: calc(100vh - 4rem); height: calc({{ $reflectionH }} + 4rem);"></div>
        </div>
    </div>

    {{-- Text in upper third; boat/hand composition sits lower in the frame --}}
    <div class="relative z-10 flex flex-col items-center h-screen px-6 pt-[11vh] md:pt-[13vh] text-center">
        <h1 class="font-display font-extrabold text-6xl md:text-8xl lg:text-9xl text-cowboy-50 mb-3 drop-shadow-[0_2px_12px_rgba(0,0,0,0.8)]">
            Hello Internet,
        </h1>
        <p class="font-ui text-xl md:text-2xl text-cowboy-100 mb-10 drop-shadow-[0_2px_8px_rgba(0,0,0,0.7)]">
            This is my website.
        </p>

        <a
            href="https://www.flickr.com/photos/zaskoda/2623298793/"
            class="group relative w-20 h-20 will-change-transform"
            title="I made this boat on a trip to Alaska. Watch it sail."
            target="_blank"
            rel="noopener"
            data-hero-play
        >
            <svg viewBox="0 0 80 80" class="w-full h-full text-cowboy-50 group-hover:text-copper-400 transition-colors drop-shadow-lg" aria-hidden="true">
                <circle cx="40" cy="40" r="38" stroke="currentColor" stroke-width="2" fill="rgba(0,0,0,0.35)" opacity="0.9"/>
                <polygon points="32,24 60,40 32,56" fill="currentColor" opacity="0.95"/>
            </svg>
            <span class="sr-only">I made this boat on a trip to Alaska. Watch it sail.</span>
        </a>
    </div>

    {{-- Layout spacer: reflection renders in the parallax layer behind this area --}}
    <div class="relative h-48 md:h-56 pointer-events-none -mb-px" aria-hidden="true"></div>

</section>
